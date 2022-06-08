<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Jenispembayaran_model extends Base_Model {
	
	public $_table = 'pembayaran_jenis';
	
	function __construct(){
        parent::__construct();
    }	
	
	//data jenis pembayaran
	function get_jenispembayaran() {
		$query = $this->db->get($this->_table);	
		$data = array();
		$data[0] = '-- Pilih Pembayaran --';
		
		foreach ($query->result() as $q) {
			$data[$q->id] = $q->nama_jenispembayaran;
		}
		
		return $data;
	}
	
}