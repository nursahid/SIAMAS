<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Seting Controller.
 */
class Seting extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = 'Seting Program'; // Set title page
		$this->load->library(array('settings'));
		$this->load->model(array('setting_model'));
    }

    /**
     * Programs
     */
    public function program()
    {
        $settings 	= $this->settings->get();
		$data['book'] = $this->bookModel->search(); // Search model
        $data['helloWorld'] = 'Hello World'; // Data send to wrapper

        $this->layout->set_title($this->title); // Set title page
        $this->layout->set_wrapper('hello_world', $data); // Set partial view

        $this->layout->setCacheAssets(); // Cache assets

        $this->layout->render('admin', $template_data); // layout in template
    }


}

/* End of file example.php */
/* Location: ./application/example/controllers/example.php */
