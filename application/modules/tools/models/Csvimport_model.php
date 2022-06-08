<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Csvimport_model extends CI_Model {
	
    public function __construct()
    {
        parent::__construct();
    }
	
	function select($table){
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get($table);
		return $query;
	}

	function insert($table, $data) {
	  $this->db->insert_batch($table, $data);
	}
	
}

/* End of file Csvimport_Model.php */
/* Location: ./application/models/Csvimport_model.php */