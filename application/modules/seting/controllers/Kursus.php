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
		
		//callback
		$crud->callback_column('harga',array($this,'rupiahin'));
		
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
	
	//==============================
	//CALLBACK
	function rupiahin($value, $row)
	{
		return number_format($value,0, ',', '.');
	}
}

/* End of file Kursus.php */
/* Location: ./application/modules/kursus/controllers/Kursus.php */