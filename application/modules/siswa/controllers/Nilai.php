<?php defined('BASEPATH') or exit('No direct script access allowed');

class Nilai extends Members_Controller {
    private $title;
    private $front_template;
    private $admin_template;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('members_model')); // Load model
    }
	
    public function index() {
        // get current user id
        $id = $this->auth->userid();
		//tabel nilai_penilaian JOIN dengan seting_mapel
        $data['nilai'] = $this->members_model->get_penilaian($id);

        $template_data['title'] = 'Data Nilai';
        $template_data['crumb'] = [
			'Nilai' => 'siswa/nilai',
        ];

        $this->layout->set_title($template_data['title']); // Set title page
        $this->layout->set_wrapper('nilai', $data); // Set partial view

        $this->layout->setCacheAssets(); // Cache assets

        $this->layout->render('members', $template_data); // layout in template
    }
	
}

/* End of file Profile.php */
/* Location: ./application/members/controllers/Profile.php */