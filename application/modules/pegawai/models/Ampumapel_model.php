<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ampumapel_model extends Base_Model {
	
	public $_table = 'pegawai_mapel';

	//Get Mapel by Guru
	function get_mapel_by_guru($id_guru, $tahun_ajaran) {
		$this->db->select('pegawai_mapel.*, seting_mapel.id, mapel');
		$this->db->from('pegawai_mapel');
		$this->db->join('seting_mapel', 'seting_mapel.id = pegawai_mapel.id_mapel');
		$this->db->where('id_pegawai', $id_guru);
		$this->db->where('id_tahun', $tahun_ajaran);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return FALSE;
	}
	
	//dropdown Mapel by Guru
	function get_dropdown_mapel_by_guru($id_guru, $tahun_ajaran) {
		$this->db->select('pegawai_mapel.id_mapel, seting_mapel.id, mapel');
		$this->db->from('pegawai_mapel');
		$this->db->join('seting_mapel', 'seting_mapel.id = pegawai_mapel.id_mapel');
		$this->db->where('id_pegawai', $id_guru);
		$this->db->where('id_tahun', $tahun_ajaran);
		$query = $this->db->get();
				
		$data = array();
		$data[0] = '-- Pilih Pelajaran --';		
		foreach ($query->result() as $q) {
			$data[$q->id_mapel] = $q->mapel;
		}		
		return $data;
	}
}