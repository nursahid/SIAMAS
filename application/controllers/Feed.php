<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feed extends MY_Controller {

	private $title;
	private $front_template;
	
	public function __construct()
	{
		parent::__construct();
		$this->front_template = 'template/front_template'; ;
		$this->load->model('feed_model', 'feed');
		$this->load->helper('xml'); 
	}
	
	// Feed Index
	public function index() {
        $setting = $this->settings->get();
		$data['encoding'] 	= 'utf-8';
        $data['feed_name'] 	= $setting['website_name'];
        $data['feed_url'] 	= base_url();
        $data['page_description'] = $setting['website_description'];
        $data['page_language'] 	= 'en-ca';
        $data['creator_email'] 	= $setting['email'];
        
		$query = $this->feed->getFeedArticles();  
        $data['ARTICLE_DETAILS'] = null;
		
		if($query){
		   $data['ARTICLE_DETAILS'] =  $query;
		}  
        header("Content-Type: application/rss+xml");
        $this->load->view('rss', $data);
    }
	

}
