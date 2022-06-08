<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of post_model
 *
 */
class Post_model extends Base_Model {

	public $_table = "blog";
	
    public function __construct()
    {
        parent::__construct();
    }
	
	//get post join users
	public function get_post($limit= 0) {
		$this->db->select('blg.id, blg.title, usr.full_name, blg.content, LEFT(blg.created_at, 10) AS created_at, blg.featured_image, blg.post_comment_status, blg.tags, blg.path, blg.readtimes');
		$this->db->join('users usr', 'blg.id_user = usr.id', 'LEFT');
		$this->db->where('blg.is_active', 'Y');
		$this->db->order_by('blg.created_at', 'DESC');

		if ($limit > 0) {
			$this->db->limit($limit);	
		}
		return $this->db->get($this->_table.' blg');
	}
	//PENCARIAN
	public function search($keyword) {
		$this->db->select('id, title, content, featured_image, path');
		$this->db->where('is_active', 'Y');
		$this->db->group_start();
		$this->db->like('LOWER(title)', strtolower($keyword));
		$this->db->or_like('LOWER(content)', strtolower($keyword));
		$this->db->group_end();
		$this->db->limit(10);
		return $this->db->get($this->_table);
	}
	
	//more post
	public function more_posts($slug = '', $offset = 0) {
		$id = 0;
		$query = $this->db->select('id')->where('slug', $slug)->limit(1)->get('category');
		if ($query->num_rows() == 1) {
			$res = $query->row();
			$id = $res->id;
		}		
		$this->db->select('blog.id, blog.title, blog.content, LEFT(blog.created_at, 10) AS created_at, blog.featured_image, blog.path, blog.readtimes');
		//$this->db->select('blog.*, categories_blogs.id_category, id_blog');
		$this->db->join('categories_blogs', 'blog.id=categories_blogs.id_blog', 'left');
		$this->db->where('blog.is_active', 'Y');

		$this->db->group_start();
		$this->db->like('categories_blogs.id_category', $id);
		$this->db->group_end();
		if ($offset < 0) {
			$this->db->limit(6);
		}
		if ($offset > 0) {
			$this->db->limit(6, $offset);
		}
		$this->db->order_by('blog.created_at', 'ASC');
		return $this->db->get('blog');
	}
	//arsip per tahun
	public function get_archive_year() {
		$this->db->select('LEFT(created_at, 4) as year');
		$this->db->where('is_active', 'Y');
		$this->db->group_by('1');
		$this->db->order_by('1', 'DESC');
		return $this->db->get($this->_table);
	}
	//ambil data arsip
	public function get_archives($year) {
		$this->db->select("SUBSTR(b.created_at, 6, 2) as `code`, MONTHNAME(b.created_at) AS `month`, COUNT(*) AS `count`");
		$this->db->where('YEAR(b.created_at)', $year);
		$this->db->where('b.is_active', 'Y');
		$this->db->group_by("1,2");
		$this->db->order_by('1', 'ASC');
		return $this->db->get('blog b');
	}
	//ambil tags
	public function get_tags() {
		return $this->db->select('id, tag_name as tag, slug')->get('blog_tags');
	}
	//ambil post by tag
	public function more_posts_by_tag($slug = '', $offset = 0) {
		$id = 0;
		$query = $this->db->select('id')->where('slug', $slug)->limit(1)->get('blog_tags');
		if ($query->num_rows() == 1) {
			$res = $query->row();
			$id = $res->id;
		}
		$this->db->select('b.id, b.title, b.content, LEFT(b.created_at, 10) AS created_at, b.featured_image, b.path, b.readtimes');
		$this->db->join('blogs_post_tags', 'b.id=blogs_post_tags.id_blog', 'left');
		$this->db->where('b.is_active', 'Y');

		$this->db->group_start();
		$this->db->like('blogs_post_tags.id_tag', $id);
		$this->db->group_end();
		if ($offset < 0) {
			$this->db->limit(6);
		}
		if ($offset > 0) {
			$this->db->limit(6, $offset);
		}
		return $this->db->get($this->_table.' b');
	}
	//more arsip
	public function more_archive_posts($offset = 0, $year, $month) {
		$this->db->select('id, title, content, LEFT(created_at, 10) AS created_at, featured_image, path');
		$this->db->where('is_active', 'Y');
		$this->db->where('LEFT(created_at, 4)=', $year)	;
		$this->db->where('SUBSTRING(created_at, 6, 2)=', $month);

		if ($offset < 0) {
			$this->db->limit(6);
		}
		if ($offset > 0) {
			$this->db->limit(6, $offset);
		}
		return $this->db->get($this->_table);
	}
	//artikel populer
	public function get_popular_posts($limit = 6) {
		$this->db->select('b.id, b.title, LEFT(b.created_at, 10) AS created_at, b.content, b.featured_image, b.path, b.readtimes, usr.full_name AS full_name');
		$this->db->join('users usr', 'b.id_user = usr.id', 'LEFT');
		$this->db->where('b.is_active', 'Y');
	
		$this->db->order_by('b.readtimes', 'DESC');
		$this->db->limit($limit);
		return $this->db->get($this->_table.' b');
	}
	//ambil blog yang berkaitan
	public function get_related_posts($post_categories = '', $id, $limit) {
		$categories = explode(',', $post_categories);
		//$categories = $post_categories;
		$this->db->select('id, title, content, LEFT(created_at, 10) AS created_at, featured_image, path, readtimes');
		$this->db->join('categories_blogs', 'blog.id=categories_blogs.id_blog', 'left');
		$this->db->where('blog.id !=', $id);
		$this->db->limit($limit);
		
		$no = 0;
		$this->db->group_start();
		foreach ($categories as $category) {
			if ($no == 0) {
				$this->db->like('categories_blogs.id_category', $category);
			} else {
				$this->db->or_like('categories_blogs.id_category', $category);
			}			
			$no++;
		}
		$this->db->group_end();
		$this->db->order_by('LEFT(created_at, 10) DESC');
		//$this->db->limit((int) $this->session->userdata('post_related_count'));
		return $this->db->get($this->_table);
	}
	
	//hitung pembacaan by slug/path
	public function hitung_baca($path) {
		//ambil data baca dulu sebelum  ditambahkan
		$query = $this->db->where('path', $path)->get($this->_table)->row();
		$this->db->where('path', $path)->update($this->_table, array('readtimes' => ($query->readtimes + 1)));
	}
	
}
