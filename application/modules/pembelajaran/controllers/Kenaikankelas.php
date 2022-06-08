<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Kenaikankelas Controller.
 */
class Kenaikankelas extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();
		$this->load->model('kelas_model', 'kelas');	
		$this->load->model('kenaikankelas_model', 'kenaikankelas');	
		$this->load->model('tinggalkelas_model', 'tinggalkelas');
        $this->title = "Siswa";
    }

    /***************************
     * Kenaikan per Kelas
     **************************/
	function index() {
		if (isset($_POST['j_action']) && $_POST['j_action'] == 'add_param') {
			for ($i = 0; $i < sizeof($_POST['secondList']); $i++) {
				$d['id_kelas'] = $_POST['kelasakhir'];
				$d['id_tahun'] = $this->kelas->tahunajaran_aktif();
				$d['id_siswa'] = $_POST['secondList'][$i];
				
				$arr = $this->db->get_where('siswa_kelas', array('id_kelas' => $_POST['kelasakhir'], 'id_tahun' => $this->kelas->tahunajaran_aktif(), 'id_siswa' => $_POST['secondList'][$i]));
				if ($arr->num_rows() > 0) {
				}
				else {
					$this->db->insert('siswa_kelas', $d);				
				}
			}
			redirect('pembelajaran/kenaikankelas');
		}
		else {
			$data['kenaikankelas'] = $this->kenaikankelas->get_data_kenaikankelas();
			//data['tahunajaran'] = $this->kenaikankelas->get_tahun_ajaran();	
			$data['thnsebelum'] 	= $this->kenaikankelas->get_ta();
			$data['thnsesudah'] 	= $this->kenaikankelas->get_ta('Y');
			$data['tahunaktif'] 	= $this->kelas->tahunajaran_aktif();
						
			$this->layout->set_wrapper('kenaikankelas/kenaikankelas', $data);
			//view
			$template_data["title"] = "Pembelajaran";
			$template_data["subtitle"] = "Kenaikan Kelas";
			$template_data["crumb"] = ["Pembelajaran" => "pembelajaran", "Kenaikan Kelas" => "pembelajaran/kenaikankelas"];
			$this->layout->auth();
			$this->layout->render('admin', $template_data); 
		}
	}	
	//load data
	function loadkelas($idkelas, $idtahun) {
		$data['result']	= $this->kenaikankelas->list_siswa_perkelas($idkelas, $idtahun);	
		$this->load->view('kenaikankelas/kelasawal', $data);		
	}
	
	function loadkelasakhir($idkelas, $idtahun) {
		$data['result']	= $this->kenaikankelas->list_siswa_sudah_naik_kelas($idkelas, $idtahun);	
		$this->load->view('kenaikankelas/kelasakhir', $data);		
	}
	
	function pilihkelas() {
		$data['result'] = $this->kenaikankelas->list_kelas_tujuan($_POST['mp']);
		$this->load->view('kenaikankelas/kelastujuan', $data);
	}

	//tes
	function pilihkelas2($kelas) {
		$data['result'] = $this->kenaikankelas->list_kelas_tujuan($kelas);
		$this->load->view('kenaikankelas/kelastujuan', $data);
	}
}

/* End of file example.php */
/* Location: ./application/modules/kesiswaaan/controllers/Kenaikankelas.php */