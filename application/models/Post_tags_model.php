<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Post_tags_model
 *
 */
class Post_tags_model extends Base_Model {

	public $_table = "blogs_post_tags";
	
    public function __construct()
    {
        parent::__construct();
    }
	public function get_tags($id_blog) {
		$this->db->select('blogs_post_tags.id_blog, blogs_post_tags.id_tag, blog_tags.tag_name as tag');
		$this->db->join('blog_tags', 'blog_tags.id=blogs_post_tags.id_tag');
		$this->db->where('blogs_post_tags.id_blog', $id_blog);
		return $this->db->get('blogs_post_tags');
	}
}
