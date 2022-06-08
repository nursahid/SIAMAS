<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Tinggalkelas Controller.
 */
class Tinggalkelas extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();
		$this->load->model('kelas_model', 'kelas');	
		$this->load->model('kenaikankelas_model', 'kenaikankelas');	
		$this->load->model('tinggalkelas_model', 'tinggalkelas');
		$this->load->library('form_validation');
        $this->title = "Siswa";
    }

    /***************************
     * Siswa per Kelas
     **************************/
	function index() {
		$this->form_validation->set_error_delimiters('<div class="text-red"><i class="fa fa-times"></i> &nbsp;', '</div>');
		
		$this->form_validation->set_rules('kelasakhir', 'Kelas Akhir', 'required');
		
		if ($this->form_validation->run() === FALSE) {
			$data['kelastahunlalu'] = $this->tinggalkelas->get_kelas_tinggalkelas();
			$data['kelassaatini'] 	= $this->tinggalkelas->get_kelas_tinggalkelas();
			$data['thnsebelum'] 	= $this->tinggalkelas->get_ta();
			$data['thnsesudah'] 	= $this->tinggalkelas->get_ta('aktif');
			$data['tahunaktif'] 	= $this->sistem_model->get_tapel_aktif();
			
			//$this->session->set_flashdata('message', "<div class='text-red'><i class='fa fa-times'></i> Kelas Tujuan Belum dipilih</div>");
			
			$this->layout->set_wrapper('tinggalkelas/tinggalkelas', $data);
			//view
			$template_data["title"] = "Pembelajaran";
			$template_data["subtitle"] = "Tinggal Kelas";
			$template_data["crumb"] = ["Pembelajaran" => "pembelajaran", "Tinggal Kelas" => "pembelajaran/tinggalkelas"];
			$this->layout->auth();
			$this->layout->render('admin', $template_data); 			
			
		}
		else {
			//kelas tujuan harus dipilih
			if($_POST['kelasakhir'] == '0') {
				//tampilkan error
				$this->session->set_flashdata('message', "<div class='text-red'><i class='fa fa-times'></i> Kelas Tujuan Belum dipilih</div>");
				redirect('pembelajaran/tinggalkelas');
			}
			else {
				//tetap di kelas yang sama untuk tahun ajaran yang aktif saat ini
				if (isset($_POST['j_action']) && $_POST['j_action'] == 'add_param') {
					for ($i = 0; $i < sizeof($_POST['firstList']); $i++) {
						$d['id_kelas'] = $_POST['kelasakhir'];
						$d['id_tahun'] = $this->sistem_model->get_tapel_aktif();
						$d['id_siswa'] = $_POST['firstList'][$i];
						
						$this->db->insert('siswa_kelas', $d);
					}
					$this->session->set_flashdata('message', "<div class='text-red'><i class='fa fa-check'></i> Sukses memindahkan data siswa</div>");
					redirect('pembelajaran/tinggalkelas');
				}
			}
		}
	}	
	//load data
	function loadkelas($idkelas, $idtahun) {
		$data['result']	= $this->tinggalkelas->list_siswa_perkelas($idkelas, $idtahun);	
		$this->load->view('tinggalkelas/kelasawal', $data);		
	}
	//pilih kelas tujuan
	function pilihkelas2($kelas) {
		$data['result'] = $this->tinggalkelas->list_kelas_tujuan($kelas);
		$this->load->view('tinggalkelas/kelastujuan', $data);
	}	
	//pilih kelas tujuan
	function pilihkelas() {
		$data['result'] = $this->tinggalkelas->list_kelas_tujuan($_POST['db_idkelas']);
		$this->load->view('tinggalkelas/kelastujuan', $data);
	}
}

/* End of file example.php */
/* Location: ./application/modules/kesiswaaan/controllers/Kenaikankelas.php */