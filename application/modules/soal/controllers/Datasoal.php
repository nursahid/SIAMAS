<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Datasoal Controller.
 */
class Datasoal extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Data Soal";
    }

    /**
     * Index
     */
    public function index()
    {
		$crud = new grocery_CRUD();
		
		$crud->set_table("kuis_soal");
		$crud->set_subject("Data Soal");

		// Show in
		$crud->add_fields(["question", "answer1", "answer2", "answer3", "answer4", "answer5", "answer", "id_kuis"]);
		$crud->edit_fields(["question", "answer1", "answer2", "answer3", "answer4", "answer5", "answer", "id_kuis"]);
		$crud->columns(["question", "answer", "id_kuis"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->unset_texteditor("question", 'full_text');
		$crud->field_type("question", "text");
		$crud->field_type("answer1", "string");
		$crud->field_type("answer2", "string");
		$crud->field_type("answer3", "string");
		$crud->field_type("answer4", "string");
		$crud->field_type("answer5", "string");
		$crud->field_type("answer", "string");
		$crud->set_relation("id_kuis", "kuis", "name");

		// Relation n-n

		// Validation
		$crud->set_rules("question", "Question", "required");

		// Display As
		$crud->display_as("question", "Pertanyaaan");
		$crud->display_as("answer1", "A");
		$crud->display_as("answer2", "B");
		$crud->display_as("answer3", "C");
		$crud->display_as("answer4", "D");
		$crud->display_as("answer5", "E");
		$crud->display_as("answer", "Jawaban");
		$crud->display_as("id_kuis", "Kuis Mapel");

		// Unset action

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Data Soal";
		$template_data["crumb"] = [];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
}

/* End of file example.php */
/* Location: ./application/modules/siswa/controllers/Siswa.php */