<?php
/**
 * Library Model
 * 
 * @package    CodeIgniter AWS S3 Integration Library
 * @author     scriptigniter <scriptigniter@gmail.com>
 * @link       http://www.scriptigniter.com/cis3demo/
 */

class Cis3integration_model extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}
	function SaveForm($form_data)
	{
		$this->db->insert('files', $form_data);
		if ($this->db->affected_rows() == '1')
		{
			return TRUE;
		}
		return FALSE;
	}
	
	function delete_file($file_id)
	{
		$result = $this->db->get_where('files',array('id'=>$file_id));
		if($result->num_rows())
		{
			$row = $result->row();
			if($row->type=="file")
			{
				$file_path = "uploads/".$row->file;
			}
			else
			{
				$file_path = "user_photos/".$row->file;
			}
			
			//Delete the file from S3 bucket
			if($this->cis3integration_lib->delete_s3_object($file_path))
			{
				//File deleted so now delete the entry from database too
				$this->db->delete('files',array('id'=>$file_id));
				return true;
			}
		}
		
		return false;
	}
}
?>