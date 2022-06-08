<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
class Daftar_alumni extends MY_Controller {
 
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
            'img_width' => 80,
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
		
		$data['page'] = 'contact';
        $data['title'] = 'Contact Form | TechArise';             
		$this->layout->set_wrapper('frontpage/daftar-alumni', $data);
		$template_data['title'] = 'Hubungi Kami';
		$template_data["crumb"] = ["Daftar" => "daftar-alumni",];
		$this->layout->render('front', $template_data);
    }
	public function validateForm() {
		
		$this->form_validation->set_error_delimiters('<i class="fa fa-times"></i> &nbsp;', '<br />');

		$this->form_validation->set_rules('nama', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('kelamin', 'Kelamin', 'required');
		$this->form_validation->set_rules('tempat_lahir', 'Tempat Kelahiran', 'required');
		$this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('angkatan', 'Tahun Lulus', 'required');
		$this->form_validation->set_rules('captcha', 'Kode Sekuriti', 'required|callback_captcha_check');

        if ($this->form_validation->run() == FALSE){
            $errors = validation_errors();
            echo json_encode(['error'=>$errors]);
        }else{
			//variabel
			$setting = $this->settings->get();
			
			$nis	= $_POST['nis'];
			$nama	= $_POST['nama'];
			$tempat_lahir= $_POST['tempat_lahir'];
			$tgl_lahir	= $_POST['thn_tanggal'].'-'.$_POST['bln_tanggal'].'-'.$_POST['tgl_tanggal'];
			$email		= $_POST['email'];
			$kelamin 	= $_POST['kelamin'];
			$alamat		= $_POST['alamat'];
			$telepon	= $_POST['phone'];
			$agama		= $_POST['agama'];
			$tgl_daftar = $_POST['thn_tgldaftar'].'-'.$_POST['bln_tgldaftar'].'-'.$_POST['tgl_tgldaftar'];
			$angkatan	= $_POST['angkatan'];
			$status		= 'Alumni';
			//$foto	= $_POST['file'];
			
			//insert ke database
			$data = array('nis'=> $nis, 'nama'=> $nama, 'tempat_lahir'=> $tempat_lahir, 'tgl_lahir'=> $tgl_lahir, 
						  'kelamin'=> $kelamin, 'agama'=> $agama, 'alamat'=> $alamat, 'email'=> $email, 
						  'tgl_daftar'=> $tgl_daftar, 'angkatan'=> $angkatan, 'hp_ortu'=> $telepon, 'status'=> $status, 
						  'foto'=> $foto);
			$this->db->insert('siswa',$data);
			//kirim pesan email
			//---------------
            $config = array (
                  'mailtype' => 'html',
                  'charset'  => 'utf-8',
                  'priority' => '1'
                );
            $message	= '';
            $bodyMsg 	= '<p style="font-size:14px;font-weight:normal;margin-bottom:10px;margin-top:0;">Ijinkan saya ['.$nama.'], sisa angkatan '.$angkatan.'... Mendaftar sebagai alumni</p>';   
            $delimeter 	= $nama;
            $dataMail 	= array('website_name'=>$setting->website_name,'topMsg'=>'Halo Team', 'bodyMsg'=>$bodyMsg, 'thanksMsg'=>'Best regards,', 'delimeter'=> $delimeter);
 
            $this->email->initialize($config);
            $this->email->from($email, $nama);
            $this->email->to($setting->email);
            $this->email->subject('PENDAFTARAN ALUMNI ANGKATAN '.$angkatan.'');
            $message = $this->load->view('auth/email/email_kontak', $dataMail, TRUE);
            $this->email->message($message);
            $this->email->send();
 
            // Email balasan
            $bodyMsg = '<p style="font-size:14px;font-weight:normal;margin-bottom:10px;margin-top:0;">Terima kasih telah melakukan pendaftaran alumni.</p>';                 
            $dataMail = array('website_name'=>$setting->website_name, 'topMsg'=>'Hi '.$nama, 'bodyMsg'=>$bodyMsg, 'thanksMsg'=>'Best regards,', 'delimeter'=> 'Team '.$setting->website_name.'');
 
            $this->email->initialize($config);
            $this->email->from($setting->email, $setting->website_name);
            $this->email->to($email);
            $this->email->subject('PENDAFTARAN ALUMNI Sdr. '.$nama.'');
            $message = $this->load->view('auth/email/email_kontak', $dataMail, TRUE);
            $this->email->message($message);
            $this->email->send(); 
			
		   //tampilkan pesan sukses
		   echo json_encode(['success'=>'<div style="color:#fff;"><i class="fa fa-check"></i> &nbsp;Pendaftaran Berhasil ...</div>']);
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
	
}