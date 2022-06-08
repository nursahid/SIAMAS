<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lembaga extends MY_Controller {
    
	private $title;

    public function __construct()
    {
        parent::__construct();
		$this->load->library(array('settings','form_validation'));
		$this->load->helper(array('form','url','rupiah'));
		$this->load->model(array('setting_model','kesiswaan/siswa_model'));

        $this->title = "Mata Pelajaran";
    }

	public function index() {
        //get setting
		$settings 	= $this->settings->get();
		// get current user id
        //$user = $this->siswa_model->get_by('id', $this->auth->userid());
		//variabel
		$data['setting'] 	= $settings;
		//$data['siswa'] 		= $user;	
		//View
		$template_data['title'] = 'Seting & Konfigurasi';
		$template_data['subtitle'] = 'Konfigurasi Sekolah';
        $template_data['crumb'] = ['Seting Lembaga' => 'seting/lembaga',];

        //$this->layout->setCacheAssets();		
		$this->layout->set_wrapper('data_lembaga', $data);
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}

	public function update() {
		// Displaying Errors In Div
		$this->form_validation->set_error_delimiters('<div class="text-red"><i class="fa fa-times"></i> &nbsp;', '</div>');
		
		$this->form_validation->set_rules('namasekolah', 'Nama Sekolah', 'required');
		$this->form_validation->set_rules('alamat', 'Alamat', 'required');
		$this->form_validation->set_rules('telepon', 'Telepon', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_rules('namakepsek', 'Kepala Sekolah', 'required');
		//$this->form_validation->set_rules('npsn', 'NPSN', 'required');
		//$this->form_validation->set_rules('nss', 'NSS', 'required');
		//$this->form_validation->set_rules('kodepos', 'Kode Pos', 'required');

		//$this->form_validation->set_message('required', 'Wajib diisi');
		
		if($this->form_validation->run() == FALSE) {
			//error
			echo json_encode(array('status'=>0, 'pesan' => validation_errors()));
			
		} else {
			//Update database 
			$data = array(
					'namasekolah' => $this->input->post('namasekolah',TRUE),
					'alamat' => $this->input->post('alamat',TRUE),
					'kelurahan' => $this->input->post('kelurahan',TRUE),
					'kecamatan' => $this->input->post('kecamatan',TRUE),
					'kabupaten' => $this->input->post('kabupaten',TRUE),
					'provinsi' => $this->input->post('provinsi',TRUE),
					'telepon' => $this->input->post('telepon',TRUE),
					'email' => $this->input->post('email',TRUE),
					'namakepsek' => $this->input->post('namakepsek',TRUE),
					'nipkepsek' => $this->input->post('nipkepsek',TRUE),
					'npsn' => $this->input->post('npsn',TRUE),
					'nss' => $this->input->post('nss',TRUE),
					'kodepos' => $this->input->post('kodepos',TRUE),
					'website_name' => $this->input->post('website_name',TRUE),
			);
			$this->setting_model->save($data);
				
			//sukses
			echo json_encode(array('status'=>1, 'pesan' => '<i class="fa fa-check"></i>  Data Berhasil disimpan.</div>'));
		}
	}

}	//end controller