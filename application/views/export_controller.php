<?php

echo '<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * ' . ucfirst($table->table_name) . ' Controller.
 */
class ' . ucfirst($table->table_name) . ' extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "' . $table->subject . '";
    }

    /**
     * Index
     */
    public function index()
    {
    }

    /**
     * CRUD
     */
' . $method . '
}

/* End of file example.php */
/* Location: ./application/modules/' . $table->table_name . '/controllers/' . ucfirst($table->table_name) . '.php */';