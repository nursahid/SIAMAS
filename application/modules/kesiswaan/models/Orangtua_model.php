<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orangtua_model extends Base_Model {
	
	public $_table = 'siswa_orangtua';
	

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
		$this->db->or_like('nama_lengkap', $q);
		$this->db->or_like('tmp_lahir', $q);
		$this->db->or_like('tgl_lahir', $q);
		$this->db->or_like('alamat', $q);
		$this->db->or_like('posisi', $q);
		$this->db->limit($limit, $start);
        return $this->db->get($this->_table)->result();
    }
	
	function select() {
		$this->db->order_by('nama', 'ASC');
		$query = $this->db->get($this->_table);
		return $query;
	}

}