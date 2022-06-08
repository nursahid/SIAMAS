<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Members_model extends Base_Model
{
	public $_table = 'siswa';

    public function __construct()
    {
        parent::__construct();
    }

	//get it
    public function get_it($where, $value = FALSE) {
        if (!$value) {
            $value = $where;
            $where = 'id';
        }        
        $user = $this->db->where($where, $value)->get($this->_table)->row_array();
        return $user;
    }
	public function update($id, $data) {
		$this->db->where("id", $id);
		$this->db->update($this->_table, $data);

		return $this->db->affected_rows();
	}
	
	public function total_rows() {
		$data = $this->db->get($this->_table);

		return $data->num_rows();
	}
	//get data jurusan/prodi
	public function get_id_jurusan($id_siswa) {
		//join tabel siswa_kelas
		$this->db->select('*');
		$this->db->from('kelas');
		$this->db->join('siswa_kelas', 'siswa_kelas.id_kelas = kelas.id');
		$this->db->where('siswa_kelas.id_siswa',$id_siswa);
		$data = $this->db->get()->row();

		return $data->id_jurusan;
	}
	public function get_jurusan($id_siswa) {
		//id_jurusan dari model diatas
		$id_jurusan = $this->get_id_jurusan($id_siswa);
		$this->db->select('*');
		$this->db->from('ref_jurusan');
		$this->db->where('id',$id_jurusan);
		$data = $this->db->get()->row();

		return $data->jurusan;
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
    function total_piutang($id_siswa) {
		$this->db->select('pembayaran.*,SUM(pembayaran.nominal) AS total_piutang');
		$this->db->from('pembayaran');
		$this->db->where('id_siswa', $id_siswa);
		$this->db->where('status','belum bayar');
		$query = $this->db->get();
		if($query->num_rows() >= 1) {
			$hasil = $query->row()->total_piutang;
		} else {
			$hasil = "0,00";
		}
		return $hasil;
	}
	//----------- PENILAIAN --------------------
	//GET DATA NILAI
	public function get_penilaian($id_siswa) {
		//join tabel nilai_penilaian
		$this->db->select('*');
		$this->db->from('nilai_penilaian');
		$this->db->join('nilai_jenis_penilaian', 'nilai_jenis_penilaian.id = nilai_penilaian.id_jnspenilaian');
		$this->db->join('seting_mapel', 'seting_mapel.id = nilai_penilaian.id_mapel');
		$this->db->where('nilai_penilaian.id_siswa',$id_siswa);
		$query = $this->db->get();
		//handel jika tidak ditemukan data
		if($query->num_rows() >= 1) {
			$hasil = $query->result();
		} else {
			$hasil = NULL;
		}

		return $hasil;
	}
	//get data JENIS PENILAIAN
	public function get_jenis_penilaian($id_siswa) {
		//join tabel nilai_penilaian
		$this->db->select('*');
		$this->db->from('nilai_jenis_penilaian');
		$this->db->join('nilai_penilaian', 'nilai_penilaian.id_jnspenilaian = nilai_jenis_penilaian.id');
		$this->db->where('nilai_penilaian.id_siswa',$id_siswa);
		$data = $this->db->get()->row();

		return $data->jenis_penilaian;
	}
	
	//==========================================
	//haspassword
    public function hash($password) {
        $this->load->library('PasswordHash', array('iteration_count_log2' => 8, 'portable_hashes' => FALSE));
        
        // hash password
        return $this->passwordhash->HashPassword($password);
    }
	//cek password
    public function check_password($password, $stored_hash) {
        $this->load->library('PasswordHash', array('iteration_count_log2' => 8, 'portable_hashes' => FALSE));
        
        // check password
        return $this->passwordhash->CheckPassword($password, $stored_hash);
    }	
	
	
}
/* End of file Members_model.php */
/* Location: ./application/siswa/models/Members_model.php */
