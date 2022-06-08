<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sliders_model extends Base_Model {
	
	public $_table = 'image_sliders';
	
    public function __construct()
    {
        parent::__construct();
    }
	public function get_image_sliders() {
		return $this->db->select('id, caption, image')->where('is_deleted', 'false')->get($this->_table);
	}
}