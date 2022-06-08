<?php defined('BASEPATH') or exit('No direct script access allowed');

class Pembayaran extends Members_Controller {

    public function __construct()
    {
        parent::__construct();
		$this->load->model(array('bayar_model','members_model')); 
    }
	
    public function index() {
        // get current user id
        $id  = $this->auth->userid(); 
		$nis = $this->session->userdata('nis');
		$tapel 		= $this->bayar_model->get_tahun_ajaran();
		$semester 	= $this->bayar_model->get_semester();
		$data['pembayarans'] = $this->bayar_model->get_pembayaran($id);
		$data['jml_pembayaran']	= $this->bayar_model->total_pembayaran($id);

        $template_data['title'] = 'Data Pembayaran';
        $template_data['crumb'] = [
			'Absensi' => 'siswa/pembayaran',
        ];

        $this->layout->set_title($template_data['title']); // Set title page
        $this->layout->set_wrapper('pembayaran', $data); // Set partial view

        $this->layout->setCacheAssets(); // Cache assets

        $this->layout->render('members', $template_data); // layout in template
    }
	
}

/* End of file Absensi.php */
/* Location: ./application/members/controllers/Absensi.php */