<?php

defined('BASEPATH') or exit('No direct script access allowed');

class MediaModel extends MY_Model
{
    public $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = 'media';
    }
}

/* End of file mediaModel.php */
/* Location: ./application/models/mediaModel.php */
