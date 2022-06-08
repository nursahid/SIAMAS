<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Pegawai Controller.
 */
class Pegawai extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

		$this->load->model(array('pegawai_model'));
		$this->load->model(array('ampumapel_model'));	
		$this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
    }

    /**
     * Index
     */
    public function index()
    {
		//view
		$this->layout->set_wrapper('index', $data);
		$template_data["title"] = "Kepegawaian";
		$template_data["subtitle"] = "Manajemen Data Pegawai";
		$template_data["crumb"] = ["Kepegawaian" => "pegawai/index",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); 
	}	 
	 
	 
	public function data()
	{
		$crud = new grocery_CRUD();
		
		$crud->set_table("pegawai");
		$crud->set_subject("Pegawai");

		// Show in
		$crud->add_fields(["nama", "nik", "kelamin", "tempat_lahir", "tgl_lahir", "jabatan", "status_kepegawaian", "alamat", "kodepos", "hp", "email", "status_kawin", "nama_sutri", "pekerjaan_sutri", "foto"]);
		$crud->edit_fields(["nama", "nip", "nik", "kelamin", "tempat_lahir", "tgl_lahir", "jabatan", "status_kepegawaian", "alamat", "kodepos", "hp", "email", "status_kawin", "nama_sutri", "pekerjaan_sutri", "foto"]);
		$crud->columns(["nama", "nip", "jabatan", "hp", "token"]);

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
		$crud->unset_texteditor('alamat');
		
		//Callbacks
		$crud->callback_column('nip',array($this,'nip_callback'));
		$crud->callback_add_field('kelamin',array($this,'kelamin_callback'));
		$crud->callback_edit_field('kelamin',array($this,'kelamin_callback'));
		
		$crud->callback_column('token',array($this,'password_callback'));
		$crud->callback_column('detail',array($this,'callback_detail'));
		
		$crud->callback_after_insert(array($this,'buatnip_callback'));
		//callback after update, jika status kepegawaian berubah maka grup users berubah
		$crud->callback_after_update(array($this,'cekjabatan_callback'));
		
		//GET STATE
		$state = $crud->getState();
		$state_info = $crud->getStateInfo();
		var_dump($state_info);
		//jika id = 1,2,3 =>unset_delete()
		if($state == 'list') {
			//if($state_info->id == 1 || $state_info->id == 2 $state_info->id == 3 ) {
				$crud->unset_delete();
			//}
		} elseif($state == 'add') {
			$crud->set_js("assets/plugins/jquery.slugify/speakingurl.js");
			$crud->set_js("assets/plugins/jquery.slugify/slugify.min.js");
			$crud->set_js("assets/js/pegawai_add.js");
		} elseif($state == 'edit') {
			$crud->set_js("assets/plugins/jquery.slugify/speakingurl.js");
			$crud->set_js("assets/plugins/jquery.slugify/slugify.min.js");
			//jika edit sesuaikan dengan yang aktif
			$row = $this->pegawai_model->get($this->uri->segment(4));
			$status_kawin = $row->status_kawin;
			if($status_kawin =='Kawin') {
				$crud->set_js("assets/js/pegawai_edit.js");
			} elseif($status_kawin =='Belum Kawin') {
				$crud->set_js("assets/js/pegawai_add.js");
			}
		} elseif($state == 'read') {
		    $id_pegawai = $state_info->primary_key;
		    redirect('pegawai/detail/'.$id_pegawai);
		}
		
		//view
		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Pegawai";
		$template_data["crumb"] = ["Pegawai" => "pegawai",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
	//===================
	// Detail Pegawai  // 
	//===================
    public function detail($id_pegawai) {
		$id_pegawai = $this->uri->segment(3);
		$data['q']  = $this->pegawai_model->get_by('id',$id_pegawai);
		$data['tapel'] = $this->sistem_model->get_tapel_aktif();
		$data['kelas_sekarang'] = ''; 
				
		$this->layout->set_wrapper('pegawai_detail', $data);
		//view
		$template_data["title"] = "Data Pegawai";
		$template_data["crumb"] = ["Pegawai" => "pegawai",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); 
    }

	//===============================
	//   PENGAMPU MATA PELAJARAN
	//-------------------------------
	public function ampumapel()
	{
		$crud = new grocery_CRUD();
		
		$crud->set_table("pegawai_mapel");
		//where id_tahun = tahun aktif
		$tapel_aktif = $this->sistem_model->get_tapel_aktif();
		$crud->where('id_tahun',$tapel_aktif );
		$crud->set_subject("Ampu Mapel");

		// Show in
		$crud->add_fields(["id_tahun", "id_pegawai", "id_mapel", "id_tingkat"]);
		$crud->edit_fields(["id_tahun", "id_pegawai", "id_mapel", "id_tingkat"]);
		$crud->columns(["id_tahun", "id_pegawai", "id_mapel", "id_tingkat"]);

		// Fields type
		$crud->field_type("id", "integer");

		// Relation n-n
		$crud->set_relation("id_pegawai", "pegawai", "nama");
		$crud->set_relation("id_mapel", "seting_mapel", "mapel");
		$crud->set_relation("id_tahun", "seting_tahun_ajaran", "tahun");
		$crud->set_relation("id_tingkat", "seting_tingkat", "tingkat");

		// Validation
		$crud->set_rules("id_pegawai", "Pegawai", "required");
		$crud->set_rules("id_mapel", "Mapel", "required");
		$crud->set_rules("id_tahun", "Tahun Ajaran", "required");
		$crud->set_rules("id_tingkat", "Tingkat", "required");

		// Display As
		$crud->display_as("id_pegawai", "Pegawai");
		$crud->display_as("id_mapel", "Mata Pelajaran");
		$crud->display_as("id_tahun", "Tahun Ajaran");
		$crud->display_as("id_tingkat", "Tingkat");

		//CALLBACKS
		$crud->callback_add_field('id_tahun',array($this,'addfield_tapel_callback'));
		$crud->callback_edit_field('id_tahun',array($this,'addfield_tapel_callback'));
		
		// Unset action
		$crud->unset_read();

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Ampu Mata Pelajaran";
		$template_data["crumb"] = ["Ampu Mapel" => "pegawai/ampumapel",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
		
	//==============================
	//set nip
	function setnip($id) {
		$tgl_lahir = $this->pegawai_model->get_tgllahir($id);
		//buang tanda minus
		$data['nip'] = str_replace('-','',$tgl_lahir);
		//insert password
		$this->db->where('id', $id)->update('pegawai', $data);

		redirect('pegawai');
	}
	//set password
	function setpassword($id) {
		$randomtoken = $this->randomtoken(6);
		$user = $this->pegawai_model->get_by('id', $id);
		//CEK NIP apakah sudah ada
		if($user->nip == '' || $user->nip == '-' || $user->nip == NULL ) {
			//alert
			echo '<script type="text/javascript">
				alertify.alert = "NIP masih kosong";
				</script>';
			redirect('pegawai');
		} else {
			//CEK id_user, jika sudah ada tidak perlu dimasukkan ke ion_auth
			if($user->id_user) {
				//update
				$data = array('token' => $user->nip,
							  'password' => $this->pegawai_model->hash($user->nip)
						);
				$this->db->where('id', $id)->update('pegawai', $data);
				
			} else {
				//masukkan ke ion_auth dengan groups sebagai guru
				$username = $user->nip;
				$password = $user->nip;
				$email 	  = $user->email;
				$jabatan  = $user->jabatan;
				$additional_data = array('full_name'=>$user->nama, 'email'=>$user->email, 'photo'=>$user->foto, 'phone'=>$user->hp);
				//jabatan kepsek
				if($jabatan == '1') {
					$group = array('3');
					$insertid = $this->ion_auth->register($username,$password,$email,$additional_data, $group);
					$this->ion_auth->remove_from_group(array('2'), $insertid);
				}
				elseif($jabatan == '2') {
					$group = array('3');
					$insertid = $this->ion_auth->register($username,$password,$email,$additional_data, $group);
					$this->ion_auth->remove_from_group(array('2'), $insertid);
				}
				//jabatan guru
				elseif($jabatan == '3') {
					$group = array('4');
					$insertid = $this->ion_auth->register($username,$password,$email,$additional_data, $group);
					$this->ion_auth->remove_from_group(array('2'), $insertid);
				} 
				//jabatan bendahara
				elseif($jabatan == '4') {
					$group = array('5');
					$insertid = $this->ion_auth->register($username,$password,$email,$additional_data, $group);
					$this->ion_auth->remove_from_group(array('2'), $insertid);
				}
				//lainnya
				else {
					$group = array('6');
					$insertid = $this->ion_auth->register($username,$password,$email,$additional_data, $group);
					$this->ion_auth->remove_from_group(array('2'), $insertid);
				}
				//-----------------------------------------------------------
				//update
				$data = array('token' => $user->nip,
							  'password' => $this->pegawai_model->hash($user->nip),
							  'id_user' => $insertid
						);
				$this->db->where('id', $id)->update('pegawai', $data);
			}
			redirect('pegawai');
		}
	}
	//reset password
	function resetpassword($id) {
		$randomtoken = $this->randomtoken(6);
		//get data by id
		$pegawai = $this->pegawai_model->get_by('id', $id);
		$password = $pegawai->nip;
		$id_user  = $pegawai->id_user;		
		//update
		$data = array('token' => $pegawai->nip,
					  //'password' => $this->pegawai_model->hash($password)
					  'password' => $this->ion_auth_model->hash_password($password)
					);
		$this->db->where('id', $id)->update('pegawai', $data);
		//update ion_auth
		$this->load->model(array('ion_auth_model', 'user_model'));
		//jika username contoh, yaitu admin, kepsek, guru dan bendahara maka username tidak bisa direset
		$user = $this->user_model->get_by('id', $id_user);
		if($user->username == 'admin' || $user->username == 'kepsek' || $user->username == 'guru' || $user->username == 'bendahara') {
			$dataion = array('password' => $this->ion_auth_model->hash_password($password));			
		} else {
			$dataion = array('username' => $pegawai->nip,
						     'password' => $this->ion_auth_model->hash_password($password)
						);			
		}
		//$this->ion_auth_model->update($id_user, $dataion);
		$this->db->where('id', $id_user)->update('users', $dataion);

		redirect('pegawai');
	}
	//random token
	function randomtoken($panjang = '') {
		$karakter = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$panjangKarakter = strlen($karakter);
		$acakString = '';
		for ($i=0; $i < $panjang; $i++) {
			$acakString .= $karakter[rand(0,$panjangKarakter - 1)];
		}
		return $acakString;
	}

	
    //================
    // Import EXCEL //
    //================
    public function import() {
		//VIEW
		$this->layout->set_title('Import Data Pegawai');
		$this->layout->set_wrapper('pegawai_import_excel', $data);			
		$template_data['title'] = 'Import Data Pegawai';
		$template_data['crumb'] = ['Pegawai' => 'pegawai',];
		$this->layout->render('admin', $template_data); 		   
	}
   public function uploadexcel(){
        $fileName = $_FILES['userfile']['name'];
         
        $config['upload_path'] = './assets/uploads/excel/'; //buat folder dengan nama assets di root folder
        $config['file_name'] = $fileName;
        $config['allowed_types'] = 'xls|xlsx|csv';
		$config['overwrite'] = true;
        $config['max_size'] = 20480;
         
        $this->load->library('upload');
        $this->upload->initialize($config);
         
        if(! $this->upload->do_upload('userfile') ) {
			$this->session->set_flashdata('message','<b>Error: </b>'.$this->upload->display_errors().''); 
			redirect('pegawai/import/','refresh');
		} else {
			$media = $this->upload->data();
			$inputFileName = FCPATH.'assets/uploads/excel/'.$media['file_name'];
			if (! is_readable($inputFileName)) die('cant read file, check permissions');
			try {
				$inputFileType = IOFactory::identify($inputFileName);
				$objReader = IOFactory::createReader($inputFileType);
				$objPHPExcel = $objReader->load($inputFileName);
			} catch(Exception $e) {
				$error = ('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
				$this->session->set_flashdata('message','<b>Error: </b>'.$error.''); 
				redirect('pegawai/import/','refresh');
			}
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
             
            for ($row = 2; $row <= $highestRow; $row++){                  //  Read a row of data into an array                 
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                                NULL,
                                                TRUE,
                                                FALSE);
                                                 
                //Sesuaikan sama nama kolom tabel di database
                $data = array(
                    "nama"			=> $rowData[0][0],
                    "nip"			=> $rowData[0][1],
                    "nik"			=> $rowData[0][2],
                    "tempat_lahir"	=> $rowData[0][3],
					"tgl_lahir"		=> $rowData[0][4],
					"kelamin"		=> $rowData[0][5],
					"alamat"		=> $rowData[0][6],
					"jabatan"		=> $rowData[0][7],
					"hp"			=> $rowData[0][8],
					"email"			=> $rowData[0][9],
					"npwp"			=> $rowData[0][10]
                );
                 
                //sesuaikan nama dengan nama tabel
                $insert = $this->insert_ignore("pegawai",$data);
                unlink($media['file_path']); 
				delete_files($media['file_path']);
            }			
		}
        //pesan sukses
		$count = $highestRow;
		$this->session->set_flashdata('message','<span class="badge btn-info">Upload berhasil, Total: <b>'.$count.'</b> data.</span>'); 
		redirect('pegawai/import/','refresh');
    }	
	
	//insert ignore
	protected function insert_ignore($table,array $data) {
        $_prepared = array();
         foreach ($data as $col => $val)
        $_prepared[$this->db->escape_str($col)] = $this->db->escape($val); 
        $this->db->query('INSERT IGNORE INTO '.$table.' ('.implode(',',array_keys($_prepared)).') VALUES('.implode(',',array_values($_prepared)).');');
    }

	
	//--------------
	// ALL Callbacks
	//--------------
	
	//Cek Perubahan Jabatan
	function cekjabatan_callback($post_array, $primary_key) {
		$user 	 = $this->pegawai_model->get_by('id', $primary_key);
		$jabatan = $user->jabatan;
		$id_user = $user->id_user;
		//1. Cek NIP, jika sudah ada lanjutkan, jika belum buat NIK
		if($user->nip == '' || $user->nip == '-' || $user->nip == NULL ) {
			//alert
			echo '<script type="text/javascript">
				alertify.alert = "NIP masih kosong";
				</script>';
			redirect('pegawai');
		} else {
			
			//2. cek perubahan jabatan
			if($post_array->jabatan == $jabatan) {
				//gak ngapa2in
			} else {
				//group = 3 (kepsek), 4 (guru), 5 (bendahara), 6 (pustakawan), 7 (siswa)
				//jabatan kepsek dan wakasek
				if($jabatan == '1') {
					//cek user group
					$user_groupID = $this->ion_auth->get_users_groups($id_user)->row()->id;
					//buang dulu
					$this->ion_auth->remove_from_group($user_groupID, $id_user);
					//add groups
					$this->ion_auth->add_to_group(3, $id_user);
					$this->ion_auth->remove_from_group(array('2'), $id_user);
				}
				elseif($jabatan == '2') {
					//cek user group
					$user_groupID = $this->ion_auth->get_users_groups($id_user)->row()->id;
					//buang dulu
					$this->ion_auth->remove_from_group($user_groupID, $id_user);
					//add groups
					$this->ion_auth->add_to_group(3, $id_user);
					$this->ion_auth->remove_from_group(array('2'), $id_user);
				}
				//jabatan guru
				elseif($jabatan == '3') {
					//cek user group
					$user_groupID = $this->ion_auth->get_users_groups($id_user)->row()->id;
					//buang dulu
					$this->ion_auth->remove_from_group($user_groupID, $id_user);
					//add groups
					$this->ion_auth->add_to_group(4, $id_user);
					$this->ion_auth->remove_from_group(array('2'), $id_user);
				} 
				//jabatan bendahara
				elseif($jabatan == '4') {
					//cek user group
					$user_groupID = $this->ion_auth->get_users_groups($id_user)->row()->id;
					//buang dulu
					$this->ion_auth->remove_from_group($user_groupID, $id_user);
					//add groups
					$this->ion_auth->add_to_group(5, $id_user);
					$this->ion_auth->remove_from_group(array('2'), $id_user);
				}
				//jabatan pustakawan
				elseif($jabatan == '5') {
					//cek user group
					$user_groupID = $this->ion_auth->get_users_groups($id_user)->row()->id;
					//buang dulu
					$this->ion_auth->remove_from_group($user_groupID, $id_user);
					//add groups
					$this->ion_auth->add_to_group(6, $id_user);
					$this->ion_auth->remove_from_group(array('2'), $id_user);
				}
				//jabatan lainnya
				else {
						$group = array('7');
						$id_user = $this->ion_auth->register($username,$password,$email,$additional_data, $group);
						$this->ion_auth->remove_from_group(array('2'), $id_user);
				}
				
			}//end cek NIP
		}
		
		return TRUE;
	}
	
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
	//CALLBACK PASSWORD
	function password_callback($value, $row)
	{
		if ($value == '') {
			$val = $row->token." &nbsp;<a data-toggle='tooltip' data-placement='bottom' title='Set Password. Pastikan sudah memiliki NIP' href='".site_url('pegawai/setpassword/'.$row->id)."'><i class='fa fa-plus'></i></a>";
		}else{
			$val = $row->token." &nbsp;<a data-toggle='tooltip' data-placement='bottom' title='Reset Password' href='".site_url('pegawai/resetpassword/'.$row->id)."'><i class='fa fa-refresh'></i></a>";
		}
		return $val;
	}	
	//CALLBACK NIP
	function nip_callback($value, $row)
	{
	if ($value == '' || $value == '-' || $value == NULL) {
			$val = $row->nip." &nbsp;<a data-toggle='tooltip' data-placement='bottom' title='Buat NIP sementara (thn-bln-tgl)' href='".site_url('pegawai/setnip/'.$row->id)."'><i class='fa fa-plus'></i></a>";
		}else{
			$val = $row->nip;
		}
		return $val;
	}
	//Buat NIP dulu sebelum disimpan`
	function buatnip_callback($post_array, $primary_key) {
		$tgl_lahir = $this->pegawai_model->get_by('id',$primary_key)->tgl_lahir;
		//buang tanda minus
		$data['nip'] = str_replace('-','',$tgl_lahir);
		//update NIP data pegawai
		$this->pegawai_model->update($primary_key, $data);
		
		return TRUE;
	}
	//--------------------
	// Tahun Ajaran Aktif
	function addfield_tapel_callback() {
		$id_tahun = $this->sistem_model->get_tapel_aktif();
		$q = $this->db->get_where('seting_tahun_ajaran', array('id' => $id_tahun), 1)->row();  
		return '<input type="hidden" name="id_tahun" value="'.$id_tahun.'"><strong>'.$q->tahun.'</strong>';
	}	
	
}

/* End of file Pegawai.php */
/* Location: ./application/modules/pegawai/controllers/Pegawai.php */