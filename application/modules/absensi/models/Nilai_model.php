<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nilai_model extends CI_Model {

	function __construct(){
        parent::__construct();
    }	

	//--------- Data Siswa -----------
	function get_detail_siswa($id) {
		$arr = $this->db->get_where('siswa', array('nis' => $id));
		
		if ($arr->num_rows() > 0) return $arr->row();
		return FALSE;
	}	
	//---- Data Kelas ----------
	function get_kelas() {
		$arr = $this->db->query("SELECT id, kelas FROM kelas WHERE id_tahun=(SELECT id FROM seting_tahun_ajaran WHERE status='Y')");
				
		if ($arr->num_rows() > 0) {
			foreach ($arr->result() as $q) {
				$w[0] = '-- Pilih Kelas --';
				$w[$q->id] = $q->kelas;
			}
			return $w;
		}
		return FALSE;
	}
	//---- Mata Pelajaran------------
	function get_total_mapel($id) {
		$arr = $this->db->query("SELECT COUNT(idmp) AS total FROM pengampu WHERE idkelas='".$id."' AND id_tahun=(SELECT id FROM seting_tahun_ajaran WHERE statusnya='aktif')");		
		
		return $arr->row();
	}
	
	function get_jumlah_mapel($nis) {
		$arr = $this->db->query("SELECT COUNT(nis) AS jumlah FROM nilai WHERE nis='".$nis."' AND idtahun=(SELECT id FROM seting_tahun_ajaran WHERE statusnya='aktif')");
		
		return $arr->row();
	}
	
	function get_mapel_for_score($idtingkat) {
		$a = $this->get_tahun_ajaran();
		
		$this->db->select('mp.KDMP, mp.MP, g.NIP, g.NAMA')->from('pengampu p')->join('guru g', 'p.nip=g.NIP')->join('mp', 'p.idmp=mp.KDMP')->join('refkelas r', 'p.idkelas=r.id')->where(array('p.idtahun' => $a->id, 'p.idkelas' => $idtingkat));		
		$arr = $this->db->get();
		
		if ($arr->num_rows() > 0) return $arr->result();
		return FALSE;
	}
	//------- Nilai ----------------
	function get_nilai_siswa($nis) {
		$arr = $this->db->query("SELECT kdmp, nilai FROM nilai WHERE idtahun=(SELECT id FROM seting_tahun_ajaran WHERE status='Y') AND nis='".$nis."' AND sem=(SELECT id FROM seting_semester WHERE status='Y') ORDER BY kdmp ASC");
		
		if ($arr->num_rows() > 0) return $arr->result();
		return FALSE;
	}
	
	function get_report_nilai($nis) {
		$ta = $this->get_tahun_ajaran();
		$sem = $this->get_sem_aktif();
		
		$this->db->select('mp.KDMP, mp.MP, mp.ALIAS, nilai.nilai')->from('nilai')->join('mp', 'nilai.kdmp=mp.KDMP')->where(array('idtahun' => $ta->id, 'sem' => $sem->id, 'nis' => $nis));
		$arr = $this->db->get();
		
		if ($arr->num_rows() > 0) return $arr;
		return FALSE;
	}
	
	function cek_nilai_siswa($nis, $kdmp, $idtahun) {
		$arr = $this->db->get_where('nilai', array('nis' => $nis, 'kdmp' => $kdmp, 'id_tahun' => $idtahun));
		
		if ($arr->num_rows() > 0) return TRUE;
		return FALSE;
	}
	
	function get_nama_siswa($nis) {
		$arr = $this->db->get_where('siswa', array('nis' => $nis));
		
		if ($arr->num_rows() > 0) return $arr->row();
		return FALSE;
	}
	//------------telpon ortu--------------
	function get_telp_ortu($nis) {
		$this->db->select('hp_ortu')->from('siswa')->where('nis', $nis);
		$arr = $this->db->get();
		
		if ($arr->num_rows() > 0) return $arr->row();
		return FALSE;
	}
	//---------- seting TAPEL dan SEMESTER
	function get_tahun_ajaran() {
		$this->db->select('id')->from('seting_tahun_ajaran')->where('status', 'Y');
		$arr = $this->db->get();
		
		return $arr->row();
	}
	
	function get_sem_aktif() {
		$this->db->select('id')->from('seting_semester')->where('status', 'Y');
		$arr = $this->db->get();
		
		return $arr->row();
	}
	
}
