<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Keuangan Controller.
 */
class Keuangan extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

		$this->load->model(array('spp_model', 'pembelajaran/kelas_model'));
    }

    /**
     * Index
     */
    public function index()
    {
		//view
		$this->layout->set_wrapper('index', $data);
		$template_data["title"] = "Keuangan";
		$template_data["subtitle"] = "Manajemen Keuangan";
		$template_data["crumb"] = ["Keuangan" => "keuangan",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); 
	}
	
	//Input pembayaran
	public function pembayaran()
	{
		$crud = new grocery_CRUD();
		
		$crud->set_table("pembayaran_jenis");
		$crud->set_subject("Jenis Pembayaran");

		// Show in
		$crud->add_fields(["nama_jenispembayaran"]);
		$crud->edit_fields(["nama_jenispembayaran"]);
		$crud->columns(["nama_jenispembayaran", "manajemen"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("nama_jenispembayaran", "string");

		// Relation n-n

		// Validation
		$crud->set_rules("nama_jenispembayaran", "Nama jenispembayaran", "required");

		// Display As
		$crud->display_as("nama_jenispembayaran", "Nama Pembayaran");

		// Unset action
		$crud->unset_operations();
		
		//callbacks
		$crud->callback_column('manajemen',array($this,'manajemen_callback'));
		
		//view
		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Jenis Pembayaran";
		$template_data["crumb"] = [];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
	
	public function manajemen_callback($value, $row) {
		return "<a data-toggle='tooltip' data-placement='bottom' title='Data Pembayaran' class='btn btn-sm btn-danger' href='".site_url('keuangan/bayar/'.$row->id)."'><i class='fa fa-money'></i>&nbsp; Kelola Pembayaran</a>";
	}
	
	public function tes_kelas($id_kelas) {
		$kelas = $this->spp_model->get_data_siswa_perkelas($id_kelas);
		var_dump($kelas);
	}
	
	//bayar
    function bayar2($id_jenispembayaran) {
		$bln = array(1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
		for ($i=1;$i<=12;$i++){
			$aBln= (strlen($i)==1 ? '0'.$i : $i);
			$dBln[$aBln] = $bln[$i]; 
		}
		
		$data['jenispembayaran']	= $id_jenispembayaran;
		$data['bln']	 = $dBln;
		$data['default'] = date('m');
        $data['kelas'] 	 = $this->sistem_model->get_kelas();
		//view
		$this->layout->set_wrapper('bayar_view', $data);
		$template_data["title"] = "Data Pembayaran";
		$template_data["crumb"] = ["Pembayaran" => "pembayaran",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); 
    }

    function lists() {				
		$data['status'] = array('belum' => 'Belum Bayar', 'sudah' => 'Sudah Bayar');
		$data['datas'] = $this->spp_model->get_data_siswa_perkelas($_POST['kelas']);
		$data['post']	= $_POST;
		$this->load->view('list', $data);
    }

    function add() {
		
		if (isset($_POST['j_action']) && $_POST['j_action'] == 'add_absen') {
			$f['bulan']		= $_POST['bulan'];
			$f['tahun']		= $_POST['tahun'];
			$f['idtahun']	= $this->sistem_model->get_tapel_aktif();
			$f['idsem']		= $this->sistem_model->get_semester_aktif();
			$f['idkelas']	= $_POST['kelas'];
			
			for ($i = 0; $i < sizeof($_POST['idsiswa']); $i++) {
				if ($_POST['status'][$i] == 'sudah') {
					$f['id_siswa']			= $_POST['idsiswa'][$i];				
					$f['id_jnspembayaran']	= $_POST['jenispembayaran'][$i];				
					$f['nilai']				= $_POST['nilai'][$i];
					$f['tgl_transaksi'] 	= $_POST['tglbayar'][$i];
					$this->db->insert('pembayaran', $f);
				}
			}
		}		
    }
	
	function detail($param, $id = '') {
		if (isset($param) AND $param !== '') {			
			if ($param == 'hapus') {
				if (isset($_POST['j_action']) AND $_POST['j_action'] !== '') {
					if ($_POST['j_action'] == 'delete_spp' AND trim($id) !== '') {
						$this->db->delete('spp', array('id' => $id));
						$this->data['msg'] = setMessage('delete', 'spp');
						$this->LoadView('template/msg', $this->data);
					}
				}
				else {
					if (isset($id) AND trim($id) !== '') {
						$this->data['row'] = $this->spp->get_detail_spp($id);
						$this->LoadView('spp/hapus', $this->data);
					}
				}		
			}
			else
				redirect('spp');
		}
		else {
			redirect('spp');
		}		
	}
	
	function data($id = '') {		
				
		$this->form_validation->set_rules('db_tanggal', 'Tanggal', 'required');
		$this->form_validation->set_rules('db_NIS', 'NIS', 'required');
				
		if ($this->form_validation->run() === FALSE) {	
			
			$this->data['ta']  = $this->sistem_model->get_tapel_aktif();
			$this->data['sem'] = $this->sistem_model->get_semester_aktif();
			$this->LoadView('spp/form', $this->data);
		}
		else {
			
			if (isset($_POST['j_action']) AND $_POST['j_action'] !== '') {
				if ($_POST['j_action'] == 'add_param') {
					$d = parseForm($_POST);
					
					$this->db->insert('spp', $d);
					$this->data['msg'] = setMessage('insert', 'spp');
					$this->LoadView('template/msg', $this->data);
				}
			} 
			else
				redirect('spp');
		}	
	}
	
	
	//===================
	//pembayaran
	//===================
	public function bayar($id_jenispembayaran)
	{
		$crud = new grocery_CRUD();
		
		$crud->set_table("pembayaran");
		$crud->where('id_jnspembayaran',$id_jenispembayaran);
		$crud->set_subject("Pembayaran ".$this->sistem_model->get_nama_pembayaran($id_jenispembayaran));
		// Show in
		$crud->add_fields(["no_referensi", "id_jnspembayaran", "tgl_transaksi", "id_siswa", "nilai",  "keterangan"]);
		$crud->edit_fields(["no_referensi", "id_jnspembayaran", "tgl_transaksi", "id_siswa", "nilai", "keterangan"]);
		$crud->columns(["no_referensi", "id_jnspembayaran", "id_siswa", "nilai"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("no_referensi", "string");
		$crud->field_type("tgl_transaksi", "date");
		$crud->field_type("nilai", "string");
		$crud->field_type("bulan", "integer");
		$crud->field_type("tahun", "integer");
		$crud->field_type("id_semester", "true_false");
		$crud->field_type("keterangan", "text");

		// Relation
		$crud->set_relation("id_tahun", "seting_tahun_ajaran", "tahun");
		$crud->set_relation("id_jnspembayaran", "pembayaran_jenis", "nama_jenispembayaran");
		//$crud->set_relation("id_siswa", "siswa", "{nama} - {tgl_lahir}");
		//dropdown siswa terkoneksi dengan rombel (siswa_kelas -> kelas)
		$this->db->select('siswa.id, siswa.nama');		
		$this->db->from('siswa'); 
		$query = $this->db->get(); 
		$finalArray = array(); 
		foreach ($query->result() as $row) { 
			$finalArray[$row->id] = $row->nama." - ".$this->kelas_model->get_kelas_by_siswa($row->id); 
		} 
		$crud->field_type('id_siswa','dropdown', $finalArray); 
		
		//unset
		$crud->unset_texteditor("keterangan", 'full_text');

		// Validation
		$crud->set_rules("tgl_transaksi", "Tgl. transaksi", "required");
		$crud->set_rules("id_jnspembayaran", "Jns. pembayaran", "required");
		$crud->set_rules("id_siswa", "Siswa", "required");
		$crud->set_rules("nilai", "Nominal", "required");
		$crud->set_rules("id_semester", "Semester", "required");

		// Display As
		$crud->display_as("no_referensi", "No. Referensi");
		$crud->display_as("tgl_transaksi", "Tgl. Transaksi");
		$crud->display_as("id_jnspembayaran", "Jenis Pembayaran");
		$crud->display_as("id_siswa", "Siswa");
		$crud->display_as("nilai", "Nominal");
		$crud->display_as("bulan", "Bulan");
		$crud->display_as("tahun", "Tahun");
		$crud->display_as("id_semester", "Semester");
		$crud->display_as("id_tahun", "Tahun");
		$crud->display_as("keterangan", "Keterangan");

		// CALLBACK
		$crud->callback_add_field('no_referensi',array($this,'noreferensi_callback'));
		$crud->callback_edit_field('no_referensi',array($this,'edit_noreferensi_callback'));
		$crud->callback_add_field('id_semester',array($this,'semester_callback'));
		$crud->callback_edit_field('id_semester',array($this,'semester_callback'));
		$crud->callback_add_field('id_jnspembayaran',array($this,'jenispembayaran_callback'));
		$crud->callback_edit_field('id_jnspembayaran',array($this,'jenispembayaran_callback'));
		//after insert, untuk mengambil data bulan dan tahun serta tapel dan semester aktif, update ke database
		$crud->callback_after_insert(array($this,'pembayaran_afterinsert_callback'));
		$crud->callback_after_update(array($this,'pembayaran_afterinsert_callback'));
		
		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Pembayaran";
		$template_data["crumb"] = ["Pembayaran" => "#",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}


	
	//=========== FUNGSI ==========
	public function genCode() {
		//1. No_urut 
		$row = $this->db->select_max('id')->get('pembayaran')->row();
		$nostart = 1;
		if(empty($row)) {
			$noreg = sprintf("%04s", $nostart);
		} else {
			$notmp = $nostart+$row->id;
			$noreg = sprintf("%04s", $notmp);
		}
		//2. Kode Thn-Bln
		$BlnDaftar 	= date("m");
		$ThnDaftar 	= date("Y");
		$digitthn 	= substr($ThnDaftar,2); //tahun jadi dua digit
		//KODE = 2DigitThnDaftar.Bln.Noregister
		$code = $ThnDaftar.$BlnDaftar.$noreg;
		return $code;
	}
	
	//=====================
	// ALL CALLBACK
	//=====================
	//jenispembayaran	
	function jenispembayaran_callback(){
		$id_jnspembayaran = $this->uri->segment(3);
		$res = $this->db->select('*')->where('id',$id_jnspembayaran)->get('pembayaran_jenis')->row();
		return '<input type="hidden" name="id_jnspembayaran" value="'.$res->id.'"><span class="text-red"><strong>'.$res->nama_jenispembayaran.'</strong></span>';
	}
	//kode transaksi	
	function noreferensi_callback(){
		return '<input type="hidden" name="no_referensi" value="'.$this->genCode().'"><span class="text-red"><strong>'.$this->genCode().'</strong></span>';
	}
	function edit_noreferensi_callback($value){
		return '<input type="text" name="no_referensi" value="'.$value.'" readonly>';
	}	
	
	// semester
	function semester_callback($value) {
		$semester_aktif = $this->sistem_model->get_semester_aktif();
		
		if($value == '1') {
			$data = '<input type="radio" name="id_semester" value="1" checked/> Semester 1 &nbsp;
					 <input type="radio" name="id_semester" value="2" /> Semester 2';
		} elseif($value == '2') {
			$data = '<input type="radio" name="id_semester" value="1" /> Semester 1 &nbsp;
					 <input type="radio" name="id_semester" value="2" checked/> Semester 2';
		} else {
			if($semester_aktif == '1') {
				$data = '<input type="radio" name="id_semester" value="1" checked/> Semester 1 &nbsp;
						 <input type="radio" name="id_semester" value="2" /> Semester 2';
			} elseif($semester_aktif == '2') {
				$data = '<input type="radio" name="id_semester" value="1" /> Semester 1 &nbsp;
						 <input type="radio" name="id_semester" value="2" checked/> Semester 2';
			}			
		}
		return $data;
	}	
	
	//pembayaran_afterinsert_callback
	function pembayaran_afterinsert_callback($post_array, $primary_key) {
		$trx = explode('/',$post_array['tgl_transaksi']);
		$bulan = $trx[1];
		$tahun = $trx[2];
		$data = array(
				'bulan' => $bulan,
				'tahun' => $tahun,
				'id_semester' => $this->sistem_model->get_semester_aktif(),
				'id_tahun' => $this->sistem_model->get_tapel_aktif()
		);		
		$this->db->where('id', $primary_key)->update('pembayaran', $data);
		
		return TRUE;
	}
	//rupiahin
	function rupiahin($value, $row)
	{
		return 'Rp '.number_format($value,0, ',', '.');
	}
	function tanggalin($value, $row){
		return tgl_indo($value);
	}
	
}

/* End of file example.php */
/* Location: ./application/modules/pembayaran/controllers/Pembayaran.php */