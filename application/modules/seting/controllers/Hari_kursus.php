<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Hari_kursus Controller.
 */
class Hari_kursus extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Hari Kursus";
    }

	//---------------------
	//hari
	//---------------------
	public function index()
	{
		$crud = new grocery_CRUD();
		
		$crud->set_table("belajar_harikursus");
		$crud->set_subject("Hari Kursus");

		// Show in
		$crud->add_fields(["hari_kursus"]);
		$crud->edit_fields(["hari_kursus"]);
		$crud->columns(["hari_kursus"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("hari_kursus", "string");
		
		// Unset action

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Hari Kursus";
		$template_data["crumb"] = ["Hari Kursus" => "seting/hari_kursus",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
}

/* End of file Hari_kursus.php */
/* Location: ./application/modules/seting/controllers/Hari_kursus.php */