<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Komentar_model extends Base_Model {
	
	public $_table = 'comments';
	
    public function __construct()
    {
        parent::__construct();
    }
	//komentar berita
	public function get_post_comments($id) {
		$this->db->select('cm.id, cm.comment_name, cm.url, LEFT(cm.comment_at, 10) AS created_at, cm.comments, cmn.email')
			->join('blog b', 'cm.id_blog = b.id', 'LEFT')
			->where('cm.is_active', 'Y')
			->where('cm.id_blog', $id)
			->order_by('cm.comment_at', 'DESC')
			->limit(10);
		return $this->db->get($this->_table. ' cm');
	}
	//more komentar
	public function more_comments($id_blog, $offset = 0) {
		$this->db->select('cmn.id, cmn.comment_name, cmn.url, LEFT(cmn.comment_at, 10) AS created_at, cmn.comments, cmn.email');
		$this->db->join('blog blg', 'cmn.id_blog = blg.id', 'LEFT');
		$this->db->where('cmn.is_active', 'Y');
		$this->db->where('cmn.id_blog', $id_blog);
		$this->db->order_by('cmn.comment_at', 'DESC');
		if ($offset < 0) {
			$this->db->limit(10);
		}
		if ($offset > 0) {
			$this->db->limit(10, $offset);
		}
		return $this->db->get($this->_table. ' cmn');
	}
	//Cek Nama
	function check_nama($nama){
	   $this->db->select('comment_name');
	   $this->db->where('comment_name',$nama);
	   $query =$this->db->get('comments');
	   $row = $query->row();
	   if ($query->num_rows > 0){
			 return $row->comment_name; 
	   }else{
			 return "";
	  }
	}
	//Cek Email
	//Cek Nama
	function check_email($email){
	   $this->db->select('email');
	   $this->db->where('email',$email);
	   $query =$this->db->get('comments');
	   $row = $query->row();
	   if ($query->num_rows > 0){
			 return $row->email; 
	   }else{
			 return "";
	  }
	}
	
}