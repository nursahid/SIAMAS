<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Dashboard Controller.
 */
class Dashboard extends MY_Controller {
    private $title;

    public function __construct() {
        parent::__construct();
        // models
        $this->load->model(array('users_model', 'pegawai/pegawai_model', 'kesiswaan/siswa_model'));
        // helpers
        $this->load->helper('utility_helper');
    }

	public function index() {
        last_url('set'); // save last url

        // database date
        $data['database'] = $this->db->database;
        
        // users data
        $data['users'] 			= $this->users_model->get_newest(8);
        $data['total_users'] 	= count($data['users']);
		$data['userdata']		= $this->ion_auth->user()->row();
		$data['total_siswa_aktif'] = $this->siswa_model->count_by('status','Aktif');
		$data['total_siswa_alumni'] = $this->siswa_model->count_by('status','Alumni');
		$data['total_siswa_mutasi'] = $this->siswa_model->count_by('status','Mutasi');

        // layout view
        $this->layout->set_wrapper('dashboard', $data);

        $this->layout->auth();

        $template_data['title'] = 'Dashboard';
        $template_data['crumb'] = [
            'Dashboard' => '',
        ];

        //$this->layout->setCacheAssets();

        $this->layout->render('admin', $template_data);
	}

}

/* End of file Dashboard.php */
/* Location: ./application/modules/admin/controllers/Dashboard.php */