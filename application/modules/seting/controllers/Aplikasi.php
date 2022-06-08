<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Aplikasi Controller.
 */
class Aplikasi extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

		$this->load->library(array('settings'));
		$this->load->model(array('setting_model'));
		$this->load->helper('file');
    }

    /**
     * Index
     */
    public function index()
    {
        $settings 	= $this->settings->get();
		$data['setting'] = $settings;

        $this->layout->set_title('Konfigurasi Sms Gateway');
		
		$template_data['title'] = 'Seting & Konfigurasi';
		$template_data['subtitle'] = 'Konfigurasi Aplikasi';
        $template_data['crumb'] = ['Seting Lembaga' => 'seting/lembaga', 'Seting Aplikasi' => 'seting/aplikasi',];
		
        $this->layout->set_wrapper('aplikasi', $data); 

        $this->layout->setCacheAssets();

        $this->layout->render('admin', $template_data);
    }
	
    public function simpan()
    {
		// Displaying Errors In Div
		$this->form_validation->set_error_delimiters('<div class="text-red"><i class="fa fa-times"></i> ', '</div>');
		
		$this->form_validation->set_rules('angkaawal_nis', 'Angka Awal NIS', 'required');
		$this->form_validation->set_rules('perpage', 'Angka Per Halaman', 'required');

		//$this->form_validation->set_message('required', 'Wajib diisi');
		
		if($this->form_validation->run() == FALSE) {
			//error
			echo json_encode(array('status'=>0, 'pesan' => validation_errors()));
			
		} else {
			//Update database 
			$data = array(
					'angkaawal_nis' => $this->input->post('angkaawal_nis',TRUE),
					'perpage' 	  	=> $this->input->post('perpage',TRUE),
			);
			$this->setting_model->save($data);
				
			//sukses
			echo json_encode(array('status'=>1, 'pesan' => '<div class="text-red"><i class="fa fa-check"></i> Data Berhasil disimpan</div>'));
		}
    }


}

/* End of file Aplikasi.php */
/* Location: ./application/seting/controllers/Aplikasi.php */
