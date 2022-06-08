<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Logowebsite extends MY_Controller {
    
	private $title;

    public function __construct()
    {
        parent::__construct();
		$this->load->library(array('settings','form_validation'));
		$this->load->helper(array('form','url','rupiah'));
		$this->load->model(array('setting_model','kesiswaan/siswa_model'));

    }

	public function index() {
        //get setting
		$settings 	= $this->settings->get();
		//variabel
		$data['setting'] 	= $this->settings->get();
		//View
		$template_data['title'] = 'Seting ';
		$template_data['subtitle'] = 'Upload Logo Website';
        $template_data['crumb'] = ['Logo Website' => 'seting/logowebsite',];

        //$this->layout->setCacheAssets();		
		$this->layout->set_wrapper('logowebsite', $data);
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
	public function simpanlogo()
	{
		$text = str_replace('.', '', $this->input->post('namawebsite')); //buang spasi
		$nmfile = "logoweb_".strtolower($text);
		$config = array(
			'upload_path' => './assets/uploads/image/',
			'allowed_types' => 'png',
			'max_size' => '2048',
			'max_width' => '2000',
			'max_height' => '2000',
			'overwrite' => TRUE,
			'file_ext_tolower' => TRUE,
			'file_name' => $nmfile
 		);
		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('filefoto')) {
			$this->session->set_flashdata('message', "<div class='text-red'><i class='fa fa-times'></i> " . $this->upload->display_errors() . "</div>");
			redirect(site_url('seting/logowebsite'));
		} else {
			$file = $this->upload->data();
			$data = array('website_logo' => $file['file_name']);
			$this->setting_model->save($data);
		}
		$this->session->set_flashdata('message', "<div class='text-red'><i class='fa fa-check'></i> Gambar berhasil diunggah.</div>");
		redirect(site_url('seting/logowebsite'));
	}

	//upload logo kabupaten
	public function simpanfavicon() {
		$status = "";
		$msg 	= "";
		$text 	= str_replace('.', '', $this->input->post('namawebsite')); //buang spasi
		$nmfile = "favicon_".strtolower($text);
		$config = array(
					'upload_path' => "./assets/uploads/image/",
					'allowed_types' => "png",
					'file_ext_tolower' => TRUE,
					'overwrite' => TRUE,
					'max_size' => "2048", // Can be set to particular file size , here it is 2 MB(2048 Kb)
					'max_height' => "2000",
					'max_width' => "2000",
					'file_name' => $nmfile
				);
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if ( ! $this->upload->do_upload('image_file'))
		{
		  $pesan 	= '<div class="text-red"><i class="fa fa-times"></i> '.$this->upload->display_errors().'</div>';
		  echo json_encode(array('status' => 'error', 'pesan' => $pesan));
		}
		else
		{
			$data = $this->upload->data();
			$this->setting_model->save(array('favicon'=>$data['file_name']));
			
			$pesan 		= '<div class="text-red"><i class="fa fa-check"></i> Favicon berhasil diunggah!</div>';
			$filename	= $data['file_name'];
			$preview 	= '<img src="'.base_url().'assets/uploads/image/'.$data["file_name"].'" width="80" height="80" class="img-thumbnail" />';

			echo json_encode(array('status' => 'success', 'pesan' => $pesan, 'preview' => $preview));
		}
	}
	
	
	
}	//end controller