<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Tags_model
 *
 */
class Tags_model extends Base_Model {

	public $_table = "blog_tags";
	
    public function __construct()
    {
        parent::__construct();
    }

	public function get_by_id($id_blog) {
		$this->db->select('blog.id, blogs_post_tags.id_blog, blogs_post_tags.id_tag');
		$this->db->join('blog', 'blog.id=blogs_post_tags.id_blog', 'left');
		$this->db->join('blog_tags', 'blog_tags.id=blogs_post_tags.id_tag', 'left');
		$this->db->where('blogs_post_tags.id_blog', $id_blog);
		return $this->db->get('blogs_post_tags');
	}

}
