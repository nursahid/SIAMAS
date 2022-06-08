<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rekapspp_model extends CI_Model {

	function __construct(){
        parent::__construct();
    }	
	
	function get_rekap_spp_persiswa($post) {
		$this->db->select('sw.nis as nis, sw.nama as nama, sp.tgl_transaksi, nilai')->from('pembayaran sp')->join('siswa sw', 'sw.id=sp.id_siswa')->where(array('sp.id_jnspembayaran' => $post['pembayaran'], 'sp.id_siswa' => $post['idsiswa'], 'sp.bulan' => $post['bulan'], 'sp.tahun' => $post['tahun']));
		$arr = $this->db->get();
		
		if ($arr->num_rows() > 0) return $arr->row();
		return FALSE;
	}
	
	function get_kelas() {
		$arr = $this->db->query("SELECT id, kelas FROM kelas WHERE id_tahun=(SELECT id FROM seting_tahun_ajaran WHERE status='Y')");
				
		if ($arr->num_rows() > 0) {
			foreach ($arr->result() as $q) {
				$w[0] = '-- Silakan Pilih Kelas --';
				$w[$q->id] = $q->kelas;
			}
			return $w;
		}
		return FALSE;
	}
	
	function report_rekap_spp($f) {
		$ta = $this->get_ta_aktif();
		$sem = $this->get_sem_aktif();
		//antra siswa_kelas dengan absensi belum ada referensi yang konek
		$this->db->select('a.id, a.nis, s.nama, a.tanggal, a.absen')->from('absensi a')->join('siswa s', 'a.nis=s.nis')->join('seting_tahun_ajaran t', 'a.tahun=t.id')->join('seting_semester m', 'a.smt=m.id')->join('siswa_kelas k', 'k.nis=a.nis')->where(array('a.tahun' => $ta->id, 'a.smt' => $sem->id, 'a.tanggal >=' => $f[0], 'a.tanggal <= ' => $f[1]));			
		
		if ($f[2]) {
			$this->db->where('a.nis', $f[2]);
		}
		
		if ($f[3]) {
			$this->db->where('k.idkelas', $f[3]);
		}
		
		$arr = $this->db->get();
		
		if ($arr->num_rows() > 0) return $arr;
		return FALSE;
	}
	//tahun ajaran aktif
	function get_ta_aktif() {
		$arr = $this->db->get_where('seting_tahun_ajaran', array('status' => 'Y'));
		$ta = $arr->row();		
		return $ta->id;
	}
	// semester aktif	
	function get_sem_aktif() {
		$this->db->select('id')->from('seting_semester')->where('status', 'Y');
		$arr = $this->db->get();
		
		return $arr->row();
	}
			
	function get_nama_siswa($nis) {
		$arr = $this->db->get_where('siswa', array('nis' => $nis));
		
		if ($arr->num_rows() > 0) return $arr->row();
		return FALSE;
	}	
}
