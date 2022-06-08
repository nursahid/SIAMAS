<?php

class Login_model extends CI_Model {
    
    /*
    * 
    * Create Variables for Each Column in the 'user' table
    */
    var $username;
    var $password;
    var $email;
    var $phoneNumber;
    /*
     * Constructor to initialize object
     */
    function __construct()
    {
        parent::__construct();
    }
	

    /*
     * Description : Function to check whether username and password matches with entry in the database.
     * Returns TRUE if login is successful
     * @return boolean
     * 
     */
    public function loginCheck($loginObj)
    {
        $this->db->where('username', $loginObj->username);
        $this->db->where('password',  md5($loginObj->password));
        $query = $this->db->get('user');  
        if($query->num_rows()==1)
		{
            $sessData = array("username" => $loginObj->username, "loggedIn"=>"1" );
            $this->session->set_userdata($sessData);
            return true;
        }
        else
        {
            echo "<h1 class='text-center'> Incorrect Username or Password</h1>";
            return false;
        }
    }

    public function loginCheckService($loginObj)
    {
        $this->db->where('username', $loginObj->username);
        $this->db->where('password',  md5($loginObj->password));
        $query = $this->db->get('user');
        if($query->num_rows()==1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /*
     * Description: Insert user Information
     * Returns TRUE if insertion is successful
     * @return boolean
     */
    public function saveUserInfo($registerObj)
    {
        $this->db->trans_start();//start transaction
        $this->db->insert('user',$registerObj);
        $this->db->trans_complete();//commit
        if ($this->db->trans_status() == false) 
        {
            return array("status" => false, "errMsg" => "Server error. Please retry after some time.");
        }
        return array("status" => true);
    }
	
	//update 
}
?>
