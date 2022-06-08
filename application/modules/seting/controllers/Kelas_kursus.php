<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Kelas_kursus Controller.
 */
class Kelas_kursus extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Kelas Kursus";
    }

	//---------------------
	//kelas
	//---------------------
	public function index()
	{
		$crud = new grocery_CRUD();
		
		$crud->set_table("kursus_kelas");
		$crud->set_subject("Kelas Kursus");

		// Show in
		$crud->add_fields(["nama_kelas", "keterangan_kelas"]);
		$crud->edit_fields(["nama_kelas", "keterangan_kelas"]);
		$crud->columns(["nama_kelas", "keterangan_kelas"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("nama_kelas", "string");

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
		$template_data["title"] = "Kelas Kursus";
		$template_data["crumb"] = ["Kelas Kursus" => "kursus/kelas",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
}

/* End of file Kelas_kursus.php */
/* Location: ./application/modules/seting/controllers/Kelas_kursus.php */