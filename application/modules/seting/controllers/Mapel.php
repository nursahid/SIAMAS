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
     * CRUD
     */
	public function index()
	{
		$crud = new grocery_CRUD();
		
		$crud->set_table("pelajaran");
		$crud->set_subject("Mata Pelajaran");

		// Show in
		$crud->add_fields(["nama"]);
		$crud->edit_fields(["nama"]);
		$crud->columns(["nama"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("nama", "string");

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
		$template_data["title"] = "Seting Mata Pelajaran";
		$template_data["crumb"] = ["Mata Pelajaran" => "mapel",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
}

/* End of file Mapel.php */
/* Location: ./application/modules/seting/controllers/Mapel.php */