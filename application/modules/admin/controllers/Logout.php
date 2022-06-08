<?php defined("BASEPATH") or exit("No direct script access allowed");

require_once APPPATH.'libraries/traits/Social_login.php';
require_once APPPATH.'libraries/traits/DB_Manager.php';
/**
 * Logout Controller.
 */
class Logout extends MY_Controller {
    
    use Social_login,DB_Manager;
    // Site
    private $title;
    private $logo;

    // Template
    private $admin_template;
    private $front_template;
    private $auth_template;
	
	private $member_template;

    // Auth view
    private $login_view;
    private $register_view;
    private $forgot_password_view;
    private $reset_password_view;

    // Default page
    private $default_page;
    private $login_success;
	
    public function __construct() {
        parent::__construct();
        // Site
        $site = $this->config->item('site');
        $this->title = $site['title'];
        $this->logo = $site['logo'];

        // Template
        $template = $this->config->item('template');
        $this->admin_template = $template['backend_template'];
        $this->front_template = $template['front_template'];
        $this->auth_template = $template['auth_template'];
		
		$this->member_template = $template['member_template'];

        // Auth view
        $view = $this->config->item('view');
        $this->login_view = $view['login'];
        $this->register_view = $view['register'];
        $this->forgot_password_view = $view['forgot_password'];
        $this->reset_password_view = $view['reset_password'];

        // Default page
        $route = $this->config->item('route');
        $this->default_page = $route['default_page'];
        //$this->login_success = $route['login_success'];
		$this->login_success = 'admin/dashboard';
	}
	
    public function index()
    {
        $this->load->model('logs');
        $this->load->helper('utility_helper');

        $user = $this->ion_auth->user()->row();
        $dataLog = [
            'status' => true,
            'id' => $user->id,
            'identity' => $user->email,
            'ip' => $this->input->ip_address()
        ];
        $this->logs->addLogs('logout', $dataLog);

        $logout = $this->ion_auth->logout();
        $this->session->unset_userdata([
            'identity',
            'username',
            'old_last_login',
            'last_url',
            'fb_978900922179232_code',
            'fb_978900922179232_access_token',
            'google_access_token',
            'token',
            'token_secret'
        ]);

        redirect('admin/login');
    }
}

/* End of file Logout.php */
/* Location: ./application/modules/admin/controllers/Logout.php */