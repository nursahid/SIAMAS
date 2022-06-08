<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Pembelajaran Controller.
 */
class Pembelajaran extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

		//$this->load->model(array('siswa_model', 'siswakelas_model', 'siswamasuk_model', 'siswakeluar_model'));
    }

    /**
     * Index
     */
    public function index()
    {
		//view
		$this->layout->set_wrapper('index', $data);
		$template_data["title"] = "Pembelajaran";
		$template_data["subtitle"] = "Kelola Pembelajaran";
		$template_data["crumb"] = ["Pembelajaran" => "Pembelajaran",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); 
	}

	
}

/* End of file Pembelajaran.php */
/* Location: ./application/modules/siswa/controllers/Pembelajaran.php */