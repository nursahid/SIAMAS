<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Agama Controller.
 */
class Agama extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Agama";
    }

    /**
     * Index
     */
    public function index()
    {
		$crud = new grocery_CRUD();
		
		$crud->set_table("seting_agama");
		$crud->set_subject("Agama");

		// Show in
		$crud->add_fields(["nama"]);
		$crud->edit_fields(["nama"]);
		$crud->columns(["nama"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("nama", "string");

		// Relation n-n

		// Display As

		// Unset action

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Seting Agama";
		$template_data["crumb"] = ["agama" => "agama",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
}

/* End of file Agama.php */
/* Location: ./application/modules/Agama/controllers/Agama.php */