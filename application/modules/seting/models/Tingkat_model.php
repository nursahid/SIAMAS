<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tingkat_model extends Base_Model {

    public $_table = 'seting_tingkat';

	//dropdown mapel
	function dropdown_tingkat() {
		$arr = $this->db->query("SELECT * FROM seting_tingkat");
				
		if ($arr->num_rows() > 0) {
			foreach ($arr->result() as $q) {
				$w[0] = '-- Pilih Tingkat --';
				$w[$q->id] = 'Tingkat '.$q->tingkat;
			}
			return $w;
		}
		return FALSE;
	}
	
}
/* End of file Tingkat_model.php */
/* Location: ./modules/seting/models/Tingkat_model.php */