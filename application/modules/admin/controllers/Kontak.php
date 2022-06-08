<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Kontak Controller.
 */
class Kontak extends Admin_Controller {
    private $title;

    public function __construct() {
        parent::__construct();
        // models
        $this->load->model(array('users_model', 'kontak_model'));
        // helpers
        $this->load->helper('utility_helper');
    }

	public function index() {
        last_url('set'); // save last url
        $crud = new grocery_CRUD();
		
        $crud->set_table('kontak');
        $crud->set_subject('Kontak');
		
		$crud->columns('nama', 'email', 'subjek', 'pesan');
		
		$crud->add_fields(["nama", "email", "subjek", "pesan"]);
		$crud->edit_fields(["nama", "email", "subjek", "pesan"]);
		
        $crud->required_fields('nama', 'pesan', 'email');
        //display
		$crud->display_as('pesan', 'pesan');
 		$crud->display_as('nama', 'Nama');
		$crud->display_as('subjek', 'Perihal');
		$crud->display_as('email', 'Email');
		//field type
		$crud->field_type("pesan", "text");
		//$crud->set_field_upload('subjek', 'assets/uploads/thumbnail');
		//require
		$crud->required_fields('nama', 'subjek', 'pesan', 'email');
		//set validation
		$crud->set_rules("nama", "Nama", "required");
		$crud->set_rules("subjek", "Subjek", "required");
		$crud->set_rules("pesan", "Pesan", "required");
		$crud->set_rules("pesan", "Pesan", "required");
		
		//callback
        //$crud->callback_before_insert(array($this, 'slug_page_check'));
        //$crud->callback_before_update(array($this, 'slug_page_check'));
				
		//unset
		$crud->unset_texteditor('pesan', 'full_text');
        $crud->unset_export();
        $crud->unset_print();
		$crud->unset_add();
        //$crud->unset_read();
		$crud->unset_edit();

        // layout view
		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Page Builder";
		$template_data["crumb"] = ["Page" => "admin/page"];
		
		$this->layout->render('admin', $template_data);
	}
	
	//=======================
	//    ALL CALLBACK      =
	//=======================

}

/* End of file Kontak.php */
/* Location: ./application/modules/admin/controllers/Kontak.php */