<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Absensi_model extends Base_Model
{
	public $_table = 'absensi';

    public function __construct()
    {
        parent::__construct();
    }

	//GET DATA ABSENSI
	public function get_absensi($nis) {
		$tahun 		= $this->get_tahun_ajaran();
		$semester 	= $this->get_semester();
		//jika NIS tidak ditemukan
		if($nis==NULL ) {
			$hasil = 'Nihil';
		} else {
			//join tabel siswa
			$this->db->select('*');
			$this->db->from('absensi');
			//$this->db->join('siswa', 'siswa.nis = absensi.nis');
			$this->db->where('absensi.tahun',$tahun);
			$this->db->where('absensi.smt',$semester);
			$this->db->where('absensi.nis',$nis);
			$query = $this->db->get();
			//handel jika tidak ditemukan data
			if($query->num_rows() >= 1) {
				$hasil = $query->result();
			} else {
				$hasil = "tidak ditemukan data";
			}					
		}
		return $hasil;
	}
	public function get_absensi_mapel($nis) {
		$tahun 		= $this->get_tahun_ajaran();
		$semester 	= $this->get_semester();
		//jika NIS tidak ditemukan
		if($nis==NULL ) {
			$hasil = 'Nihil';
		} else {
			//join tabel siswa
			$this->db->select('*');
			$this->db->from('absensi_mapel');
			//$this->db->join('siswa', 'siswa.nis = absensi.nis');
			$this->db->where('tahun',$tahun);
			$this->db->where('smt',$semester);
			$this->db->where('nis',$nis);
			$query = $this->db->get();
			//handel jika tidak ditemukan data
			if($query->num_rows() >= 1) {
				$hasil = $query->result();
			} else {
				$hasil = "tidak ditemukan data";
			}					
		}
		return $hasil;
	}	
	//---- Data TAPEL dan SEMESTER Aktif ------------
	function get_tahun_ajaran() {
		$arr = $this->db->query("SELECT * FROM seting_tahun_ajaran WHERE status='Y'");
		$b = $arr->row();
		
		return $b->id;
	}

	function get_semester() {
		$arr = $this->db->query("SELECT * FROM seting_semester WHERE status='Y'");
		$b = $arr->row();
		
		return $b->id;
	}
	
}
/* End of file Absensi_model.php */
/* Location: ./application/siswa/models/Absensi_model.php */
