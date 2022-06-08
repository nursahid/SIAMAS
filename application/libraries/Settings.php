<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Settings
* Version: 1.0.0
* Author: Nur Sahid
* Description:  Setting for website or Webbase Application.
*/
	
class Settings {

	private $table = 'settings';
	private $id    = 'id';
	private $order = 'ASC';
	
    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
		//load database
		$this->CI->load->database();
    }
	//get data
	public function get() {
		$this->CI->db->order_by('id','ASC'); 
		//$query = $this->CI->db->get($this->CI->db->dbprefix($this->table));
		$query = $this->CI->db->get($this->table);
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
			$this->CI->db->set('value', $item);
			$this->CI->db->where('name', $key);
			$this->CI->db->update($this->db->dbprefix($this->_table));
			$updatecount++;
		}
		if ( $updatecount <= 1 ) {
			return "No Data Found";
		}
		unset($updatecount, $key, $data);
		return true;
	}
	
}
