<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Mapel Controller.
 */
class Mapel extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Mata Pelajaran";
    }

    /**
     * Index
     */
    public function index()
    {
		$crud = new grocery_CRUD();
		
		$crud->set_table("seting_mapel");
		$crud->set_subject("Mata Pelajaran");

		// Show in
		$crud->add_fields(["mapel", "kode", "kurikulum", "alias"]);
		$crud->edit_fields(["mapel", "kode", "kurikulum", "alias"]);
		$crud->columns(["mapel", "kode", "kurikulum"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("mapel", "string");
		$crud->field_type("kode", "string");

		// Relation n-n

		// Validation
		$crud->set_rules("mapel", "mapel", "required");
		$crud->set_rules("kode", "Kode", "required");

		// Display As
		$crud->display_as("mapel", "Nama Mata Pelajaran");
		$crud->display_as("kode", "Kode");

		// Unset action

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Mata Pelajaran";
		$template_data["crumb"] = ["referensi" => "#","Mata Pelajaran" => "referensi/mapel",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
}

/* End of file example.php */
/* Location: ./application/modules/referensi/controllers/Mapel.php */