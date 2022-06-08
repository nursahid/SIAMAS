<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Profile Controller.
 */
class Profile extends MY_Controller {
    
	private $title;

    public function __construct() {
        parent::__construct();

        $this->title = "Profile";
    }

	public function index() {
        last_url('set'); // save last url
        $this->layout->set_wrapper('profile');
        $this->layout->auth();

        $template_data['title'] = 'User Profile';
        $template_data['crumb'] = [
        'User Profile' => '',
        ];
        $this->layout->render('admin', $template_data);

	}

}

/* End of file Profile.php */
/* Location: ./application/modules/admin/controllers/Profile.php */