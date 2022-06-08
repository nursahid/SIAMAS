<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Admin Controller.
 */
class Admin extends MY_Controller {
    private $title;

    public function __construct() {
        parent::__construct();

        $this->title = "Admin";
    }

	public function index() {
        // user is already logged in
        if ($this->ion_auth->logged_in()) {
            redirect('admin/dashboard');
        } else {
            redirect('admin/login');
        }
	}

}

/* End of file Admin.php */
/* Location: ./application/modules/admin/controllers/Admin.php */