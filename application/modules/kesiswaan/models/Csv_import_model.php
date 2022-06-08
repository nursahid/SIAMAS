<?php
class Csv_import_model extends Base_Model {
	
	public $_table = 'siswa';
	
	function select() {
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get($this->_table);
		return $query;
	}

	function insert($data) {
		$this->db->insert_batch($this->_table, $data);
	}
}
