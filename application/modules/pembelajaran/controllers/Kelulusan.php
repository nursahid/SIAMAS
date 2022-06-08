<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Kelulusan Controller.
 */
class Kelulusan extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();
		$this->load->model('kelas_model', 'kelas');	
		$this->load->model('kelulusan_model', 'kelulusan');
		$this->load->library('form_validation');
        $this->title = "Siswa";
    }

    /***************************
     * Siswa per Kelas
     **************************/
	function index() {
		if (isset($_POST['j_action']) && $_POST['j_action'] == 'add_param') {
			for ($i = 0; $i < sizeof($_POST['secondList']); $i++) {
								
				$id_kelas = $_POST['kelas'];
				$id_tahun = $this->kelas->tahunajaran_aktif();
				$id_siswa = $_POST['secondList'][$i];

				//update
				$data = array('angkatan'=>$_POST['tahunkelulusan'], 'status'=>'Alumni');
				$this->db->where('id', $_POST['secondList'][$i]);
				$this->db->update('siswa', $data);
				
				//hapus dari data siswa kelas
				$this->db->where(array( 'id_siswa' => $id_siswa, 'id_kelas' => $id_kelas, 'id_tahun' => $id_tahun));
				$this->db->delete('siswa_kelas');
			}
			redirect('pembelajaran/kelulusan');
		}
		else {
			//data kelas di tapel aktif
			$data['dropdownkelas'] 	= $this->kelulusan->get_kelas_akhir();
			$data['tahunkelulusan'] = htmlYearSelector('tahunkelulusan');
			$data['thnsebelum'] 	= $this->kelulusan->get_ta();
			$data['thnsesudah'] 	= $this->kelulusan->get_ta('Y');
			
			$data['thnsekarang'] 	= $this->kelulusan->get_ta('Y');
			$data['tahunaktif'] 	= $this->kelas->tahunajaran_aktif();
						
			$this->layout->set_wrapper('kelulusan/kelulusansiswa', $data);
			//view
			$template_data["title"] = "Pembelajaran";
			$template_data["subtitle"] = "Kelulusan Kelas";
			$template_data["crumb"] = ["Pembelajaran" => "pembelajaran", "Kelulusan Kelas" => "pembelajaran/kelulusan"];
			$this->layout->auth();
			$this->layout->render('admin', $template_data); 
		}
	}	
	//load data siswa di kelas terakhir
	function loadkelas($idkelas, $idtahun) {
		$data['result']	= $this->kelulusan->list_siswa_perkelas($idkelas, $idtahun);	
		$this->load->view('kelulusan/kelasawal', $data);		
	}
	//load siswa yang status alumni tahun ybs
	function loadkelasakhir($tahun) {
		$data['result']	= $this->kelulusan->list_siswa_sudah_lulus($tahun);	
		$this->load->view('pindahkelas/kelasakhir', $data);		
	}
	
	function pilihkelas() {
		$data['result'] = $this->kelulusan->list_kelas_tujuan($_POST['mp']);
		$this->load->view('kelulusan/kelastujuan', $data);
	}

}

/* End of file Kelulusan.php */
/* Location: ./application/modules/kesiswaaan/controllers/Kelulusan.php */