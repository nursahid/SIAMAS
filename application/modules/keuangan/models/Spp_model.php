<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Spp_model extends CI_Model {

	function __construct(){
        parent::__construct();
    }	
	
	function get_data_siswa_perkelas2($id) {	
		$this->db->select('kelas.id, siswa_kelas.id_siswa, siswa_kelas.id_kelas, siswa.nama')
				 ->from('siswa_kelas')
				 ->join('siswa', 'siswa_kelas.id_siswa=siswa.id')
				 ->join('kelas', 'siswa_kelas.id_kelas=kelas.id')
				 ->where('kelas.id', $id);
		$arr = $this->db->get();
		
		if ($arr->num_rows() > 0) return $arr;
		return FALSE;		
	}
	
	//data siswa per kelas	
	function get_data_siswa_perkelas($id_kelas) {	
		$this->db->select('r.id, k.id_siswa, s.nama, nis')->from('siswa_kelas k')->join('siswa s', 'k.id_siswa=s.id')->join('kelas r', 'k.id_kelas=r.id')->where('r.id', $id_kelas);;
		$arr = $this->db->get();
		
		if ($arr->num_rows() > 0) return $arr;
		return FALSE;		
	}
	
	function get_detail_absensi($id) {
		$arr = $this->db->get_where('absensi', array('id' => $id));
		
		if ($arr->num_rows() > 0) return $arr->row();
		return FALSE;
	}
	
	function lookup($key) {
		$tahun = $this->get_ta_aktif();
		
		$this->db->select('s.nis, s.nama, r.kelas, s.hp_ortu')->from('siswa s')->join('kelas k', 's.id=k.id_siswa')->join('kelas r', 'k.id_kelas=r.id')->where('k.id_tahun', $tahun)->like('s.nis', $key, 'after');
		$arr = $this->db->get();
		return $arr->result();
	}
	
	function cek_deposit($nis) {
		$arr = $this->db->get_where('pulsa', array('nis' => $nis));
		
		if ($arr->num_rows() > 0) return TRUE;
		return FALSE;
	}
	
	function get_ta_aktif() {
		$arr = $this->db->get_where('seting_tahun_ajaran', array('status' => 'Y'));
		$ta = $arr->row();
		
		return $ta->id;
	}	
	function get_sem_aktif() {
		$arr = $this->db->get_where('seting_semester', array('status' => 'Y'));
		$sem = $arr->row();
		
		return $sem->id;
	}
}