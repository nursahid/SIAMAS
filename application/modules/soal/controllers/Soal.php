<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Soal Controller.
 */
class Soal extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->load->model(array('soal_model','seting/mapel_model','pembelajaran/kelas_model', 'pegawai/pegawai_model'));
		$this->load->model('itemsoal_model');

    }
    /**
     * Index
     */
    public function index()
    {
		//view
		$this->layout->set_wrapper('index', $data);
		$template_data["title"] = "Soal";
		$template_data["subtitle"] = "Manajemen Data Soal";
		$template_data["crumb"] = ["Soal" => "soal",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); 
	}
	//--- Data Soal -----
    public function data()
    {
		$tapel_aktif	= $this->sistem_model->get_tapel_aktif();

		//cek group
		if($this->ion_auth->in_group(array('superadmin', 'admin'))) {
			$data['dropdown_mapel'] = $this->soal_model->dropdown_mapel();
			$this->layout->set_wrapper('index_soal', $data);
		} 
		elseif($this->ion_auth->in_group('guru')) {
			//dropdown mapel yang diampu
			$user 	 = $this->ion_auth->user()->row()->id;
			$id_guru = $this->pegawai_model->get_by('id_user',$user)->id;
			$data['dropdown_mapel'] = $this->soal_model->get_dropdown_mapel_by_guru($id_guru);
			$data['id_guru'] 		=  $id_guru;
			//view
			$this->layout->set_wrapper('index_soal_guru', $data);
		}		
		//View
		$template_data['title'] = 'Soal ';
		$template_data['subtitle'] = 'Data Soal';
        $template_data['crumb'] = ['Soal' => 'soal', 'Data Soal' => 'soal/data'];

		$this->layout->auth();
		$this->layout->render('admin', $template_data);	
	}
	//===================
	//2. Data Kuis
	//-------------------
    function datakuis() {
		$id_mapel = $_POST['mapel'];
		$id_tahun	= $this->sistem_model->get_tapel_aktif();
		$data['datakuis'] = $this->soal_model->get_many_by(array('id_mapel'=>$id_mapel));
		$this->load->view('datakuis', $data);
    }
    //================
	//Tambah Data Soal
    public function tambah($id_mapel)
    {
		$crud = new grocery_CRUD();
		
		$crud->set_table("soal");
		$crud->where('id_mapel',$id_mapel);
		//nama mapel
		$namamapel = $this->mapel_model->get_by('id',$id_mapel)->mapel;	
		
		$crud->set_subject("Soal ".ucwords($namamapel));

		// Show in
		$crud->add_fields(["id_mapel", "name", "description", "duration", "id_pegawai"]);
		$crud->edit_fields(["id_mapel", "name", "description", "duration", "id_pegawai"]);
		$crud->columns(["name", "description", "Data Soal"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("name", "string");
		$crud->field_type("description", "text");
		$crud->field_type("duration", "integer");
		$crud->field_type("pass_percentage", "string");
		//$crud->field_type("test_type", "enum");
		$crud->field_type("view_questions", "integer");
		
		//cek group
		if($this->ion_auth->in_group(array('superadmin', 'admin'))) {
			// Relation n-n
			$crud->set_relation("id_pegawai", "pegawai", "nama");
		} 
		elseif($this->ion_auth->in_group('guru')) {
			$crud->callback_add_field('id_pegawai',array($this,'idpegawai_callback'));
			$crud->callback_edit_field('id_pegawai',array($this,'idpegawai_callback'));
		}
		// Relation n-n
		$crud->set_relation("id_mapel", "seting_mapel", "mapel");

		
		//callbacks
		$crud->callback_column('duration',array($this,'duration_column_callback'));
		$crud->callback_column('Data Soal',array($this,'datasoal_column_callback'));
		$crud->callback_add_field('id_mapel',array($this,'add_kuis_callback'));
		//$crud->callback_edit_field('id_mapel',array($this,'add_kuis_callback'));
		
		//$crud->callback_add_field('test_type',array($this,'jenis_test_callback'));
		//$crud->callback_edit_field('test_type',array($this,'jenis_test_callback'));
		
		// Validation
		$crud->set_rules("name", "Name", "required");
		$crud->set_rules("duration", "Duration", "required");
		$crud->set_rules("view_questions", "View questions", "required");

		// Display As
		$crud->display_as("name", "Nama Soal");
		$crud->display_as("description", "Keterangan");
		$crud->display_as("duration", "Durasi (Detik)");
		$crud->display_as("view_questions", "Jml. Soal Tampil");
		$crud->display_as("id_pegawai", "Nama Pengajar");
		$crud->display_as("id_mapel", "Mata Pelajaran");
		$crud->display_as("test_type", "Jenis Tes");
		
		// Unset action
		$crud->unset_texteditor("description", 'full_text');

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Daftar Soal ".ucwords($namamapel);
		$template_data["crumb"] = ["Soal" => "soal",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
	//data soal by id soal
    public function itemsoal($id_mapel, $id_soal)
    {
		$crud = new grocery_CRUD();
		
		$crud->set_table("item_soal");
		$crud->where("id_soal",$id_soal);
			
		$row = $this->itemsoal_model->get($this->uri->segment(4));
		$test_type = $row->test_type;
		
		// Show in
		$crud->add_fields(["test_type", "question", "answer1", "answer2", "answer3", "answer4", "answer5", "answer", "answer_essay", "id_soal"]);
		$crud->edit_fields(["test_type", "question", "answer1", "answer2", "answer3", "answer4", "answer5", "answer", "answer_essay", "id_soal"]);
		
		//if($test_type == 'multiple') {
			$crud->columns(["question", "answer", "test_type", "id_soal"]);
		//} elseif($test_type == 'essay') {
		//	$crud->columns(["question", "answer_essay", "test_type", "id_soal"]);
		//}		
		

			// Fields type
			$crud->field_type("id", "integer");
			$crud->field_type("question", "text");
			$crud->field_type("answer1", "string");
			$crud->field_type("answer2", "string");
			$crud->field_type("answer3", "string");
			$crud->field_type("answer4", "string");
			$crud->field_type("answer5", "string");
			$crud->field_type("answer", "string");

			//Callbacks
			$crud->callback_column('answer',array($this,'answer_column_callback'));
			$crud->callback_add_field('id_soal',array($this,'soal_add_callback'));
			$crud->callback_edit_field('id_soal',array($this,'soal_add_callback'));
			$crud->callback_add_field('answer',array($this,'answer_callback'));
			$crud->callback_edit_field('answer',array($this,'answer_callback'));
			
			// Validation
			$crud->set_rules("question", "Question", "required");
			
			//unset
			//$crud->unset_texteditor("question", 'full_text');
			
			//set relation
			$crud->set_relation("id_soal", "soal", "name");

			// Display As
			$crud->display_as("question", "Pertanyaaan");
			$crud->display_as("answer1", "A");
			$crud->display_as("answer2", "B");
			$crud->display_as("answer3", "C");
			$crud->display_as("answer4", "D");
			$crud->display_as("answer5", "E");
			$crud->display_as("answer", "Jawaban Pilihan");
			$crud->display_as("answer_essay", "Jawaban Essay");
			$crud->display_as("id_soal", "soal");		
			
		//$crud->callback_add_field('test_type',array($this,'jenis_test_callback'));
		//$crud->callback_edit_field('test_type',array($this,'jenis_test_callback'));
		//$crud->field_type('test_type','true_false',array('1' => 'multiple', '0' => 'essay'));
		//GET STATE
		$state = $crud->getState();
		if($state == 'add') {
			$crud->set_js("assets/plugins/jquery.slugify/speakingurl.js");
			$crud->set_js("assets/plugins/jquery.slugify/slugify.min.js");
			$crud->set_js("assets/js/itemsoal.js");
			//$template_data['js_plugins'] = [base_url('assets/js/itemsoal.js')];
		} elseif($state == 'edit') {
			$crud->set_js("assets/plugins/jquery.slugify/speakingurl.js");
			$crud->set_js("assets/plugins/jquery.slugify/slugify.min.js");
			//jika edit sesuaikan dengan yang aktif

			if($test_type == 'multiple') {
				$crud->set_js("assets/js/itemsoal_edit1.js");
			} elseif($test_type == 'essay') {
				$crud->set_js("assets/js/itemsoal_edit2.js");
			}
		}
		//nama soal
		$namasoal = $this->soal_model->get_by('id',$id_soal)->name;			
		$crud->set_subject("Data Item Soal ".ucfirst($namasoal));

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);
		
		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Daftar Item Soal ".ucfirst($namasoal);
		$template_data["crumb"] = ["Soal" => "soal","Data Soal" => "soal/tambah/".$id_mapel."",];
		
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
	
	//================
	//ALL CALLBACKS
	//---------------
	
	function duration_column_callback($value, $row){
		return ($value/60)." menit";
	}
	//manajemen datasoal
	public function datasoal_column_callback($value, $row) {
		$id_mapel = $this->uri->segment(3);
		return "<a class='btn btn-sm btn-danger' href='".site_url('soal/itemsoal/'.$id_mapel.'/'.$row->id)."'><i class='fa fa-list'></i>&nbsp; Data Item Soal</a>";
	}
	//jawaban
	public function answer_column_callback($value, $row) {
		if($value == 1) {
			$jawaban = "A";
		} elseif($value == 2) {
			$jawaban = "B";
		} if($value == 3) {
			$jawaban = "C";
		} if($value == 4) {
			$jawaban = "D";
		} if($value == 5) {
			$jawaban = "E";
		}
		return $jawaban;
	}
	
	//Add Kuis
	function soal_add_callback() {
		$id_soal = $this->uri->segment(4);
		$query 	 = $this->db->get_where('soal', array('id' => $id_soal), 1)->row();
		return '<input type="hidden" name="id_soal" value="'.$query->id.'"><strong>'.$query->name.'</strong>';
	}
	// answer
	function answer_callback($value)
	{
		if($value == '1') {
			$data = '<input type="radio" name="kelamin" value="1" checked/> A &nbsp;
					 <input type="radio" name="kelamin" value="2" /> B &nbsp;
					 <input type="radio" name="kelamin" value="3" /> C &nbsp;
					 <input type="radio" name="kelamin" value="4" /> D &nbsp;
					 <input type="radio" name="kelamin" value="5" /> E';
		} elseif($value == '2') {
			$data = '<input type="radio" name="kelamin" value="1" /> A &nbsp;
					 <input type="radio" name="kelamin" value="2" checked/> B &nbsp;
					 <input type="radio" name="kelamin" value="3" /> C &nbsp;
					 <input type="radio" name="kelamin" value="4" /> D &nbsp;
					 <input type="radio" name="kelamin" value="5" /> E';
		}  elseif($value == '3') {
			$data = '<input type="radio" name="kelamin" value="1" /> A &nbsp;
					 <input type="radio" name="kelamin" value="2" /> B &nbsp;
					 <input type="radio" name="kelamin" value="3" checked/> C &nbsp;
					 <input type="radio" name="kelamin" value="4" /> D &nbsp;
					 <input type="radio" name="kelamin" value="5" /> E';
		} elseif($value == '4') {
			$data = '<input type="radio" name="kelamin" value="1" /> A &nbsp;
					 <input type="radio" name="kelamin" value="2" /> B &nbsp;
					 <input type="radio" name="kelamin" value="3" /> C &nbsp;
					 <input type="radio" name="kelamin" value="4" checked/> D &nbsp;
					 <input type="radio" name="kelamin" value="5" /> E';
		} elseif($value == '5') {
			$data = '<input type="radio" name="kelamin" value="1" /> A &nbsp;
					 <input type="radio" name="kelamin" value="2" /> B &nbsp;
					 <input type="radio" name="kelamin" value="3" /> C &nbsp;
					 <input type="radio" name="kelamin" value="4" /> D &nbsp;
					 <input type="radio" name="kelamin" value="5" checked/> E';
		} else {
			$data = '<input type="radio" name="kelamin" value="1" checked/> A &nbsp;
					 <input type="radio" name="kelamin" value="2" /> B &nbsp;
					 <input type="radio" name="kelamin" value="3" /> C &nbsp;
					 <input type="radio" name="kelamin" value="4" /> D &nbsp;
					 <input type="radio" name="kelamin" value="5" /> E';
		}
		return $data;
	}
	
	//add kuis
	function add_kuis_callback() {
		$id_mapel = $this->uri->segment(3);
		$q = $this->db->get_where('seting_mapel', array('id' => $id_mapel), 1)->row();
		return '<input type="hidden" name="id_mapel" value="'.$id_mapel.'"><strong>'.$q->mapel.'</strong>';
	}
	//id pegawai
	function idpegawai_callback() {
		$user = $this->ion_auth->user()->row()->id;
		$id_pegawai = $this->pegawai_model->get_by('id_user',$user)->id;
		$q = $this->db->get_where('pegawai', array('id' => $id_pegawai), 1)->row();
		return '<input type="hidden" name="id_pegawai" value="'.$q->id.'"><strong>'.$q->nama.'</strong>';
	}
	// jenis kuis
	function jenis_test_callback($value)
	{
		if($value == 'multiple') {
			$data = '<input type="radio" name="test_type" value="multiple" id="field-test_type-true" checked/> Multiple &nbsp;
					 <input type="radio" name="test_type" value="essay" id="field-test_type-false" /> Essay';
		} elseif($value == 'essay') {
			$data = '<input type="radio" name="test_type" value="multiple" id="field-test_type-true" /> Multiple &nbsp;
					 <input type="radio" name="test_type" value="essay" id="field-test_type-false" checked/> Essay';
		} else {
			$data = '<input type="radio" name="test_type" value="multiple" id="field-test_type-true" checked/> Multiple &nbsp;
					 <input type="radio" name="test_type" value="essay" id="field-test_type-false"  /> Essay';
		}
		return $data;
	}	
}

/* End of file example.php */
/* Location: ./application/modules/siswa/controllers/Siswa.php */