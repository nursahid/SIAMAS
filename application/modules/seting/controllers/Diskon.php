<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Diskon Controller.
 */
class Diskon extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Diskon Pembayaran";
    }

    /**
     * CRUD
     */
	public function index()
	{
		$crud = new grocery_CRUD();
		
		$crud->set_table("pembayaran_diskon");
		$crud->set_subject("Diskon Pembayaran");

		// Show in
		$crud->add_fields(["id_pembayaran","nama_diskon", "nilai_diskon"]);
		$crud->edit_fields(["id_pembayaran","nama_diskon", "nilai_diskon"]);
		$crud->columns(["id_pembayaran","nama_diskon", "nilai_diskon"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("nama_diskon", "string");

		// Relation n-n
		//$crud->set_relation_n_n('groups', 'users_groups', 'groups', 'user_id', 'group_id', 'name');
		$crud->set_relation("id_pembayaran", "pembayaran", "nama_pembayaran");
		
		// Display As
		$crud->display_as("id_pembayaran", "Pembayaran");
		
		// Unset action

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Seting Jenis Pembayaran";
		$template_data["crumb"] = ["Jenis Pembayaran" => "Diskon",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
}

/* End of file Diskon.php */
/* Location: ./application/modules/seting/controllers/Diskon.php */