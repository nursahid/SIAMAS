<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kenaikankelas_model extends Base_Model {
	
	public $_table = 'kelas';
	
	function __construct(){
        parent::__construct();
    }
	
	function list_siswa_perkelas($idkelas, $idtahun) {
		$idnow = $idtahun + 1;
		$arr = $this->db->query("SELECT `siswa_kelas`.`id_siswa`, `siswa`.`id`, `nama` FROM (`siswa_kelas`) JOIN `siswa` ON `siswa`.`id`=`siswa_kelas`.`id_siswa` WHERE `id_kelas` = '".$idkelas."' AND `id_tahun` = '".$idtahun."' AND `siswa_kelas`.`id_siswa` NOT IN (SELECT `id_siswa` FROM siswa_kelas WHERE id_tahun='".$idnow."')");
		
		if ($arr->num_rows() > 0) {						
			foreach ($arr->result() as $q) {
				$d[$q->id] = $q->nama;			
			}				
			return $d;
		}
		else
			return FALSE;
	}
	
	function list_siswa_sudah_naik_kelas($idkelas, $idtahun) {
		$this->db->select('siswa_kelas.id_siswa, siswa.id, nama')
				 ->from('siswa_kelas')
				 ->join('siswa', 'siswa.id=siswa_kelas.id_siswa')
				 ->where(array('id_kelas' => $idkelas, 'id_tahun' => $idtahun));
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
	
	function list_kelas_tujuan($kelas) {
		$tingkat = $this->get_tingkat_from_kelas($kelas);
		$arr = $this->db->query("SELECT * FROM kelas WHERE id_tahun=(SELECT id FROM seting_tahun_ajaran WHERE status='Y') AND id_tingkat='".$tingkat."' + 1");
		
		if ($arr->num_rows() > 0) {
			$f[0] = '-- Pilih Kelas --';
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
	
	function get_data_kenaikankelas() {
		//tak tambahin 4 dan 5 buat kelas 8 dan 9
		$arr = $this->db->query("SELECT * FROM kelas WHERE id_tahun=((SELECT id FROM seting_tahun_ajaran WHERE status='Y') - 1) AND (id_tingkat='1' OR id_tingkat='2' OR id_tingkat='4' OR id_tingkat='5')");
		
		$data = array();
		$data[0] = '-- Silakan Pilih --';
		
		foreach ($arr->result() as $q) {
			$data[$q->id] = $q->kelas;
		}
		
		return $data;
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