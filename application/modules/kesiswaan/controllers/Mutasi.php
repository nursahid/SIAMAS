<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Mutasi Controller.
 */
class Mutasi extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Siswa";
		$this->load->model(array('siswa_model', 'siswakelas_model', 'siswamasuk_model', 'siswakeluar_model'));
    }

    /**
     * Index
     */
    public function index()
    {
		//view
		$this->layout->set_wrapper('mutasi_index', $data);
		$template_data["title"] = "Mutasi Siswa";
		$template_data["crumb"] = ["Mutasi" => "kesiswaan/mutasi",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); 
	}
    public function masuk()
    {
		$crud = new grocery_CRUD();
		
		$crud->set_table("mutasi_masuk");
		//$crud->where('masuk_dikelas','aktif');
		$crud->set_subject("Mutasi Masuk");

		// Show in
		$crud->add_fields(["nama", "nisn", "nik", "tempat_lahir", "tgl_lahir", "kelamin", "alamat", "kelurahan", "kecamatan", "kabupaten", "provinsi", "kodepos", "asal_sekolah", "masuk_dikelas", "tgl_mutasi_masuk", "keterangan"]);
		$crud->edit_fields(["nama", "nik", "tempat_lahir", "tgl_lahir", "kelamin", "alamat", "kelurahan", "kecamatan", "kabupaten", "provinsi", "kodepos", "asal_sekolah", "masuk_dikelas", "tgl_mutasi_masuk", "keterangan"]);
		$crud->columns(["nisn", "nama", "kelamin", "alamat", "masuk_dikelas", "pengelolaan"]);

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
		//$crud->unset_read();
		$crud->unset_texteditor('alamat');
		$crud->unset_texteditor('keterangan');
		
		//Callbacks
		$crud->callback_add_field('kelamin',array($this,'kelamin_callback'));
		$crud->callback_edit_field('kelamin',array($this,'kelamin_callback'));		
		$crud->callback_column('pengelolaan',array($this,'pengelolaan_column_callback'));
		
		//state
		$state = $crud->getState();
		$state_info = $crud->getStateInfo();
		
		if($state == 'list' || $state == 'ajax_list') {
			$id_siswa = $this->siswa_model->get($state_info->primary_key)->id;
			if($id_siswa) {
				//hilangkan edit dan delete
				$crud->unset_edit();
				$crud->unset_delete();
			}
		}
		elseif($state == 'read') {
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
	public function masuk_detail($id_siswa) {
		
	}

    public function setsiswa($id_siswa) {
		//cek tgl daftar dulu
		$id_siswa = $this->uri->segment(4);
		$q = $this->siswamasuk_model->get_by('id',$id_siswa);
		//ambil id_jurusan dari tabel kelas
		$this->load->model(array('pembelajaran/kelas_model','siswakelas_model'));
		$prodi 		 = $this->kelas_model->get_by('id',$q->masuk_dikelas)->id_jurusan;
		$tapel_aktif = $this->sistem_model->get_tapel_aktif();
		$data = array('nama'=>$q->nama,
					  'nisn'=>$q->nisn,
					  'nik'=>$q->nik,
					  'tempat_lahir'=>$q->tempat_lahir,
					  'tgl_lahir'=>$q->tgl_lahir,
					  'kelamin'=>$q->kelamin,
					  'agama'=>$q->agama,
					  'alamat'=>$q->alamat,
					  'kelurahan'=>$q->kelurahan,
					  'kecamatan'=>$q->kecamatan,
					  'kabupaten'=>$q->kabupaten,
					  'provinsi'=>$q->provinsi,
					  'kodepos'=>$q->kodepos,
					  'program_studi'=>$prodi,
					  'asal_sekolah'=>$q->asal_sekolah,
					  'tgl_daftar'=>$q->tgl_mutasi_masuk
				);
		//var_dump($data);
		$insert_id = $this->siswa_model->insert($data);
		//masukkan ke kelas
		$data2 = array('id_siswa'=>$insert_id,
					   'id_kelas'=>$q->masuk_dikelas,
					   'id_tahun'=>$tapel_aktif
				);
		$this->siswakelas_model->insert($data2);
		//redirect
		redirect('kesiswaan/siswa');
	}	

    public function keluar()
    {
		$crud = new grocery_CRUD();
		
		$crud->set_table("mutasi_keluar");
		$crud->set_subject("Mutasi Keluar");

		// Show in
		$crud->add_fields(["id_siswa", "jenis_mutasi", "tanggal_mutasi", "keterangan"]);
		$crud->edit_fields(["id_siswa", "jenis_mutasi", "tanggal_mutasi", "keterangan"]);
		$crud->columns(["id_siswa", "jenis_mutasi", "tanggal_mutasi"]);

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
		$crud->unset_texteditor('keterangan');
		
		//Callbacks
		$crud->callback_add_field('jenis_mutasi',array($this,'jenis_mutasi_callback'));
		$crud->callback_edit_field('jenis_mutasi',array($this,'jenis_mutasi_callback'));
		$crud->callback_after_insert(array($this,'update_datasiswa_callback'));
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
	public function keluar_detail($id_siswa) {
		
	}
	
	//=====================
	// ALL CALLBACKS
	//--------------
	// kelamin
	function kelamin_callback($value)
	{
		if($value == 'L') {
			$data = '<input type="radio" name="kelamin" value="L" checked/> Laki-Laki &nbsp;
					 <input type="radio" name="kelamin" value="P" /> Perempuan';
		} elseif($value == 'P') {
			$data = '<input type="radio" name="kelamin" value="L" /> Laki-Laki &nbsp;
					 <input type="radio" name="kelamin" value="P" checked/> Perempuan';
		} else {
			$data = '<input type="radio" name="kelamin" value="L" checked/> Laki-Laki &nbsp;
					 <input type="radio" name="kelamin" value="P" /> Perempuan';
		}
		return $data;
	}
	// jenis_mutasi
	function jenis_mutasi_callback($value)
	{
		if($value == 'Pindah') {
			$data = '<input type="radio" name="jenis_mutasi" value="Pindah" checked/> Pindah &nbsp;
					 <input type="radio" name="jenis_mutasi" value="Mengundurkan Diri" /> Mengundurkan Diri';
		} elseif($value == 'Mengundurkan Diri') {
			$data = '<input type="radio" name="jenis_mutasi" value="Pindah" /> Pindah &nbsp;
					 <input type="radio" name="jenis_mutasi" value="Mengundurkan Diri" checked/> Mengundurkan Diri';
		} else {
			$data = '<input type="radio" name="jenis_mutasi" value="Pindah" checked/> Pindah &nbsp;
					 <input type="radio" name="jenis_mutasi" value="Mengundurkan Diri" /> Mengundurkan Diri';
		}
		return $data;
	}
	//pengelolaan
	function pengelolaan_column_callback($value, $row) {
		//get data by nisn
		$nisn = $this->siswa_model->get_by('nisn',$row->nisn)->nisn;
		//jika sudah ada, hilangkan tombol setsiswa
		if($nisn) {
			$view = "<span class='badge btn-danger'>Sudah masuk ke database</span>";
		} else {
			$view = "<a href='".site_url('kesiswaan/mutasi/setsiswa/'.$row->id)."' class='btn btn-xs btn-primary' data-toggle='tooltip' data-placement='bottom' title='Masukkan ke Data Siswa'><i class='fa fa-plus'></i> Masukkan ke Database</a>";		
		}		
		return $view;
	}
	//Kolom ID
	function idsiswa_callback() {
		$id_siswa = $this->uri->segment(5);
		$q = $this->db->get_where('siswa', array('id' => $id_siswa), 1)->row();
		return '<input type="hidden" name="id" value="'.$id_siswa.'"><strong>'.$q->nama.'</strong>';
	}
	//Kolom masuk_dikelas
	function masuk_dikelas_alumni_callback() {
		return '<input type="hidden" name="masuk_dikelas" value="Alumni"><strong>Alumni</strong>';
	}
	//setelah insert
	function update_datasiswa_callback($post_array, $primary_key) {
		$id_siswa = $post_array['id_siswa'];
		$tahunaktif = $this->sistem_model->get_tapel_aktif();

		//1. Data siswa jadi Mutasi
			$data = array('status'=>'Mutasi');
			$this->siswa_model->update($id_siswa, $data);
		
		//2. Hapus di data kelas, jika ada
		   //ambil data kelas terakhir (pada tahun aktif)
			$qry = $this->db->get_where('siswa_kelas', array('id_siswa' => $id_siswa, 'id_tahun' => $tahunaktif));
			if ($qry->num_rows() > 0) {
				$dt = $qry->row();
				//ambil variabel id_kelas
				$id_kelas = $dt->id_kelas;
				//hapus data
				$this->db->where(array( 'id_siswa' => $id_siswa, 'id_kelas' => $id_kelas, 'id_tahun' => $tahunaktif));
				$this->db->delete('siswa_kelas');
			}
		
		return true;
	}
	
}

/* End of file Mutasi.php */
/* Location: ./application/modules/siswa/controllers/Mutasi.php */