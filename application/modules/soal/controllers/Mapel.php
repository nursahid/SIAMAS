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

        $this->title = "Mapel Soal";
    }

    /**
     * Index
     */
    public function index()
    {
		$crud = new grocery_CRUD();
		
		$crud->set_table("kuis_mapel");
		$crud->set_subject("Mapel Soal");

		// Show in
		$crud->add_fields(["id_kuis", "id_mapel"]);
		$crud->edit_fields(["id_kuis", "id_mapel"]);
		$crud->columns(["id_kuis", "id_mapel"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->set_relation("id_kuis", "kuis", "name");
		$crud->set_relation("id_mapel", "seting_mapel", "mapel");

		// Relation n-n

		// Display As
		$crud->display_as("id_kuis", "Kuis");
		$crud->display_as("id_mapel", "Mata Pelajaran");

		// Unset action

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Mapel Soal";
		$template_data["crumb"] = [];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
}

/* End of file example.php */
/* Location: ./application/modules/siswa/controllers/Siswa.php */