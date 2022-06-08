<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class Alumni extends MY_Controller {
 
    public function __construct() {
        parent::__construct();
        
		$this->load->model('alumni_model','alumni');
		$this->load->helper('captcha');
		$this->load->helper(array('form', 'url'));
		$this->load->helper('security');
        $this->load->library('form_validation');
        $this->load->library('email');
		$this->form_validation->CI =& $this;
    }

	public function index() {
		
		$total_rows = $this->alumni->more_alumni(0)->num_rows();
		$total_pages = ceil($total_rows / 20);
		
		$data['total_pages']= $total_pages;
		$data['query'] 		= $this->alumni->more_alumni(-1);
		
        //$data['datas'] = $model;
        //$data['pagination'] = $this->pagination->create_links();

        $this->layout->set_title('Daftar Alumni Sekolah');
        $this->layout->set_wrapper('frontpage/loop-alumni', $data);

        $this->layout->setCacheAssets();
		$template_data["title"] = "Daftar Alumni";
		$template_data["crumb"] = ["Alumni" => "alumni",];
        $this->layout->render('front', $template_data);
	}

	/**
	 * More Files
	 */
	public function more_alumni() {
		$page_number = (int) $this->input->post('page_number', true);
		$offset = ($page_number - 1) * 20;
		$response = [];
		$query = $this->alumni->more_alumni($offset);		
		$rows = [];
		foreach($query->result() as $row) {
			$photo = 'default.png';
			if ($row->photo && file_exists($_SERVER['DOCUMENT_ROOT'] . '/assets/uploads/image/'.$row->foto)) {
				$photo = $row->foto;
			}
			$rows[] = [
				'nis' => $row->nis,
				'nisn' => $row->nisn,
				'nama' => $row->nama,
				'kelamin' => $row->kelamin,
				'tempat_lahir' => $row->tempat_lahir,
				'tgl_lahir' => tgl_indo($row->tgl_lahir),
				'tgl_daftar' => $row->tgl_daftar,
				'angkatan' => $row->angkatan,
				'foto' => base_url('assets/uploads/image/'.$photo)
			];
		}
		$response['rows'] = $rows;
		$this->output->set_content_type('application/json', 'utf-8')->set_output(json_encode($response, JSON_PRETTY_PRINT))->_display();
		exit;
	}
	
	
}