<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Level Controller.
 */
class Level extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Level Keahlian";
    }

    /**
     * CRUD
     */
	public function index()
	{
		$crud = new grocery_CRUD();
		
		$crud->set_table("kursus_level");
		$crud->set_subject("Level Keahlian");

		// Show in
		$crud->add_fields(["level", "keterangan_level"]);
		$crud->edit_fields(["level", "keterangan_level"]);
		$crud->columns(["level", "keterangan_level"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("level", "string");

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
		$template_data["crumb"] = ["Level Keahlian" => "level",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
}

/* End of file Level.php */
/* Location: ./application/modules/seting/controllers/Level.php */