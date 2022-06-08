<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Siswamasuk_model extends Base_Model {
	
	public $_table = 'mutasi_masuk';
	

	//tahun ajaran aktif
	function get_tapel_aktif() {
		$arr = $this->db->get_where('seting_tahun_ajaran', array('status' => 'Y'));
		$ta = $arr->row();		
		return $ta->id;
	}
	
}