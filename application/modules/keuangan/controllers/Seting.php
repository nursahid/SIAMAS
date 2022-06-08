<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Seting Controller.
 */
class Seting extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Jenis Pembayaran";
    }

    /**
     * CRUD
     */
	public function index()
	{
		$crud = new grocery_CRUD();
		
		$crud->set_table("pembayaran_jenis");
		$crud->set_subject("Jenis Pembayaran");

		// Show in
		$crud->add_fields(["nama_jenispembayaran"]);
		$crud->edit_fields(["nama_jenispembayaran"]);
		$crud->columns(["nama_jenispembayaran"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("nama_jenispembayaran", "string");

		// Relation n-n

		// Validation
		$crud->set_rules("nama_jenispembayaran", "Nama jenispembayaran", "required");

		// Display As
		$crud->display_as("nama_jenispembayaran", "Nama Pembayaran");
		// Unset action

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Keuangan";
		$template_data["subtitle"] = "Seting Jenis Pembayaran";
		$template_data["crumb"] = ["Keuangan" => "keuangan", "Jenis Pembayaran" => "keuangan/seting",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
}

/* End of file Seting.php */
/* Location: ./application/modules/keuangan/controllers/Seting.php */