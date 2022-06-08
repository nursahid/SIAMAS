<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Signup_model extends CI_Model 
{
	/*	Function is used to add new user.	*/
	public function addUser($data)
	{
		$this->db->insert('login', $data);
		$userId = $this->db->insert_id();
		if($this->db->insert_id())
		{
			return $userId;
		}
		else
		{
			return false;
		}
	}
	public function userAlreadyExists($loginId,$loginType)
	{
		$query = $this->db->get_where('login', array('loginId' => $loginId,'loginType' => $loginType));
		return $query->row();
	}
	
}
/* End of file Signup_model.php */
/* Location: ./application/models/Signup_model.php */ 
?>