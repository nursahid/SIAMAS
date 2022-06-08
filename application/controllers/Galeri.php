<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Galeri extends MY_Controller {

	private $title;
	private $front_template;
	
	public function __construct()
	{
		parent::__construct();
		$this->front_template = 'template/front_template'; ;
		$this->load->model('media_model', 'photos');
	}
	
	// Bootstrap Carousel
	public function index() {

        $data['title'] = 'Galeri Kegiatan';
        $data['photos'] = $this->photos->get_all();
		
		$this->layout->set_title($data['title']);
        $this->layout->set_wrapper('frontpage/galery', $data);
        // CSS and JS plugins
        $template_data['css_plugins'] = [
            base_url('assets/plugins/blueimp-gallery/blueimp-gallery.css'),
			base_url('assets/plugins/blueimp-gallery/bootstrap-image-gallery.css')
        ];
        $template_data['js_plugins'] = [
			base_url('assets/plugins/blueimp-gallery/jquery.blueimp-gallery.js'),
            base_url('assets/plugins/blueimp-gallery/blueimp-gallery.js'),
			base_url('assets/plugins/blueimp-gallery/bootstrap-image-gallery.js')
        ];		
        $template_data['title'] = 'Galeri Kegiatan'; // Data send to template
        $template_data['crumb'] = ['Galeri' => 'galeri',];
		$this->layout->render('front', $template_data);
	}
	

}
