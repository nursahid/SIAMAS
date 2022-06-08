<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Page Controller.
 */
class Page extends MY_Controller
{
    // Site
    private $title;
    private $logo;

    // Template
    private $admin_template;
    private $front_template;
    private $auth_template;

    // Default page
    private $default_page;
    private $login_success;
	
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Grocery_CRUD');
        $this->title = "Page Builder";

        // Site
        $site = $this->config->item('site');
        $this->title = $site['title'];
        $this->logo = $site['logo'];

        // Template
        $template = $this->config->item('template');
        $this->admin_template = $template['backend_template'];
        $this->front_template = $template['front_template'];
        $this->auth_template = $template['auth_template'];

        // Default page
        $route = $this->config->item('route');
        $this->default_page = $route['default_page'];
        $this->login_success = $route['login_success'];		
		
    }

    /**
     * Index
     */
    public function index()
    {
        last_url('set'); // save last url
        $crud = new grocery_CRUD();

        $crud->set_table('page');
        $crud->set_subject('Page Builder');

        // Views Base myIgniter
        $dir = './application/views/page';
        $files = scandir($dir);
        $view_list['default'] = 'default';
        foreach ($files as $key => $value) {
            if ($key != 0 && $key != 1) {
                if (strpos($value, '.php')) {
                    $view_page = str_replace('.php', '', $value);
                    $view_list[$view_page] = $view_page;
                }
            }
        }

        $crud->field_type('view', 'dropdown', $view_list);
		$crud->field_type("content", "text");
		
        $crud->callback_field('breadcrumb', array($this, 'breadcrumb_callback'));
        //$crud->callback_field('template', array($this, 'template_page'));
		
		$crud->callback_before_insert(array($this, 'html_decode_callback'));
		$crud->callback_before_update(array($this, 'html_decode_callback'));
		
        $crud->callback_before_insert(array($this, 'slug_page_check'));
        $crud->callback_before_update(array($this, 'slug_page_check'));
        $crud->callback_column('slug', array($this, 'slug_page_link'));
        //$crud->callback_column('Generate Module', array($this, 'export_php_callback'));
        $crud->callback_after_upload(array($this, 'featured_upload'));

        $crud->set_field_upload('featured_image', 'assets/uploads/thumbnail');

		 // Misc
        //$crud->columns('title', 'template', 'Generate Module', 'slug');
		$crud->columns('title', 'content', 'featured_image', 'slug');
		
		$crud->add_fields(["title", "featured_image", "slug", "breadcrumb", "content", "keyword", "description"]);
		$crud->edit_fields(["title", "featured_image", "slug", "breadcrumb", "content", "keyword", "description"]);
		
        $crud->required_fields('title', 'slug', 'content');
        //display
		$crud->display_as('slug', 'Link');
 		$crud->display_as('title', 'Judul Halaman');
		$crud->display_as('featured_image', 'Gambar Pendukung');
		$crud->display_as('content', 'Isi Halaman');
		$crud->display_as('keyword', 'Kata Kunci');
		$crud->display_as('description', 'Deskripsi');
		
		//$crud->unset_texteditor('content', 'full_text');
		$crud->unset_texteditor('description', 'full_text');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_read();
        
		$crud->add_action('View', 'fa fa-eye', '', '', array($this, 'link_view_page'));

        //$crud->set_theme('flexigrid');
        $data = (array) $crud->render();

        $this->layout->set_privilege(1);
        $this->layout->set_wrapper('grocery', $data, 'page', false);
        //$this->layout->auth();

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
        $template_data['grocery_js'] = $data['js_files'];
        $template_data['title'] = 'Page Builder';
        $template_data['crumb'] = [
            'Page Builder' => '',
        ];

        if ($this->uri->segment(4) != 'add' && $this->uri->segment(4) != 'edit') {
            $this->layout->setCacheAssets();
        }

        $this->layout->render('admin', $template_data);
	}

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

    /**
     * Callback input radio Template Frontend or Backend.
     *
     * @return HTML
     **/
    public function template_page($value = '', $primary_key = null)
    {
        $checked['front'] = '';
        $checked['back'] = '';
        if ($value != '') {
            switch ($value) {
                case 'frontend':
                    $checked['front'] = 'checked="checked"';
                    break;
                case 'backend':
                    $checked['back'] = 'checked="checked"';
                    break;
            }
        } else {
            $checked['front'] = 'checked="checked"';
        }
        $front = '<label><input type="radio" name="template" value="frontend" '.$checked['front'].' class="check"> Frontend</label>';
        $back = '<label><input type="radio" name="template" value="backend" '.$checked['back'].' class="check"> Backend</label>';

        return '<div class="radio">'.$front.$back.'</div>';
    }

    /**
     * Callback Slug if exist.
     *
     * @return array
     **/
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
	
	//==================================
	//CALLBACKS
	//==================================
    /**
     * Return form.
     *
     * @return HTML
     **/
    public function breadcrumb_callback($value = '', $primary_key = null)
    {
        $crumbContent = '<div class="breadcrumb-content">';
        $listCrumb = '';
        $endCrumbContent = '</div><button type="button" class="btn btn-default btn-sm btn-block btn-flat" id="addBreadcrumb"><i class="fa fa-plus-circle"></i> Add Breadcrumb</button>';

        if ($value != 'NULL' && $value != '') {
            $decodeCrumb = json_decode($value);
            if ($decodeCrumb) {
                foreach ($decodeCrumb as $value) {
                    $listCrumb .= $this->breadcrumb_form($value->label, $value->link);
                }
            } else {
                $listCrumb = $this->breadcrumb_form('', '');
            }
        } else {
            $listCrumb .= $this->breadcrumb_form();
        }

        return $crumbContent.$listCrumb.$endCrumbContent;
    }
	
    /**
     * Return input breadcrumb.
     *
     * @return HTML
     **/
    public function breadcrumb_form($val_label = '', $val_link = '')
    {
        $crumbItem = '<div class="row form-breadcrumb">
			<div class="col-xs-4">						
				 <input type="text" name="label[]" value="'.$val_label.'" id="inputLabel" class="form-control" placeholder="Label">
				 <br>
			 </div>
			 <div class="col-xs-6">
				 <input type="text" name="link[]" value="'.$val_link.'" id="inputLabel" class="form-control" placeholder="Link">
				 <br>
			 </div>
			 <div class="col-xs-2">
				 <button type="button" class="remove-crumb btn btn-danger btn-flat btn-block">
					<i class="fa fa-times-circle"></i>
				</button>
				<br>
			</div>
		</div>';
        return $crumbItem;
    }
	
	//Decode HTML
	//-----------------------
	public function html_decode_callback($post_array, $primary_key) {
		//$post_array['content'] = strip_tags($post_array['content'], '<p><a><table><img>');
		//$post_array['content'] = htmlentities($post_array['content']); 
		
		return $post_array;
	}
	
	
}

/* End of file Kursus.php */
/* Location: ./application/modules/kursus/controllers/Kursus.php */