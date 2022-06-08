<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of tag model
 *
 */
class Tag_model extends Base_Model {

	public $_table = "blog_tags";
	
    public function __construct()
    {
        parent::__construct();
    }

    function add_blog_tags($tags) {
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                $tag_array = array("tag_name" => $tag);
                $this->db->insert_batch($this->_table, $tag_array);
            }
            return TRUE;
        }
        return NULL;
    }

}
