<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Siswa_model extends Base_Model {

	public $_table = "siswa";
	
    public function __construct()
    {
        parent::__construct();
    }
	/**
	* get latest registered users
	* @return array
	*/
	function get_newest($limit = 0,$return = 'object'){
		$this->db->order_by('tgl_input','DESC');
		return $this->get($limit,$return);
	}

    /*
     * Insert user information
     */
    public function insert($data = array()) {
        //add created and modified data if not included
        if(!array_key_exists("tgl_input", $data)){
            $data['tgl_input'] = date("Y-m-d H:i:s");
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

	
}

/* End of file User_Model.php */
/* Location: ./application/models/User_model.php */