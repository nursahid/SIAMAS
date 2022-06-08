<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Soal_model extends Base_Model {

	public $_table = "soal";
	
    public function __construct()
    {
        parent::__construct();
    }

	function get_soal_multiple($id_mapel, $id_soal, $limit) {
		$this->db->select('item_soal.*, soal.name, soal.test_type');
		$this->db->from('item_soal');
		$this->db->limit($limit,0);
		$this->db->join('soal', 'soal.id = item_soal.id_soal');
		$this->db->where('item_soal.test_type', 'multiple');
		$this->db->where('soal.id', $id_soal);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return FALSE;		
	}
	function get_soal_essay($id_mapel, $id_soal, $limit) {
		$this->db->select('item_soal.*, soal.name, soal.test_type');
		$this->db->from('item_soal');
		$this->db->limit($limit,0);
		$this->db->join('soal', 'soal.id = item_soal.id_soal');
		$this->db->where('item_soal.test_type', 'essay');
		$this->db->where('soal.id', $id_soal);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return FALSE;		
	}
	
	//dropdown mapel
	function dropdown_mapel() {
		$arr = $this->db->query("SELECT * FROM seting_mapel");
				
		if ($arr->num_rows() > 0) {
			foreach ($arr->result() as $q) {
				$w[0] = '-- Pilih Mata Pelajaran --';
				$w[$q->id] = $q->mapel;
			}
			return $w;
		}
		return FALSE;
	}
	//dropdown mapel by id_pegawai
	function get_dropdown_mapel_by_guru($id_guru) {
		// get data 
		$this->db->select('pegawai_mapel.id_mapel, seting_mapel.id, mapel');
		$this->db->from('pegawai_mapel');
		$this->db->join('seting_mapel', 'seting_mapel.id = pegawai_mapel.id_mapel');
		$this->db->where('id_pegawai', $id_guru);
		$this->db->where('id_tahun', $this->tahunajaran_aktif());
		$query = $this->db->get();
				
		$data = array();
		$data[0] = '-- Pilih Pelajaran --';		
		foreach ($query->result() as $q) {
			$data[$q->id_mapel] = $q->mapel;
		}		
		return $data;
	}

	//Tahun Ajaran Aktif
	function tahunajaran_aktif() {
		$arr = $this->db->query("SELECT * FROM seting_tahun_ajaran WHERE status='Y'");
		$b = $arr->row();
		
		return $b->id;
	}

	
}

/* End of file Soal_model.php */
/* Location: ./application/models/Soal_model.php */