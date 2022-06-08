<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Kenaikankelas Controller.
 */
class Rekapspp extends MY_Controller {

	var $data;
	
    function __construct() {
		parent::__construct();        
        $this->load->model('rekapspp_model', 'rekapspp');	
		$this->load->model('spp_model', 'spp');
		$this->load->model('kesiswaan/siswa_model', 'siswa');	
    }

    function index() {
		$bln = array(1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
		for ($i=1;$i<=12;$i++){
			$aBln= (strlen($i)==1 ? '0'.$i : $i);
			$dBln[$aBln] = $bln[$i]; 
		}
		
		$data['bln']	= $dBln;
		$data['default'] = date('m');        
		$data['kelas'] = $this->rekapspp->get_kelas();

		$this->layout->set_wrapper('rekapspp_view', $data);
		//view
		$template_data["title"] = "Rekap SPP";
		$template_data["crumb"] = ["Rekap SPP" => "cetak/rekapspp_",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); 		
    }

	function lists() {		
		
		$this->data['status'] = array('belum' => 'Belum Bayar', 'sudah' => 'Sudah Bayar');
		
		if ($_POST['nis'] > 0) {
			$this->data['result'] = $this->rekapspp->get_rekap_spp_persiswa($_POST);
			$this->load->view('rekapspp_listpersiswa', $this->data);
		}
		else {
			$this->data['absen'] = $this->spp->get_data_siswa_perkelas($_POST['kelas']);
			$this->data['post']	= $_POST;
			$this->load->view('rekapspp_list', $this->data);
		}
    }
			
	function ekspor($param1, $param2, $param3) {
		$d = array('bulan' => $param1, 'tahun' => $param2, 'kelas' => $param3);
		$this->data['absen'] = $this->spp->get_data_siswa_perkelas($param3);
		$this->data['post']	= $d;
		$this->load->view('rekapspp_excel', $this->data);
	}
	
	//Lookup
	function lookup( ) {		
		$keyword = strtolower($_POST['q']);  		
        $query = $this->siswa->lookup($keyword); //Search siswa
        if( ! empty($query) ) {
            foreach( $query as $row ) {
				if (strpos(strtolower($row->nama), $keyword) !== false) {
					$key = $row->nama;
					$value = $row->nama;
					$alamat = $row->alamat;
					$nohp2 = $row->no_hp;
					
					echo "$key|$value|$alamat|$nohp2\n";
				}				
            }
        }       
    }
	
	
}