<?php defined('BASEPATH') or exit('No direct script access allowed');

class Home extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('blog/blogModel');
        $this->load->model('blog/categoryModel');
		$this->load->model('kategori_model','kategori');
		$this->load->model('post_model','blogpost');
		$this->load->model('page_model','pages');
		$this->load->model('tags_model','tags');
		$this->load->model('sliders_model','sliders');
    }
	
    public function index($slug='')
    {
        last_url('set'); // save last url

        $this->db->where('slug', 'home');
        $data['content'] = $this->db->get('page')->row();

        if ($data['content']) {
            $template_data['title'] = $data['content']->title;
            if ($data['content']->breadcrumb != 'null') {
                $crumbs = json_decode($data['content']->breadcrumb);
                foreach ($crumbs as $value) {
                    $add_crumb[$value->label] = $value->link;
                }
            } else {
                $add_crumb['page'] = '';
            }
            $template_data['crumb'] = $add_crumb;

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

			$this->db->join('users', 'blog.id_user = users.id', 'left');
			$data['posts'] = $this->blogModel->search(null, $this->limit, 0);
            // View wrapper
            if ($data['content']->view == 'default') {
                $this->layout->set_wrapper('page', $data);
            } else {
                $this->layout->set_wrapper('page/'.$data['content']->view, $data);
            }
            $this->layout->setCacheAssets();
            $this->layout->render('front', $template_data);
        } else {
            show_404();
        }
    }	
}

?>