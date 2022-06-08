<?php defined("BASEPATH") or exit("No direct script access allowed");

require_once APPPATH.'libraries/traits/Social_login.php';
require_once APPPATH.'libraries/traits/DB_Manager.php';
/**
 * Login Controller.
 */
class Login extends MY_Controller {
    
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
        //$this->default_page 	= $route['default_page'];
        //$this->login_success 	= $route['login_success'];
		$this->default_page 	= 'admin/dashboard';
		$this->login_success 	= 'admin/dashboard';
	}
	
    public function index($identity = null, $password = null)
    {
        $data['features'] = $this->config->item('features');
        
        if ($this->ion_auth->logged_in()) {
            redirect(last_url());
        }

        $this->load->model('logs');
        
        if (!$data['features']['disable_all_social_logins']) {
            $this->social_login_init();

            $sociallogin = $this->social_login(); // Return Fb and google login urls array from main controller

            if ($data['features']['login_via_facebook']) {
                $data['login_url'] = $sociallogin[0]; // Login_url is used to get FB Login Url from main controller
            }
            if ($data['features']['login_via_google']) {
                  $data['googlelogin'] = $sociallogin[1]; // googlelogin is used to get Google Login Url from main controller
            }
            if ($data['features']['login_via_google']) {
                  $data['twitter'] = $sociallogin[2]; // twitterlogin is used to get twitter Login Url from main controller
            }
        }

        if ($this->input->post('identity') || $identity != null) {
            if ($identity == null) {
                $identity = $this->input->post('identity');
                $password = $this->input->post('password');
                $remember = (bool) $this->input->post('remember');
            } else {
                $remember = false;
            }

            if ($this->ion_auth->login($identity, $password, $remember)) {
                $dataLog = [
                    'status' => true,
                    'identity' => $identity,
                    'ip' => $this->input->ip_address()
                ];
                $this->logs->addLogs('login', $dataLog);

                redirect((last_url() == '') ? 'admin/dashboard' : last_url());
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                $dataLog = [
                    'status' => false,
                    'message' => str_replace('&times;Close', '', strip_tags($this->ion_auth->errors())),
                    'identity' => $identity,
                    'ip' => $this->input->ip_address()
                ];
                $this->logs->addLogs('login', $dataLog);

                redirect('admin/login');
            }
        } else {
            $data['name'] = $this->title;
            $data['logo'] = $this->logo;
            $data['message'] = $this->session->flashdata('message');
            $data['google_recaptcha'] = $this->config->item('google_recaptcha');
            $this->layout->set_wrapper('login', $data);
            
            $template_data['js_plugins'] = [base_url('assets/js/myigniter/login.js')];
            if ($data['features']['google_recaptcha']) {
                $template_data['js_plugins'][] = '//www.google.com/recaptcha/api.js';
            } else {
                $this->layout->setCacheAssets();
            }

            $this->layout->render('auth', $template_data); // auth_template
        }
    }
}

/* End of file Login.php */
/* Location: ./application/modules/admin/controllers/Login.php */