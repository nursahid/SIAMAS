<?php defined('BASEPATH') or exit('No direct script access allowed');

class Absensi extends Members_Controller {

    public function __construct()
    {
        parent::__construct();
		$this->load->model(array('absensi_model','members_model')); 
    }
	
    public function index() {
        // get current user id
        $id  = $this->auth->userid(); 
		//get data siswa
		$rows = $this->members_model->get_by('id',$id);
		//$nis = $this->session->userdata('nis');
		$nis = $rows->nis;
		$tapel 		= $this->absensi_model->get_tahun_ajaran();
		$semester 	= $this->absensi_model->get_semester();
        //$data['absensi'] = $this->db->query('SELECT * FROM absensi WHERE nis="'.$nis.'"')->result();
		$data['absensi'] = $this->absensi_model->get_absensi($nis);
		$data['absensi_mapel'] = $this->absensi_model->get_absensi_mapel($nis);
		$data['data'] 	 = $rows;

        $template_data['title'] = 'Data Absensi';
        $template_data['crumb'] = [
			'Absensi' => 'siswa/absensi',
        ];

        $this->layout->set_title($template_data['title']); // Set title page
        $this->layout->set_wrapper('absensi', $data); // Set partial view

        $this->layout->setCacheAssets(); // Cache assets

        $this->layout->render('members', $template_data); // layout in template
    }
	
}

/* End of file Absensi.php */
/* Location: ./application/members/controllers/Absensi.php */