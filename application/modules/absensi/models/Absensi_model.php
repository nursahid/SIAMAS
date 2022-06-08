<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Absensi_model extends Base_Model {

	public $_table = 'absensi_mapel';
	
    public function __construct() {
        parent::__construct();
    }
	
	//---- Detail Absensi ------------
	function get_detail_absensi($id) {
		$arr = $this->db->get_where('absensi_mapel', array('id' => $id));
		
		if ($arr->num_rows() > 0) return $arr->row();
		return FALSE;
	}
	//get absensi per siswa by NIS, tgl awal dan tgl akhir
	function get_absensi_persiswa($nis,$date_from,$date_to) {
		$tahun 	  = $this->tahunajaran_aktif();
		$semester = $this->semester_aktif();
		//$query = $this->db->get_where('absensi', array('nis'=>$nis, 'tahun'=>$tahun,'smt'=>$semester));
		$this->db->select('*');
		$this->db->from('absensi_mapel');
		$this->db->where('nis',$nis);
		$this->db->where('tahun',$tahun);
		$this->db->where('smt',$semester);
		//$this->db->where("tanggal BETWEEN '$date_from' AND '$date_to'");
		$this->db->where("tanggal >= CAST('$date_from' AS DATE) AND tanggal <= CAST('$date_to' AS DATE)");
		$query = $this->db->get();
		//$query = $this->db->query("SELECT * FROM absensi WHERE nis=$nis AND tahun=$tahun AND smt=$semester AND tanggal BETWEEN $date_from AND $date_to");
		
		if ($query->num_rows() > 0) return $query->result();
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
	//---- Data Kelas by Pegawai ----------
	//menampilkan semua daftar kelas
	function get_kelas_by_pegawai($id_pegawai) {	
		$this->db->select('pegawai_mapel.id_tingkat, kelas.id, kelas.kelas, kelas.id_tahun');
		$this->db->from('pegawai_mapel');
		$this->db->join('kelas', 'kelas.id_tingkat = pegawai_mapel.id_tingkat');
		$this->db->where('id_pegawai', $id_pegawai);
		$this->db->where('kelas.id_tahun', $this->tahunajaran_aktif());
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
	// --------- Data Siswa Per Kelas -----
	function get_data_siswa_perkelas($id_kelas) {	
		$this->db->select('r.id as idkelas, s.nis, s.nama, s.hp_ortu')->from('siswa_kelas k')->join('siswa s', 'k.id_siswa=s.id')->join('kelas r', 'k.id_kelas=r.id')->where('r.id', $id_kelas);
		$arr = $this->db->get();

		if ($arr->num_rows() > 0) return $arr;
		return FALSE;		
	}
	
	function lookup($key) {
		$tahun = $this->tahunajaran_aktif();
		
		$this->db->select('s.id, s.nis, s.nama, s.hp_ortu, r.kelas')->from('siswa s')->join('siswa_kelas k', 's.id=k.id_siswa')->join('kelas r', 'k.id_kelas=r.id')->where('k.id_tahun', $tahun)->like('s.nis', $key, 'after');
		$arr = $this->db->get();
		return $arr->result();
	}
	//get nama mata pelajaran
	public function get_nama_mapel($id_mapel) {
		//join tabel siswa_kelas
		$this->db->select('*');
		$this->db->from('seting_mapel');
		$this->db->where('id',$id_mapel);
		$data = $this->db->get()->row();

		return $data->mapel;
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
	
	//---- Data TAPEL dan SEMESTER Aktif ------------
	function tahunajaran_aktif() {
		$arr = $this->db->query("SELECT * FROM seting_tahun_ajaran WHERE status='Y'");
		$b = $arr->row();
		
		return $b->id;
	}
	
	function semester_aktif() {
		$arr = $this->db->get_where('seting_semester', array('status' => 'Y'));
		$sem = $arr->row();
		
		return $sem->id;
	}
}