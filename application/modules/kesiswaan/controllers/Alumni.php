<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Alumni Controller.
 */
class Alumni extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Siswa";
    }

    /**
     * Index
     */
    public function index()
    {
		$crud = new grocery_CRUD();
		
		$crud->set_table("siswa");
		$crud->set_subject("Alumni");
		$crud->where('status','alumni');

		// Show in
		$crud->add_fields(["nama", "nisn", "nik", "tempat_lahir", "tgl_lahir", "kelamin", "agama", "alamat", "kelurahan", "kecamatan", "kabupaten", "provinsi", "ekonomi", "anak_ke", "jml_saudara", "foto", "hp_ortu", "asal_sekolah", "status"]);
		$crud->edit_fields(["nama", "nisn", "nik", "tempat_lahir", "tgl_lahir", "kelamin", "agama", "alamat", "kelurahan", "kecamatan", "kabupaten", "provinsi", "ekonomi", "anak_ke", "jml_saudara", "foto", "hp_ortu", "asal_sekolah", "status"]);
		$crud->columns(["nama", "nisn", "nik", "tempat_lahir", "tgl_lahir", "kelamin", "alamat", "ekonomi", "foto"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("nama", "string");
		$crud->field_type("nis", "string");
		$crud->field_type("nisn", "string");
		$crud->field_type("nik", "string");
		$crud->field_type("tempat_lahir", "string");
		$crud->field_type("tgl_lahir", "date");
		$crud->field_type("kelamin", "enum");
		$crud->set_relation("agama", "ref_agama", "agama");
		$crud->field_type("alamat", "string");
		$crud->field_type("kelurahan", "string");
		$crud->field_type("kecamatan", "string");
		$crud->field_type("kabupaten", "string");
		$crud->field_type("provinsi", "string");
		$crud->field_type("ekonomi", "string");
		$crud->field_type("anak_ke", "integer");
		$crud->field_type("jml_saudara", "integer");
		$crud->field_type("id_saudara", "string");
		$crud->field_type("foto", "string");
		$crud->field_type("angkatan", "integer");
		$crud->field_type("program_studi", "integer");
		$crud->field_type("id_ortu", "integer");
		$crud->field_type("hp_ortu", "string");
		$crud->set_relation("asal_sekolah", "ref_sekolahasal", "namasekolah");
		$crud->field_type("status", "enum");
		$crud->field_type("token", "string");
		$crud->field_type("password", "string");

		// Relation n-n

		// Validation
		$crud->set_rules("nama", "Nama", "required");
		$crud->set_rules("tempat_lahir", "Tempat lahir", "required");
		$crud->set_rules("tgl_lahir", "Tgl lahir", "required");
		$crud->set_rules("ekonomi", "Ekonomi", "required");

		// Display As
		$crud->display_as("nisn", "NISN");
		$crud->display_as("nik", "NIK");
		$crud->display_as("tempat_lahir", "Tempat Lahir");
		$crud->display_as("tgl_lahir", "Tgl. Lahir");
		$crud->display_as("kelamin", "Kelamin");
		$crud->display_as("agama", "Agama");
		$crud->display_as("alamat", "Alamat");
		$crud->display_as("kelurahan", "Kelurahan");
		$crud->display_as("kecamatan", "Kecamatan");
		$crud->display_as("kabupaten", "Kabupaten");
		$crud->display_as("provinsi", "Provinsi");
		$crud->display_as("ekonomi", "Kondisi Ekonomi");
		$crud->display_as("anak_ke", "Anak Ke");
		$crud->display_as("jml_saudara", "Jml. Saudara");
		$crud->display_as("foto", "Foto");

		// Unset action
		$crud->unset_operations();
		
		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Data Siswa";
		$template_data["crumb"] = ["Kesiswaan" => "kesiswaan","Siswa" => "kesiswaan/siswa",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
}

/* End of file example.php */
/* Location: ./application/modules/siswa/controllers/Siswa.php */