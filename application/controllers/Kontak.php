<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class Kontak extends MY_Controller {
 
    public function __construct() {
        parent::__construct();
        // load email lib
		$this->load->helper('captcha');
		$this->load->helper(array('form', 'url'));
		$this->load->helper('security');
        $this->load->library('form_validation');
        $this->load->library('email');
		$this->form_validation->CI =& $this;
    }
    // contact
    public function index() {
        $config = array(
            'img_url' => base_url() . 'captcha/',
            'img_path' => 'captcha/',
            'img_height' => 35,
            'word_length' => 6,
            'img_width' => 100,
            'font_size' => 50,
			'colors'  => array(
					'background' => array(255, 255, 255),
					'border' => array(255, 255, 255),
					'text' => array(0, 0, 0),
				)
				
        );
        $captcha = create_captcha($config);
        $this->session->unset_userdata('valuecaptchaCode');
        $this->session->set_userdata('valuecaptchaCode', $captcha['word']);
        $data['captchaImg'] = $captcha['image']; 
		//wrapper
		$this->layout->set_wrapper('frontpage/kontak', $data);
		$template_data['title'] = 'Hubungi Kami';
		$template_data["crumb"] = ["Kontak" => "kontak",];
		$this->layout->render('front', $template_data);
    }
	
	public function validateForm() {
		
		$this->form_validation->set_error_delimiters('<i class="fa fa-times"></i> &nbsp;', '<br />');

		$this->form_validation->set_rules('nama', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('subjek', 'Subjek', 'required');
		$this->form_validation->set_rules('pesan', 'Pesan', 'required');
		$this->form_validation->set_rules('captcha', 'Kode Sekuriti', 'required|callback_captcha_check');

        if ($this->form_validation->run() == FALSE){
            $errors = validation_errors();
            echo json_encode(['error'=>$errors]);
        }else{
			//variabel
			$setting = $this->settings->get();
			$nama	= $_POST['nama'];
			$email	= $_POST['email'];
			$subjek = $_POST['subjek'];
			$pesan	= $_POST['pesan'];
			
			//insert ke database
			$data = array('nama'=> $nama, 'email'=> $email, 'subjek'=> $subjek, 'pesan'=> $pesan, 'tanggal'=> date('Y-m-d'));
			$this->db->insert('kontak',$data);
			//kirim pesan email
			//---------------
            $config = array (
                  'mailtype' => 'html',
                  'charset'  => 'utf-8',
                  'priority' => '1'
                );
            $message	= '';
            $bodyMsg 	= '<p style="font-size:14px;font-weight:normal;margin-bottom:10px;margin-top:0;">'.$pesan.'</p>';   
            $delimeter 	= $nama;
            $dataMail 	= array('website_name'=>$setting->website_name,'topMsg'=>'Halo Team', 'bodyMsg'=>$bodyMsg, 'thanksMsg'=>'Best regards,', 'delimeter'=> $delimeter);
 
            $this->email->initialize($config);
            $this->email->from($email, $nama);
            $this->email->to($setting->email);
            $this->email->subject('KONTAK: '.$subjek.'');
            $message = $this->load->view('auth/email/email_kontak', $dataMail, TRUE);
            $this->email->message($message);
            $this->email->send();
 
            // Email balasan
            $bodyMsg = '<p style="font-size:14px;font-weight:normal;margin-bottom:10px;margin-top:0;">Thank you for contacting us.</p>';                 
            $dataMail = array('website_name'=>$setting->website_name, 'topMsg'=>'Hi '.$nama, 'bodyMsg'=>$bodyMsg, 'thanksMsg'=>'Best regards,', 'delimeter'=> 'Team '.$setting->website_name.'');
 
            $this->email->initialize($config);
            $this->email->from($setting->email, $setting->website_name);
            $this->email->to($email);
            $this->email->subject('BALASAN KONTAK');
            $message = $this->load->view('auth/email/email_kontak', $dataMail, TRUE);
            $this->email->message($message);
            $this->email->send(); 
			
		   //tampilkan pesan sukses
		   echo json_encode(['success'=>'<div style="color:#fff;"><i class="fa fa-check"></i> &nbsp;Pesan berhasil terkirim</div>']);
        }
    } 
    public function captcha_check($str) {
		//cek apakah sama dengan sesi captcha
		if ($str == $this->session->userdata('valuecaptchaCode')) {
            return TRUE;
        }
        else {
            $this->form_validation->set_message('captcha_check', 'Sekuriti Kode tidak cocok');
            return FALSE;
        }
    }
	//Refresh CaptCha
    public function refresh() {
        $config = array(
            'img_url' => base_url() . 'captcha/',
            'img_path' => 'captcha/',
            'img_height' => 35,
            'word_length' => 6,
            'img_width' => 100,
            'font_size' => 50,
			'colors'  => array(
					'background' => array(255, 255, 255),
					'border' => array(255, 255, 255),
					'text' => array(0, 0, 0),
					'grid' => array(255, 40, 40)
			)
        );
        $captcha = create_captcha($config);
        $this->session->unset_userdata('valuecaptchaCode');
        $this->session->set_userdata('valuecaptchaCode', $captcha['word']);
        echo $captcha['image'];
    }
	public function validate_captcha() { 
       if ($this->input->post('captcha') != $this->session->userdata['captcha']) { 
           $this->form_validation->set_message('validate_captcha', 'Captcha Code is wrong'); 
           return false; 
       } else { 
           return true; 
       } 
	}
	
}
?>