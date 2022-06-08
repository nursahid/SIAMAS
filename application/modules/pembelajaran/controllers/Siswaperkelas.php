<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Siswaperkelas Controller.
 */
class Siswaperkelas extends MY_Controller
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
     * Siswa per Kelas
     **************************/
	function index() {
		if (isset($_POST['j_action']) && $_POST['j_action'] == 'add_param') {
			for ($i = 0; $i < sizeof($_POST['secondList']); $i++) {
				$d['id_siswa']	  = $_POST['secondList'][$i];
				$d['id_kelas'] = $_POST['kelasakhir'];
				$d['id_tahun'] = $this->kelas->tahunajaran_aktif();
				$this->db->insert('siswa_kelas', $d);				
			}
			redirect('pembelajaran/siswaperkelas');
		}
		else {
			$data['result'] = $this->kelas->get_siswa_no_class();
			$data['kelas'] = $this->kelas->get_data_kelas();
			$data['row'] = $this->kelas->get_data_kelas();
						
			$this->layout->set_wrapper('siswaperkelas', $data);
			//view
			$template_data["title"] = "Pembelajaran";
			$template_data["subtitle"] = "Pengelolaan Rombel";
			$template_data["crumb"] = ["Pembelajaran" => "pembelajaran", "Siswa Kelas" => "pembelajaran/siswakelas"];
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
	
	//====================
    public function index2()
    {
		$crud = new grocery_CRUD();
		
		$crud->set_table("siswa_kelas");
		$crud->set_subject("Pembagian Kelas");

		// Show in
		$crud->add_fields(["id_siswa", "id_kelas"]);
		$crud->edit_fields(["id_siswa", "id_kelas"]);
		$crud->columns(["id_siswa", "id_kelas"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->set_relation("id_siswa", "siswa", "nama");
		$crud->set_relation("id_kelas", "kelas", "nama");

		// Relation n-n

		// Validation
		$crud->set_rules("id_siswa", "Id siswa", "required");

		// Display As
		$crud->display_as("id_siswa", "Siswa");
		$crud->display_as("id_kelas", "Kelas");

		// Unset action

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Pembagian Kelas";
		$template_data["crumb"] = [];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
}

/* End of file example.php */
/* Location: ./application/modules/kesiswaaan/controllers/Siswaperkelas.php */