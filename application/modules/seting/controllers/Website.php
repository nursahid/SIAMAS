<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Website Controller.
 */
class Website extends MY_Controller
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

        $this->layout->set_title('Konfigurasi Website');
		
		$template_data['title'] = 'Seting dan Konfigurasi';
		$template_data['subtitle'] = 'Konfigurasi Website';
        $template_data['crumb'] = ['Seting Website' => 'seting/website',];
		
        $this->layout->set_wrapper('website', $data); 

        $this->layout->setCacheAssets();

        $this->layout->render('admin', $template_data);
    }
	
    public function simpan()
    {
		// Displaying Errors In Div
		$this->form_validation->set_error_delimiters('<div class="text-red"><i class="fa fa-times"></i> ', '</div>');
		
		$this->form_validation->set_rules('namawebsite', 'Nama Website', 'required');
		$this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required');
		$this->form_validation->set_rules('keywords', 'Keywords', 'required');
		$this->form_validation->set_rules('slogan', 'Slogan Website', 'required');

		//$this->form_validation->set_message('required', 'Wajib diisi');
		
		if($this->form_validation->run() == FALSE) {
			//error
			echo json_encode(array('status'=>0, 'pesan' => validation_errors()));
			
		} else {			
				$data = array(
						'website_name' 		=> $this->input->post('namawebsite',TRUE),
						'website_description'=> $this->input->post('deskripsi',TRUE),
						'website_keywords' 	=> $this->input->post('keywords',TRUE),
						'website_slogan' 	=> $this->input->post('slogan',TRUE),
				);
				$this->setting_model->save($data);				
				//sukses
				echo json_encode(array('status'=>1, 'pesan' => '<div class="text-red"><i class="fa fa-check"></i> Data Berhasil disimpan</div>'));

			
		}
    }


}

/* End of file Website.php */
/* Location: ./application/seting/controllers/Website.php */
