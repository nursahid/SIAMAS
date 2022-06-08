<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Penilaian Controller.
 */
class Penilaian extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();
		$this->load->model('absensi/absensi_model', 'absensi');	
		$this->load->model('nilai_model', 'nilai');
        $this->load->model(array('jenispenilaian_model','seting/jurusan_model','pegawai/pegawai_model','pembelajaran/kelas_model'));
    }

    /**
     * Index
     */
    public function index()
    {
		//view
		$this->layout->set_wrapper('index', $data);
		$template_data["title"] = "Penilaian";
		$template_data["subtitle"] = "Manajemen Data Nilai";
		$template_data["crumb"] = ["Penilaian" => "penilaian",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); 
	}
	
	public function data()
    {
        $userid = $this->ion_auth->user()->row()->id;
		$id_pegawai = $this->pegawai_model->get_by('id_user', $userid)->id;
        // layout view
		//cek group
		if($this->ion_auth->in_group(array('superadmin', 'admin'))) {
			$data['dropdown_prodi'] = $this->jurusan_model->dropdown_jurusan();
			$data['dropdown_tingkat'] = $this->kelas_model->get_data_tingkat();
			$data['dropdown_jenis'] = $this->jenispenilaian_model->get_dropdown();
			//$data['dropdown_kelas_bytingkat'] = $this->kelas_model->get_kelas_by_tingkat();
			$this->layout->set_wrapper('index_penilaian', $data);
		} 
		elseif($this->ion_auth->in_group('guru')) {
			//redirek
			redirect('penilaian/tambah');
		}
        $this->layout->auth();
        $template_data['title'] = 'Penilaian';
        $template_data['crumb'] = ['Penilaian' => 'penilaian',];
        $this->layout->setCacheAssets();
        $this->layout->render('admin', $template_data);		
	}

	//===================
	//2. pilih tingkat
	//-------------------
    function pilihkelas() {
		$id_jurusan = $_POST['jurusan'];
		$id_tingkat = $_POST['tingkat'];
		$id_tahun	= $this->sistem_model->get_tapel_aktif();
		$data['datakelas'] = $this->kelas_model->get_many_by(array('id_jurusan'=>$id_jurusan, 'id_tingkat'=>$id_tingkat, 'id_tahun'=>$id_tahun));
		$data['tgl'] = parseFormTgl('tanggal');
		$this->load->view('index_pilihkelas', $data);
    }
	//=================================
	//3. Tampilkan data siswa by kelas
	//---------------------------------
	public function manage($id_jnspenilaian,$id_kelas)
	{
		//$id_jnspenilaian	= $_POST['jenis_penilaian'];
		//$id_kelas	= $_POST['kelas'];
		$penilaian = $this->jenispenilaian_model->get_by('id',$id_jnspenilaian)->jenis_penilaian;
		
		$crud = new grocery_CRUD();
		
		$crud->set_table("nilai_penilaian");
		$crud->where('id_jnspenilaian',$id_jnspenilaian);
		$crud->where('id_kelas',$id_kelas);
		
		$crud->set_subject("Penilaian ".ucfirst(strtolower($penilaian)));

		// Show in
		$crud->add_fields(["id_jnspenilaian", "id_siswa", "id_mapel", "deskripsi", "nilai"]);
		$crud->edit_fields(["id_siswa", "tgl_penilaian", "deskripsi", "nilai"]);
		$crud->columns(["id_kelas", "id_siswa", "id_mapel", "deskripsi", "nilai"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("deskripsi", "text");
		$crud->field_type("nilai", "integer");
		$crud->field_type("huruf", "string");

		// Relation n-n
		$crud->set_relation("id_jnspenilaian", "nilai_jenis_penilaian", "jenis_penilaian");
		$crud->set_relation("id_kelas", "kelas", "kelas");
		$crud->set_relation("id_siswa", "siswa", "nama");
		$crud->set_relation("id_mapel", "seting_mapel", "mapel");

		// Validation
		$crud->set_rules("id_jnspenilaian", "Id jnspenilaian", "required");
		$crud->set_rules("id_siswa", "Id siswa", "required");
		$crud->set_rules("deskripsi", "Deskripsi", "required");
		$crud->set_rules("nilai", "Nilai", "required");

		// Display As
		$crud->display_as("id_jnspenilaian", "Jenis Penilaian");
		$crud->display_as("id_kelas", "Kelas");
		$crud->display_as("id_siswa", "Nama Santri");
		$crud->display_as("id_mapel", "Pelajaran");
		$crud->display_as("deskripsi", "Deskripsi");
		$crud->display_as("nilai", "Nilai");
		$crud->display_as("huruf", "Huruf");
		$crud->display_as("tgl_penilaian", "Tanggal Penilaian");

		// callback
		$crud->callback_add_field('id_jnspenilaian',array($this,'jnspenilaian_konsep_add_callback'));
		$crud->callback_edit_field('id_siswa',array($this,'idsiswa_callback'));
		$crud->callback_after_update(array($this,'huruf_update_callback'));
		
		//unset
		$crud->unset_read();
		$crud->unset_texteditor('deskripsi');
		//state
		$state = $crud->getState();
		$state_info = $crud->getStateInfo();
		if($state == 'add') {
		    $id_jnspenilaian = $this->uri->segment(3);
			$id_kelas = $this->uri->segment(4);
		    redirect('penilaian/tambah/'.$id_jnspenilaian.'/'.$id_kelas);
		}
		
		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);
				
		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Penilaian ".ucfirst(strtolower($penilaian));
		$template_data["crumb"] = ["Penilaian Konsep" => "penilaian/konsep",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
	//===================
	//TAMBAH DATA
	//-------------------
    public function tambah($id_jnspenilaian='',$id_kelas='')
    {
        $userid = $this->ion_auth->user()->row()->id;
		$qy = $this->pegawai_model->get_by('id_user', $userid);
		$id_pegawai = $qy->id;
        // layout view
		//cek group
		if($this->ion_auth->in_group(array('superadmin', 'admin'))) {
			//$data['dropdown_tingkat'] = $this->kelas_model->get_data_tingkat(); 
			$data['dropdown_kelas'] = $this->kelas_model->dropdown_kelas($id_kelas);
			$data['datakelas'] = $this->kelas_model->get_by('id',$id_kelas);
			$this->layout->set_wrapper('viewnilai', $data);
		} 
		elseif($this->ion_auth->in_group('guru')) {
			//dropdown mapel yang diampu
			$data['dropdown_kelas'] = $this->nilai->get_kelas_by_pegawai($id_pegawai);
			$data['dropdown_mapel'] = $this->nilai->get_dropdown_mapel_by_guru($id_pegawai);
			$data['dropdown_jenis'] = $this->jenispenilaian_model->get_dropdown();
			$data['id_guru'] 		= $id_pegawai;
			$data['nippegawai'] 	= $qy->nip;
			$data['namapegawai'] 	= $qy->nama;
			//view
			$this->layout->set_wrapper('viewnilai_guru', $data);
		}
        $this->layout->auth();
        $template_data['title'] = 'Penilaian';
        $template_data['crumb'] = ['Penilaian' => 'penilaian',];
        $this->layout->setCacheAssets();
        $this->layout->render('admin', $template_data);
    }

	//=============================
	//  KONSEP
	//=============================
    function lists() {
		$this->load->model('absensi/absensi_model');
		$data['datasiswa'] = $this->nilai->get_data_siswa_perkelas($_POST['kelas']);
		$data['tgl'] = parseFormTgl('tanggal');
		$this->load->view('listnilai', $data);
    }
	function add() {
		if (isset($_POST['j_action']) && $_POST['j_action'] == 'add_nilai') {
			//jika pengampu kosong
			if($_POST['pengampu'] == '') {
				//pesan error
				$this->session->set_flashdata('message', "<div class='text-red'><i class='fa fa-times'></i> Belum ada pengampu</div>");
				redirect('penilaian/manage/'.$this->uri->segment(3).'/'.$this->uri->segment(4));
			} else {
				foreach($_POST['idsiswa'] AS $key => $val) {
					$tapel = $this->sistem_model->get_tapel_aktif();
					$semester = $this->sistem_model->get_semester_aktif();
					$tanggal = $_POST['thn_tanggal'].'-'.$_POST['bln_tanggal'].'-'.$_POST['tgl_tanggal'];
					//$tanggal = parseFormTgl('tanggal');
					$data = array(
								'id_jnspenilaian' => $_POST['jenis_penilaian'],
								'id_mapel'	=> $_POST['mapel'],
								'tgl_penilaian'	=> $tanggal,
								'id_kelas'	=> $_POST['kelas'],
								'id_tahun'	=> $tapel,
								'id_semester'	=> $semester,
								'id_siswa' 	=> $_POST['idsiswa'][$key],
								'nip' => $_POST['pengampu'],
								'deskripsi' => $_POST['deskripsi'],
								'nilai' => $_POST['nilai'][$key],
								'huruf' => ucwords(terbilang($_POST['nilai'][$key]))
							);				

					$insert_query = $this->insert_ignore('nilai_penilaian', $data);
				}
			}
			
		}
	}
	
	//========================
	// ALL AJAX
	//------------------------
 	function getTingkatAjax($id_jurusan){
		$query = $this->db->query("SELECT * FROM seting_tingkat WHERE id_jurusan=".$id_jurusan."");	    
		$data = $query->result();
		
		echo json_encode($data);
	}
 	function getKelasAjax($id_tingkat){
		$query = $this->db->query("SELECT * FROM kelas WHERE id_tingkat=".$id_tingkat." AND id_tahun=(SELECT id FROM seting_tahun_ajaran WHERE status='Y')");
	    
		$data = $query->result();
		
		echo json_encode($data);
	}

	//tampilkan semua mapel berdasarkan tingkat
	public function getMapelAjax($id_tingkat){ 
		// POST data 
		$this->db->select('seting_mapel.id, mapel');
		$this->db->from('mapel_tingkat');
		$this->db->join('seting_mapel', 'seting_mapel.id = mapel_tingkat.id_mapel');
		$this->db->where('id_tingkat', $id_tingkat);
		$data = $this->db->get()->result();
		
		echo json_encode($data); 
	}
 	function getKelas($id_tingkat){
		$query = $this->db->query("SELECT * FROM kelas WHERE id_tingkat=".$id_tingkat." AND id_tahun=(SELECT id FROM seting_tahun_ajaran WHERE status='Y')");
	    
		$data = "<option value=''> -- Pilih Kelas -- </option>";
	    foreach ($query->result() as $value) {
	        $data .= "<option value='".$value->id_tingkat."'>".$value->kelas."</option>";
	    }
	    echo $data;
	}
 	function getPengampu2($id_mapel){
	    $query = $this->nilai->get_pegawai_by_mapel($id_mapel);
	    $data  = "<input type='hidden' name='pengampu' id='pengampu' value='".$query->nip."' class='input-sm' />".$query->nama."";
	    if($data == NULL) {
			$info = "Blm Ada Pengampu";			
		} else {
			$info = $data;
		}
		echo $info;
	}
 	function getPengampu($id_mapel){
		$this->db->select('pegawai_mapel.id_pegawai as id, pegawai_mapel.id_mapel, pegawai.nama, pegawai.nip');
		$this->db->from('pegawai_mapel');
		$this->db->join('pegawai', 'pegawai.id = pegawai_mapel.id_pegawai');
		$this->db->where('id_mapel', $id_mapel);
		
		$arr = $this->db->get();
		$query = $arr->row();
	    if($arr->num_rows() > 0) {
			$data  = "<input type='hidden' name='pengampu' id='pengampu' value='".$query->nip."' class='input-sm' />".$query->nama."";
		} else {
			$data = "Belum ada pengampu";
		}
		echo $data;
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
	
	//=====================
	// ALL CALLBACK
	//=====================
	//Jenis Penilaian KONSEP	
	function jnspenilaian_konsep_add_callback(){
		$res = $this->db->select('*')->where('id', '1')->get('nilai_jenis_penilaian')->row();
		return '<input type="hidden" name="id_jnspenilaian" value="'.$res->id.'"><strong>'.$res->nama_jnspenilaian.'</strong>';
	}
	//Jenis Penilaian PRAKTEK	
	function jnspenilaian_praktek_add_callback(){
		$res = $this->db->select('*')->where('id', '2')->get('nilai_jenis_penilaian')->row();
		return '<input type="hidden" name="id_jnspenilaian" value="'.$res->id.'"><strong>'.$res->nama_jnspenilaian.'</strong>';
	}
	function idsiswa_callback() {
		$id_penilaian = $this->uri->segment(6);		
		$q1 = $this->db->get_where('nilai_penilaian', array('id' => $id_penilaian), 1)->row();
		$q2 = $this->db->get_where('siswa', array('id' => $q1->id_siswa), 1)->row();
		return '<input type="hidden" name="id_siswa" value="'.$q2->id.'"><strong>'.$q2->nama.'</strong>';
	}
	function huruf_update_callback($post_array, $primary_key) {
		$data = array(
				'huruf' => ucwords(terbilang($post_array['nilai']))
			);
		$this->db->update('nilai_penilaian',$data,array('id'=>$primary_key));
		return true;
	}
}

/* End of file Penilaian.php */
/* Location: ./application/modules/Penilaian/controllers/Penilaian.php */