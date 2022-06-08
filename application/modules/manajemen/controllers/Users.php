<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Users Controller.
 */
class Users extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "User Manajemen";
    }

    /**
     * Index
     */
    public function index()
    {
        last_url('set'); // save last url
        $this->load->library('Grocery_CRUD');
        $crud = new grocery_CRUD();

        $crud->set_table('users');
        $crud->set_subject('Users');

        $this->load->config('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'gif|jpeg|jpg|png');

        $crud->columns('username', 'email', 'groups', 'active');
		
        if ($this->uri->segment(3) !== 'read') {
            $crud->add_fields('username', 'photo', 'full_name', 'email', 'phone', 'groups', 'password', 'password_confirm');
            $admin_group = $this->config->item('admin_group', 'ion_auth');
            if ($this->ion_auth->in_group($admin_group)) {
                $crud->edit_fields('username', 'photo', 'full_name', 'email', 'phone', 'groups', 'last_login', 'old_password', 'new_password');
                $crud->set_relation_n_n('groups', 'users_groups', 'groups', 'user_id', 'group_id', 'name');
            } else {
                $crud->edit_fields('username', 'photo', 'full_name', 'email', 'phone', 'last_login', 'old_password', 'new_password');
            }
        } else {
            $crud->set_read_fields('username', 'photo', 'full_name', 'email', 'phone', 'last_login');
        }
        // Image social media
        if ($this->uri->segment(3) == 'read' || $this->uri->segment(3) == 'edit') {
            $id = $this->uri->segment(4);
            $this->db->where('id', $id);
            $checkUser = $this->db->get('users')->row();
            $checkImg = explode('http', $checkUser->photo);
            if (isset($checkImg[1])) {
                $crud->callback_edit_field('photo', array($this, 'fieldPhotoUsers'));
            }
        }
        $crud->callback_column('groups', array($this, 'groups_users'));

        // Validation
        $crud->required_fields('username', 'full_name', 'email', 'password', 'password_confirm');
        $crud->set_rules('email', 'E-mail', 'required|valid_email');
        $crud->set_rules('password', 'Password', 'required|min_length['.$this->config->item('min_password_length', 'ion_auth').']|max_length['.$this->config->item('max_password_length', 'ion_auth').']|matches[password_confirm]');
        $crud->set_rules('new_password', 'New password', 'min_length['.$this->config->item('min_password_length', 'ion_auth').']|max_length['.$this->config->item('max_password_length', 'ion_auth').']');

        // Field types
        $crud->change_field_type('last_login', 'readonly');
        $crud->change_field_type('password', 'password');
        $crud->change_field_type('password_confirm', 'password');
        $crud->change_field_type('old_password', 'password');
        $crud->change_field_type('new_password', 'password');
        $crud->set_field_upload('photo', 'assets/uploads/image');

        // Callbacks
        $crud->callback_insert(array($this, 'create_user_callback'));
        $crud->callback_update(array($this, 'edit_user_callback'));
        $crud->callback_field('last_login', array($this, 'last_login_callback'));
        $crud->callback_column('active', array($this, 'active_callback'));
        $crud->callback_after_upload(array($this, 'avatar_upload'));
        $crud->callback_delete(array($this, 'delete_user'));

        if ($this->uri->segment(3) == 'index') {
            $crud->unset_back_to_list();
        }
        $crud->set_theme('flexigrid');

        $data = (array) $crud->render();
        if ($this->uri->segment(4) != 'edit' || $this->uri->segment(5) != $this->ion_auth->user()->row()->id) {
            $this->layout->set_privilege(1);
        }

        $this->layout->set_wrapper('grocery', $data, 'page', false);
        $this->layout->auth();

        $template_data['grocery_css'] = $data['css_files'];
        $template_data['grocery_js'] = $data['js_files'];

		$template_data["title"] = "User Manajemen";
		$template_data["crumb"] = ["manajemen" => "#","User Manajemen" => "manajemen/pengguna",];

        $this->layout->setCacheAssets();

        $this->layout->render('admin', $template_data);
	}
	
	//KUMPULAN CALLBACK
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

        return "<a href='".site_url('manajemen/users/manual_activate/'.$row->id.'/'.$value)."'>$val</a>";
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

        redirect('manajemen/Users');
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

/* End of file Users.php */
/* Location: ./application/modules/referensi/manajemen/Users.php */