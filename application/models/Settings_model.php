<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings_model extends CI_Model {

    public $_table 		  = 'settings';
    public $id 			  = 'id';
    public $order 		  = 'ASC';

    function __construct() {
        parent::__construct();
    }
	//get data
	public function get() {
		$this->db->order_by('id','ASC'); 
		$query = $this->db->get($this->db->dbprefix($this->_table));
		if($query->num_rows()>0) {
			foreach ($query->result() as $row) {
				$output[$row->name] = $row->value;
			}			
		} else {
			$output[''] = 'No Records';
		}
		  return $output;
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
/* End of file Settings_model.php */
/* Location: ./application/models/Settings_model.php */