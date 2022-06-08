<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Blog extends MY_Controller
{
    private $limit = 10;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('tag_model', 'tag');
        $this->load->model('blogModel');
        $this->load->model('categoryModel');
        $this->load->library('form_validation');

    }

    public function index($offset = 0)
    {
        $this->load->library('pagination');

        $this->db->join('users', 'blog.id_user = users.id', 'left');
        $model = $this->blogModel->search(null, $this->limit, $offset);
        $count = $this->blogModel->count();

        $config['base_url'] = site_url('blog/index');
        $config['total_rows'] = $count;
        $config['per_page'] = $this->limit;
        $config['uri_segment'] = 3;
        $config['num_links'] = 5;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li class="paginate_button">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="paginate_button active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        
        $this->pagination->initialize($config);
        
        $data['model'] = $model;
        $data['pagination'] = $this->pagination->create_links();

        $this->layout->set_title('Blog');
        $this->layout->set_wrapper('index', $data);

        $this->layout->setCacheAssets();

        $this->layout->render();
    }

    public function read($path, $id)
    {
        $this->load->model('blogModel');
        $this->load->model('categoryModel');

        $this->db->join('users', 'blog.id_user = users.id', 'left');
        $model = $this->blogModel->single(['blog.id' => $id]);

        $this->db->join('categories_blogs', 'category.id = categories_blogs.id_category', 'left');
        $category = $this->categoryModel->search(['id_blog' => $id]);
        $data = [
            'model' => $model,
            'categories' => $category
        ];

        $this->layout->set_title($this->title . ' : ' . $model->title);
        $this->layout->set_wrapper('read', $data);
        $this->layout->render();
    }
	//==========================
	//   Kelola Blog
	//--------------------------
    public function tambah() {
        if ($this->input->post('add_tags')) {
            $this->form_validation->set_rules('blog_tags', 'Tags', 'trim|required');

            $blog_tags = $this->input->post('blog_tags', TRUE);

            $blog_related_tags = '';
            if (is_array(str_split($blog_tags)) && count(str_split($blog_tags)) > 2) {
                $blog_tags = $this->format_tags_keywords($blog_tags);
                $blog_related_tags = explode('$', $blog_tags);
            } else {
                //$this->handle_error('Enter Tags');
				$this->session->set_flashdata('message', 'Enter Tags');
				$data['type'] = 'error';
            }

            if ($this->form_validation->run($this)) {
                $resp = $this->tag->add_blog_tags($blog_related_tags);
                if ($resp === TRUE) {
                    //$this->handle_success('Tags are added successfully');
					$this->session->set_flashdata('message', 'Tags are added successfully');
					$data['type'] = 'success';
                }
            }

            $data['blog_tags'] = $blog_related_tags;
        }
        //$data['errors'] = $this->error;
        //$data['success'] = $this->success;
		$data['message'] = $this->session->flashdata('message');

		//view
		$this->layout->set_wrapper('tambah_berita', $data);
        // CSS and JS plugins
        $template_data['css_plugins'] = [
            base_url('assets/plugins/tags/textext.core.css'),
			base_url('assets/plugins/tags/textext.plugin.tags.css')
        ];
        $template_data['js_plugins'] = [
           // base_url('assets/plugins/tinymce/jquery.tinymce.min.js'),
            base_url('assets/plugins/tinymce/tinymce.min.js')
        ];		
		$template_data["title"] = "Tambah Berita";
        $template_data['crumb'] = ['Blog' => 'blog/tambah/'];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); 
    }
	
    public function kelola()
    {
        $this->layout->auth();

        $crud = new grocery_CRUD();

        $crud->set_table('blog');
        $crud->set_subject('Berita');
		
        $crud->columns('title', 'categories', 'tags', 'id_user', 'created_at');
		$crud->add_fields(["title", "path", "featured_image", "content", "keyword", "description", "categories", "id_user", "tags"]);
		$crud->edit_fields(["title", "path", "featured_image", "content", "keyword", "description", "categories", "id_user", "tags"]);
		//display
        $crud->display_as('id_user', 'Username');
		$crud->display_as('title', 'Judul Berita');
		$crud->display_as('path', 'Slug');
		$crud->display_as('featured_image', 'Gambar Pendukung');
		$crud->display_as('content', 'Isi Berita');
		$crud->display_as('keyword', 'Kata Kunci');
		$crud->display_as('description', 'Deskripsi');
		$crud->display_as('categories', 'Kategori');
		$crud->display_as('created_at', 'Dibuat Tgl.');

        $segment = $this->uri->segment(3);
        if ($segment != 'edit' && $segment != 'add') {
            $crud->set_relation('id_user', 'users', 'username');
        }
		$state = $crud->getState();
		if ($state==='add')
		{
			//slug
			$crud->set_js("assets/plugins/jquery.slugify/speakingurl.js");
			$crud->set_js("assets/plugins/jquery.slugify/slugify.min.js");
			$crud->set_js("assets/js/add_content.js");
		}
		else
		{
			//slug
			$crud->set_js("assets/plugins/jquery.slugify/speakingurl.js");
			$crud->set_js("assets/plugins/jquery.slugify/slugify.min.js");
			$crud->set_js("assets/js/add_content.js");
		}		
        //$crud->change_field_type('path', 'hidden');
        $crud->change_field_type('id_user', 'hidden');
		
		$crud->set_field_upload('featured_image', 'assets/uploads/thumbnail');
		//unset
        $crud->unset_fields('created_at');
        $crud->unset_columns('content', 'path', 'readtimes');
		$crud->unset_texteditor('description', 'full_text');
		//callback
		//$crud->callback_edit_field('id_user',array($this,'iduser_callback'));		
        //$crud->callback_before_insert(array($this, 'savePath'));
        //$crud->callback_before_update(array($this, 'savePath'));
		
        $crud->callback_column('title', array($this, 'linkBlog'));
		
		//set relation
        $crud->set_relation_n_n('categories', 'categories_blogs', 'category', 'id_blog', 'id_category', 'category');
		$crud->set_relation_n_n('tags', 'blogs_post_tags', 'blog_tags', 'id_blog', 'id_tag', 'tag_name');
		
        $data = (array) $crud->render();

        $template_data['grocery_css'] = $data['css_files'];
        $template_data['grocery_js'] = $data['js_files'];
        $template_data['title'] = 'Artikel Berita';
        $template_data['crumb'] = [
            'Blog' => '',
        ];

        $this->layout->set_wrapper('grocery', $data, 'page', false);

        if ($this->uri->segment(3) != 'add' && $this->uri->segment(3) != 'edit') {
            $this->layout->setCacheAssets();
        }

        $this->layout->render('admin', $template_data);
    }

    public function linkBlog($value, $row)
    {
        return '<a href="' . site_url('blog/read/' . $row->path . '/' . $row->id) . '" title="' . $value .'">' . $value . '</a>';
    }

    public function savePath($post_array, $primary_key = null)
    {
        $path = strtolower(urlencode(str_replace(' ', '-', $post_array['title'])));
        $post_array['path'] = $path;
        $post_array['id_user'] = $this->ion_auth->user()->row()->id;

        return $post_array;
    }
	//======================
	// Kategori Controller
	//----------------------
    public function kategori()
    {
        $this->layout->auth();

        $crud = new grocery_CRUD();

        $crud->set_table('category');
        $crud->set_subject('Kategori');
		
		//display
        $crud->display_as('category', 'Kategori');
		$crud->display_as('created_at', 'Dibuat Tgl.');
		$crud->display_as('description', 'Deskripsi');
		//field type
		$crud->field_type("description", "text");
		//unset
        $crud->unset_fields('created_at');
		$crud->unset_texteditor('description', 'full_text');
		
		$state = $crud->getState();
		if ($state==='add')
		{
			//slug
			$crud->set_js("assets/plugins/jquery.slugify/speakingurl.js");
			$crud->set_js("assets/plugins/jquery.slugify/slugify.min.js");
			$crud->set_js("assets/js/add_kategori.js");
		}
		else
		{
			//slug
			$crud->set_js("assets/plugins/jquery.slugify/speakingurl.js");
			$crud->set_js("assets/plugins/jquery.slugify/slugify.min.js");
			$crud->set_js("assets/js/add_kategori.js");
		}
		//render view
        $data = (array) $crud->render();

        $template_data['grocery_css'] = $data['css_files'];
        $template_data['grocery_js'] = $data['js_files'];
        $template_data['title'] = 'Kategori Berita';
        $template_data['crumb'] = [
            'Kategori Berita' => 'blog/kategori',
        ];

        $this->layout->set_wrapper('grocery', $data, 'page', false);

        $this->layout->setCacheAssets();

        $this->layout->render('admin', $template_data);
    }
	//=======================
	//   komentar COntroler
	//-----------------------
    public function komentar()
    {
        $this->layout->auth();

        $crud = new grocery_CRUD();

        $crud->set_table('comments');
        $crud->set_subject('Komentar');
		// Show in
		$crud->add_fields(["id_blog", "comment_name", "comments", "url", "email"]);
		$crud->edit_fields(["id_blog", "comment_name", "comments", "url", "email"]);
		$crud->columns(["comment_name", "comments", "id_blog", "comment_at"]);
		
		//display
        $crud->display_as('id_blog', 'Judul Berita');
		$crud->display_as('comment_name', 'Komentator');
		$crud->display_as('comments', 'Komentar');
		$crud->display_as('comment_at', 'Tanggal');
		
		//set relation
        $crud->set_relation("id_blog", "blog", "title");		
        
		$crud->unset_fields('is_active');
		$crud->unset_texteditor('comments', 'full_text');

        $data = (array) $crud->render();

        $template_data['grocery_css'] = $data['css_files'];
        $template_data['grocery_js'] = $data['js_files'];
        $template_data['title'] = 'Komentar Berita';
        $template_data['crumb'] = [
            'Komentar Berita' => 'blog/komentar',
        ];

        $this->layout->set_wrapper('grocery', $data, 'page', false);

        $this->layout->setCacheAssets();

        $this->layout->render('admin', $template_data);
    }
	//=================
	//  Tags Controller
	//-----------------
    public function tags()
    {
        $this->layout->auth();

        $crud = new grocery_CRUD();

        $crud->set_table('blog_tags');
        $crud->set_subject('Tags');
		$crud->columns(["tag_name", "slug"]);
		
		//display
        $crud->display_as('tag_name', 'Tag');
		$crud->display_as('created_at', 'Dibuat Tgl.');

		//unset
        $crud->unset_fields('created_at');
		
		$state = $crud->getState();
		if ($state==='add')
		{
			//slug
			$crud->set_js("assets/plugins/jquery.slugify/speakingurl.js");
			$crud->set_js("assets/plugins/jquery.slugify/slugify.min.js");
			$crud->set_js("assets/js/add_tag.js");
		}
		else
		{
			//slug
			$crud->set_js("assets/plugins/jquery.slugify/speakingurl.js");
			$crud->set_js("assets/plugins/jquery.slugify/slugify.min.js");
			$crud->set_js("assets/js/add_tag.js");
		}
		//render view
        $data = (array) $crud->render();

        $template_data['grocery_css'] = $data['css_files'];
        $template_data['grocery_js'] = $data['js_files'];
        $template_data['title'] = 'Tags Berita';
        $template_data['crumb'] = ['Tags Berita' => 'blog/tags',];

        $this->layout->set_wrapper('grocery', $data, 'page', false);

        $this->layout->setCacheAssets();

        $this->layout->render('admin', $template_data);
    }
    public function tags_() {
        if ($this->input->post('add_tags')) {
            $this->form_validation->set_rules('blog_tags', 'Tags', 'trim|required');

            $blog_tags = $this->input->post('blog_tags', TRUE);

            $blog_related_tags = '';
            if (is_array(str_split($blog_tags)) && count(str_split($blog_tags)) > 2) {
                $blog_tags = $this->format_tags_keywords($blog_tags);
                $blog_related_tags = explode('$', $blog_tags);
            } else {
                //$this->handle_error('Enter Tags');
				$this->session->set_flashdata('message', 'Enter Tags');
				$data['type'] = 'error';
            }

            if ($this->form_validation->run($this)) {
                $resp = $this->tag->add_blog_tags($blog_related_tags);
                if ($resp === TRUE) {
                    //$this->handle_success('Tags are added successfully');
					$this->session->set_flashdata('message', 'Tags are added successfully');
					$data['type'] = 'success';
                }
            }

            $data['blog_tags'] = $blog_related_tags;
        }
        //$data['errors'] = $this->error;
        //$data['success'] = $this->success;
		$data['message'] = $this->session->flashdata('message');
        //$this->load->view('tags_view', $data);
		//view
		$this->layout->set_wrapper('tags_view', $data);
        // CSS and JS plugins
        $template_data['css_plugins'] = [
            base_url('assets/plugins/tags/textext.core.css'),
			base_url('assets/plugins/tags/textext.plugin.tags.css')
        ];
        $template_data['js_plugins'] = [
            base_url('assets/plugins/tags/textext.core.js'),
            base_url('assets/plugins/tags/textext.plugin.tags.js')
        ];		
		$template_data["title"] = "Tags";
        $template_data['crumb'] = ['Blog' => 'blog/tags/','Tags' => 'blog/tags/'];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); 
    }
    private function format_tags_keywords($string) {
        preg_match_all('`(?:[^,"]|"((?<=\)"|[^"])*")*`x', $string, $result);
        $tags = '';
        foreach ($result as $arr) {
            $i = 0;
            foreach ($arr as $val) {
                if ($i % 2 == 1) {
                    $i++;
                    continue;
                }
                $tags .= $val . '$';
                $i++;
            }
            $tags = str_replace('[', '', $tags);
            $tags = str_replace(']', '', $tags);
            $tags = rtrim($tags, '$');
            $tags = str_replace('"', '', $tags);
            break;
        }
        return $tags;
    }	
	//=====================
	//    CALLBACKS
	//---------------------
	//Kolom ID
	function iduser_callback() {
		$id_user = $this->uri->segment(4);
		$q = $this->db->get_where('users', array('id' => $id_user), 1)->row();
		return '<input type="hidden" name="id_user" value="'.$id_user.'"><strong>'.$q->username.'</strong>';
	}	
	
}

/* End of file blog.php */
/* Location: ./application/controllers/blog.php */
