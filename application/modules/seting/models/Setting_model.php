<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Setting_model extends Base_Model {

    public $_table = 'settings';

	//----------------
	//get
    public function get($where, $value = FALSE) {
        if (!$value) {
            $value = $where;
            $where = 'id';
        }        
        $user = $this->db->where($where, $value)->get($this->_table)->row_array();
        return $user;
    }
 	//save settings
	public function save( $data = '' ) {
		if (!is_array($data)) {
			return "No Data Found";
		}
		$updatecount = 1;
		foreach($data as $key => $item) {
			$this->db->set('value', $item);
			$this->db->where('name', $key);
			$this->db->update($this->db->dbprefix($this->_table));
			$updatecount++;
		}
		if ( $updatecount <= 1 ) {
			return "No Data Found";
		}
		unset($updatecount, $key, $data);
		return true;
	}

}
/* End of file Setting_model.php */
/* Location: ./modules/seting/models/Setting_model.php */