<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Akses Controller.
 */
class Akses extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->load->model(array('soal_model','pembelajaran/kelas_model', 'pegawai/pegawai_model'));
    }

    /**
     * Index
     */
    public function index()
    {
		$crud = new grocery_CRUD();
		
		$crud->set_table("soal_users");
		
		$crud->set_subject("Akses Soal");

		// Show in
		$crud->add_fields(["id_soal", "id_user"]);
		$crud->edit_fields(["id_soal", "id_user"]);
		$crud->columns(["id_soal", "id_user", "status"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("status", "enum");
		//cek group
		if($this->ion_auth->in_group(array('admin', 'members'))) {
			// Relation n-n
			$crud->set_relation("id_soal", "soal", "name");
			$crud->set_relation("id_user", "siswa", "nama", array('status'=>'Aktif'));
		} 
		elseif($this->ion_auth->in_group('guru')) {
			$user = $this->ion_auth->user()->row()->id;
			$id_pegawai = $this->pegawai_model->get_by('id_user',$user)->id;
			//CUSTOM DROPDOWN
			$crud->set_relation("id_soal", "soal", "name", array('id_pegawai'=>$id_pegawai));
			$crud->set_relation("id_user", "siswa", "nama", array('status'=>'Aktif'));
		}

		// Validation
		$crud->set_rules("id_soal", "Soal", "required");
		$crud->set_rules("id_user", "Siswa", "required");

		// Display As
		$crud->display_as("id_soal", "Soal");
		$crud->display_as("id_user", "Siswa");

		// Unset action

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Akses Soal";
		$template_data["crumb"] = [];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
}

/* End of file example.php */
/* Location: ./application/modules/siswa/controllers/Siswa.php */