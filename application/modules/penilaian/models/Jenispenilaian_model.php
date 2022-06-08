<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Jenispenilaian_model extends Base_Model {

    public $_table = 'nilai_jenis_penilaian';

    public function __construct()
    {
        parent::__construct();
    }
	
	//dropdown mapel by id_pegawai
	function get_dropdown() {
		// get data 
		$this->db->select('*');
		$this->db->from($this->_table);
		$query = $this->db->get();
				
		$data = array();
		$data[0] = '-- Pilih Jenis Penilaian --';		
		foreach ($query->result() as $q) {
			$data[$q->id] = $q->jenis_penilaian;
		}		
		return $data;
	}
}
/* End of file Jenispenilaian_model.php */
/* Location: ./modules/penilaian/models/Jenispenilaian_model.php */