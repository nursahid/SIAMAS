<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Siswa_model extends Base_Model {
	
	public $_table = 'siswa';
	

	//Get Data Siswa by Id Siswa
	function get_data_by_id($id_siswa) {
		$this->db->select('siswa.*, ref_sekolahasal.id, namasekolah');
		$this->db->from('siswa');
		$this->db->join('ref_sekolahasal', 'ref_sekolahasal.id = siswa.asal_sekolah');
		$this->db->where('siswa.id', $id_siswa);
		$this->db->where('status', 'Aktif');
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		return FALSE;
	}

	//--- Get Kelas by Siswa
	function get_kelas_by($id_siswa) {
		$this->db->select('siswa_kelas.*, kelas.kelas');
		$this->db->from('siswa_kelas');
		$this->db->join('kelas', 'siswa_kelas.id_kelas = kelas.id');
		$this->db->where('siswa_kelas.id_siswa', $id_siswa);
		$this->db->where('siswa_kelas.id_tahun', $this->get_tapel_aktif());
		
		$arr = $this->db->get();
		
		if ($arr->num_rows() > 0) {
			$q = $arr->row();
			return $q->kelas;
		} else {
			return '<span class="badge btn-danger">Belum masuk dalam Rombel</span>';
		}
	}
	
	
	/**
	* get latest registered users
	* @return array
	*/
	function get_newest($limit = 0,$return = 'object'){
		$this->db->order_by('tgl_daftar','DESC');
		return $this->get($limit,$return);
	}

    /*
     * Insert user information
     */
    public function insert($data = array()) {
        //add created and modified data if not included
        if(!array_key_exists("tgl_daftar", $data)){
            $data['tgl_daftar'] = date("Y-m-d");
        }
        if(!array_key_exists("tgl_update", $data)){
            $data['tgl_update'] = date("Y-m-d H:i:s");
        }
        
        //insert user data to users table
        $insert = $this->db->insert($this->_table, $data);
        
        //return the status
        if($insert){
            return $this->db->insert_id();;
        }else{
            return false;
        }
    }
    /*
     * Update user information
     */
    public function update($primary_key, $data = array()) {
        //add created and modified data if not included
        if(!array_key_exists("tgl_update", $data)){
            $data['tgl_update'] = date("Y-m-d H:i:s");
        }
        
        //update user data to users table
		$this->db->where('id', $primary_key);
        $update = $this->db->update($this->_table, $data);
        
        return true;
    }
	//=======================
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
	//===========================
    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
		$this->db->or_like('nama_lengkap', $q);
		$this->db->or_like('tmp_lahir', $q);
		$this->db->or_like('tgl_lahir', $q);
		$this->db->or_like('alamat', $q);
		$this->db->or_like('posisi', $q);
		$this->db->limit($limit, $start);
        return $this->db->get($this->_table)->result();
    }
	
	function select() {
		$this->db->order_by('nama', 'ASC');
		$query = $this->db->get($this->_table);
		return $query;
	}
	//lookup	
	function lookup($key) {
		$tahun = $this->get_tapel_aktif();		
		//$this->db->select('s.id, s.nis, s.nama, r.kelas, s.no_hp, k.id_siswa, k.id_kelas')->from('siswa s')->join('siswa_kelas k', 's.id=k.id_siswa')->join('kelas r', 'k.id_kelas=r.id')->where('k.id_tahun', $tahun)->like('s.nis', $key, 'after');
		$this->db->select('*')->from('siswa');
		$arr = $this->db->get();
		return $arr->result();
	}
	//tahun ajaran aktif
	function get_tapel_aktif() {
		$arr = $this->db->query("SELECT * FROM seting_tahun_ajaran WHERE status='Y'");
		$b = $arr->row();
		
		return $b->id;
	}
	
}