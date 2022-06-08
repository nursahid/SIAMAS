<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sms_model extends CI_Model {
	
	public $_table = 'outbox';
	
	/**
	* Sending Message
	*/
	public function send_message() {
		$data = array('DestinationNumber'=>$this->input->post('no_hp'),
					'Coding'=>'Default_No_Compression',
					'TextDecoded'=>$this->input->post('pesan'),
					'CreatorID'=>'1'
				);
		$this->db->insert('outbox',$data);
	}
	
	function lookup($keyword){ 
		$this->db->select('*')->from('pbk'); 
		$this->db->like('Number',$keyword,'after'); 
		$query = $this->db->get(); 
		return $query->result(); 
	}
	
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
	function cari_datasitem($i){
		$this->db->select('SendingDateTime');
		$this->db->from('sentitems');
		$this->db->like('SendingDateTime',$i,'after');
		$inbox  = $this->db->get();
		$jumlah = $inbox->num_rows();
		$jumlah = !empty($jumlah)?$jumlah:'0';
		return $jumlah;
	}
	function cari_totalsitem(){
		$inbox  = $this->db->get('sentitems');
		$jumlah = $inbox->num_rows();
		$jumlah = !empty($jumlah)?$jumlah:'0';
		return $jumlah;
	}	
	
}