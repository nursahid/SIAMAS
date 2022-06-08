<?php

defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Files Controller.
 */
class Aws3 extends MY_Controller
{
    private $title;
    private $front_template;
    private $admin_template;
    private $custom_errors = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->library("form_validation");

        $this->title = "aws3";
        $this->front_template = 'template/front_template';
        $this->admin_template = 'template/admin_template';

         //Write this to your Controller constructor
        $this->load->helper(['security', 'form', 'url', 'file', 'cis3integration_helper']);

        //optinally pass config array as per need.
        $config = [
            'bucket_name' => 'kotacdev',
            'region' => 'us-east-1',
            'scheme' => 'http'
        ];
        $this->load->library('cis3integration_lib', $config);
        $this->load->model('cis3integration_model');
    }

    public function index()
    {
        redirect('aws3/demo');
    }

    /**
     * Demo 1, Simple file upload to S3 bucket
     *
     * @access public
     */
    public function demo()
    {
        $this->file_name = "";
        if (@$_FILES['file']['name'] != "") {
            $config['upload_path']   = 'uploads/';//Leave blank if want to upload at root of bucket
            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc|docs|zip';
            $config['remove_spaces'] = true;
            $config['max_size']      = '5120';//5MB
            
            //S3 integration library config
            $config['acl'] = 'public-read';
            $config['make_unique_filename'] = false;
            
            $this->_upload_file($config, 'file');
            //$this->form_validation->set_rules('file', 'file', 'callback_check_file[file]');
        } else {
            $this->form_validation->set_rules('file', 'File', 'required');
        }
        
        $this->form_validation->set_rules('user_name', 'user_name', 'max_length[255]|xss_clean');
        $this->form_validation->set_error_delimiters('<br /><span style="color:red">', '</span>');
        
        $data['files_result'] = $this->db->query("SELECT * from files WHERE type LIKE 'file' ORDER by id DESC LIMIT 10");
        
        if ($this->form_validation->run() == false) { // validation hasn't been passed
            $template_data['title'] = 'Amazon Web Service S3 API';
            $template_data['crumb'] = [
                'Amazon Web Service S3' => '',
            ];

            $this->layout->set_title('Aws3 API');
            $this->layout->set_wrapper('aws3/upload', $data);

            $this->layout->setCacheAssets();

            $this->layout->render('admin', $template_data);
        } else {
            $user_name = @$this->input->post('user_name') != "" ? @$this->input->post('user_name') : "Anonymous";
            $form_data = array(
                'file' => @$this->file_name,
                'user_name' => @$user_name,
                'type' => 'file'
            );
            // run insert model to write data to db
            if ($this->cis3integration_model->SaveForm($form_data) == true) { // the information has therefore been successfully saved in the db
                $this->session->set_flashdata('msg', 'File has been successfully uploaded, Check below table to ensure.');
                redirect('aws3/demo'); // or whatever logic you need
            } else {
                $this->session->set_flashdata('msg', 'An error occurred saving your information. Please try again later.');
                redirect('aws3/demo'); // or whatever logic you need
            }
        }
    }
    
    /**
     * Demo 2, Image upload to S3 bucket after resizing
     *
     * @access public
     */
    public function manual_upload_demo()
    {
        $this->file_name = "";
        if (@$_FILES['file']['name'] != "") {
            $config['upload_path']   = 'user_photos/';//Leave blank if want to upload at root of bucket
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['remove_spaces'] = true;
            $config['max_size']      = '5120';//5MB
            
            //S3 integration only config
            $config['acl'] = 'public-read';
            $config['make_unique_filename'] = false;
            
            $this->_upload_photo($config, 'file');
            $this->form_validation->set_rules('file', 'file', 'callback_check_file[file]');
        } else {
            $this->form_validation->set_rules('file', 'File', 'required');
        }
        $this->form_validation->set_rules('user_name', 'user_name', 'max_length[255]|xss_clean');
        $this->form_validation->set_error_delimiters('<br /><span style="color:red">', '</span>');

        $data['files_result'] = $this->db->query("SELECT * from files WHERE type LIKE 'photo' ORDER by id DESC LIMIT 10");
        
        if ($this->form_validation->run() == false) { // validation hasn't been passed
            $this->load->view('header');
            $this->load->view('upload_photo', $data);
            $this->load->view('footer');
        } else { // passed validation proceed to post success logic
            $user_name = @$this->input->post('user_name') != "" ? @$this->input->post('user_name') : "Anonymous";
            $form_data = array(
                'file' => @$this->file_name,
                'user_name' => @$user_name,
                'type' => 'photo'
                );
            
            // run insert model to write data to db
            if ($this->cis3integration_model->SaveForm($form_data) == true) { // the information has therefore been successfully saved in the db
                $this->session->set_flashdata('msg', 'File has been successfully uploaded, Checkout below table to ensure.');
                redirect('cis3integration/manual_upload_demo'); // or whatever logic you need
            } else {
                $this->session->set_flashdata('msg', 'An error occurred saving your information. Please try again later.');
                redirect('cis3integration'); // or whatever logic you need
            }
        }
    }
    
    /**
     * Delete File from S3 Bucket
     *
     * @access public
     */
    public function delete_file($file_id="")
    {
        if (!$file_id) {
            $this->session->set_flashdata('msg', 'Nothing to delete.');
            redirect('aws3');
        }

        if ($this->cis3integration_model->delete_file($file_id)==true) {
            $this->session->set_flashdata('msg', 'File successfully deleted from S3 Bucket.');
            redirect('aws3'); // or whatever logic you need
        } else {
            $this->session->set_flashdata('msg', 'Unable to delete file.');
            redirect('aws3'); // or whatever logic you need
        }
    }

    /**
     * Check file upload error occured or not, If occured then set the form field error.
     *
     * @access private
     */
    public function check_file($field, $field_value)
    {
        if (isset($this->custom_errors[$field_value])) {
            $this->form_validation->set_message('check_file', $this->custom_errors[$field_value]);
            unset($this->custom_errors[$field_value]);
            return false;
        }
        return true;
    }
    
    /**
     * File upload to S3 Bucket
     *
     * @access private
     */
    public function _upload_file($config, $field_name)
    {
        $this->load->library('upload');
        $this->load->library('aws3_upload');
        $this->aws3_upload->initialize($config);
        if (!$this->aws3_upload->do_upload_s3($field_name)) {
            $this->custom_errors[$field_name] = $this->aws3_upload->display_errors();
        } else {
            $upload_data      = $this->aws3_upload->data();
            $this->file_name = $upload_data['file_name'];
        }
    }
    
    /**
     * Upload Image to server, Resize that and then upload to S3 Bucket
     *
     * @access private
     */
    
    public function _upload_photo($config, $field_name)
    {
        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($field_name)) {
            $this->custom_errors[$field_name] = $this->upload->display_errors();
        } else {
            $upload_data = $this->upload->data();
            
            $uploaded_name = $upload_data['file_name'];
            $row_name = $upload_data['raw_name'];
            $ext = $upload_data['file_ext'];

            $config                   = array();
            $unique_name              = '_thumb' . rand(1, 9999);
            $config['image_library']  = 'gd2';
            $config['source_image']   = 'user_photos/' . $uploaded_name;
            $config['maintain_ratio'] = false;
            $config['overwrite']      = true;
            $config['width']          = 200;
            $config['height']         = 250;
            $config['new_image']      = $new_image = 'user_photos/' . $row_name . $unique_name . $ext;
            $this->load->library('image_lib', $config);
            $this->image_lib->initialize($config);
            if (!$this->image_lib->resize()) { //if file does not resize then use full size image
                    //Not resized so use existing file
                $photo_file_name = $uploaded_name;
            } else {
                //resized so use resized file
                $photo_file_name = $row_name . $unique_name . $ext;
                @unlink('user_photos/' . $uploaded_name);
            }
            //Now upload the resized file to s3 bukcet
            if (!$this->upload->do_upload_manually("user_photos/", $photo_file_name)) {
                //Optionally you can pass the S3 path as third parameter if you need to upload file at different location in S3 Bucket like $this->upload->do_upload_manually("user_photos/", "myfile.jpg","myphotos/")
            //In short: $this->upload->do_upload_manually(from location, file name, copy to this location in bukcet)
                $photo_file_name = "";
            } else {
                $data = array(
                    'upload_data' => $this->upload->data()
                    );
                $photo_file_name = $data['upload_data']['file_name'];
                $this->file_name = $data['upload_data']['file_name'];
            }

            //Most importasnt part reset image library
            $this->image_lib->clear();
            return ($photo_file_name);
        }
    }
    
    
    /**
     * Demo 3, Copy a file in S3 bucket,
     * You can also copy a file from one bucket to another bucket
     *
     * @access public
     * To run this demo Ensure that you already have myfile.jpg in your bucket, If you do not have this file then upload using S3 Console or using our upload demo.
    */
    public function copy_s3_file()
    {
        $flag = $this->cis3integration_lib->copy_s3_file("myfile.jpg", "copy_of_myfile.jpg");
        if ($flag) {
            $data = array("message"=>"File 'myfile.jpg' successfully copied as 'copy_of_myfile.jpg' in '".BUCKET_NAME."' Bucket<br>
                See here ".anchor(s3_site_url('copy_of_myfile.jpg'), "File"));
            $this->load->view("message", $data);
        } else {
            $data = array("message"=>"There is some error to copy the file 'myfile.jpg' as 'copy_of_myfile.jpg', Please try again after some time");
            $this->load->view("message", $data);
        }
    }
    
    /**
     * Demo 4, To create a bucket in your AWS accout
     *
     * @access public
     */
    public function create_bucket($bucket_name="")
    {
        $flag = $this->cis3integration_lib->create_bucket($bucket_name);
        if ($flag) {
            $data = array("message"=>"Bucket '$bucket_name' successfully created.<br/>
                To prevent the abusing of system only creation of '$bucket_name' is allowed in this demo and that bucket is already exist in my AWS account, Once you buy this script you can easily create as many buckets you want in your AWS account.");
            $this->load->view("message", $data);
        } else {
            $data = array("message"=>"To prevent the abusing of system only creation of '$bucket_name' is allowed in this demo and that bucket is already exist in my AWS account, Once you buy this script you can easily create as many buckets you want in your AWS account.");
            $this->load->view("message", $data);
        }
    }
}

/* Location: ./application/modules/files/controllers/Files.php */
