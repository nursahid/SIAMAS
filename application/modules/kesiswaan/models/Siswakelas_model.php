<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Siswakelas_model extends Base_Model {
	
	public $_table = 'siswa_kelas';
	

	//tahun ajaran aktif
	function get_tapel_aktif() {
		$arr = $this->db->get_where('seting_tahun_ajaran', array('status' => 'Y'));
		$ta = $arr->row();		
		return $ta->id;
	}
	
}