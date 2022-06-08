<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kelas_model extends Base_Model {
	
	public $_table = 'kelas';

    public function __construct()
    {
        parent::__construct();
    }
	
    public function retrieve_all($parent_id = null, $array_where = array()) {
        $this->db->where('id_tingkat', $parent_id);

        foreach ($array_where as $key => $value) {
            $this->db->where($key, $value);
        }

        $this->db->order_by('id', 'ASC');
        $result = $this->db->get('kelas');
        return $result->result_array();
    }
	
 	function dropdown_kelas($id_kelas) {	
		$this->db->select('id, kelas');
		$this->db->from('kelas');
		$this->db->where('id', $id_kelas);
		$this->db->where('id_tahun', $this->tahunajaran_aktif());
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $q) {
				$w[0] = '-- Pilih Kelas --';
				$w[$q->id] = $q->kelas;
			}
			return $w;
		}
		return FALSE;
	}	
	// siswa tanpa kelas
	function get_siswa_no_class() {
		$arr = $this->db->query('SELECT `s`.`id`, `s`.`nama` FROM (`siswa` s) LEFT JOIN `siswa_kelas` k ON `k`.`id_siswa`=`s`.`id` WHERE `s`.`status`= "Aktif" AND `k`.`id_kelas` IS NULL');
		//$arr = $this->db->query("SELECT * FROM siswa LEFT JOIN siswa_kelas ON siswa_kelas.id_siswa = siswa.id WHERE siswa_kelas.id_kelas IS NULL");
		if ($arr->num_rows() > 0) {
			foreach ($arr->result() as $q) {
				$d[$q->id] = $q->nama;
			}
			return $d;
		}
		return FALSE;
	}
	//data siswa per kelas	
	function get_data_siswa_perkelas($id_kelas) {	
		$this->db->select('r.id, k.id_siswa, s.nama, nis')->from('siswa_kelas k')->join('siswa s', 'k.id_siswa=s.id')->join('kelas r', 'k.id_kelas=r.id')->where('r.id', $id_kelas);;
		$arr = $this->db->get();
		
		if ($arr->num_rows() > 0) return $arr;
		return FALSE;		
	}
	
	//data kelas
	function get_data_kelas() {
		$arr = $this->db->query("SELECT * FROM kelas WHERE id_tahun=(SELECT id FROM seting_tahun_ajaran WHERE status='Y')");
		//$arr = $this->db->query("SELECT * FROM kelas WHERE aktif='Ya'");
				
		$data = array();
		$data[0] = '-- Silakan Pilih --';
		
		foreach ($arr->result() as $q) {
			$data[$q->id] = $q->kelas;
		}
		
		return $data;
	}
	
	//data tingkat
	function get_data_tingkat() {
		$arr = $this->db->query("SELECT * FROM seting_tingkat ORDER BY id");
		//$arr = $this->db->query("SELECT * FROM kelas WHERE aktif='Ya'");
				
		$data = array();
		$data[0] = '-- Pilih Tingkat --';
		
		foreach ($arr->result() as $q) {
			$data[$q->id] = "Tingkat ".$q->tingkat;
		}
		
		return $data;
	}
	
	//data tingkat by pegawai
	function get_data_tingkat_by_pegawai($id_pegawai) {
		// get data 
		$this->db->select('pegawai_mapel.id_tingkat, seting_tingkat.id, tingkat');
		$this->db->from('pegawai_mapel');
		$this->db->join('seting_tingkat', 'seting_tingkat.id = pegawai_mapel.id_tingkat');
		$this->db->where('id_pegawai', $id_guru);
		$this->db->where('id_tahun', $this->tahunajaran_aktif());
		$query = $this->db->get();
				
		$data = array();
		$data[0] = '-- Pilih Tingkat --';		
		foreach ($query->result() as $q) {
			$data[$q->id_tingkat] = "Tingkat ".$q->tingkat;
		}		
		return $data;
	}
	//---- Data Nama Kelas by tingkat ----------
	function get_kelas_by_siswa($id_siswa) {	
		$this->db->select('kelas.id, kelas.kelas');
		$this->db->from('siswa_kelas');
		$this->db->join('kelas', 'kelas.id = siswa_kelas.id_kelas');
		$this->db->where('id_siswa', $id_siswa);
		$this->db->where('siswa_kelas.id_tahun', $this->tahunajaran_aktif());
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			$q = $query->row();
			return $q->kelas;
		}
		return "Belum memiliki kelas";
	}
	
	//---- Data Kelas by tingkat ----------
	function get_kelas_by_tingkat($id_tingkat) {	
		$this->db->select('id, kelas');
		$this->db->from('kelas');
		$this->db->where('id_tingkat', $id_tingkat);
		$this->db->where('id_tahun', $this->tahunajaran_aktif());
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $q) {
				$w[0] = '-- Pilih Kelas --';
				$w[$q->id] = $q->kelas;
			}
			return $w;
		}
		return FALSE;
	}
	//---- Data Kelas by Pegawai ----------
	function get_kelas_bypegawai($id_pegawai) {	
		$this->db->select('pegawai_mapel.id_kelas as id, kelas.kelas');
		$this->db->from('pegawai_mapel');
		$this->db->join('kelas', 'kelas.id = pegawai_mapel.id_kelas');
		$this->db->where('id_pegawai', $id_pegawai);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $q) {
				$w[0] = '-- Pilih Kelas --';
				$w[$q->id] = $q->kelas;
			}
			return $w;
		}
		return FALSE;
	}
	//dropdown mapel by id_pegawai
	function get_dropdown_mapel_by_guru($id_guru) {
		// get data 
		$this->db->select('pegawai_mapel.id_mapel, seting_mapel.id, seting_mapel.mapel');
		$this->db->from('pegawai_mapel');
		$this->db->join('seting_mapel', 'seting_mapel.id = pegawai_mapel.id_mapel');
		$this->db->where('pegawai_mapel.id_pegawai', $id_guru);
		$this->db->where('pegawai_mapel.id_tahun', $this->tahunajaran_aktif());
		$query = $this->db->get();
				
		$data = array();
		$data[0] = '-- Pilih Pelajaran --';		
		foreach ($query->result() as $q) {
			$data[$q->id_mapel] = $q->mapel;
		}		
		return $data;
	}
	
	//mapel yang dikelas
	function get_mapel_kelas($tingkat) {
		$arr = $this->db->query('SELECT `s`.`id`, `s`.`nama` FROM (`kelas_mapel` k) JOIN `seting_mapel` s ON `k`.`id_mapel`=`s`.`id` WHERE `k`.`id_tingkat`="'.$tingkat.'" ORDER BY id');
		if ($arr->num_rows() > 0) {
			return $arr->result();
		}
		return FALSE;
	}
	
	//mapel yang tidak dikelas
	function get_mapel_no_class($tingkat) {
		$arr = $this->db->query('SELECT `s`.`id`, `s`.`nama`, `k`.`id_mapel` FROM (`seting_mapel` s) LEFT JOIN `kelas_mapel` k ON `k`.`id_mapel`=`s`.`id` WHERE `s`.`id`  NOT IN ( SELECT id_mapel FROM kelas_mapel WHERE id_tingkat="'.$tingkat.'") ORDER BY id');
		if ($arr->num_rows() > 0) {
			foreach ($arr->result() as $q) {
				$d[$q->id] = $q->nama;
			}
			return $d;
		}
		return FALSE;
	}
	//get id _kelas by id_mapel, id_guru
	function get_idkelas_by_mapelguru($id_mapel, $id_guru) {
		$query = $this->db->query("SELECT * FROM pegawai_mapel WHERE id_mapel='".$id_mapel."' AND id_pegawai='".$id_guru."' ");
		$data  = $query->row();
		
		return $data->id_tingkat;
	}	
	//Tahun Ajaran Aktif
	function tahunajaran_aktif() {
		$arr = $this->db->query("SELECT * FROM seting_tahun_ajaran WHERE status='Y'");
		$b = $arr->row();
		
		return $b->id;
	}	
	
}