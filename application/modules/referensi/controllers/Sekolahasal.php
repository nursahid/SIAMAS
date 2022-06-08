<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Sekolahasal Controller.
 */
class Sekolahasal extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Sekolah Asal";
    }

    /**
     * Index
     */

	public function index()
	{
		$crud = new grocery_CRUD();
		
		$crud->set_table("ref_sekolahasal");
		$crud->set_subject("Sekolah Asal");

		// Show in
		$crud->add_fields(["namasekolah", "alamatsekolah"]);
		$crud->edit_fields(["namasekolah", "alamatsekolah"]);
		$crud->columns(["namasekolah", "alamatsekolah"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("namasekolah", "string");
		$crud->unset_texteditor("alamatsekolah", 'full_text');
		$crud->field_type("alamatsekolah", "text");

		// Relation n-n

		// Validation
		$crud->set_rules("namasekolah", "Namasekolah", "required");

		// Display As
		$crud->display_as("namasekolah", "Nama Sekolah");
		$crud->display_as("alamatsekolah", "Alamat Sekolah");

		// Unset action

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Sekolah Asal";
		$template_data["crumb"] = ["Referensi" => "#","Sekolah Asal" => "sekolahasal",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
}

/* End of file example.php */
/* Location: ./application/modules/ref_sekolahasal/controllers/Sekolahasal.php */