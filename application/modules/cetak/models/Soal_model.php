<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Soal_model extends CI_Model {

	
    public function __construct()
    {
        parent::__construct();
    }
	//cari secara random
	function get_soal_multiple($id_mapel, $id_kuis, $limit) {
		$this->db->select('kuis_soal.*, kuis.name, kuis.test_type');
		$this->db->from('kuis_soal');
		$this->db->limit($limit,1);
		$this->db->join('kuis', 'kuis.id = kuis_soal.id_kuis');
		$this->db->where('kuis.test_type', 'multiple');
		$this->db->where('kuis.id', $id_kuis);
		$this->db->order_by('RAND()');
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return FALSE;		
	}
	//cari secara random
	function get_soal_essay($id_mapel, $id_kuis, $limit) {
		$this->db->select('kuis_essay.*, kuis.name, kuis.test_type');
		$this->db->from('kuis_essay');
		$this->db->limit($limit,1);
		$this->db->join('kuis', 'kuis.id = kuis_essay.id_kuis');
		$this->db->where('kuis.test_type', 'essay');
		$this->db->where('kuis.id', $id_kuis);
		$this->db->order_by('RAND()');
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return FALSE;		
	}
	
}

/* End of file Soal_model.php */
/* Location: ./application/models/Soal_model.php */