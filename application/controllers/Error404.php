<?php defined('BASEPATH') or exit('No direct script access allowed');

class Error404 extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }
	

    /**
     * Error 404.
     *
     * @return HTML
     **/
    public function index()
    {
        $this->output->set_status_header('404');
        $this->layout->set_wrapper('error_page/error_404');
        $this->layout->render();
    }	
}

?>