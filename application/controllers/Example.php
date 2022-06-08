<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Example extends CI_Controller {

    public function index()
    {
        $this->load->helper(array('form', 'url'));

        // load the BotDetect Captcha library and set its parameter
        $this->load->library('botdetect/BotDetectCaptcha', array(
            'captchaConfig' => 'ExampleCaptcha'
        ));

        // make Captcha Html accessible to View code
        $data['captchaHtml'] = $this->botdetectcaptcha->Html();

        // initially, the message shown to the visitor is empty
        $data['captchaValidationMessage'] = '';

        if ($_POST) {
            // validate the user-entered Captcha code when the form is submitted
            $code = $this->input->post('CaptchaCode');
            $isHuman = $this->botdetectcaptcha->Validate($code);

            if ($isHuman) {
                // Captcha validation passed
                $data['captchaValidationMessage'] = 'CAPTCHA validation passed, human visitor confirmed!';
                // TODO: continue with form processing, knowing the submission was made by a human
            } else {
                // Captcha validation failed, return an error message
                $data['captchaValidationMessage'] = 'CAPTCHA validation failed, please try again.';
            }
        }

        $this->load->view('botdetect/example', $data);
    }
}
