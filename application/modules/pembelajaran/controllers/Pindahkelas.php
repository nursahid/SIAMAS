<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Pindahkelas Controller.
 */
class Pindahkelas extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();
		$this->load->model('kelas_model', 'kelas');	
		$this->load->model('kenaikankelas_model', 'kenaikankelas');	
		$this->load->model('pindahkelas_model', 'pindahkelas');
		$this->load->library('form_validation');
        $this->title = "Siswa";
    }

    /***************************
     * Siswa per Kelas
     **************************/
	function index() {
		if (isset($_POST['j_action']) && $_POST['j_action'] == 'add_param') {
			for ($i = 0; $i < sizeof($_POST['secondList']); $i++) {
				
				$tapel_aktif = $this->kelas->tahunajaran_aktif();
				
				$d['id_kelas'] = $_POST['kelasakhir'];
				$d['id_tahun'] = $tapel_aktif;
				$d['id_siswa'] = $_POST['secondList'][$i];
				
				$arr = $this->db->get_where('siswa_kelas', array('id_kelas' => $_POST['kelasakhir'], 'id_tahun' => $tapel_aktif, 'id_siswa' => $_POST['secondList'][$i]));
				if ($arr->num_rows() > 0) {
					//update
					$data = array('id_kelas'=>$_POST['kelasakhir']);
					$this->db->where('id_siswa', $_POST['secondList'][$i]);
					$this->db->update('siswa_kelas', $data);
				}
				else {
					//jika belum ada data, insert
					$this->db->insert('siswa_kelas', $d);				
				}
			}
			redirect('pembelajaran/pindahkelas');
		}
		else {
			//data kelas di tapel aktif
			$data['dropdownkelas'] = $this->pindahkelas->get_kelas();
			//data['tahunajaran'] = $this->pindahkelas->get_tahun_ajaran();	
			$data['thnsebelum'] 	= $this->pindahkelas->get_ta();
			$data['thnsesudah'] 	= $this->pindahkelas->get_ta('Y');
			
			$data['thnsekarang'] 	= $this->pindahkelas->get_ta('Y');
			$data['tahunaktif'] 	= $this->kelas->tahunajaran_aktif();
						
			$this->layout->set_wrapper('pindahkelas/pindahkelas', $data);
			//view
			$template_data["title"] = "Pembelajaran";
			$template_data["subtitle"] = "Pindah Kelas";
			$template_data["crumb"] = ["Pembelajaran" => "pembelajaran", "Pindah Kelas" => "pembelajaran/pindahkelas"];
			$this->layout->auth();
			$this->layout->render('admin', $template_data); 
		}
	}	
	//load data
	function loadkelas($idkelas, $idtahun) {
		$data['result']	= $this->pindahkelas->list_siswa_perkelas($idkelas, $idtahun);	
		$this->load->view('pindahkelas/kelasawal', $data);		
	}
	function loadkelasakhir($idkelas, $idtahun) {
		$data['result']	= $this->pindahkelas->list_siswa_sudah_pindah($idkelas, $idtahun);	
		$this->load->view('pindahkelas/kelasakhir', $data);		
	}	
	function pilihkelas() {
		$data['result'] = $this->pindahkelas->list_kelas_tujuan($_POST['mp']);
		$this->load->view('pindahkelas/kelastujuan', $data);
	}

}

/* End of file Pindahkelas.php */
/* Location: ./application/modules/kesiswaaan/controllers/Pindahkelas.php */