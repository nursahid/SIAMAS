<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Tingkat Controller.
 */
class Tktkelas extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Tingkat";
    }

    /**
     * Index
     */
    public function index()
    {
		$crud = new grocery_CRUD();
		
		$crud->set_table("seting_tingkat");
		$crud->set_subject("Tingkat");

		// Show in
		$crud->add_fields(["nama", "tingkat"]);
		$crud->edit_fields(["nama", "tingkat"]);
		$crud->columns(["tingkat", "nama", "id_jurusan"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("nama", "string");
		$crud->field_type("tingkat", "string");

		// Relation
		$crud->set_relation("id_jurusan", "ref_jurusan", "jurusan");

		// Validation
		$crud->set_rules("nama", "Nama", "required");
		$crud->set_rules("tingkat", "Tingkat", "required");

		// Display As
		$crud->display_as("nama", "Nama");
		$crud->display_as("tingkat", "Tingkat");
		$crud->display_as("id_jurusan", "Jenjang");

		// Unset action
		$crud->unset_add();
		$crud->unset_delete();

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Tingkat";
		$template_data["crumb"] = ["Referensi" => "#","Tingkat" => "referensi/tingkat",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
}

/* End of file example.php */
/* Location: ./application/modules/seting_tingkat/controllers/Seting_tingkat.php */