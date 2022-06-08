<?php

defined('BASEPATH') or exit('No direct script access allowed');

class CategoryModel extends MY_Model
{
    public $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = 'category';
    }
}

/* End of file categoryModel.php */
/* Location: ./application/models/categoryModel.php */
