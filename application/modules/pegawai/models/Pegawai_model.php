<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pegawai_model extends Base_Model {
	
	public $_table = 'pegawai';
	
    public function __construct()
    {
        parent::__construct();
    }	

	//hash password
    public function hash($password) {
        $this->load->library('PasswordHash', array('iteration_count_log2' => 8, 'portable_hashes' => FALSE));
        
        // hash password
        return $this->passwordhash->HashPassword($password);
    }
	//get tgl_lahir
	public function get_tgllahir($id_pegawai) {
        $this->db->select('tgl_lahir');
		$this->db->where('id', $id_pegawai);
		$result = $this->db->get('pegawai')->row();
        if($result) {
            $ret= $result->tgl_lahir;
        } else {
			$ret= "Nihil";
		}     
        return $ret;	
	}
	//get nama kelas
    public function get_kelas($id_siswa){
        $this->db->select('kelas.nama as namakelas');
		$this->db->where('id_siswa', $id_siswa);
		$this->db->join('siswa', 'siswa.id = siswa_kelas.id_siswa');
		$this->db->join('kelas', 'kelas.id = siswa_kelas.id_kelas');
		$result = $this->db->get('siswa_kelas')->row();
        if($result) {
            $ret= $result->kelas;
        } else {
			$ret= "Nihil";
		}     
        return $ret;
	}
	//PEGAWAI FOR DROPDOWN
	function get_pegawai_semua() {
		//$arr = $this->db->query('SELECT `s`.`id`, `s`.`nama` FROM (`pegawai` s)');
		$arr = $this->db->query("SELECT * FROM pegawai");
		if ($arr->num_rows() > 0) {
			$d[] = '-- Silakan Pilih Pegawai --';
			foreach ($arr->result() as $q) {
				$d[$q->id] = $q->nama;
			}
			return $d;
		}
		return FALSE;
	}
	// pegawai tanpa mapel
	function get_pegawai_no_mapel() {
		$arr = $this->db->query('SELECT `s`.`id`, `s`.`nama` FROM (`pegawai` s) LEFT JOIN `pegawai_mapel` k ON `k`.`id_pegawai`=`s`.`id` WHERE `k`.`id_mapel` IS NULL');
		//$arr = $this->db->query("SELECT * FROM pegawai LEFT JOIN pegawai_mapel ON pegawai_mapel.id_pegawai = pegawai.id WHERE pegawai_mapel.id_mapel IS NULL");
		if ($arr->num_rows() > 0) {
			foreach ($arr->result() as $q) {
				$d[$q->id] = $q->nama;
			}
			return $d;
		}
		return FALSE;
	}
	// pegawai tujuan
	function list_mapel_tujuan($id_mapel) {
		//$tingkat = $this->get_tingkat_from_kelas($kelas);
		//$arr = $this->db->query("SELECT * FROM pegawai WHERE id_tahun=(SELECT id FROM seting_tahun_ajaran WHERE status='Y') AND id_tingkat='".$tingkat."' + 1");
		$arr = $this->db->query('SELECT `s`.`id`, `s`.`nama` FROM (`pegawai` s) LEFT JOIN `pegawai_mapel` k ON `k`.`id_pegawai`=`s`.`id` WHERE `k`.`id_mapel`="'.$id_mapel.'"');
		if ($arr->num_rows() > 0) {
			$f[0] = '-- Silakan Pilih --';
			foreach ($arr->result() as $q) {
				$f[$q->id] = $q->nama;
			}
			return $f;
		}
		else
			return FALSE;
	}
	// pegawai sudah punya mapel
	function list_pegawai_sudah_punya_mapel($idmapel) {
		$this->db->select('pegawai_mapel.id_pegawai, pegawai.id, nama')->from('pegawai_mapel')->join('pegawai', 'pegawai.id=pegawai_mapel.id_pegawai')->where(array('id_mapel' => $idmapel));
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
	// pegawai per mapel
	function list_pegawai_permapel($idmapel) {
		//$idnow = $idtahun + 1;
		$arr = $this->db->query("SELECT `pegawai_mapel`.`id_pegawai`, `pegawai`.`id`, `nama` FROM (`pegawai_mapel`) JOIN `pegawai` ON `pegawai`.`id`=`pegawai_mapel`.`id_pegawai` WHERE `pegawai_mapel`.`id_mapel` = '".$idmapel."' AND `pegawai_mapel`.`id_pegawai` ");
		
		if ($arr->num_rows() > 0) {						
			foreach ($arr->result() as $q) {
				$d[$q->id] = $q->nama;			
			}				
			return $d;
		}
		else
			return FALSE;
	}
	//data mapel
	function get_data_mapel() {
		$arr = $this->db->query("SELECT * FROM seting_mapel");
				
		$data = array();
		$data[0] = '-- Silakan Pilih --';
		
		foreach ($arr->result() as $q) {
			$data[$q->id] = $q->nama;
		}
		
		return $data;
	}
	
	
} /* End of file Pegawai_model.php */
/* Location: ./application/models/Pegawai_model.php */