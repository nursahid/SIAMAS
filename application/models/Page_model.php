<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page_model extends Base_Model {
	
	public $_table = 'page';
	public $primary_key = 'id_page';
	
    public function __construct()
    {
        parent::__construct();
    }
	
	public function search($keyword) {
		return $this->db
			->select('id_page as id, title, content, featured_image, slug')
			->like('title', $keyword)
			->where('is_active', 'Y')
			->limit(10)
			->get($this->_table);
	}
	
}