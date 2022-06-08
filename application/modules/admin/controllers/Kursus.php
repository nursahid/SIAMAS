<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Kursus Controller.
 */
class Kursus extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Kursus";
    }

    /**
     * Index
     */
    public function index()
    {
		$crud = new grocery_CRUD();
		
		$crud->set_table("kursus");
		$crud->set_subject("Kursus");

		// Show in
		$crud->add_fields(["id_level", "id_kelas", "nama_kursus", "harga"]);
		$crud->edit_fields(["id_level", "id_kelas", "nama_kursus", "harga"]);
		$crud->columns(["id_level", "id_kelas", "nama_kursus", "harga"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->set_relation("id_level", "kursus_level", "level");
		$crud->set_relation("id_kelas", "kursus_kelas", "nama_kelas");
		$crud->field_type("nama_kursus", "string");
		$crud->field_type("harga", "string");

		// Relation n-n

		// Validation
		$crud->set_rules("id_level", "Id level", "required");
		$crud->set_rules("id_kelas", "Id kelas", "required");
		$crud->set_rules("nama_kursus", "Nama kursus", "required");
		$crud->set_rules("harga", "Harga", "required");

		// Display As
		$crud->display_as('id_level','Level');
		$crud->display_as('id_kelas','Kelas');
		
		// Unset action

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Kursus";
		$template_data["crumb"] = ["belajar" => "belajar/kelas",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
	//---------------------
	//kelas
	//---------------------
	public function kelas()
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

/* End of file Kursus.php */
/* Location: ./application/modules/kursus/controllers/Kursus.php */