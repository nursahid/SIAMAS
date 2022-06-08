<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Jurusan_model extends Base_Model {

    public $_table = 'ref_jurusan';

    public function __construct() {
        parent::__construct();
    }
	
	function dropdown_jurusan() {
		$arr = $this->db->query("SELECT * FROM ref_jurusan");
				
		if ($arr->num_rows() > 0) {
			foreach ($arr->result() as $q) {
				$w[0] = '-- Pilih Jurusan --';
				$w[$q->id] = $q->jurusan;
			}
			return $w;
		}
		return FALSE;
	}
	
}
/* End of file Jurusan_model.php */
/* Location: ./modules/seting/models/Jurusan_model.php */