<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Praktek Controller.
 */
class Praktek extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();
		$this->load->model('absensi/absensi_model', 'absensi');	
		$this->load->model('nilai_model', 'nilai');
        $this->load->model(array('jenispenilaian_model','seting/jurusan_model','pegawai/pegawai_model','pembelajaran/kelas_model'));
    }

    /**
     * Index
     */

	public function index()
	{
		$crud = new grocery_CRUD();
		
		$crud->set_table("nilai_penilaian");
		$crud->where('id_jnspenilaian','2');
		
		$crud->set_subject("Penilaian");

		// Show in
		$crud->add_fields(["id_jnspenilaian", "id_siswa", "id_mapel", "deskripsi", "nilai", "huruf"]);
		$crud->edit_fields(["id_jnspenilaian", "id_siswa", "id_mapel", "deskripsi", "nilai", "huruf"]);
		$crud->columns(["id_jnspenilaian", "id_siswa", "id_mapel", "deskripsi", "nilai", "huruf"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("deskripsi", "string");
		$crud->field_type("nilai", "integer");
		$crud->field_type("huruf", "string");

		// Relation n-n
		$crud->set_relation("id_jnspenilaian", "nilai_jenis_penilaian", "jenis_penilaian");
		$crud->set_relation("id_kelas", "kelas", "kelas");
		$crud->set_relation("id_siswa", "siswa", "nama");
		$crud->set_relation("id_mapel", "seting_mapel", "mapel");

		// Validation
		$crud->set_rules("id_jnspenilaian", "Id jnspenilaian", "required");
		$crud->set_rules("id_siswa", "Id siswa", "required");
		$crud->set_rules("deskripsi", "Deskripsi", "required");
		$crud->set_rules("nilai", "Nilai", "required");

		// Display As
		$crud->display_as("id_jnspenilaian", "Jenis Penilaian");
		$crud->display_as("id_siswa", "Data Santri");
		$crud->display_as("id_mapel", "Pelajaran");
		$crud->display_as("deskripsi", "Deskripsi");
		$crud->display_as("nilai", "Nilai");
		$crud->display_as("huruf", "Huruf");

		// callback
		$crud->callback_add_field('id_jnspenilaian',array($this,'jnspenilaian_praktek_add_callback'));

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Penilaian";
		$template_data["crumb"] = ["Penilaian Konsep" => "penilaian/konsep",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
	//========================
	//PRIVATE FUNCTIONS
	//insert ignore
	protected function insert_ignore($table,array $data) {
        $_prepared = array();
         foreach ($data as $col => $val)
        $_prepared[$this->db->escape_str($col)] = $this->db->escape($val); 
        $this->db->query('INSERT IGNORE INTO '.$table.' ('.implode(',',array_keys($_prepared)).') VALUES('.implode(',',array_values($_prepared)).');');
    }
	
	//=====================
	// ALL CALLBACK
	//=====================
	//Jenis Penilaian KONSEP	
	function jnspenilaian_konsep_add_callback(){
		$res = $this->db->select('*')->where('id', '1')->get('nilai_jenis_penilaian')->row();
		return '<input type="hidden" name="id_jnspenilaian" value="'.$res->id.'"><strong>'.$res->nama_jnspenilaian.'</strong>';
	}
	//Jenis Penilaian PRAKTEK	
	function jnspenilaian_praktek_add_callback(){
		$res = $this->db->select('*')->where('id', '2')->get('nilai_jenis_penilaian')->row();
		return '<input type="hidden" name="id_jnspenilaian" value="'.$res->id.'"><strong>'.$res->nama_jnspenilaian.'</strong>';
	}
	function idsiswa_callback() {
		$id_penilaian = $this->uri->segment(6);		
		$q1 = $this->db->get_where('nilai_penilaian', array('id' => $id_penilaian), 1)->row();
		$q2 = $this->db->get_where('siswa', array('id' => $q1->id_siswa), 1)->row();
		return '<input type="hidden" name="id_siswa" value="'.$q2->id.'"><strong>'.$q2->nama.'</strong>';
	}
	function huruf_update_callback($post_array, $primary_key) {
		$data = array(
				'huruf' => ucwords(terbilang($post_array['nilai']))
			);
		$this->db->update('nilai_penilaian',$data,array('id'=>$primary_key));
		return true;
	}
}

/* End of file Praktek.php */
/* Location: ./application/modules/penilaian/controllers/Praktek.php */