<?php

defined('BASEPATH') or exit('No direct script access allowed');

class BookModel extends MY_Model
{
	public $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = 'book'; // Table name
    }
}

/* End of file Book.php */
/* Location: ./application/example/models/Book.php */
