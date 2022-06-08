<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Kodejurusan Controller.
 */
class Kodejurusan extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Kode Jurusan";
    }

    /**
     * Index
     */

	public function index()
	{
		$crud = new grocery_CRUD();
		
		$crud->set_table("ref_jurusan");
		$crud->set_subject("Kode Jurusan");

		// Show in
		$crud->add_fields(["jurusan", "kode"]);
		$crud->edit_fields(["jurusan", "kode"]);
		$crud->columns(["id","jurusan", "kode"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("jurusan", "string");
		$crud->field_type("kode", "string");

		// Relation n-n

		// Validation
		$crud->set_rules("jurusan", "jurusan", "required");
		$crud->set_rules("kode", "Kode", "required");

		// Display As
		$crud->display_as("id", "ID");
		$crud->display_as("jurusan", "Jurusan");
		$crud->display_as("kode", "Kode");

		// Unset action
		//$crud->unset_add();
		$crud->unset_delete();

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Kode Jurusan";
		$template_data["crumb"] = ["Referensi" => "#","Kode Jurusan" => "referensi/kodejurusan",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
}

/* End of file example.php */
/* Location: ./application/modules/referensi/controllers/Kodejurusan.php */