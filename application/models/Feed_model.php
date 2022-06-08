<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feed_model extends Base_Model {
	
	public $_table = 'blog';
	
    public function __construct()
    {
        parent::__construct();
    }	
	//get feed
	function getFeedArticles(){ 
		$this->db->select("id,title,description,path,content");
		$whereCondition = array('is_active' =>'Y');     
		$this->db->where($whereCondition);  
		$this->db->order_by("id", "desc");
		$this->db->from($this->_table);
		
		$query = $this->db->get(); 
		
		return $query->result();   
	}
}