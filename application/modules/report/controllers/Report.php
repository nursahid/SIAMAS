<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model(array('report_model'));
		//$this->load->library('smsgateway');
    }
	
	public function index() {
		//variabel
		$data['jml_siswa'] 	= $this->report_model->total_siswa();
		$data['jml_pegawai']= $this->report_model->total_pegawai();
		$data['jml_mutasi_masuk']	= $this->report_model->total_mutasi_masuk();
		$data['jml_mutasi_keluar']	= $this->report_model->total_mutasi_keluar();
		$data['jml_kelas'] 	= $this->report_model->total_kelas();
		$data['jml_jurusan']= $this->report_model->total_jurusan();
		
		$template_data['title'] = 'Laporan ';
		$template_data['subtitle'] = ' Administrasi Sekolah ';
        $template_data['crumb'] = ['Report' => 'report',];
		//view
		$this->layout->set_wrapper('report', $data);
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
	
	//---------- SISWA-----------
    public function siswa() {
		$crud = new grocery_CRUD();
		
		$crud->set_table("siswa");
		$crud->set_subject("Siswa");
		$crud->where('status','aktif');

		// Show in
		$crud->columns(["nama", "nis", "nisn", "tempat_lahir", "tgl_lahir", "kelamin"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("nama", "string");
		$crud->field_type("nis", "string");
		$crud->field_type("nisn", "string");
		$crud->field_type("nik", "string");
		$crud->field_type("tempat_lahir", "string");
		$crud->field_type("tgl_lahir", "date");
		$crud->field_type("kelamin", "enum");
		$crud->field_type("alamat", "text");
		$crud->field_type("kelurahan", "string");
		$crud->field_type("kecamatan", "string");
		$crud->field_type("kabupaten", "string");
		$crud->field_type("provinsi", "string");
		$crud->field_type("ekonomi", "string");
		$crud->field_type("anak_ke", "integer");
		$crud->field_type("jml_saudara", "integer");
		$crud->field_type("id_saudara", "string");
		$crud->field_type("angkatan", "integer");
		//$crud->field_type("program_studi", "integer");
		$crud->field_type("id_ortu", "integer");
		$crud->field_type("hp_ortu", "string");
		$crud->set_relation("asal_sekolah", "ref_sekolahasal", "namasekolah");
		$crud->field_type("status", "enum");
		$crud->field_type("token", "string");
		$crud->field_type("password", "string");
		
		$crud->set_field_upload("foto", 'assets/uploads/siswa');

		// Relation n-n
		$crud->set_relation("agama", "ref_agama", "agama");
		$crud->set_relation("program_studi", "ref_jurusan", "jurusan");

		// Validation
		$crud->set_rules("nama", "Nama", "required");
		$crud->set_rules("tempat_lahir", "Tempat lahir", "required");
		$crud->set_rules("tgl_lahir", "Tgl lahir", "required");
		//$crud->set_rules("alamat", "Alamat", "required");

		// Display As
		$crud->display_as("nis", "NIS");
		$crud->display_as("nisn", "NISN");
		$crud->display_as("nik", "NIK");
		$crud->display_as("tempat_lahir", "Tempat Lahir");
		$crud->display_as("tgl_lahir", "Tgl. Lahir");
		$crud->display_as("kelamin", "JK");
		$crud->display_as("<agama></agama>", "Agama");
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
		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_delete();
		
		//Callbacks
		
		//state
		$state = $crud->getState();
		$state_info = $crud->getStateInfo();
		if($state == 'read') {
		    $id_siswa = $state_info->primary_key;
		    redirect('kesiswaan/siswa/lihat/'.$id_siswa);
		}		
		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Data Siswa";
		$template_data["crumb"] = ["Report" => "report","Siswa" => "report/siswa",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
	
	//----------- PEGAWAI -----------------
	public function pegawai() {
		$crud = new grocery_CRUD();
		
		$crud->set_table("pegawai");
		$crud->set_subject("Pegawai");

		// Show in
		$crud->add_fields(["nama", "nik", "kelamin", "tempat_lahir", "tgl_lahir", "jabatan", "status_kepegawaian", "alamat", "kodepos", "hp", "email", "status_kawin", "nama_sutri", "pekerjaan_sutri", "foto"]);
		$crud->edit_fields(["nama", "nip", "nik", "kelamin", "tempat_lahir", "tgl_lahir", "jabatan", "status_kepegawaian", "alamat", "kodepos", "hp", "email", "status_kawin", "nama_sutri", "pekerjaan_sutri", "foto"]);
		$crud->columns(["nama", "nip", "tempat_lahir", "tgl_lahir", "jabatan", "hp", "status_kepegawaian"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("nama", "string");
		//$crud->field_type("nip", "string");
		$crud->field_type("nik", "string");
		$crud->field_type("kelamin", "enum");
		$crud->field_type("tempat_lahir", "string");
		$crud->field_type("tgl_lahir", "date");
		$crud->field_type("status_kepegawaian", "enum");
		$crud->field_type("agama", "string");
		$crud->field_type("alamat", "text");
		$crud->field_type("rt", "string");
		$crud->field_type("rw", "string");
		$crud->field_type("dusun", "string");
		$crud->field_type("kelurahan", "string");
		$crud->field_type("kecamatan", "string");
		$crud->field_type("kabupaten", "string");
		$crud->field_type("provinsi", "string");
		$crud->field_type("kodepos", "string");
		$crud->field_type("hp", "string");
		$crud->field_type("email", "string");
		$crud->field_type("tugas_tambahan", "string");
		$crud->field_type("nama_ibu", "string");
		$crud->field_type("status_kawin", "enum");
		$crud->field_type("nama_sutri", "string");
		$crud->field_type("pekerjaan_sutri", "string");
		$crud->field_type("npwp", "string");
		$crud->field_type("nama_npwp", "string");
		$crud->field_type("token", "string");
		$crud->field_type("password", "string");
		
		$crud->set_field_upload("foto", 'assets/uploads/image');

		// Relation n-n
		$crud->set_relation("jabatan", "ref_jabatanpegawai", "jabatan");
		
		// Validation
		$crud->required_fields('nama', 'kelamin', 'tempat_lahir', 'tgl_lahir', 'jabatan', 'hp', 'email');
		$crud->set_rules("nama", "Nama", "required");
		$crud->set_rules("kelamin", "Kelamin", "required");
		$crud->set_rules("tempat_lahir", "Tempat Lahir", "required");
		$crud->set_rules("tgl_lahir", "Tgl. Lahir", "required");
		$crud->set_rules("jabatan", "Jabatan", "required");
		$crud->set_rules("hp", "No. HP", "required");
		$crud->set_rules("email", "Email", "required");

		// Display As
		$crud->display_as("nama", "Nama Lengkap");
		$crud->display_as("nip", "NIP");
		$crud->display_as("nik", "NIK");
		$crud->display_as("tempat_lahir", "Tempat Lahir");
		$crud->display_as("tgl_lahir", "Tgl. Lahir");
		$crud->display_as("status_kepegawaian", "Status Kepegawaian");
		$crud->display_as("rt", "RT");
		$crud->display_as("rw", "RW");
		$crud->display_as("dusun", "Dusun");
		$crud->display_as("kelurahan", "Kelurahan");
		$crud->display_as("kecamatan", "Kecamatan");
		$crud->display_as("kabupaten", "Kabupaten");
		$crud->display_as("provinsi", "Provinsi");
		$crud->display_as("kodepos", "Kode Pos");
		$crud->display_as("hp", "No. HP");
		$crud->display_as("nama_sutri", "Nama Suami/Istri");
		$crud->display_as("pekerjaan_sutri", "Pekerjaan Suami/Istri");
		
		// Unset action
		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_delete();
		
		//GET STATE
		$state = $crud->getState();
		$state_info = $crud->getStateInfo();
		if($state == 'read') {
		    $id_pegawai = $state_info->primary_key;
		    redirect('pegawai/detail/'.$id_pegawai);
		}
		
		//view
		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Data Pegawai";
		$template_data["crumb"] = ["Report" => "report","Pegawai" => "report/pegawai"];
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
	//------- MUTASI MASUK -------
    public function mutasi_masuk() {
		$crud = new grocery_CRUD();
		
		$crud->set_table("mutasi_masuk");
		//$crud->where('masuk_dikelas','aktif');
		$crud->set_subject("Mutasi Masuk");

		// Show in
		$crud->columns(["nisn", "nama", "tempat_lahir", "tgl_lahir", "kelamin", "alamat", "asal_sekolah", "masuk_dikelas"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("nama", "string");
		$crud->field_type("agama", "string");
		$crud->field_type("nisn", "string");
		$crud->field_type("nik", "string");
		$crud->field_type("tempat_lahir", "string");
		$crud->field_type("tgl_lahir", "date");
		$crud->field_type("kelamin", "enum");
		$crud->field_type("alamat", "text");
		$crud->field_type("kelurahan", "string");
		$crud->field_type("kecamatan", "string");
		$crud->field_type("kabupaten", "string");
		$crud->field_type("provinsi", "string");
		$crud->field_type("kodepos", "string");

		
		$crud->set_field_upload("foto", 'assets/uploads/siswa');

		// Relation n-n
		$crud->set_relation("agama", "ref_agama", "agama");
		$crud->set_relation("asal_sekolah", "ref_sekolahasal", "namasekolah");
		
		$tapel_aktif = $this->sistem_model->get_tapel_aktif();
		$crud->set_relation("masuk_dikelas", "kelas", "kelas", array('id_tahun'=>$tapel_aktif));

		// Validation
		$crud->required_fields('nama', 'tempat_lahir', 'tgl_lahir', 'masuk_dikelas', 'tgl_mutasi_masuk');
		
		$crud->set_rules("nisn", "NISN", "required");
		$crud->set_rules("nama", "Nama", "required");
		$crud->set_rules("tempat_lahir", "Tempat lahir", "required");
		$crud->set_rules("tgl_lahir", "Tgl. lahir", "required");
		$crud->set_rules("masuk_dikelas", "Masuk di Kelas", "required");
		$crud->set_rules("tgl_mutasi_masuk", "Tgl. Masuk", "required");

		// Display As
		$crud->display_as("nisn", "NISN");
		$crud->display_as("nik", "NIK");
		$crud->display_as("tempat_lahir", "Tempat Lahir");
		$crud->display_as("tgl_lahir", "Tgl. Lahir");
		$crud->display_as("kelamin", "JK");
		$crud->display_as("agama", "Agama");
		$crud->display_as("alamat", "Alamat");
		$crud->display_as("kelurahan", "Kelurahan");
		$crud->display_as("kecamatan", "Kecamatan");
		$crud->display_as("kabupaten", "Kabupaten");
		$crud->display_as("provinsi", "Provinsi");
		$crud->display_as("kodepos", "Kodepos");
		$crud->display_as("masuk_dikelas", "Masuk ke Kelas?");
		$crud->display_as("jml_saudara", "Jml. Saudara");

		// Unset action
		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_delete();
		
		//Callbacks
		
		//state
		$state = $crud->getState();
		$state_info = $crud->getStateInfo();
		
		if($state == 'read') {
		    $id_siswa = $state_info->primary_key;
		    redirect('kesiswaan/mutasi/masuk_detail/'.$id_siswa);
		}
		
		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Data Siswa";
		$template_data["crumb"] = ["Kesiswaan" => "kesiswaan", "Mutasi Masuk" => "kesiswaan/mutasi/masuk",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
	//--------- MUTASI KELUAR -------
    public function mutasi_keluar() {
		$crud = new grocery_CRUD();
		
		$crud->set_table("mutasi_keluar");
		$crud->set_subject("Mutasi Keluar");

		// Show in
		$crud->columns(["id_siswa", "jenis_mutasi", "tanggal_mutasi", "keterangan"]);

		// Fields type
		//$crud->field_type("jenis_mutasi", "enum");
		//$crud->field_type("tanggal_mutasi", "date");
		//$crud->field_type("keterangan", "text");
		
		// Relation n-n
		$crud->set_relation("id_siswa", "siswa", "nama", array('status'=>'Aktif'));

		// Validation
		$crud->set_rules("id_siswa", "Nama Siswa", "required");
		$crud->set_rules("jenis_mutasi", "Jenis Mutasi", "required");
		$crud->set_rules("tanggal_mutasi", "Tgl Mutasi", "required");

		// Display As
		$crud->display_as("id_siswa", "Nama Siswa");
		$crud->display_as("jenis_mutasi", "Jenis Mutasi");
		$crud->display_as("tanggal_mutasi", "Tgl. Mutasi");

		// Unset action
		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_delete();
		
		//Callbacks
		
		//state
		$state = $crud->getState();
		$state_info = $crud->getStateInfo();
		
		if($state == 'read') {
		    $id_siswa = $state_info->primary_key;
		    redirect('kesiswaan/mutasi/keluar_detail/'.$id_siswa);
		}
		
		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Data Siswa";
		$template_data["crumb"] = ["Kesiswaan" => "kesiswaan", "Mutasi Keluar" => "kesiswaan/mutasi/keluar",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
	//--------- KELAS ------------
    public function kelas() {
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
		$template_data["crumb"] = ["Report" => "report","Kelas" => "report/kelas",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
	function link_kelastapel($primary_key, $row) {
	    return site_url('report/kelastapel').'/'.$primary_key;
	}
	public function kelastapel($id_tahun) {
		$crud = new grocery_CRUD();
		
		$crud->set_table("kelas");
		$crud->where('id_tahun', $id_tahun);
		$crud->set_subject("Kelas");

		// Show in
		$crud->columns(["kelas", "id_tingkat", "id_jurusan", "jml_siswa"]);

		// Fields type
		//$crud->field_type("kelas", "string");
		//relation
		$crud->set_relation("id_tingkat", "seting_tingkat", "tingkat");
		$crud->set_relation("id_jurusan", "ref_jurusan", "jurusan");
		$crud->set_relation("id_tahun", "seting_tahun_ajaran", "tahun");

		//CALLBACKS		
		$crud->callback_column('jml_siswa',array($this,'jmlsiswa_column_callback'));
		// Unset action
		$crud->unset_operations();		
		// Validation
		$crud->set_rules("kelas", "Nama Kelas", "required");
		$crud->set_rules("id_tingkat", "Tingkat", "required");

		// Display As
		$crud->display_as("kelas", "Nama Kelas");
		$crud->display_as("id_tingkat", "Tingkat");
		$crud->display_as("id_jurusan", "Jurusan");
		$crud->display_as("id_tahun", "Tahun Ajaran");
		
		//ambil data tingkat
		$this->load->model('referensi/tahunajaran_model');
		$query = $this->tahunajaran_model->get_by('id', $id_tahun);
		$tahunajaran = $query->tahun;
		// RENDER
		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Data Pokok ";
		$template_data["subtitle"] = "Kelas - Tapel : ".$tahunajaran;
		$template_data["crumb"] = ["Report" => "report","Kelas" => "report/kelas",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
	
	//--------- JURUSAN ----------
	public function jurusan() {
		$crud = new grocery_CRUD();
		
		$crud->set_table("ref_jurusan");
		$crud->set_subject("Kode Jurusan");

		// Show in
		$crud->columns(["jurusan", "kode"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("jurusan", "string");
		$crud->field_type("kode", "string");

		// Relation n-n

		// Validation
		$crud->set_rules("jurusan", "jurusan", "required");
		$crud->set_rules("kode", "Kode", "required");

		// Display As
		$crud->display_as("id", "ID");
		$crud->display_as("jurusan", "Jurusan");
		$crud->display_as("kode", "Kode");

		// Unset action
		$crud->unset_operations();

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Data Jurusan";
		$template_data["crumb"] = ["Report" => "report","Jurusan" => "report/kodejurusan",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
	
	
	
	//-------- GRAFIK ------------
    public function grafik()
    {
        $settings 	= $this->settings->get();
		$data['setting'] = $settings;

        $this->layout->set_title('Grafik Laporan');
		
		$template_data['title'] = 'Laporan';
		$template_data['subtitle'] = 'Grafik Laporan';
        $template_data['crumb'] = ['Seting SMS' => 'smsgateway/seting',];
		
        $this->layout->set_wrapper('perancangan', $data); 

        $this->layout->setCacheAssets();

        $this->layout->render('admin', $template_data);
    }	
	//CETAK
    public function cetak()
    {
        $settings 	= $this->settings->get();
		$data['setting'] = $settings;

        $this->layout->set_title('Cetak Laporan');
		
		$template_data['title'] = 'Print';
		$template_data['subtitle'] = 'Cetak Laporan';
        $template_data['crumb'] = ['Seting SMS' => 'smsgateway/cetak',];
		
        $this->layout->set_wrapper('perancangan', $data); 

        $this->layout->setCacheAssets();

        $this->layout->render('admin', $template_data);
    }	

	//================
	// CALLBACKS
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
		$this->load->model('pembelajaran/kelas_model');
		$jml_kelas = $this->kelas_model->count_by('id_tahun',$row->id);
		return $jml_kelas." rombel";
	}
	//jmlsiswa
	function jmlsiswa_column_callback($value, $row) {
		$this->load->model('kesiswaan/siswakelas_model');
		$id_tapel = $this->uri->segment(3);
		$jmlsiswa = $this->siswakelas_model->count_by(array('id_tahun'=>$id_tapel,'id_kelas'=>$row->id));
		return $jmlsiswa." siswa";
	}	
	//Tgl Pengiriman
	function tglpengiriman_column_callback($value, $row)
	{
		return tgl_indo_timestamp($row->SendingDateTime);
	}
	
}
/* End of file Report.php */
/* Location: ./application/modules/smsgateway/controllers/report.php */