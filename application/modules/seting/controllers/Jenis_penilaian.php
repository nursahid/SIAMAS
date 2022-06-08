<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Jenis_penilaian Controller.
 */
class Jenis_penilaian extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Jenis Penilaian";
    }

    /**
     * CRUD
     */
	public function index()
	{
		$crud = new grocery_CRUD();
		
		$crud->set_table("nilai_jenis_penilaian");
		$crud->set_subject("Jenis Penilaian");

		// Show in
		$crud->add_fields(["jenis_penilaian"]);
		$crud->edit_fields(["jenis_penilaian"]);
		$crud->columns(["jenis_penilaian"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("jenis_penilaian", "string");

		// Relation n-n
		//$crud->set_relation_n_n('groups', 'users_groups', 'groups', 'user_id', 'group_id', 'name');
		//$crud->set_relation("id_pembayaran", "pembayaran", "nama_pembayaran");
		
		// Display As
		//$crud->display_as("id_pembayaran", "Pembayaran");
		
		// Unset action

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Jenis Penilaian";
		$template_data["crumb"] = ["Jenis penilaian" => "jenis_penilaian",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
}

/* End of file Jenis_penilaian.php */
/* Location: ./application/modules/seting/controllers/Jenis_penilaian.php */