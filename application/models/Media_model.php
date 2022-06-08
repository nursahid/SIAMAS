<?php

class Media_model extends Base_Model {
	
	public $_table = 'media';
	
	//protected $where = array('status' => 'active');
	//protected $order_by = array('pos', 'ASC');
	protected $upload_fields = array('file' => './assets/uploads/image/');
}