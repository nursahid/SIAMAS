<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Dashboard Controller.
 */
 
 class Absensi extends MY_Controller {

	var $data;
	
    function __construct() {
		parent::__construct();        
		$this->load->model('absensi_model', 'absensi');	
		$this->load->model('penilaian/nilai_model', 'nilai');
		$this->load->model(array('pegawai/pegawai_model'));
    }
	
    public function index()
    {
		//view
		$this->layout->set_wrapper('index', $data);
		$template_data["title"] = "Absensi";
		$template_data["subtitle"] = "Manajemen Data Absensi";
		$template_data["crumb"] = ["Absensi" => "absensi",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); 
	}
    function manage() {
        $userid = $this->ion_auth->user()->row()->id;
		$id_pegawai = $this->pegawai_model->get_by('id_user', $userid)->id;
        // layout view
		//cek group
		if($this->ion_auth->in_group(array('superadmin', 'admin'))) {
			$data['kelas'] 			= $this->nilai->get_kelas();
			$data['dropdown_mapel'] = $this->absensi->dropdown_mapel();
			$this->layout->set_wrapper('viewkelas', $data);
		} 
		elseif($this->ion_auth->in_group('guru')) {
			//dropdown mapel yang diampu
			$data['kelas'] 	 		= $this->nilai->get_kelas_by_pegawai($id_pegawai);
			$data['dropdown_mapel'] = $this->absensi->get_dropdown_mapel_by_guru($id_pegawai);
			$data['id_guru'] 		= $id_pegawai;
			//view
			$this->layout->set_wrapper('viewkelas_guru', $data);
		}
		//icheck
		$template_data['js_plugins'] = [
			base_url('assets/plugins/iCheck/icheck.min.js')
		];
		$template_data['css_plugins'] = [
            base_url('assets/plugins/iCheck/skins/square/blue.css')
        ];
        $this->layout->auth();
        $template_data['title'] = 'Absensi Kelas';
        $template_data['crumb'] = ['Absensi' => 'absensi',];
        $this->layout->setCacheAssets();
        $this->layout->render('admin', $template_data);
    }

    function lists() {
		$data['status'] = array('belum' => '--', 'hadir' => 'Hadir', 'sakit' => 'Sakit', 'izin' => 'Izin', 'alfa' => 'Alfa');
		$data['absen'] = $this->absensi->get_data_siswa_perkelas($_POST['kelas']);
		$data['tgl'] = parseFormTgl('tanggal');

		$this->load->view('absensi/list', $data);
    }

    function add() {
		if (isset($_POST['j_action']) && $_POST['j_action'] == 'add_absen') {

			$f['tanggal']	= parseFormTgl('tanggal');
			$f['tahun']		= $this->absensi->tahunajaran_aktif();
			$f['smt']		= $this->absensi->semester_aktif();
				
			for ($i = 0; $i < sizeof($_POST['nis']); $i++) {
				
				if($_POST['nis'][$i] == "" || $_POST['nis'][$i] == NULL || $_POST['nis'][$i] == '-') {
					//error
					$this->session->set_flashdata('message', "<div class='text-red'><i class='fa fa-times'></i> Siswa ".$_POST['nama'][$i]." belum ada NIS</div>");
					redirect('absensi');
				}
				else {
					//Cek Grup
					if($this->ion_auth->in_group(array('superadmin', 'admin'))) {
						$f['nis']		= $_POST['nis'][$i];
						$f['absen']		= $_POST['status'][$i];
						$f['keterangan']= $_POST['keterangan'][$i];
						//absensi harian
						$this->insert_ignore('absensi', $f);						
					}
					elseif($this->ion_auth->in_group('guru')) {
						$f['id_mapel']	= $_POST['mapel'];
						$f['nis']		= $_POST['nis'][$i];
						$f['absen']		= $_POST['status'][$i];
						$f['keterangan']= $_POST['keterangan'][$i];
						//absensi per mapel
						$this->insert_ignore('absensi_mapel', $f);
					}
					
					//jika opsi pilih SMS diaktif
					if($_POST['kirim_sms'] == 1) {
						if($_POST['status'][$i] == 'alfa') {
							//kirim sms ke no HP ortu dengan insert data ke outbox
							$tanggal = parseFormTgl('tanggal');
							$nama	 = $_POST['nama'][$i];
							$kelas	 = $this->sistem_model->get_nama_kelas($_POST['kelas'][$i]);
							$pesan 	 = "Tanggal ".$tanggal.". Siswa ".$nama.", kelas ".$kelas.". Tdk MASUK Tanpa Keterangan";
							$data 	 = array('DestinationNumber'=>$_POST['nohp'][$i], 'TextDecoded'=>$pesan);
							$this->db->insert('outbox', $data);
						}
					}					
				}
				
			}
		}		
    }
	
	function detail($param, $id = '') {
		if (isset($param) AND $param !== '') {			
			if ($param == 'hapus') {
				if (isset($_POST['j_action']) AND $_POST['j_action'] !== '') {
					
					if ($_POST['j_action'] == 'delete_absensi' AND trim($id) !== '') {
						
						$this->db->delete('absensi_mapel', array('id' => $id));
						
						$this->data['msg'] = setMessage('delete', 'absensi');
						$this->load->view('template/msg', $this->data);
					}
				}
				else {
					if (isset($id) AND trim($id) !== '') {
						$this->data['row'] = $this->absensi->get_detail_absensi($id);
						$this->load->view('absensi/hapus', $this->data);
					}
				}		
			}
			else
				redirect('absensi');
		}
		else {
			redirect('absensi');
		}		
	}
	
	function data($id = '') {		
				
		$this->form_validation->set_rules('db_tanggal', 'Tanggal', 'required');
		$this->form_validation->set_rules('db_NIS', 'NIS', 'required');
				
		if ($this->form_validation->run() === FALSE) {	
			$this->load->model('referensi_model', 'ref');
			
			$this->data['ta'] = $this->ref->tahunajaran_aktif();
			$this->data['sem'] = $this->ref->semester_aktif();
			$this->load->view('absensi/form', $this->data);
		}
		else {
			
			if (isset($_POST['j_action']) AND $_POST['j_action'] !== '') {
				
				if ($_POST['j_action'] == 'add_param') {
					$d = parseForm($_POST);
					
					$this->db->insert('absensi_mapel', $d);
					
					$this->data['msg'] = setMessage('insert', 'absensi');
					$this->load->view('template/msg', $this->data);
				}
			} 
			else
				redirect('absensi');
		}	
	}
	//=============================
	//   LIHAT DATA ABSENSI       =
	//=============================
    function lihat() {
        $userid = $this->ion_auth->user()->row()->id;
		$id_pegawai = $this->pegawai_model->get_by('id_user', $userid)->id;
        // layout view
		//cek group
		if($this->ion_auth->in_group(array('superadmin', 'admin'))) {
			$data['kelas'] = $this->nilai->get_kelas();
			$data['dropdown_mapel'] = $this->absensi->dropdown_mapel();
			$this->layout->set_wrapper('lihatdata', $data);
		} 
		elseif($this->ion_auth->in_group('guru')) {
			//dropdown mapel yang diampu
			$data['kelas'] 	 = $this->nilai->get_kelas_by_pegawai($id_pegawai);
			$data['dropdown_mapel'] = $this->absensi->get_dropdown_mapel_by_guru($id_pegawai);
			$data['id_guru'] =  $id_pegawai;
			//view
			$this->layout->set_wrapper('lihatdata_guru', $data);
		}
        $this->layout->auth();
        $template_data['title'] = 'Absensi Kelas';
        $template_data['crumb'] = ['Absensi' => 'absensi',];
        $this->layout->setCacheAssets();
        $this->layout->render('admin', $template_data);
    }
    function datalists() {
		$data['setting'] 	= $this->settings->get();
		$data['namakelas'] 	= $this->sistem_model->get_nama_kelas($_POST['kelas']);
		$data['namamapel'] 	= $this->sistem_model->get_nama_mapel($_POST['mapel']);
		$data['id_mapel']	= $_POST['mapel'];
		
		$data['status'] = array('belum' => 'Belum Absen', 'hadir' => 'Hadir', 'sakit' => 'Sakit', 'izin' => 'Izin', 'alfa' => 'Alfa');
		$data['absen'] 	= $this->absensi->get_data_siswa_perkelas($_POST['kelas']);
		$data['tgl'] 	= parseFormTgl('tanggal');
		$this->load->view('datalist', $data);
    }
	
	
	//========================
	//PRIVATE FUNCTIONS
	//insert ignore
	protected function insert_ignore($table,array $data) {
        $_prepared = array();
         foreach ($data as $col => $val)
        $_prepared[$this->db->escape_str($col)] = $this->db->escape($val); 
        $this->db->query('INSERT IGNORE INTO '.$table.' ('.implode(',',array_keys($_prepared)).') VALUES('.implode(',',array_values($_prepared)).');');
    }
	
	
}