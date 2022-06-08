<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Smsgateway Controller.
 */
class Smsgateway extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Index
     */
    public function index()
    {
		//view
		$this->layout->set_wrapper('index', $data);
		$template_data["title"] = "SMS Gateway";
		$template_data["subtitle"] = "Manajemen SMS";
		$template_data["crumb"] = ["SMS Gateway" => "smsgateway",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); 
	}

}

/* End of file Smsgateway.php */
/* Location: ./application/modules/smsgateway/controllers/Smsgateway.php */