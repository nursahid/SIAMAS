<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Page_builder Controller.
 */
class Page_builder extends Admin_Controller {
    private $title;

    public function __construct() {
        parent::__construct();
        // models
        $this->load->model(array('users_model', 'kesiswaan/siswa_model'));
        // helpers
        $this->load->helper('utility_helper');
    }

	public function index() {
        last_url('set'); // save last url
        $crud = new grocery_CRUD();
		
        $crud->set_table('page');
        $crud->set_subject('Halaman Statis');
		
		$crud->columns('title', 'content', 'featured_image', 'slug');
		
		$crud->add_fields(["title", "featured_image", "slug", "content", "keyword", "description"]);
		$crud->edit_fields(["title", "featured_image", "slug", "content", "keyword", "description"]);
		
        $crud->required_fields('title', 'slug', 'content');
        //display
		$crud->display_as('slug', 'Slug');
 		$crud->display_as('title', 'Judul Halaman');
		$crud->display_as('featured_image', 'Gambar Pendukung');
		$crud->display_as('content', 'Isi Halaman');
		$crud->display_as('keyword', 'Kata Kunci');
		$crud->display_as('description', 'Deskripsi');
		//field type
		$crud->field_type("content", "text");
		$crud->set_field_upload('featured_image', 'assets/uploads/thumbnail');
		//require
		$crud->required_fields('title', 'slug', 'content');
		//set validation
		$crud->set_rules("title", "Judul", "required");
		$crud->set_rules("slug", "Slug", "required");
		$crud->set_rules("content", "Isi Halaman", "required");
		
		$state = $crud->getState();
		if ($state==='add')
		{
			//slug
			$crud->set_js("assets/plugins/jquery.slugify/speakingurl.js");
			$crud->set_js("assets/plugins/jquery.slugify/slugify.min.js");
			$crud->set_js("assets/js/add_page.js");
		}
		else
		{
			//slug
			$crud->set_js("assets/plugins/jquery.slugify/speakingurl.js");
			$crud->set_js("assets/plugins/jquery.slugify/slugify.min.js");
			$crud->set_js("assets/js/add_page.js");
		}		
		//callback
        //$crud->callback_before_insert(array($this, 'slug_page_check'));
        //$crud->callback_before_update(array($this, 'slug_page_check'));
        $crud->callback_column('slug', array($this, 'slug_page_link'));
        $crud->callback_after_upload(array($this, 'featured_upload'));
		
		//add action
		$crud->add_action(' ', 'fa fa-eye', '', '', array($this, 'link_view_page'));
		
		//unset
		//$crud->unset_texteditor('content', 'full_text');
		$crud->unset_texteditor('description', 'full_text');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_read();

        // layout view
		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);
        // CSS and JS plugins
        $template_data['css_plugins'] = [
            base_url('assets/plugins/highlightjs/styles/tomorrow-night-eighties.css')
        ];
        $template_data['js_plugins'] = [
            base_url('assets/plugins/highlightjs/highlight.pack.js'),
            base_url('assets/plugins/clipboard/dist/clipboard.min.js'),
            base_url('assets/js/page_builder.js')
        ];
		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Page Builder";
		$template_data["crumb"] = ["Page" => "admin/page"];
		
		$this->layout->render('admin', $template_data);
	}
	
	//=======================
	//    ALL CALLBACK      =
	//=======================
	
    /**
     * Featured image upload compress.
     *
     * @return Image
     **/
    public function featured_upload($uploader_response, $field_info, $files_to_upload)
    {
        $this->load->library('image_moo');
        $file_uploaded = $field_info->upload_path.'/'.$uploader_response[0]->name;

        $this->image_moo->load($file_uploaded)->resize_crop(350, 200)->save($file_uploaded, true);

        return true;
    }
    /**
     * Link view generated page.
     *
     * @return string
     **/
    public function link_view_page($primary_key, $row)
    {
        $this->db->where('id_page', $primary_key);
        $get_slug = $this->db->get('page')->row()->slug;

        return site_url('page').'/'.$get_slug;
    }	
    public function slug_page_check($post_array, $primary_key)
    {
        $slug = $post_array['slug'];
        $lower = strtolower($slug);
        $slug = str_replace(' ', '-', $lower);

        $this->db->where('slug', $slug);
        $get = $this->db->get('page');
        if ($get->num_rows() != 0) {
            if ($get->row()->id_page != $primary_key) {
                $slug = $slug.$get->num_rows();
            }
        }
        $post_array['slug'] = $slug;

        // Option breadcrumb link
        foreach ($post_array['label'] as $key => $value) {
            if ($value != '') {
                $link = $post_array['link'][$key] != '' ? $post_array['link'][$key] : '';
                $crumbArray[] = ['label' => $value, 'link' => $link];
            }
        }
        $post_array['breadcrumb'] = json_encode($crumbArray);

        return $post_array;
    }

    /**
     * Link slug use in menu.
     *
     * @return string
     **/
    public function slug_page_link($value, $row)
    {
        return $this->template_link('page/'.$value);
    }

    /**
     * Template link.
     *
     * @return HTML
     **/
    public function template_link($link)
    {
        return '<div class="link"><a class="copy-link"><i class="fa fa-copy"></i></a> <input type="text" class="form-link" value="'.$link.'" ></div>';
    }

}

/* End of file Page_builder.php */
/* Location: ./application/modules/admin/controllers/Page_builder.php */