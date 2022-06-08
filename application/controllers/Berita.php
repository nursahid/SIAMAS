<?php defined('BASEPATH') or exit('No direct script access allowed');

class Berita extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('blog/blogModel');
        $this->load->model('blog/categoryModel');
		$this->load->model('kategori_model','kategori');
		$this->load->model('post_model','blogpost');
		$this->load->model('sliders_model','sliders');
		$this->load->model('komentar_model','komentar');
		$this->load->model('page_model','pages');
		$this->load->model('poskategori_model','poskategori');
		$this->load->model('tags_model','tags');
		$this->load->model('post_tags_model','posttags');
		$this->load->helper('security');
		$this->load->library('form_validation');
		$this->form_validation->CI =& $this;
	}

    public function index($offset = 0)
    {
        $this->load->library('pagination');

        $this->db->join('users', 'blog.id_user = users.id', 'left');
        $model = $this->blogModel->search(null, $this->limit, $offset);
        $count = $this->blogModel->count();

        $config['base_url'] = site_url('berita/index');
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
        
        $data['datas'] = $model;
        $data['pagination'] = $this->pagination->create_links();

        $this->layout->set_title('Daftar Berita Sekolah');
        $this->layout->set_wrapper('frontpage/blog_post', $data);

        $this->layout->setCacheAssets();
		$template_data["title"] = "Berita Terbaru";
		$template_data["crumb"] = ["Berita" => "berita",];
        $this->layout->render('front', $template_data);
    }
	
    public function detail($path)
    {
        //$this->load->helper(['string']);
		$this->blogpost->hitung_baca($path);		
		$id = $this->blogpost->get_by('path',$path)->id;
		
		$this->db->join('users', 'blog.id_user = users.id', 'left');
		$model = $this->blogModel->single(['blog.path' => $path]);
		
        $this->db->join('categories_blogs', 'category.id = categories_blogs.id_category', 'left');
        $category = $this->categoryModel->search(['id_blog' => $id]);
        $data = [
            'post' => $this->blogpost->get_post(1)->row(),
            'categories' => $category,
			'id'	=> $id
        ];
		
		$this->db->select('cm.id, cm.comment_name, cm.url, LEFT(cm.comment_at, 10) AS created_at, cm.comments')
			->join('blog b', 'cm.id_blog = b.id', 'LEFT')
			->where('cm.is_active', 'Y')
			->where('cm.id_blog', $id)
			->order_by('cm.comment_at', 'DESC')
			->limit(10);
		$query = $this->db->get('comments cm');
		$data['post_comments'] = $query;
		$total_rows = $this->komentar->more_comments($id, 0)->num_rows();
		$this->vars['total_comment_pages'] = ceil($total_rows / 10); // Total Comment Page
		//var_dump($query);
		//view
        $this->layout->set_title($this->title . ' | ' . $model->title);
        $this->layout->set_wrapper('frontpage/single-post', $data);
        $this->layout->render();
    }
	
	//pencarian berita
	public function cari(){
		$jumlah= $this->db->get('blog')->num_rows();
		$config['base_url'] 	= base_url().'berita/cari/';
		$config['total_rows'] 	= $jumlah;
		$config['per_page'] 	= 10; 	
		if ($this->uri->segment('3')==''){
			$dari = 0;
		}else{
			$dari = $this->uri->segment('3');
		}
			
		if (is_numeric($dari)) {
			if ($this->input->post('cari')!=''){
					$data['title'] = "Hasil Pencarian keyword - ".$this->cetak($this->input->post('kata'));
					//$data['description'] = description();
					//$data['keywords'] = keywords();
					$post = $this->sistem_model->cari_berita($this->input->post('kata'));
			}else{
					$data['title'] = "Semua Berita";
					//$data['description'] = description();
					//$data['keywords'] = keywords();
					$post = $this->sistem_model->get_join('blog','users','id_user','id','id','DESC',$dari,$config['per_page']);
					$this->pagination->initialize($config);
			}
		}else{
			redirect('berita');
		}
			
		//$this->template->load(template().'/template',template().'/berita',$data);
		$data['datas'] = $post;
        $this->layout->set_title($this->title . ' | ' . $post->title);
        $this->layout->set_wrapper('frontpage/blog_post', $data);
        $this->layout->render();
	}
    function cetak($str){
        return strip_tags(htmlentities($str, ENT_QUOTES, 'UTF-8'));
    }
	

	//-----------
	// PENCARIAN
	//---------------
	public function search() {
		if ($_POST) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('keyword', 'Kata Kunci Pencarian', 'trim|required|alpha_numeric_spaces|max_length[100]');
			$data['posts'] = $data['pages'] = FALSE;
			if ($this->form_validation->run() == FALSE) {
				$this->session->unset_userdata('keyword');
				$data['title'] = 'Anda memasukan karakter yang tidak diizinkan oleh sistem kami!';
			} else {
				$keyword = trim(strip_tags($this->input->post('keyword', true)));
				$this->session->set_userdata('keyword', $keyword);
				$data['title'] = 'Hasil pencarian dengan kata kunci "'.$this->session->userdata('keyword').'"';
				$data['posts'] = $this->blogpost->search($keyword);
				$data['pages'] = $this->pages->search($keyword);
			}
			//$data['content'] = 'themes/'.theme_folder().'/search-results';
			//$this->load->view('themes/'.theme_folder().'/index', $data);
			//view
			$this->layout->set_title($this->title . ' | ' . $data['title']);
			$this->layout->set_wrapper('frontpage/search-results', $data);
			$this->layout->render();
		} else {
			redirect(base_url());
		}
	}
	//Kategori
	public function tes($slug='') {
        $id = $this->blogpost->get_by('path',$slug)->id;
		
		$this->db->select('cm.id, cm.comment_name, cm.url, LEFT(cm.comment_at, 10) AS created_at, cm.comments')
			->join('blog b', 'cm.id_blog = b.id', 'LEFT')
			->where('cm.is_active', 'Y')
			->where('cm.id_blog', $id)
			->order_by('cm.comment_at', 'DESC')
			->limit(10);
		$query = $this->db->get('comments cm');
		var_dump($query);
	}
	//-------------
	//  KATEGORI
	//-------------
	public function kategori($slug='') {
		////$slug = $this->uri->segment(2);
		if ($slug) {
			$query 		= $this->kategori->get_by('slug', $slug);
			$total_rows = $this->blogpost->more_posts($slug, 0)->num_rows();
			
			$data['title'] 		= strtoupper(str_replace('-', ' ', $slug));
			$data['total_rows'] = $total_rows;
			$data['total_page'] = ceil($total_rows / 6);
			$data['query'] 		= $this->blogpost->more_posts($slug, -1);

			//view
			$this->layout->set_title($this->title . ' | ' . $data['title']);
			$this->layout->set_wrapper('frontpage/loop-posts', $data);
			$this->layout->render();
			
		} else {
			show_404();
		}
	}
	public function more_kategoris() {
		$slug = $this->input->post('slug', true);
		$page_number = intval($this->input->post('page_number', true));
		$offset = ($page_number - 1) * 6;
		$response = [];
		if ($slug) {
			$query = $this->blogpost->more_posts($slug, $offset);
			$total_rows = $this->blogpost->more_posts($slug, 0)->num_rows();
			$rows = [];
			foreach($query->result() as $row) {
				$rows[] = $row;
			}
			$response['rows'] = $rows;
		}

		//$this->output->set_content_type('application/json', 'utf-8')->set_output(json_encode($response, JSON_PRETTY_PRINT))->_display();
		echo json_encode($response, JSON_PRETTY_PRINT);
		exit;
	}
	//-------------
	//  ARSIP
	//-------------	
	public function arsip($year='',$month='') {
		//$year = substr($this->uri->segment(2), 0, 4);
		//$month = substr($this->uri->segment(3), 0, 2);
		if ($year && $month) {
			$total_rows = $this->blogpost->more_archive_posts(0, $year, $month)->num_rows();
			$data['title'] = bulan($month).' '.$year;
			$data['total_page'] = ceil($total_rows / 6);
			$data['query'] = $this->blogpost->more_archive_posts(-1, $year, $month);
			//$data['content'] = 'themes/'.theme_folder().'/loop-posts';
			//$this->load->view('themes/'.theme_folder().'/index', $data);
			//view
			$this->layout->set_title($this->title . ' | ' . $data['title']);
			$this->layout->set_wrapper('frontpage/loop-posts', $data);
			$this->layout->render();
		} else {
			show_404();
		}
	}
	public function more_arsips() {
		$year = substr($this->input->post('year', true), 0, 4);
		$month = substr($this->input->post('month', true), 0, 2);
		$page_number = intval($this->input->post('page_number', true));
		$offset = ($page_number - 1) * 6;
		$query = $this->blogpost->more_archive_posts($offset, $year, $month);
		$total_rows = $this->blogpost->more_archive_posts(0, $year, $month)->num_rows();
		$rows = [];
		foreach($query->result() as $row) {
			$rows[] = $row;
		}
		$response = [
			'rows' => $rows,
			'total_page' => ceil($total_rows / 6)
		];

		//$this->output->set_content_type('application/json', 'utf-8')->set_output(json_encode($response, JSON_PRETTY_PRINT))->_display();
		echo json_encode($response, JSON_PRETTY_PRINT);
		exit;
	}
	
	//-------------
	//  TAGS
	//-------------
	public function tags($tag = '') {
		if ($tag) {
			$data['title'] = '#' . ucwords(str_replace('-', ' ', $tag));
			$total_rows = $this->blogpost->more_posts_by_tag($tag, 0)->num_rows();
			$data['total_rows'] = $total_rows;
			$data['total_page'] = ceil($total_rows / 6);
			$data['query'] = $this->blogpost->more_posts_by_tag($tag, -1);
			//view
			$this->layout->set_title($this->title . ' | ' . $data['title']);
			$this->layout->set_wrapper('frontpage/loop-posts', $data);
			$this->layout->render();
		} else {
			show_404();
		}
	}
	public function more_tags() {
		$tag = $this->input->post('tag', true);
		$page_number = intval($this->input->post('page_number', true));
		$offset = ($page_number - 1) * 6;
		$response = [];
		if ($tag) {
			$query = $this->blogpost->more_posts_by_tag($tag, $offset);
			$total_rows = $this->blogpost->more_posts_by_tag($tag, 0)->num_rows();
			$rows = [];
			foreach($query->result() as $row) {
				$rows[] = $row;
			}
			$response['rows'] = $rows;
		}

		//$this->output->set_content_type('application/json', 'utf-8')->set_output(json_encode($response, JSON_PRETTY_PRINT))->_display();
		echo json_encode($response, JSON_PRETTY_PRINT);
		exit;
	}
	
	//------------------
	//   KIRIM KOMENTAR
	//------------------
	public function submit_comment() {
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		//$this->form_validation->set_error_delimiters('<div class="text-red"><i class="fa fa-times"></i> &nbsp;', '</div>');
		
		//cek data yang ganda
		$this->form_validation->set_rules('comment_name', 'Nama Anda', 'required');
		$this->form_validation->set_rules('comments', 'Komentar', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|callback_email_check|is_unique[comments.email]');
		//$this->form_validation->set_rules('co_phone', 'Phone',array('required',array($this->komentar, 'valid_phone')));

		//$this->form_validation->set_message('required', 'Wajib diisi');
		
		if($this->form_validation->run() == FALSE) {
			//error
			echo json_encode(array('status'=>0, 'pesan' => validation_errors()));
			
		} else {
			//Update database 
			$data = array(
					'id_blog' => $this->input->post('id_blog',TRUE),
					'parent_id' => 0,
					'comment_name' => $this->input->post('comment_name',TRUE),
					'url' => $this->input->post('url',TRUE),
					'comments' => $this->input->post('comments',TRUE),
					//'comment_at' => time(),
					'email' => $this->input->post('email',TRUE),
			);
			$this->db->insert('comments',$data);
				
			//sukses
			echo json_encode(array('status'=>1, 'pesan' => ' Data Berhasil disimpan'));
		}
	}
	
	public function more_comments() {
		$comment_post_id = (int) $this->input->post('id_blog', true);
		$page_number = (int) $this->input->post('page_number', true);
		$offset = ($page_number - 1) * 10;
		$response = [];
		if ($comment_post_id > 0) {
			$query = $this->komentar->more_comments($comment_post_id, $offset);
			$rows = [];
			foreach($query->result() as $row) {
				$rows[] = [
					'comments' => $row->comments,
					'comment_name' => $row->comment_name,
					'url' => $row->url,
					'comment_at' => nama_hari(date('N', strtotime($row->comment_at))) . ', '. tgl_indo($row->comment_at),
				];
			}
			$response['comments'] = $rows;
		}

		$this->output->set_content_type('application/json', 'utf-8')->set_output(json_encode($response, JSON_PRETTY_PRINT))->_display();
		exit;
	}
	
	//===================
	//    CALLBACKS
	//-------------------
    public function commentname_check($str) {
        //cek data database
		$cek = $this->db->query("SELECT * FROM comments where comment_name='".$str."'")->row();
		$nama = $cek->comment_name;
		if ($str == $nama) {
            $this->form_validation->set_message('commentname_check', 'Nama {field} sudah digunakan');
            return FALSE;
        }
        else {
            return TRUE;
        }
    }
    public function email_check($str) {
        $this->load->library('form_validation');
		//cek data database
		$cek = $this->db->query("SELECT email FROM comments where email='".$str."'")->row();
		$email = $cek->email;
		if ($str == $email) {
            $this->form_validation->set_message('email_check', 'E-mail {field} sudah digunakan');
            return FALSE;
        }
        else {
            return TRUE;
        }
    }
	
}
?>