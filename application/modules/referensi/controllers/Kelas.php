<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Kelas Controller.
 */
class Kelas extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Tahun Ajaran";
		$this->load->model(array('pegawai/pegawai_model', 'pegawai/ampumapel_model','pembelajaran/kelas_model'));
		$this->load->model(array('tingkat_model','mapeltingkat_model','jurusan_model'));
    }

    /**
     * Index
     */
    public function index()
    {
		$crud = new grocery_CRUD();
		
		$crud->set_table("seting_tahun_ajaran");
		$crud->set_subject("Kelas/Rombel");
		// Show in
		$crud->columns(["tahun", "rombel", "status"]);
		// Display As
		$crud->display_as("tahun", "Tahun Ajaran");

		// Unset action
		$crud->unset_operations();
		//ADD ACTION
		$crud->add_action('Data Kelas/Rombel', 'fa fa-plus-circle', '', 'btn btn-sm btn-danger',array($this,'link_kelastapel'));
		//CALLBACK
		$crud->callback_column('status',array($this,'status_column_callback'));
		//kolom kelas, hitung jml kelas
		$crud->callback_column('rombel',array($this,'rombel_column_callback'));
		
		//RENDER
		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Data Pokok ";
		$template_data["subtitle"] = "Kelas/Rombel";
		$template_data["crumb"] = ["Data Pokok" => "referensi","Kelas" => "referensi/kelas",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
	function link_kelastapel($primary_key, $row) {
	    return site_url('referensi/kelas/kelastapel').'/'.$primary_key;
	}
	public function kelastapel($id_tahun)
	{
		$crud = new grocery_CRUD();
		
		$crud->set_table("kelas");
		$crud->where('id_tahun', $id_tahun);
		$crud->set_subject("Kelas");

		// Show in
		$crud->add_fields(["kelas", "id_tingkat", "id_jurusan", "id_tahun"]);
		$crud->edit_fields(["kelas", "id_tingkat", "id_jurusan", "id_tahun"]);
		$crud->columns(["kelas", "id_tingkat", "id_jurusan", "jml_siswa", "data_siswa"]);

		// Fields type
		//$crud->field_type("kelas", "string");
		//relation
		$crud->set_relation("id_tingkat", "seting_tingkat", "tingkat");
		$crud->set_relation("id_jurusan", "ref_jurusan", "jurusan");
		$crud->set_relation("id_tahun", "seting_tahun_ajaran", "tahun");

		//CALLBACKS
		$crud->callback_add_field('id_tahun',array($this,'addfield_tapel_callback'));
		$crud->callback_edit_field('id_tahun',array($this,'addfield_tapel_callback'));
		
		//$crud->callback_edit_field('id_jurusan',array($this,'editfield_jurusan_kelastapel_callback'));
		
		$crud->callback_column('jml_siswa',array($this,'jmlsiswa_column_callback'));
		$crud->callback_column('data_siswa',array($this,'datasiswa_column_callback'));
		
		// Validation
		$crud->set_rules("kelas", "Nama Kelas", "required");
		$crud->set_rules("id_tingkat", "Tingkat", "required");

		// Display As
		$crud->display_as("kelas", "Nama Kelas");
		$crud->display_as("id_tingkat", "Tingkat");
		$crud->display_as("id_jurusan", "Jurusan");
		$crud->display_as("id_tahun", "Tahun Ajaran");
		
		//ambil data tingkat
		$this->load->model('tahunajaran_model');
		$query = $this->tahunajaran_model->get_by('id', $id_tahun);
		$tahunajaran = $query->tahun;
		// RENDER
		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Data Pokok ";
		$template_data["subtitle"] = "Kelas - Tapel : ".$tahunajaran;
		$template_data["crumb"] = ["Data Pokok" => "referensi","Kelas" => "referensi/kelas",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
	
	//data siswa by id kelas
    public function itemsiswa($id_tapel, $id_kelas)
    {
		$crud = new grocery_CRUD();
		
		$crud->set_table("siswa_kelas");
		$crud->where("id_kelas",$id_kelas);
		
		// Show in
		$crud->add_fields(["test_type", "question", "answer1", "answer2", "answer3", "answer4", "answer5", "answer", "answer_essay", "id_soal"]);
		$crud->edit_fields(["test_type", "question", "answer1", "answer2", "answer3", "answer4", "answer5", "answer", "answer_essay", "id_soal"]);
		
		$crud->columns(["id_siswa", "id_kelas", "id_tahun"]);
		
		// Validation
		$crud->set_rules("id_siswa", "Siswa", "required");
		// Display As
		$crud->display_as("id_siswa", "Siswa");
		$crud->display_as("id_kelas", "Kelas");
		$crud->display_as("id_tahun", "Tahun Ajaran");		
		//unset
		$crud->unset_operations();
		
		//set relation
		$crud->set_relation("id_siswa", "siswa", "nama");
		$crud->set_relation("id_kelas", "kelas", "kelas");
		$crud->set_relation("id_tahun", "seting_tahun_ajaran", "tahun");

		//nama kelas
		$namakelas = $this->kelas_model->get_by('id',$id_kelas)->kelas;			
		$crud->set_subject("Data Siswa ".ucfirst($namakelas));

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);
		
		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Daftar Siswa ".ucfirst($namakelas);
		$template_data["crumb"] = ["Seting" => "#","Kelas" => "seting/pmb/kelastapel/".$id_tapel."",];
		
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}	

	//=========================
	// ALL CALLBACK
	//=========================
	//Tahun Ajaran Aktif
	function tapel_aktif_callback() {
		$id_tahun = $this->sistem_model->get_tapel_aktif();
		$q = $this->db->get_where('seting_tahun_ajaran', array('id' => $id_tahun), 1)->row();  
		return '<input type="hidden" name="id_tahun" value="'.$id_tahun.'"><strong>'.$q->tahun.'</strong>';
	}
	
	//pengelolaan
	function kelolamapel_column_callback($value, $row) {
		//jurusan/tingkat
		$view = "<a href='".site_url('seting/pmb/mapeltingkatke/'.$row->id)."' class='btn btn-sm btn-success' data-toggle='tooltip' data-placement='bottom' title='Data Mata Pelajaran'><i class='fa fa-book'></i> &nbsp;&nbsp;Mata Pelajaran</a> &nbsp;";
		
		return $view;
	}
	//kolom status
	function status_column_callback($value, $row) {
		//jumlah data kelas by kolom
		if ($row->status == 'Y') {
			$val = "<span class='badge btn-danger'>Aktif</span>";
		}elseif ($row->status == 'N'){
			$val = "-";
		}
		return $val;
	}
	//kolom rombel
	function rombel_column_callback($value, $row) {
		$jml_kelas = $this->kelas_model->count_by('id_tahun',$row->id);
		return $jml_kelas." rombel";
	}
	//jmlsiswa
	function jmlsiswa_column_callback($value, $row) {
		$this->load->model('kesiswaan/siswakelas_model');
		$id_tapel = $this->uri->segment(4);
		$jmlsiswa = $this->siswakelas_model->count_by(array('id_tahun'=>$id_tapel,'id_kelas'=>$row->id));
		return $jmlsiswa." siswa";
	}
	//manajemen data siswa
	public function datasiswa_column_callback($value, $row) {
		$id_tapel = $this->uri->segment(4);
		return "<a class='btn btn-sm btn-danger' href='".site_url('referensi/kelas/itemsiswa/'.$id_tapel.'/'.$row->id)."'><i class='fa fa-list'></i>&nbsp; Data Siswa</a>";
	}
	//kolom jml mapel
	function jmlmapel_column_callback($value, $row) {
		$this->load->model('mapeltingkat_model');
		$jml_kelas = $this->mapeltingkat_model->count_by('id_tingkat',$row->id);
		return $jml_kelas." mapel";
	}
	//kolom id_mapel
	function mapel_column_callback($value, $row) {
		$q = $this->db->get_where('seting_mapel', array('id' => $row->id_mapel), 1)->row();  
		return $q->mapel;
	}

	//Tahun Ajaran
	function addfield_tapel_callback() {
		$id_tahun = $this->uri->segment(4);
		$q = $this->db->get_where('seting_tahun_ajaran', array('id' => $id_tahun), 1)->row();  
		return '<input type="hidden" name="id_tahun" value="'.$id_tahun.'"><strong>'.$q->tahun.'</strong>';
	}
	//Jurusan Kelas TAPEL
	function editfield_jurusan_kelastapel_callback() {
		$d = $this->db->get_where('kelas', array('id' => $this->uri->segment(6)), 1)->row();
		$q = $this->db->get_where('ref_jurusan', array('id' => $d->id_jurusan), 1)->row();  
		return '<input type="hidden" name="id_jurusan" value="'.$d->id_jurusan.'"><strong>'.$q->jurusan.'</strong>';
	}
	//Jurusan
	function addfield_jurusan_callback() {
		$id_jurusan = $this->uri->segment(4);
		$q = $this->db->get_where('ref_jurusan', array('id' => $id_jurusan), 1)->row();  
		return '<input type="hidden" name="id_jurusan" value="'.$id_jurusan.'"><strong>'.$q->jurusan.'</strong>';
	}
	//Tingkat
	function addfield_tingkat_callback() {
		$id_tingkat = $this->uri->segment(4);
		$q = $this->db->get_where('seting_tingkat', array('id' => $id_tingkat), 1)->row();  
		return '<input type="hidden" name="id_tingkat" value="'.$id_tingkat.'"><strong>'.$q->nama.'</strong>';
	}
	
	//kelastapel_afterinsert_callback
	//--Hanya untuk SIMAPON
	function kelastapel_afterinsert_callback($post_array, $primary_key) {
		//jika pilih tingkat 7-9, maka jenjangnya adalah MTs
		if( $post_array['id_tingkat'] == '1' || $post_array['id_tingkat'] == '2' || $post_array['id_tingkat'] == '3' ) {
			$jenjang = '1';
		} elseif( $post_array['id_tingkat'] == '4' || $post_array['id_tingkat'] == '5' || $post_array['id_tingkat'] == '6' ) {
			$jenjang = '2';
		}
		$data = array('id_jurusan' => $jenjang);		
		$this->db->where('id', $primary_key)->update('kelas', $data);
		
		return TRUE;
	}
	
}

/* End of file pmb.php */
/* Location: ./application/modules/seting/controllers/pmb.php */