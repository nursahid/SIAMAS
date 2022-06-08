<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Orangtua Controller.
 */
class Orangtua extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Siswa";
		$this->load->model(array('siswa_model', 'orangtua_model'));
    }

    /**
     * Index
     */
    public function index($id_siswa='')
    {
		$crud = new grocery_CRUD();
		
		$crud->set_table("siswa_orangtua");
		$crud->where('id_siswa',$id_siswa);
		$crud->set_subject("Data Orang Tua");

		// Show in
		$crud->add_fields(["id_siswa", "nama_ayah", "tmpt_lahir_ayah", "tgl_lahir_ayah", "pekerjaan_ayah", "nama_ibu", "tmpt_lahir_ibu", "tgl_lahir_ibu", "pekerjaan_ibu", "alamat", "tentang"]);
		$crud->edit_fields(["id_siswa", "nama_ayah", "tmpt_lahir_ayah", "tgl_lahir_ayah", "pekerjaan_ayah", "nama_ibu", "tmpt_lahir_ibu", "tgl_lahir_ibu", "pekerjaan_ibu", "alamat", "tentang"]);
		$crud->columns(["id_siswa", "nama_ayah", "nama_ibu"]);

		// Fields type
		$crud->field_type("nama_ayah", "string");
		$crud->field_type("nama_ibu", "string");
		$crud->field_type("alamat", "string");
		$crud->field_type("kontak", "string");

		// Relation n-n
		$crud->set_relation("id_siswa", "siswa", "nama");

		// Validation
		$crud->set_rules("id_siswa", "Siswa", "required");
		$crud->set_rules("nama_ayah", "Nama ayah", "required");
		$crud->set_rules("nama_ibu", "Nama ibu", "required");

		// Display As
		$crud->display_as("id_siswa", "Nama Siswa");
		$crud->display_as("tmpt_lahir_ayah", "Tempat Kelahiran Ayah");
		$crud->display_as("tgl_lahir_ayah", "Tgl. Kelahiran Ayah");
		$crud->display_as("tmpt_lahir_ibu", "Tempat Kelahiran Ibu");
		$crud->display_as("tgl_lahir_ibu", "Tgl. Kelahiran Ibu");
		
		//callback
		$crud->callback_add_field('id_siswa',array($this,'siswa_add_callback'));
		
		// Data Siswa
		$q = $this->db->select('*')->where('id',$id_siswa)->get('siswa')->row();
		$nama_Siswa = $q->nama;
		//cek apakah sudah ada data ortu
		$cek = $this->orangtua_model->count_by('id_siswa', $id_siswa);
		if($cek > 0) {
			//unset add
			$crud->unset_add();
		}
		//unset
		$crud->unset_delete();
		
		//view	
		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "<small>Data Orang Siswa an :</small> ".$nama_Siswa;
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