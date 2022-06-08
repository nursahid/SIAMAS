<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Uploadlogo extends MY_Controller {
    
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
		// get current user id
        //$user = $this->siswa_model->get_by('id', $this->auth->userid());
		//variabel
		$data['setting'] 	= $this->settings->get();
		//$data['siswa'] 		= $user;	
		//View
		$template_data['title'] 	= 'Seting & Konfigurasi ';
		$template_data['subtitle'] 	= 'Upload Logo';
        $template_data['crumb'] 	= ['Seting Lembaga' => 'seting/lembaga', 'Upload Logo' => 'seting/uploadlogo',];

        //$this->layout->setCacheAssets();		
		$this->layout->set_wrapper('upload_logo', $data);
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
	public function store()
	{
		$text = str_replace(' ', '', $this->input->post('nama_file')); //buang spasi
		$nmfile = "logo_".strtolower($text);
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
			$this->session->set_flashdata('message', "<div style='color:#ff0000;'>" . $this->upload->display_errors() . "</div>");
			redirect(site_url('seting/uploadlogo'));
		} else {
			$file = $this->upload->data();
			$data = array(
					//'nama_file' => $this->input->post('nama_file'),
					'logosekolah' => $file['file_name']
			);

			//$this->uploadimage->insert($data);
			$this->setting_model->save($data);
		}
		$this->session->set_flashdata('message', "<div style='color:#00a65a;'>Gambar berhasil diunggah.</div>");
		redirect(site_url('seting/uploadlogo'));
	}
	//upload logo sekolah
	public function logosekolah() {
		//$this->load->library('upload');
		$text = str_replace(' ', '', $this->input->post('namasekolah')); //buang spasi
		$nmfile = "logo_".strtolower($text);
		$config = array(
					'upload_path' => "./assets/uploads/image/",
					'allowed_types' => "png",
					'file_ext_tolower' => TRUE,
					'overwrite' => TRUE,
					'max_size' => "2048", // Can be set to particular file size , here it is 2 MB(2048 Kb)
					'max_height' => "768",
					'max_width' => "1024",
					'file_name' => $nmfile
				);
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if ( ! $this->upload->do_upload('userfile'))
		{
		  $status 	= "error";
		  $msg 		= $this->session->set_flashdata('message', notify($this->upload->display_errors(),'error'));
		}
		else
		{
		  $data 	= $this->upload->data();
		  $status 	= "success";
		  $msg		= 'Your file was successfully uploaded!: ';
		  $this->setting_model->save(array('logosekolah'=>$data['file_name']));
		}
		echo json_encode(array('status' => $status, 'msg' => $msg));
	}
	//upload logo kabupaten
	public function logokabupaten() {
		$status = "";
		$msg 	= "";
		$text 	= str_replace(' ', '', $this->input->post('kabupaten')); //buang spasi
		$nmfile = "logo_".strtolower($text);
		$config = array(
					'upload_path' => "./assets/uploads/image/",
					'allowed_types' => "png",
					'file_ext_tolower' => TRUE,
					'overwrite' => TRUE,
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
		  $status 	= 'error';
		  $msg 		= $this->session->set_flashdata('message2', $this->upload->display_errors());
		}
		else
		{
		  $data = $this->upload->data();
		  $this->setting_model->save(array('logokabupaten'=>$data['file_name']));
		  $status 	= "success";
		  $msg 		= 'Logo Kabupaten berhasil diunggah!: ';
		  $filename	= $data['file_name'];
		  $preview 	= '<img src="'.base_url().'assets/uploads/image/'.$data["file_name"].'" width="300" height="225" class="img-thumbnail" />';

		}
		echo json_encode(array('status' => $status, 'msg' => $msg, 'preview' => $preview));
	}
	
	
	
}	//end controller