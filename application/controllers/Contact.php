<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends MY_Controller {

    public function index()
    {
        // load the BotDetect Captcha library and set its parameter
        $this->load->library('botdetect/BotDetectCaptcha', array(
            'captchaConfig' => 'ContactCaptcha'
        ));

        $this->load->helper(array('form', 'url'));
        $this->load->library(array('form_validation', 'email'));

        $this->form_validation->set_error_delimiters('<p class="validation_errors">', '</p>');

        $validationConfig = array(
            array(
                'field'   => 'Email', 
                'label'   => 'Email', 
                'rules'   => 'required|valid_email'
            ),
            array(
                'field'   => 'Message', 
                'label'   => 'Message', 
                'rules'   => 'required|min_length[6]'
            ),
            array( // Captcha code user input validation rules
                'field'   => 'CaptchaCode', 
                'label'   => 'Captcha code', 
                'rules'   => 'callback_captcha_validate'
            )
        );
        $this->form_validation->set_rules($validationConfig);

        $data['emailSent'] = false;

        if ($_POST) {
            // run form validation when the form is submitted
            if ($this->form_validation->run() == true) {
                // the form validation (including Captcha validation) passed, send email;
                // we'll include some code showing how to send mail from CodeIgniter, but
                // please note that this code is not production safe, and is simplified 
                // for the purposes of this sample
                $from = $this->input->post('email');
                $message = $this->input->post('message');

                $this->email->from($from);
                $this->email->to('TODO: email address of recipient'); 
                $this->email->subject('TODO: subject');
                $this->email->message($message);  
                // TODO: uncomment only after you have configured your email settings
                //$this->email->send();

                // reset Captcha status after each email sent, since we don't want the user to
                // be able to send an unlimited number of emails after solving the Captcha once
                $this->botdetectcaptcha->Reset(); 

                $data['emailSent'] = true;
            } else {
                // the form validation failed, don't send email
            }
        }

        // the Captcha is not shown if the user already solved it but validation of
        // other form fields failed
        if (!$this->botdetectcaptcha->IsSolved) {
            $data['captchaSolved'] = false;
            $data['captchaHtml'] = $this->botdetectcaptcha->Html();
        } else {
            $data['captchaSolved'] = true;
            $data['captchaHtml'] = '';
        }

        $this->load->view('botdetect/contact', $data);
    }

    // Captcha validation callback used in form validation
    public function captcha_validate($code)
    {
        // user considered human if they previously solved the Captcha...
        $isHuman = $this->botdetectcaptcha->IsSolved;
        if (!$isHuman) {
            // ...or if they solved the current Captcha
            $isHuman = $this->botdetectcaptcha->Validate($code);
        }

        // set error if Captcha validation failed
        if (!$isHuman) {
            $this->form_validation->set_message('captcha_validate', 'Please retype the characters from the image correctly.');
        }

        return $isHuman;
    }
}
