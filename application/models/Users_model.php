<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model {

	private $table = "users";
	private $primary_key = "id";

	/**
	* get data from users table
	* @return array
	*/
	function get($limit = 0,$return = 'object'){
		//$query = $this->db->query("SELECT * FROM ".$this->table." WHERE id NOT IN (SELECT id FROM users WHERE id=1)");
		$this->db->where_not_in('id', 1);
		return $this->db->get($this->table,$limit)->result();
	}

	/**
	* get latest registered users
	* @return array
	*/
	function get_newest($limit = 0,$return = 'object'){
		$this->db->order_by('created_on','DESC');
		return $this->get($limit,$return);
	}
	
}

/* End of file Users_Model.php */
/* Location: ./application/models/Users_model.php */