<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Prestasi Controller.
 */
class Prestasi extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Prestasi Santri";
		$this->load->model('siswa_model');
    }

    /**
     * Index
     */
    public function index($id_siswa='')
    {
		$crud = new grocery_CRUD();
		
		$crud->set_table("prestasi_siswa");
		$crud->where('id_siswa',$id_siswa);
		$crud->set_subject("Data Prestasi");

		// Show in
		$crud->add_fields(["id_siswa", "prestasi", "tgl_perolehan"]);
		$crud->edit_fields(["id_siswa", "prestasi", "tgl_perolehan"]);
		$crud->columns(["id_siswa", "tgl_perolehan", "prestasi"]);

		// Relation n-n
		$crud->set_relation("id_siswa", "siswa", "nama");

		// Validation
		$crud->set_rules("id_siswa", "Santri", "required");
		$crud->set_rules("prestasi", "Prestasi", "required");

		// Display As
		$crud->display_as("id_siswa", "Nama Santri");
		$crud->display_as("prestasi", "Prestasi Santri");
		
		//callback
		$crud->callback_add_field('id_siswa',array($this,'siswa_add_callback'));
		$crud->callback_edit_field('id_siswa',array($this,'siswa_add_callback'));
		
		// Data Siswa
		$q = $this->db->select('*')->where('id',$id_siswa)->get('siswa')->row();
		$nama_santri = $q->nama;
		
		//unset
		$crud->unset_delete();
		
		//view	
		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "<small>Data Prestasi Santri an :</small> ".$nama_santri;
		$template_data["crumb"] = ["Kesiswaan" => "#","Siswa" => "kesiswaan/siswa",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
	
	//=====================
	// ALL CALLBACK
	//=====================
	//siswa Add	
	function siswa_add_callback(){
		$id_siswa = $this->uri->segment(4);
		$res = $this->db->select('*')->where('id',$id_siswa)->get('siswa')->row();
		return '<input type="hidden" name="id_siswa" value="'.$res->id.'"><strong>'.$res->nama.'</strong>';
	}
}

/* End of file example.php */
/* Location: ./application/modules/siswa/controllers/Siswa.php */