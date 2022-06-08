<?php if (!defined('BASEPATH'))  exit('No direct script access allowed');

class Cetak extends MY_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model(array('laporan_model','kesiswaan/siswa_model','settings_model','sistem_model',
								 'seting/mapel_model','pembelajaran/kelas_model','keuangan/jenispembayaran_model'));
		$this->load->model('rekapspp_model', 'rekapspp');
		$this->load->library(array('fpdf','PdfGenerator'));
    }
	
    /**
     * Index
     */
    public function index()
    {
		//view
		$this->layout->set_wrapper('index', $data);
		$template_data["title"] = "Cetak";
		$template_data["subtitle"] = "Cetak Data";
		$template_data["crumb"] = ["Cetak" => "cetak",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); 
	}
	
	public function all() {		
		$tapel_aktif	= $this->sistem_model->get_tapel_aktif();
		//dropdown tingkat pada tapel aktif
		$data['dropdown_tingkat'] = $this->kelas_model->get_data_tingkat(); 
		//$data['info_soal'] = $info_soal;
		//dropdown pembayaran siswa
		$data['dropdown_pembayaran'] = $this->jenispembayaran_model->get_jenispembayaran();
		$bln = array(1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
		for ($i=1;$i<=12;$i++){
			$aBln= (strlen($i)==1 ? '0'.$i : $i);
			$dBln[$aBln] = $bln[$i]; 
		}
		
		$data['bln']	= $dBln;
		$data['kelas']  = $this->rekapspp->get_kelas();
						
		//View
		$template_data['title'] = 'Print ';
		$template_data['subtitle'] = 'Cetak Data';
        $template_data['crumb'] = ['Cetak' => 'cetak', ];
		
		$this->layout->set_wrapper('view_cetak', $data);
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
	
	
	//tampilkan semua mapel berdasarkan tingkat
	public function getMapelAjax($id_tingkat){ 
		// POST data 
		//$id_tingkat = $this->input->post();		
		// get data 
		$this->db->select('seting_mapel.id, mapel, mapel_tingkat.id_mapel');
		$this->db->from('mapel_tingkat');
		$this->db->join('seting_mapel', 'seting_mapel.id = mapel_tingkat.id_mapel');
		$this->db->where('id_tingkat', $id_tingkat);
		$data = $this->db->get()->result();
		
		echo json_encode($data); 
	}	
 	function getMapel($id_tingkat){
		// get data 
		$this->db->select('seting_mapel.id, mapel');
		$this->db->from('mapel_tingkat');
		$this->db->join('seting_mapel', 'seting_mapel.id = mapel_tingkat.id_mapel');
		$this->db->where('id_tingkat', $id_tingkat);
		$query = $this->db->get();
		
	    $data = "<option value=''> -- Pilih Mata Pelajaran -- </option>";
	    foreach ($query->result() as $value) {
	        $data .= "<option value='".$value->id."'>".$value->mapel."</option>";
	    }
	    echo $data;
	}
	//ambil data kuis
	public function getKuisAjax($id_mapel) {
		// get data 
		$this->db->select('id, name');
		$this->db->from('soal');
		$this->db->where('id_mapel', $id_mapel);
		$data = $this->db->get()->result();
		
		echo json_encode($data);
	}
 	function getKuis($id_mapel){
		// get data 
		$this->db->select('*');
		$this->db->from('soal');
		$this->db->where('id_mapel', $id_mapel);
		$query = $this->db->get();
		
	    $data = "<option value=''> -- Pilih Soal -- </option>";
	    foreach ($query->result() as $value) {
	        $data .= "<option value='".$value->id."'>".$value->name."</option>";
	    }
	    echo $data;
	}

	function infoSoal($id_soal){
		$this->load->model(array('soal/soal_model','soal/itemsoal_model',));
		$jml_soal = $this->itemsoal_model->count_by('id_soal',$id_soal);
		
		echo $jml_soal;
	}
 	function getSiswa($id_kelas){
		// get data 
		$this->db->select('siswa_kelas.id_siswa, siswa.nama');
		$this->db->from('siswa_kelas');
		$this->db->join('siswa', 'siswa.id = siswa_kelas.id_siswa');
		$this->db->where('id_kelas', $id_kelas);
		$this->db->where('status', 'Aktif');
		$query = $this->db->get();
		
	    $data = "<option value=''> -- Pilih Siswa -- </option>";
	    foreach ($query->result() as $value) {
	        $data .= "<option value='".$value->id_siswa."'>".$value->nama."</option>";
	    }
	    echo $data;
	}	
} //end	