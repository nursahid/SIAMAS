<?php (defined('BASEPATH')) or exit('No direct script access allowed');

/**
 * Description of base_controller
 *
 * @author rogier
 */
class MY_Controller extends MX_Controller
{
    /**
     * Contructor, used to reference to the parents constructor.
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->config->load('myigniter');
        $this->load->library('layout');
        $this->load->library('ion_auth');
        $this->load->library('auth');
        $this->load->helper('utility');
		
		$this->load->model(array('user_model','kontak_model'));

        $this->layout->sess_language();
		
    }
	
	/**
	 * Insert image in tinyMCE Editor
	 */
	public function tinymce_upload_handler() {
		$config['upload_path'] = './assets/uploads/media/';
		$config['allowed_types'] = 'jpg|png|jpeg';
		$config['max_size'] = 0;
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload('file')) {
			$this->output->set_header('HTTP/1.0 500 Server Error');
			exit;
		} else {
			$file = $this->upload->data();
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode(['location' => base_url().'assets/uploads/media/'.$file['file_name']]))
				->_display();
			exit;
		}
	}
}

//Admin Controller
class Admin_Controller extends MY_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model('user_model');
		$this->userdata	= $this->ion_auth->user()->row();
		//if (!$this->ion_auth->logged_in()){
		//if(!$this->ion_auth->is_admin()) {
		if(!$this->ion_auth->in_group(array('superadmin', 'admin'))) {
			$this->session->set_userdata('redirect', $this->uri->uri_string());
			redirect('admin/login');			
		}
    }
	//update profil
	public function updateProfil() {
		if ($this->userdata != '') {
			$data = $this->user_model->get($this->userdata->id);

			$this->session->set_userdata('userdata', $data);
			$this->userdata = $this->session->userdata('userdata');
		}	
	}
}



//Members Controller
class Members_Controller extends MY_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model(array('siswa/members_model', 'siswa_model'));
		$this->userdata	= $this->members_model->get($this->auth->userid());
        if (!$this->auth->loggedin()) {
            $this->session->set_userdata('redirect', $this->uri->uri_string());
			redirect('siswa/login');
        }
    }
	//update profil
	public function updateProfil() {
		if ($this->userdata != '') {
			$data = $this->siswa_model->get($this->userdata->id);

			$this->session->set_userdata('userdata', $data);
			$this->userdata = $this->session->userdata('userdata');
		}	
	}
}