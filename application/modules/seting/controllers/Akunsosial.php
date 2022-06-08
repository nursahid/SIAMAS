<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Akunsosial Controller.
 */
class Akunsosial extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

		$this->load->library(array('settings'));
		$this->load->model(array('setting_model'));
		$this->load->helper(array('url','file'));
    }

    /**
     * Index
     */
    public function index()
    {
        $settings 	= $this->settings->get();
		$data['setting'] = $settings;

        $this->layout->set_title('Konfigurasi Akun Sosial');
		
		$template_data['title'] = 'Seting dan Konfigurasi';
		$template_data['subtitle'] = 'Konfigurasi Akun Sosial';
        $template_data['crumb'] = ['Akun Sosial' => 'seting/akunsosial',];
		
        $this->layout->set_wrapper('akunsosial', $data); 

        $this->layout->setCacheAssets();

        $this->layout->render('admin', $template_data);
    }
	
    public function simpan()
    {
		// Displaying Errors In Div
		$this->form_validation->set_error_delimiters('<div class="text-red"><i class="fa fa-times"></i> ', '</div>');
		
		$this->form_validation->set_rules('facebook', 'Facebook', 'required');
		$this->form_validation->set_rules('twitter', 'Twitter', 'required');
		$this->form_validation->set_rules('gplus', 'Google Plus', 'required');
		$this->form_validation->set_rules('instagram', 'Instagram', 'required');

		//$this->form_validation->set_message('required', 'Wajib diisi');
		
		if($this->form_validation->run() == FALSE) {
			//error
			echo json_encode(array('status'=>0, 'pesan' => validation_errors()));
			
		} else {			
				$data = array(
						'facebook' 	=> $this->input->post('facebook',TRUE),
						'twitter'	=> $this->input->post('twitter',TRUE),
						'gplus' 	=> $this->input->post('gplus',TRUE),
						'instagram' => $this->input->post('instagram',TRUE),
						'youtube' 	=> $this->input->post('youtube',TRUE),
				);
				$this->setting_model->save($data);				
				//sukses
				echo json_encode(array('status'=>1, 'pesan' => '<div class="text-red"><i class="fa fa-check"></i> Data Berhasil disimpan</div>'));

			
		}
    }


}

/* End of file Akunsosial.php */
/* Location: ./application/seting/controllers/Akunsosial.php */
