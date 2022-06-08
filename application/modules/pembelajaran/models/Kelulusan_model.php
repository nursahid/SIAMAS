<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kelulusan_model extends CI_Model {
	
	public $_table = 'kelas';
	
	function __construct(){
        parent::__construct();
    }
	
	//---- Data Kelas di tinkat Paling tinggi ----------
	function get_kelas_akhir() {
		//$this->db->select_max('age');
		$query = $this->db->select_max('id')->get('seting_tingkat')->row();
		$tingkat_akhir = $query->id;
		$arr = $this->db->query("SELECT id, kelas FROM kelas WHERE id_tahun=(SELECT id FROM seting_tahun_ajaran WHERE status='Y') AND id_tingkat='".$tingkat_akhir."'");
				
		if ($arr->num_rows() > 0) {
			foreach ($arr->result() as $q) {
				$w[0] = '-- Pilih Kelas --';
				$w[$q->id] = $q->kelas;
			}
			return $w;
		}
		return FALSE;
	}	

	//daftar siswa pada kelas dengan tapel aktif
	function list_siswa_perkelas($idkelas, $idtahun) {
		$idnow = $idtahun + 1;
		$arr = $this->db->query("SELECT `siswa_kelas`.`id_siswa`, `siswa`.`id`, `nama` FROM (`siswa_kelas`) JOIN `siswa` ON `siswa`.`id`=`siswa_kelas`.`id_siswa` WHERE `id_kelas` = '".$idkelas."' AND `id_tahun` = '".$idtahun."' ");
		
		if ($arr->num_rows() > 0) {						
			foreach ($arr->result() as $q) {
				$d[$q->id] = $q->nama;			
			}				
			return $d;
		}
		else
			return FALSE;
	}
	//daftar siswa yang sudah lulus ditahun ybs
	function list_siswa_sudah_lulus($tahun) {
		$this->db->select('id, nama, angkatan')
				 ->from('siswa')
				 ->where(array('angkatan' => $tahun));
		$arr = $this->db->get();
		
		if ($arr->num_rows() > 0) {					
			foreach ($arr->result() as $q) {
				$d[$q->id] = $q->nama;						
			}			
			return $d;
		}
		else
			return FALSE;
	}
	
	
	//kelas dengan tingkat sama
	function list_kelas_tujuan($kelas) {
		$tingkat = $this->get_tingkat_from_kelas($kelas);
		$arr = $this->db->query("SELECT * FROM kelas WHERE id_tahun=(SELECT id FROM seting_tahun_ajaran WHERE status='Y') AND id_tingkat='".$tingkat."' ");
		
		if ($arr->num_rows() > 0) {
			$f[0] = '-- Silakan Pilih --';
			foreach ($arr->result() as $q) {
				$f[$q->id] = $q->kelas;
			}
			return $f;
		}
		else
			return FALSE;
	}
	
	function get_tingkat_from_kelas($kelas) {
		$arr = $this->db->get_where('kelas', array('id' => $kelas));
		$q = $arr->row();
		
		return $q->id_tingkat;
	}
	
	function get_tahun_ajaran() {
	
		$arr = $this->db->query("SELECT * FROM seting_tahun_ajaran WHERE id=((SELECT id FROM seting_tahun_ajaran WHERE status='Y') - 1) AND status='N'");
		
		foreach ($arr->result() as $key) {
			$d[0] = '-- Silakan Pilih --';
			$d[$key->id] = $key->tahun;
		}
		return $d;
	}
	
	function get_ta($status = 'N') {
		if ($status == 'Y') {
			$arr = $this->db->get_where('seting_tahun_ajaran', array('status' => 'Y'));
		}
		else {
			$arr = $this->db->query("SELECT * FROM seting_tahun_ajaran WHERE id=((SELECT id FROM seting_tahun_ajaran WHERE status='Y') - 1) AND status='N'");
		}
		
		if ($arr->num_rows() > 0) return $arr->row();
		return FALSE;
	}
	
}