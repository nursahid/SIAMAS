<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Siswakeluar_model extends Base_Model {
	
	public $_table = 'mutasi_keluar';
	
	//data siswa keluar
	function get_data() {
		$this->db->select('mutasi_keluar.jenis_mutasi, tanggal_mutasi, keterangan, id_siswa as id, siswa.nama, nis, nisn, nik, alamat, kelamin, agama, tempat_lahir, tgl_lahir');
		$this->db->from('mutasi_keluar');
		$this->db->join('siswa', 'siswa.id = mutasi_keluar.id_siswa');
		$this->db->order_by('id', 'ASC');
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return FALSE;		
	}
	//tahun ajaran aktif
	function get_tapel_aktif() {
		$arr = $this->db->get_where('seting_tahun_ajaran', array('status' => 'Y'));
		$ta = $arr->row();		
		return $ta->id;
	}
	
}