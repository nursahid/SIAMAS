<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Nilai_keterampilan Controller.
 */
class Nilai_keterampilan extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Nilai Keterampilan";
    }

    /**
     * Index
     */
    public function index()
    {
    }

    /**
     * CRUD
     */
	public function crudNilai_keterampilan()
	{
		$crud = new grocery_CRUD();
		
		$crud->set_table("nilai_keterampilan");
		$crud->set_subject("Nilai Keterampilan");

		// Show in
		$crud->add_fields(["nama", "id_tapel", "id_semester", "id_siswa", "id_mapel", "id_kd", "id_tema", "sub_tema", "jenis", "nilai"]);
		$crud->edit_fields(["nama", "id_tapel", "id_semester", "id_siswa", "id_mapel", "id_kd", "id_tema", "sub_tema", "jenis", "nilai"]);
		$crud->columns(["nama", "id_tapel", "id_semester", "id_siswa", "id_mapel", "id_kd", "id_tema", "sub_tema", "jenis", "nilai"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("nama", "string");
		$crud->set_relation("id_tapel", "tapel", "tahun");
		$crud->set_relation("id_semester", "semester", "nama");
		$crud->set_relation("id_siswa", "siswa", "nama");
		$crud->set_relation("id_mapel", "mapel", "nama");
		$crud->set_relation("id_kd", "kd_keterampilan", "kode");
		$crud->set_relation("id_tema", "tema_kd_keterampilan", "id_tema");
		$crud->field_type("sub_tema", "enum");
		$crud->field_type("jenis", "enum");
		$crud->field_type("nilai", "string");

		// Relation n-n

		// Validation
		$crud->set_rules("id_tapel", "Id tapel", "required");
		$crud->set_rules("id_semester", "Id semester", "required");

		// Display As
		$crud->display_as("id_tapel", "Tahun Ajaran");
		$crud->display_as("id_semester", "Semester");
		$crud->display_as("id_siswa", "Siswa");
		$crud->display_as("id_mapel", "Mapel");
		$crud->display_as("id_kd", "KD");
		$crud->display_as("id_tema", "Tema");

		// Unset action

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Nilai Keterampilan";
		$template_data["crumb"] = ["Nilai Keterampilan" => "admin/nilai/keterampilan",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
}

/* End of file example.php */
/* Location: ./application/modules/nilai_keterampilan/controllers/Nilai_keterampilan.php */