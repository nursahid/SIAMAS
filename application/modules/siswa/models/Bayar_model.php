<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Bayar_model extends Base_Model
{
	public $_table = 'pembayaran';

    public function __construct()
    {
        parent::__construct();
    }

	//GET DATA PEMBAYARAN
	public function get_pembayaran($id_siswa) {
		$tahun 		= $this->get_tahun_ajaran();
		$semester 	= $this->get_semester();
		//jika ID SISWA tidak ditemukan
		if($id_siswa == NULL ) {
			$hasil = 'Nihil';
		} else {
			//join tabel siswa
			$this->db->select('pembayaran.*, pembayaran_jenis.nama_jenispembayaran');
			$this->db->from('pembayaran');
			$this->db->join('siswa', 'siswa.id = pembayaran.id_siswa');
			$this->db->join('pembayaran_jenis', 'pembayaran_jenis.id = pembayaran.id_jnspembayaran');
			$this->db->where('pembayaran.id_tahun',$tahun);
			$this->db->where('pembayaran.id_semester',$semester);
			$this->db->where('pembayaran.id_siswa',$id_siswa);
			
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
	
	//--- TOTAL PEMBAYARAN
    function total_pembayaran($id_siswa) {
		$tahun 		= $this->get_tahun_ajaran();
		$semester 	= $this->get_semester();
		
		$this->db->select('pembayaran.*, SUM(pembayaran.nilai) AS total_pembayaran');
		$this->db->from('pembayaran');
		$this->db->where('id_siswa', $id_siswa);
		$this->db->where('id_tahun',$tahun);
		$this->db->where('id_semester',$semester);
		$this->db->group_by('id_pembayaran');
		$query = $this->db->get();
		if($query->num_rows() >= 1) {
			$hasil = $query->row()->total_pembayaran;
		} else {
			$hasil = "0,00";
		}
		return $hasil;
	}
	
	//--- TOTAL PENILAIAN
    function total_penilaian($id_siswa) {
		$tahun 		= $this->get_tahun_ajaran();
		$semester 	= $this->get_semester();
		
		$this->db->select('*');
		$this->db->from('nilai_penilaian');
		$this->db->where('id_siswa', $id_siswa);
		$this->db->where('id_tahun',$tahun);
		$this->db->where('id_semester',$semester);
		$query = $this->db->get();
		if($query->num_rows() >= 1) {
			$hasil = $query->num_rows();
		} else {
			$hasil = "0,00";
		}
		return $hasil;
	}
	
	//-=====Get tahun dan semester aktif
	function get_tahun_ajaran() {
		$arr = $this->db->query("SELECT * FROM seting_tahun_ajaran WHERE status='Y'");
		$b = $arr->row();
		
		return $b->id;
	}

	function get_semester() {
		$this->db->select('id')->from('seting_semester')->where('status', 'Y');
		$arr = $this->db->get();
		$b = $arr->row();
		
		return $b->id;
	}
	
}
/* End of file Members_model.php */
/* Location: ./application/example/models/Members_model.php */
