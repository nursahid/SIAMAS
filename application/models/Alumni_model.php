<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Alumni_model extends Base_model{
	
	public $_table = "siswa";
	
    public function __construct()
    {
        parent::__construct();
    }

	public function more_alumni($offset = 0) {
		$this->db->select("id, nis, nisn, nik, nama, IF(kelamin = 'L', 'Laki-laki', 'Perempuan') AS kelamin
			, tempat_lahir, LEFT(tgl_daftar, 4) AS tgl_daftar, LEFT(angkatan, 4) AS angkatan, tgl_lahir, foto");

		$this->db->where('status', 'Alumni');
		if ($offset < 0) {
			$this->db->limit(20);
		}
		if ($offset > 0) {
			$this->db->limit(20, $offset);
		}
		$this->db->order_by('angkatan', 'ASC');
		$this->db->order_by('nama', 'ASC');
		return $this->db->get($this->_table);
	}	

}