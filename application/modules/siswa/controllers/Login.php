<?php defined('BASEPATH') or exit('No direct script access allowed');

class Login extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('auth');  
		$this->load->model(array('login_model','members_model'));
	}
    
    public function index($username = null, $password = null) {
              
        // user is already logged in
        if ($this->auth->loggedin()) {
            redirect('siswa/home');
        }
        if($username == null && $password == null) {
			$username = $this->input->post('username');
			$password = $this->input->post('password');
		} else {
			$username = $this->uri->segment(3);
			$password = $this->uri->segment(4);
		}
        // form submitted
        if ($username && $password) {
            $remember = $this->input->post('remember') ? TRUE : FALSE;
            
            // get user from database
            //$user = $this->members_model->get('username', $this->input->post('username'));
			$user = $this->members_model->get_it('username', $username);
            
            if ($user) {
                // compare passwords
                if ($this->members_model->check_password($password, $user['password'])) {
                    // mark user as logged in
                    $this->auth->login($user['id'], $remember);
					//update last_login = time()
					$this->members_model->update_by(array('id'=>$user['id']),array('last_login'=>time()));
                    redirect('siswa/home');
                } else {
                    $error = 'Wrong password';
                }
            } else {
                $error = 'Siswa belum ada';
            }
        }
        
        // show login form
        $this->load->view('login_old', array('error' => $error));
        //load the view
        $template_data['title'] = 'Login Siswa';
		$template_data['error'] = $error;
		
        $this->layout->set_title($template_data['title']); // Set title page
        $this->layout->set_wrapper('login', $data); // Set partial view

        $this->layout->setCacheAssets();
		//$this->layout->render('blank', $template_data);
    }
	

}
/* End of file login.php */
/* Location: ./application/siswa/controllers/login.php */
