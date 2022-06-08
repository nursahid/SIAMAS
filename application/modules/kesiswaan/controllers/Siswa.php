<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Siswa Controller.
 */
class Siswa extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Siswa";
		$this->load->library(array('settings'));
		$this->load->model(array('siswa_model', 'orangtua_model', 'prestasi_model'));
    }

    /**
     * Index
     */
    public function index()
    {
		$crud = new grocery_CRUD();
		
		$crud->set_table("siswa");
		$crud->set_subject("Siswa");
		$crud->where('status','aktif');

		// Show in
		$crud->add_fields(["nama", "nisn", "nik", "tempat_lahir", "tgl_lahir", "kelamin", "alamat", "kelurahan", "kecamatan", "kabupaten", "provinsi", "kodepos", "ekonomi", "anak_ke", "jml_saudara", "foto", "hp_ortu", "asal_sekolah", "program_studi", "tgl_daftar"]);
		$crud->edit_fields(["nama", "nisn", "nik", "tempat_lahir", "tgl_lahir", "kelamin", "alamat", "kelurahan", "kecamatan", "kabupaten", "provinsi", "kodepos", "ekonomi", "anak_ke", "jml_saudara", "foto", "hp_ortu", "asal_sekolah", "program_studi", "tgl_daftar"]);
		$crud->columns(["nama", "nis", "nisn", "kelamin", "pengelolaan", "token"]);

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
		//$crud->unset_read();
		$crud->unset_texteditor('alamat');
		
		//Callbacks
		$crud->callback_add_field('kelamin',array($this,'kelamin_callback'));
		$crud->callback_edit_field('kelamin',array($this,'kelamin_callback'));
		$crud->callback_add_field('status',array($this,'status_callback'));
		$crud->callback_edit_field('status',array($this,'status_callback'));
		
		$crud->callback_column('pengelolaan',array($this,'pengelolaan_column_callback'));
		$crud->callback_column('nis',array($this,'nis_callback'));
		$crud->callback_column('token',array($this,'password_callback'));
		
		$crud->callback_after_insert(array($this,'create_nis_callback'));
		
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
		$template_data["crumb"] = ["Kesiswaan" => "kesiswaan","Siswa" => "kesiswaan/siswa",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}

    public function setalumni($id_siswa) {
		$crud = new grocery_CRUD();
		
		$crud->set_table("siswa");
		$crud->set_subject("Siswa");
		$crud->where('status','aktif');
		$crud->edit_fields(["id", "angkatan", "status"]);	
		//cek tgl daftar dulu
		$id_siswa = $this->uri->segment(5);
		$cek_pendaftaran = $this->siswa_model->get_by('id',$id_siswa)->tgl_daftar;
		//var_dump($cek_pendaftaran);
		//jika kosong/NULL, redirek ke siswa
		if( $cek_pendaftaran == '0000-00-00' || $cek_pendaftaran == NULL ) {
			redirect('kesiswaan/siswa');
		}		
		//unset
		$crud->unset_back_to_list();
		$crud->set_lang_string('update_success_message', 'Data berhasil disimpan<br/> Mohon tunggu dialihkan ke halaman berikutnya...
			<script type="text/javascript">
			window.location = "'.site_url('kesiswaan/siswa').'";
			</script>
			<div style="display:none">
			'
		);
		//display
		$crud->display_as("id", "Nama Santri");
		
		//callback
		$crud->callback_edit_field('id',array($this,'idsiswa_callback'));
		$crud->callback_edit_field('angkatan',array($this,'angkatan_callback'));
		$crud->callback_edit_field('status',array($this,'status_alumni_callback'));
		$crud->callback_after_update(array($this,'hapus_siswakelas_callback'));
		
		//view
		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Alumnikan Santri";
		$template_data["crumb"] = ["Kesiswaan" => "kesiswaan","Siswa" => "kesiswaan/siswa",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}	
	//========================
	//LIHAT DATA ============
    public function lihat($id_siswa) {
		$id_siswa = $this->uri->segment(4);
		$data['q'] = $this->siswa_model->get_data_by_id($id_siswa);
		$data['kelas_sekarang'] = $this->siswa_model->get_kelas_by($id_siswa); 
		//view
		$this->layout->set_wrapper('siswa_show', $data);
		$template_data["title"] = "Data Siswa";
		$template_data["crumb"] = ["Siswa" => "personil/siswa",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); 
    }
	//========================
	//UPDATE DATA ============
    public function ubah($id_siswa) {
		$id_siswa = $this->uri->segment(4);
		$data['q'] = $this->siswa_model->get_by('id',$id_siswa);
				
		$this->layout->set_wrapper('siswa_edit', $data);
		//view
		$template_data["title"] = "Update Data";
		$template_data["crumb"] = ["Siswa" => "personil/siswa",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); 
    }
	public function update() {
		//$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|max_length[15]');
		$this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'trim|required');
		$this->form_validation->set_rules('tmp_lahir', 'Tempat Lahir', 'trim|required');
		$this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'trim|required');

		$id 	= $this->input->post('id_siswa');
		//merubah mode tgl_lahir => m/d/y ke d/m/y
		$var_tgl 	= $this->input->post('tgl_lahir');
		$ubah_tgl 	= date("d/m/Y", strtotime($var_tgl));
		$tanggal 	= str_replace('/', '-', $ubah_tgl);
		$tgl_lahir 	= date('Y-m-d', strtotime($tanggal));
		//merubah mode tgl_daftar => m/d/y ke d/m/y
		$var_tgl2 	= $this->input->post('tgl_daftar');
		$ubah_tgl2 	= date("d/m/Y", strtotime($var_tgl2));
		$tanggal2 	= str_replace('/', '-', $ubah_tgl2);
		$tgl_daftar = date('Y-m-d', strtotime($tanggal2));
		
		$data = array('nama_lengkap' => $this->input->post('nama_lengkap',TRUE),
					  'tmp_lahir' => $this->input->post('tmp_lahir',TRUE),
					  'tgl_lahir' => $tgl_lahir,
					  'email' => $this->input->post('email',TRUE),
					  'kelamin' => $this->input->post('kelamin',TRUE),
					  'telepon' => $this->input->post('telepon',TRUE),
					  'alamat' => $this->input->post('alamat',TRUE),
					  'tgl_daftar' => $tgl_daftar,
					  'posisi' => $this->input->post('posisi',TRUE),
					  'is_active' => $this->input->post('is_active',TRUE),
				);
			
		if ($this->form_validation->run() == TRUE) {
			$config['upload_path'] = './assets/uploads/siswa/';
			$config['allowed_types'] = 'jpg|png';
			
			$this->load->library('upload', $config);
			
			if (!$this->upload->do_upload('foto')){
				$error = array('error' => $this->upload->display_errors());
			}
			else{
				$data_foto = $this->upload->data();
				$data['foto'] = $data_foto['file_name'];
			}

			$result = $this->siswa_model->update($id, $data);
			if ($result > 0) {
				//$this->updateProfil();
				$this->session->set_flashdata('alert', '<div class="alert alert-success">Data Profile Berhasil diubah</div>');
				redirect('kesiswaan/siswa');
			} else {
				$this->session->set_flashdata('alert', '<div class="alert alert-danger">Data Profile Gagal diubah</div>');
				redirect('kesiswaan/siswa');
			}
		} else {
			$this->session->set_flashdata('alert', validation_errors());
			redirect('kesiswaan/siswa');
		}
	}
	//update password
	public function ubah_password() {
		$this->form_validation->set_rules('passLama', 'Password Lama', 'trim|required');
		$this->form_validation->set_rules('passBaru', 'Password Baru', 'trim|required');
		$this->form_validation->set_rules('passKonf', 'Password Konfirmasi', 'trim|required');

		$id = $this->input->post('id_siswa');
		if ($this->form_validation->run() == TRUE) {
			if (md5($this->input->post('passLama')) == $this->userdata->password) {
				if ($this->input->post('passBaru') != $this->input->post('passKonf')) {
					$this->session->set_flashdata('alert', '<div class="alert alert-danger">Password Baru dan Konfirmasi Password harus sama</div>');
					redirect('kesiswaan/siswa');
				} else {
					$data = [
						//'password' => md5($this->input->post('passBaru'))
						//'password' =>$this->ion_auth_model->hash_password($this->input->post('passBaru'))
						'password' =>$this->siswa_model->hash($this->input->post('passBaru'))
					];

					$result = $this->siswa_model->update($id, $data);
					if ($result > 0) {
						//$this->updateProfil();
						$this->session->set_flashdata('alert', '<div class="alert alert-success">Password Berhasil diubah</div>');
						redirect('kesiswaan/siswa');
					} else {
						$this->session->set_flashdata('alert', '<div class="alert alert-danger">Password Gagal diubah</div>');
						redirect('kesiswaan/siswa');
					}
				}
			} else {
				$this->session->set_flashdata('alert', '<div class="alert alert-danger">Password Salah</div>');
				redirect('kesiswaan/siswa');
			}
		} else {
			$this->session->set_flashdata('alert', validation_errors());
			redirect('kesiswaan/siswa');
		}
	}
	//set password
	public function set_password($id_user='') {		
		//get data by id
		$siswa = $this->siswa_model->get_by('id', $id_user);
		//$password  = date("Ymd", strtotime($siswa->tgl_lahir));
		$username  = $siswa->nis;
		$data = array('token' => $siswa->nis,
					  'username' => $siswa->nis,
					  'password' => $this->siswa_model->hash($siswa->nis)
					);
		//Update field password
		$this->db->where('id', $id_user)->update('siswa', $data);

		redirect('kesiswaan/siswa');		
	}
	//reset password
	public function reset_password($id_user='') {		
		//get data by id
		$datasiswa = $this->siswa_model->get_by('id', $id_user);
		//$password  = date("Ymd", strtotime($datasiswa->tgl_lahir));
		$username  = $datasiswa->nis;
		$data = array('token' => $siswa->nis,
					  'password' => $this->siswa_model->hash($siswa->nis)
					);
		//Update field password
		$this->db->where('id', $id_user)->update('siswa', $data);

		redirect('kesiswaan/siswa');		
	}	
	
	//generate NIS
	function generatenis($id_user) {
		//----------------------------------------
		//NIS = 2 digit_thn_masuk+IDjurusan+kelamin+no.urut
		//----------------------------------------
		$settings 	= $this->settings->get();
		$id_user = $this->uri->segment(4);
		$user 	 = $this->db->get_where('siswa',array('id'=>$id_user))->row();
		//$alldate = str_replace('-', '', $user->tgl_daftar); // buang tanda -
		$ambilthn = date('Y', strtotime($user->tgl_daftar)); // ambil tahun saja
		//1. Digit tahun
		$digitthn = substr($ambilthn,2); //tahun jadi dua digit
		//2. Jurusan
		$jurusan = "0".$user->program_studi;
		//3. kode kelamin
		if($user->kelamin == "L") {
			$idkelamin = "01";
		} elseif($user->kelamin == "P") {
			$idkelamin = "02";
		}
		//4. Nomor Urut
		$row = $this->db->select_max('id')->get('siswa')->row();
		$nostart = $settings['angkaawal_nis'];
		if(empty($row)) {
			$noreg = sprintf("%04s", $nostart);
		} else {
			$notmp = $nostart+$row->id;
			$noreg = sprintf("%04s", $notmp);
		}
		/*
		$angka = $id_user;
		//$angka = 789;
		$hitungangka = strlen($angka);
		//jika cuma 1, tambah 3 nol
		if($hitungangka == 1) {
			$knourut = "000".$angka;
		} elseif ($hitungangka == 2) {
			$knourut = "00".$angka;
		} elseif ($hitungangka == 3) {
			$knourut = "0".$angka;
		} else {
			$knourut = $angka;
		}
		*/
		
		
		//NIS = DigitThn.Jurusan.Kelamin.NoUrut
		$no_nis   = $digitthn.$jurusan.$idkelamin.$knourut;
		$password = $this->siswa_model->hash($no_nis);
		//array
		$data = array('nis' => $no_nis, 'password' => $password);
		//update NIS
		$this->db->where('id', $id_user)->update('siswa', $data);

		redirect('kesiswaan/siswa');
	}
	public function new_nis($program_studi,$kelamin,$tgldaftar) {
		//ambil beberapa variabel user
		//$status = $this->siswa_model->get_one($id_siswa);
		//$kelamin = $status['kelamin'];
		//$tgllahir= $status['tgl_lahir'];
		//$tgldaftar = $status['tgl_daftar'];
		//=================================
		//1. No_urut 
		$row = $this->db->select_max('id')->get('siswa')->row();
		$nostart = 1;
		if(empty($row)) {
			$noreg = sprintf("%04s", $nostart);
		} else {
			$notmp = $nostart+$row->id;
			$noreg = sprintf("%04s", $notmp);
		}
		//2. Jurusan
		$jurusan = "0".$program_studi;
		//3. kode kelamin
		if($kelamin == "L") {
			$idkelamin = "01";
		} elseif($kelamin == "P") {
			$idkelamin = "02";
		}
		//3. Kode TglBlnThnLahir
		//$TglBlnThnLahir = date("dmY", strtotime($tgllahir));
		//4. Kode BlnThnDaftar
		$BlnThnDaftar 	= date("mY", strtotime($tgldaftar));
		$ThnDaftar 		= date("y", strtotime($tgldaftar));
		$digitthn 		= substr($ThnDaftar,2); //tahun jadi dua digit
		//NISbaru = 2DigitThnDaftar.JenisKelamin.Noregister
		$nis = $ThnDaftar.$jurusan.$idkelamin.$noreg;
		return $nis;
	}
	//Alumnikan
	public function alumnikan($id_siswa) {
		//buka form pengisian tahun kelulusan
		$data['id_siswa'] = $this->uri->segment(4);
		//$data['status'] = "Alumni";
		//Update field password
		//$this->db->where('id', $id_siswa)->update('siswa', $data);
		//redirect('kesiswaan/siswa');

		//cek tgl daftar dulu
		$cek_pendaftaran = $this->siswa_model->get_by('id',$id_siswa)->tgl_daftar;
		//jika kosong/NULL, redirek ke siswa
		if( $cek_pendaftaran == '0000-00-00' || $cek_pendaftaran == NULL ) {
			redirect('kesiswaan/siswa');
		}
        // layout view
        $this->layout->set_wrapper('siswa_alumnikan', $data);
        $this->layout->auth();
        $template_data['title'] = 'Santri Alumni';
        $template_data['crumb'] = ['Siswa' => 'kesiswaan/siswa/',];
        $this->layout->setCacheAssets();
        $this->layout->render('admin', $template_data);		
	}
	//tambah alumni
    function addalumni() {
		if ($_POST['j_action'] == 'add_alumni') {
			$id_siswa			= $_POST['id_siswa'];
			$data['angkatan']	= $_POST['angkatan'];
			$data['status']		= "Alumni";
			
			$this->db->where('id', $id_siswa)->update('siswa', $data);
			$res = "Data berhasil disimpan";
			echo json_encode($res);
		}		
    }
	
	
	//--------------
	// ALL Callbacks
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
	// status
	function status_callback($value)
	{
		if($value == 'Aktif') {
			$data = '<input type="radio" name="status" value="Aktif" checked/> Aktif &nbsp;
					 <input type="radio" name="status" value="Alumni" /> Alumni &nbsp;
					 <input type="radio" name="status" value="Mutasi" /> Mutasi';
		} elseif($value == 'Alumni') {
			$data = '<input type="radio" name="status" value="Aktif" /> Aktif &nbsp;
					 <input type="radio" name="status" value="Alumni" checked/> Alumni &nbsp;
					 <input type="radio" name="status" value="Mutasi" /> Mutasi';
		} elseif($value == 'Mutasi') {
			$data = '<input type="radio" name="status" value="Aktif" /> Aktif &nbsp;
					 <input type="radio" name="status" value="Alumni" /> Alumni &nbsp;
					 <input type="radio" name="status" value="Mutasi" checked/> Mutasi';
		} else {
			$data = '<input type="radio" name="status" value="Aktif" checked/> Aktif &nbsp;
					 <input type="radio" name="status" value="Alumni" /> Alumni &nbsp;
					 <input type="radio" name="status" value="Mutasi" /> Mutasi';
		}
		return $data;
	}
	//CALLBACK PASSWORD
	function password_callback($value, $row)
	{
		if ($value == '') {
			$val = $row->token." &nbsp;<a data-toggle='tooltip' data-placement='bottom' title='Set Password. Pastikan sudah memiliki NIS' href='".site_url('kesiswaan/siswa/set_password/'.$row->id)."'><i class='fa fa-plus'></i></a>";
		}else{
			$val = $row->token." &nbsp;<a data-toggle='tooltip' data-placement='bottom' title='Reset Password' href='".site_url('kesiswaan/siswa/reset_password/'.$row->id)."'><i class='fa fa-refresh'></i></a>";
		}
		return $val;
	}	
	//CALLBACK NIS
	function nis_callback($value, $row) {
		//jika nis kosong
		if($row->nis == NULL || $row->nis == '') {
			//cek tgl_daftar dan prodi dulu
			if ($row->tgl_daftar == '0000-00-00' || $row->program_studi == NULL) {
				$val = "<a href='#' class='text-red' data-toggle='tooltip' data-placement='bottom' title='Tgl. Daftar dan Prodi Pilihan kosong, jadi belum bisa generate NIS. Silakan edit dulu'><i class='fa fa-info'></i> Penting</a>";
			} 
			//siap generate
			else {
				$val = "<span class='badge btn-red'>Siap Generate</span> &nbsp;<a href='".site_url('kesiswaan/siswa/generatenis/'.$row->id)."'><i class='fa fa-plus'></i></a>";
			}
		}
		//jika berisi tampilkan
		else {
			$val = $row->nis."";
		}
		
		return $val;
	}
	//create NIS
	function create_nis_callback($post_array, $primary_key) {
		$nomor_nis 	 = $this->new_nis($post_array['program_studi'],$post_array['kelamin'],$post_array['tgl_daftar']);
		$update_data = array('nis'=>$nomor_nis);
		$update_by 	 = array('id'=>$primary_key);
		//update data
		$this->db->update('siswa',$update_data,$update_by);
		
		return true;
	}
	//pengelolaan
	function pengelolaan_column_callback($value, $row) {
		//get data
		$data = $this->siswa_model->get($row->id);
		if($data->password == NULL || $data->password == '') {
			$password = "<a href='".site_url('kesiswaan/siswa/set_password/'.$row->id)."' class='btn btn-xs btn-default' data-tooltip='tooltip' data-placement='top' title='Reset Password Login'><i class='fa fa-key'></i> Password</a> &nbsp;";
		} else {
			$password = "<a href='".site_url('siswa/login/')."' class='btn btn-xs btn-primary' data-tooltip='tooltip' data-placement='top' title='Set Password Login' target='_blank'><i class='fa fa-key'></i> Login</a>";
		}

		$view = "<a href='".site_url('kesiswaan/orangtua/index/'.$row->id.'')."' class='btn btn-xs btn-success' data-toggle='tooltip' data-placement='bottom' title='Data Orangtua'><i class='fa fa-male'></i> Orangtua</a> &nbsp;";
		$view .= "<a href='".site_url('kesiswaan/prestasi/index/'.$row->id.'')."' class='btn btn-xs btn-info' data-toggle='tooltip' data-placement='bottom' title='Data Prestasi'><i class='fa fa-list'></i> Pretasi</a> &nbsp;";
		$view .= "<a href='".site_url('kesiswaan/siswa/setalumni/edit/'.$row->id)."' class='btn btn-xs btn-danger' data-toggle='tooltip' data-placement='bottom' title='Alumnikan'><i class='fa fa-user'></i> Alumnikan</a> &nbsp;";
		$view .= $password;
		return $view;
	}
	//Kolom ID
	function idsiswa_callback() {
		$id_siswa = $this->uri->segment(5);
		$q = $this->db->get_where('siswa', array('id' => $id_siswa), 1)->row();
		return '<input type="hidden" name="id" value="'.$id_siswa.'"><strong>'.$q->nama.'</strong>';
	}
	//Kolom Status
	function status_alumni_callback() {
		return '<input type="hidden" name="status" value="Alumni"><strong>Alumni</strong>';
	}
	//Kolom Angkatan
	function angkatan_callback() {
		return htmlYearSelector('angkatan');
	}
	//setelah update
	function hapus_siswakelas_callback($post_array, $primary_key) {
		$id_siswa = $primary_key;
		$tahunaktif = $this->sistem_model->get_tapel_aktif();

		//1. Hapus di data kelas, jika ada
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

/* End of file example.php */
/* Location: ./application/modules/siswa/controllers/Siswa.php */