<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Pengguna Controller.
 */
class Pengguna extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "User Manajemen";
		$this->load->model(array('pegawai/pegawai_model'));
    }

    /**
     * Index
     */
    public function index()
    {
		$crud = new grocery_CRUD();
		
		$crud->set_table("users");
		//jika usernya adalah superadmin, maka sembunyikan
		$crud->where('id !=', 1);
		$crud->set_subject("Pengguna");

		// Show in
		$crud->add_fields(["nama", "kode"]);
		$crud->edit_fields(["nama", "kode"]);
		//$crud->columns(["nama", "kode"]);
		$crud->columns(["username", "email", "groups", "pegawai", "active"]);
		
		$this->load->config('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'gif|jpeg|jpg|png');
		
		//Add fields
		$crud->add_fields('username', 'full_name', 'email', 'phone', 'photo', 'groups', 'password', 'password_confirm');
		// Edit Fields
		$crud->edit_fields('username', 'full_name', 'email', 'phone', 'photo', 'last_login', 'groups', 'old_password', 'new_password');
		//Read fields
		$crud->set_read_fields('username', 'full_name', 'email', 'phone', 'photo', 'last_login');
		
		// Relation n-n
        $crud->set_relation_n_n('groups', 'users_groups', 'groups', 'user_id', 'group_id', 'name');

		// Validation
		$crud->required_fields('username', 'full_name', 'email', 'password', 'password_confirm');
		
        // Image social media
		$state = $crud->getState();
        if ($state == 'read' || $state == 'edit') {
            $id = $this->uri->segment(5);
            $this->db->where('id', $id);
            $checkUser = $this->db->get('users')->row();
            $checkImg = $checkUser->photo;
            if ($checkImg) {
                $crud->callback_edit_field('photo', array($this, 'fieldPhotoUsers'));
            }
        }
		
        // Field types
        $crud->change_field_type('last_login', 'readonly');
        $crud->change_field_type('password', 'password');
        $crud->change_field_type('password_confirm', 'password');
        $crud->change_field_type('old_password', 'password');
        $crud->change_field_type('new_password', 'password');
        $crud->set_field_upload('photo', 'assets/uploads/image');
		
		// Display As
		$crud->display_as("full_name", "Nama Lengkap");
		$crud->display_as("photo", "Foto");
		$crud->display_as("phone", "Telepon");
		
		//Callbacks
		$crud->callback_column('pegawai',array($this,'namapegawai_callback'));
		$crud->callback_column('groups', array($this, 'groups_users'));
        $crud->callback_column('active', array($this, 'active_callback'));
        $crud->callback_insert(array($this, 'create_user_callback'));
        $crud->callback_update(array($this, 'edit_user_callback'));
        $crud->callback_field('last_login', array($this, 'last_login_callback'));
        $crud->callback_after_upload(array($this, 'avatar_upload'));
        $crud->callback_delete(array($this, 'delete_user'));
		
		//add action
		//$crud->add_action('Pegawai', '', 'manajemen/pengguna/cekpegawai', 'fa fa-user');
		
		// Unset action
		$crud->unset_add();
		$crud->unset_delete();
		$crud->unset_read();
		
		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "User Manajemen";
		$template_data["crumb"] = ["manajemen" => "#","User Manajemen" => "manajemen/pengguna",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}

	public function cekpegawai($id_user) {
		//get drop down pegawai
		$data['id_user']  = $id_user;
		$data['pegawais'] = $this->pegawai_model->get_pegawai_semua();
		//view
		$this->layout->set_wrapper('cek_pegawai', $data);
		$template_data["title"] = "Manajemen Pengguna";
		$template_data["subtitle"] = "Manajemen Data Pegawai";
		$template_data["crumb"] = ["Pengguna" => "manajemen/pengguna",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
	function simpanpegawai() {
		//get variabel
		$id_pegawai = $this->input->post('id_pegawai');
		$id_user  	= $this->input->post('id_user');
		//------------------------------------------
		//1. update data pegawai
		$data = array('id_user' => $id_user);
		$this->db->where('id', $id_pegawai)->update('pegawai', $data);
		//2. update data users setelah update data pegawai
		//get data pegawai
		$query = $this->pegawai_model->get_by('id_user',$id_user);

		$datauser = array('full_name' => $query->nama);
		$this->db->where('id', $id_user)->update('users', $datauser);
		
		redirect('manajemen/pengguna');
	}
	
	//KUMPULAN CALLBACK
	//=======================
	
	//Nama Pegawai
	function namapegawai_callback($value, $row) {
		//get data
		$data = $this->pegawai_model->get_by('id_user',$row->id);
		if($data->nama == NULL) {
			$nama_pegawai = "<span class='badge btn-red'>Belum terkoneksi dgn Data Pegawai</span> &nbsp;<a data-toggle='tooltip' data-placement='bottom' title='Klik disini untuk Input Pegawai' href='".site_url('manajemen/pengguna/cekpegawai/'.$row->id)."'><i class='fa fa-plus'></i></a>";
		} else {
			$nama_pegawai = $data->nama." &nbsp;<a data-toggle='tooltip' data-placement='bottom' title='Klik disini untuk Reset Pegawai' href='".site_url('manajemen/pengguna/cekpegawai/'.$row->id)."'><i class='fa fa-refresh'></i></a>";
		}
		return $nama_pegawai;
	}
	
    /**
     * Magic Image URL
     * @param  string $value
     * @param  integer $primary_key
     * @return html
     */
    public function fieldPhotoUsers($value = null, $primary_key = null)
    {
        $html = '<img src="' .base_url('assets/uploads/image/'). $value . '" height="50px">';
        return $html;
    }

    /**
     * Call back groups.
     *
     * @param string $value
     * @param string $row
     *
     * @return Groups
     */
    public function groups_users($value, $row)
    {
        $id_groups[] = 0;
        $return = '';
        $this->db->where('user_id', $row->id);
        $users_groups = $this->db->get('users_groups')->result();
        if ($users_groups) {
            foreach ($users_groups as $value) {
                $id_groups[] = $value->group_id;
            }
            $this->db->where('id in('.implode(',', $id_groups).')');
            $groups = $this->db->get('groups')->result();
            if ($groups) {
                foreach ($groups as $value) {
                    $groups_name[] = $value->name;
                }
                $return = implode(', ', $groups_name);
            }
        }

        return $return;
    }

    /**
     * Avatar upload compress.
     *
     * @return Image
     **/
    public function avatar_upload($uploader_response, $field_info, $files_to_upload)
    {
        $this->load->library('image_moo');
        $file_uploaded = $field_info->upload_path.'/'.$uploader_response[0]->name;

        $this->image_moo->load($file_uploaded)->resize_crop(160, 160)->save($file_uploaded, true);

        return true;
    }
	
    /**
     * Callback active or inactive user.
     *
     * @return HTML
     **/
    public function active_callback($value, $row)
    {
        if ($value == 1) {
            $val = 'active';
        } else {
            $val = 'inactive';
        }

        return "<a href='".site_url('manajemen/pengguna/manual_activate/'.$row->id.'/'.$value)."'>$val</a>";
    }

    /**
     * Redirect link after trigger active or deactive user.
     *
     * @return Rerirect
     **/
    public function manual_activate($id, $value)
    {
        if ($value == 1) {
            $this->ion_auth->deactivate($id);
        } else {
            $this->ion_auth->activate($id);
        }

        redirect('manajemen/pengguna');
    }

    /**
     * Callback date & time last login user.
     *
     * @return string
     **/
    public function last_login_callback($value = '', $primary_key = null)
    {
        $value = date('l Y/m/d H:i', $value);

        return $value;
    }

    /**
     * Delete user.
     *
     * @return bool
     **/
    public function delete_user($primary_key)
    {
        if ($this->ion_auth_model->delete_user($primary_key)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Callback manual edit user.
     *
     * @return bool
     **/
    public function edit_user_callback($post_array, $primary_key)
    {
        $this->load->model('logs');
        $identity = $post_array[$this->config->item('identity', 'ion_auth')];
        $groups = $post_array['groups'];
        $old = $post_array['old_password'];
        $new = $post_array['new_password'];
        $data = array(
            'username' => $post_array['username'],
            'email' => $post_array['email'],
            'full_name' => $post_array['full_name'],
            'photo' => $post_array['photo'],
			'phone' => $post_array['phone'],
            );
        if ($old != '') {
            $change = $this->ion_auth->update($primary_key, $data) && $this->ion_auth->change_password($identity, $old, $new);
        } else {
            $change = $this->ion_auth->update($primary_key, $data);
        }

        if ($groups) {
            $this->ion_auth->remove_from_group('', $primary_key);
            $this->addGroups($groups, $primary_key);
        }

        if ($change) {
            $dataLog = [
            'status' => true,
            'via' => 'admin',
            'identity' => $data['email'],
            'ip' => $this->input->ip_address()
            ];
            $this->logs->addLogs('update_user', $dataLog);

            return true;
        } else {
            $dataLog = [
            'status' => false,
            'via' => 'admin',
            'message' => str_replace('&times;Close', '', strip_tags($this->ion_auth->errors())),
            'identity' => $data['email'],
            'ip' => $this->input->ip_address()
            ];
            $this->logs->addLogs('update_user', $dataLog);

            return false;
        }
    }
    public function addGroups($groups, $primary_key)
    {
        if ($groups) {
            foreach ($groups as $value) {
                $this->ion_auth->add_to_group($value, $primary_key);
            }
        }
    }

    /**
     * Callback manual add user.
     *
     * @return bool
     **/
    public function create_user_callback($post_array, $primary_key = null)
    {
        $this->load->model('logs');

        $username = $post_array['username'];
        $password = $post_array['password'];
        $email = $post_array['email'];
        $group = $post_array['groups'];
        $data = [
			'full_name' => $post_array['full_name'],
			'photo' => $post_array['photo'],
			'phone' => $post_array['phone'],
        ];
        $register = $this->ion_auth->register($username, $password, $email, $data, $group);

        if ($register) {
            $dataLog = [
            'status' => true,
            'via' => 'admin',
            'identity' => $email,
            'ip' => $this->input->ip_address()
            ];
        } else {
            $dataLog = [
            'status' => false,
            'via' => 'admin',
            'message' => str_replace('&times;Close', '', strip_tags($this->ion_auth->errors())),
            'identity' => $email,
            'ip' => $this->input->ip_address()
            ];
        }
        $this->logs->addLogs('register', $dataLog);

        return $register;
    }

	
}

/* End of file Pengguna.php */
/* Location: ./application/modules/referensi/manajemen/Pengguna.php */