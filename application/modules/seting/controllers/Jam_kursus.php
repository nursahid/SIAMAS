<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Jam_kursus Controller.
 */
class Jam_kursus extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Jam Kursus";
    }

	//---------------------
	//jam
	//---------------------
	public function index()
	{
		$crud = new grocery_CRUD();
		
		$crud->set_table("belajar_jamkursus");
		$crud->set_subject("Jam Kursus");

		// Show in
		$crud->add_fields(["jam_kursus"]);
		$crud->edit_fields(["jam_kursus"]);
		$crud->columns(["jam_kursus"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("jam_kursus", "string");
		
		// Unset action

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Jam Kursus";
		$template_data["crumb"] = ["Jam Kursus" => "seting/jam_kursus",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
}

/* End of file Jam_kursus.php */
/* Location: ./application/modules/seting/controllers/Jam_kursus.php */