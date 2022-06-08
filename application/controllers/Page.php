<?php defined('BASEPATH') or exit('No direct script access allowed');

class Page extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('page_model');
	}

    public function index($slug)
    {

		$model = $this->page_model->get_by('slug',$slug);
		$data['page'] = $model;
            // Set meta tags
            if ($data['content']->title != '') {
                $this->layout->set_title($data['content']->title);
            }
            if ($data['content']->keyword != '') {
                $this->layout->set_meta_tags('keyword', $data['content']->keyword);
            }
            if ($data['content']->description != '') {
                $this->layout->set_meta_tags('description', $data['content']->description);
            }

            // Set schema
            $this->layout->set_schema('og:site_name', $this->title);
            $this->layout->set_schema('og:title', $data['content']->title);
            $image = $data['content']->featured_image != '' ? base_url('assets/uploads/thumbnail/'.$data['content']->featured_image) : base_url($this->logo);
            $this->layout->set_schema('og:image', $image);
            if ($data['content']->description != '') {
                $this->layout->set_schema('og:description', $data['content']->description);
            }		
        $this->layout->set_title($this->title . ' | ' . $model->title);
        $this->layout->set_wrapper('frontpage/page_view', $data);
        $this->layout->render();
    }

    function cetak($str){
        return strip_tags(htmlentities($str, ENT_QUOTES, 'UTF-8'));
    }
	
}

?>