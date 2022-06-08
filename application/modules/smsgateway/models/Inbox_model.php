<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inbox_model extends Base_Model {
	
	public $_table = 'inbox';
	

	function cari_datainbox($i){
		$this->db->select('ReceivingDateTime');
		$this->db->from('inbox');
		$this->db->like('ReceivingDateTime',$i,'after');
		$inbox  = $this->db->get();
		$jumlah = $inbox->num_rows();
		$jumlah = !empty($jumlah)?$jumlah:'0';
		return $jumlah;
	}
	function cari_totalinbox(){
		$inbox = $this->db->get('inbox');
		$jumlah = $inbox->num_rows();
		$jumlah = !empty($jumlah)?$jumlah:'0';
		return $jumlah;
	}

}