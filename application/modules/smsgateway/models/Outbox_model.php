<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Outbox_model extends Base_Model {
	
	public $_table = 'outbox';
	

	function cari($i){
		$this->db->select('*');
		$this->db->from($this->_table);
		$this->db->like('DestinationNumber',$i);
		$this->db->or_like('TextDecoded',$i);
		$inbox  = $this->db->get();
		$jumlah = $inbox->num_rows();
		$jumlah = !empty($jumlah)?$jumlah:'0';
		return $jumlah;
	}
	function total(){
		$inbox = $this->db->get($this->_table);
		$jumlah = $inbox->num_rows();
		$jumlah = !empty($jumlah)?$jumlah:'0';
		return $jumlah;
	}

}