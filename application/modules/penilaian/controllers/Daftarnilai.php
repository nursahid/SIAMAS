<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Daftarnilai Controller.
 */
class Daftarnilai extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();
		$this->load->model('absensi/absensi_model', 'absensi');	
		$this->load->model('nilai_model', 'nilai');
        $this->load->model(array('jenispenilaian_model','penilaian_model','seting/jurusan_model','pegawai/pegawai_model','pembelajaran/kelas_model'));
    }

    /**
     * Index
     */
	public function index()
    {
        $userid = $this->ion_auth->user()->row()->id;
		$id_pegawai = $this->pegawai_model->get_by('id_user', $userid)->id;
        // layout view
		//cek group
		if($this->ion_auth->in_group(array('superadmin', 'admin'))) {
			$data['dropdown_jenis'] = $this->jenispenilaian_model->get_dropdown();
			$data['dropdown_jurusan'] = $this->jurusan_model->dropdown_jurusan();
			$data['dropdown_tingkat'] = $this->kelas_model->get_data_tingkat();
			$data['dropdown_mapel'] = $this->nilai->get_mapel_by_pegawai($id_pegawai); // mapel by tingkat
			//$data['dropdown_kelas_bytingkat'] = $this->kelas_model->get_kelas_by_tingkat();
			$this->layout->set_wrapper('lihat_daftarnilai', $data);
		} 
		elseif($this->ion_auth->in_group('guru')) {
			//redirek
			redirect('penilaian/daftarnilai/lihat');
		}
        $this->layout->auth();
        $template_data['title'] = 'Daftarnilai';
        $template_data['crumb'] = ['Daftarnilai' => 'Daftarnilai',];
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
		$this->load->view('daftarnilai_pilihkelas', $data);
    }
	//=================================
	//3. Tampilkan data siswa by kelas
	//---------------------------------
    public function lihat($id_jenispenilaian='',$id_kelas='')
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
			$this->layout->set_wrapper('lihat_daftarnilai', $data);
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
			$this->layout->set_wrapper('lihat_daftarnilai_guru', $data);
		}
        $this->layout->auth();
        $template_data['title'] = 'Daftar Nilai';
        $template_data['crumb'] = ['Daftar Nilai' => 'daftarnilai',];
        $this->layout->setCacheAssets();
        $this->layout->render('admin', $template_data);
    }

	//====================================================
	//  4. Menampilkan data siswa by kelas masing-masing =
	//====================================================
    //function lists($penilaian='',$kelas='',$mapel='',$nip='') {
	function lists() {
		//variabel post
		$penilaian = $_POST['jenis_penilaian'];
		$kelas	= $_POST['kelas'];
		$mapel 	= $_POST['mapel'];
		$nip	= $_POST['pengampu'];
		
		$data['setting'] 	= $this->settings->get();
		$data['datanilai'] 	= $this->nilai->get_data_siswa_perkelas($_POST['kelas']);
		$data['namakelas'] 	= $this->sistem_model->get_nama_kelas($_POST['kelas']);
		$data['namamapel'] 	= $this->sistem_model->get_nama_mapel($_POST['mapel']);
		$data['tgl'] 		= parseFormTgl('tanggal');
		$data['post']		= $_POST;
		//cek group
		if($this->ion_auth->in_group(array('superadmin', 'admin'))) {
			//buat admin
			$this->load->view('list_datanilai', $data);
		} 
		elseif($this->ion_auth->in_group('guru')) {
			//buat guru
			$this->load->view('list_datanilai_guru', $data);
		}
    }
	
	function namakelas($kelas) {
		$data = $this->sistem_model->get_nama_kelas($kelas);
		echo $data;
	}
	
	//========================
	// ALL AJAX
	//------------------------
 	function getTingkatAjax($id_jurusan){
		$query = $this->db->query("SELECT * FROM seting_tingkat WHERE id_jurusan=".$id_jurusan."");	    
		$data = $query->result();
		
		echo json_encode($data);
	}
 	function getKelasAjax($id_jurusan,$id_tingkat){
		$query = $this->db->query("SELECT * FROM kelas WHERE id_jurusan=".$id_jurusan." AND id_tingkat=".$id_tingkat." AND id_tahun=(SELECT id FROM seting_tahun_ajaran WHERE status='Y')");
	    
		$data = $query->result();
		
		echo json_encode($data);
	}

	//tampilkan semua mapel berdasarkan tingkat
	public function getMapelAjax($id_jurusan,$id_tingkat){ 
		$this->db->select('seting_mapel.id, mapel');
		$this->db->from('mapel_tingkat');
		$this->db->join('seting_mapel', 'seting_mapel.id = mapel_tingkat.id_mapel');
		$this->db->where('id_jurusan', $id_jurusan);
		$this->db->where('id_tingkat', $id_tingkat);
		$data = $this->db->get()->result();
		
		echo json_encode($data); 
	}
	//=======================
	//        NO AJAX       -
	//-----------------------
	public function getMapel($id_jurusan,$id_tingkat){ 
		$this->db->select('seting_mapel.id, mapel');
		$this->db->from('mapel_tingkat');
		$this->db->join('seting_mapel', 'seting_mapel.id = mapel_tingkat.id_mapel');
		$this->db->where('id_jurusan', $id_jurusan);
		$this->db->where('id_tingkat', $id_tingkat);
		$query = $this->db->get();
		
		$data = "<option value=''> -- Pilih Mata Pelajaran -- </option>";
	    foreach ($query->result() as $value) {
	        $data .= "<option value='".$value->id."'>".$value->mapel."</option>";
	    }
	    echo $data;
	}
	
 	function getKelas($id_tingkat){
		$query = $this->db->query("SELECT * FROM kelas WHERE id_tingkat=".$id_tingkat." AND id_tahun=(SELECT id FROM seting_tahun_ajaran WHERE status='Y')");
	    
		$data = "<option value=''> -- Pilih Kelas -- </option>";
	    foreach ($query->result() as $value) {
	        $data .= "<option value='".$value->id_tingkat."'>".$value->kelas."</option>";
	    }
	    echo $data;
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
	//Jenis Daftarnilai KONSEP	
	function jnsDaftarnilai_konsep_add_callback(){
		$res = $this->db->select('*')->where('id', '1')->get('nilai_jenis_Daftarnilai')->row();
		return '<input type="hidden" name="id_jenispenilaian" value="'.$res->id.'"><strong>'.$res->nama_jnsDaftarnilai.'</strong>';
	}
	//Jenis Daftarnilai PRAKTEK	
	function jnsDaftarnilai_praktek_add_callback(){
		$res = $this->db->select('*')->where('id', '2')->get('nilai_jenis_Daftarnilai')->row();
		return '<input type="hidden" name="id_jenispenilaian" value="'.$res->id.'"><strong>'.$res->nama_jnsDaftarnilai.'</strong>';
	}
	function idsiswa_callback() {
		$id_Daftarnilai = $this->uri->segment(6);		
		$q1 = $this->db->get_where('nilai_Daftarnilai', array('id' => $id_Daftarnilai), 1)->row();
		$q2 = $this->db->get_where('siswa', array('id' => $q1->id_siswa), 1)->row();
		return '<input type="hidden" name="id_siswa" value="'.$q2->id.'"><strong>'.$q2->nama.'</strong>';
	}
	function huruf_update_callback($post_array, $primary_key) {
		$data = array(
				'huruf' => ucwords(terbilang($post_array['nilai']))
			);
		$this->db->update('nilai_Daftarnilai',$data,array('id'=>$primary_key));
		return true;
	}
}

/* End of file Daftarnilai.php */
/* Location: ./application/modules/Daftarnilai/controllers/Daftarnilai.php */