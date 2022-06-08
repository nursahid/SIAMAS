<?php

defined('BASEPATH') or exit('No direct script access allowed');

class BlogModel extends MY_Model
{
    public $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = 'blog';
    }
}

/* End of file blogModel.php */
/* Location: ./application/models/blogModel.php */
