<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migrate extends MX_Controller
{
    const MODULE = 'aws3';

    public function __construct()
    {
        parent::__construct();
        $this->load->library('migration');
    }

    public function index($version = 0)
    {
        if ($this->migration->init_module($this::MODULE)) {
            if ($version == 0) {
                $migrate = $this->migration->latest();
            } else {
                $migrate = $this->migration->version($version);
            }
            if (!$migrate) {
                show_error($this->migration->error_string());
            } else {
                $this->output->set_content_type('application/json')->set_output(json_encode(['success' => true, 'message' => 'Model migrated']));
            }
        } else {
            $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'Module not valid']));
        }
    }

    public function remove()
    {
        if ($this->migration->init_module($this::MODULE)) {
            $migrate = $this->migration->version(0);
            $this->output->set_content_type('application/json')->set_output(json_encode(['success' => true, 'message' => 'Model migrated']));
        } else {
            $this->output->set_content_type('application/json')->set_output(json_encode(['success' => false, 'message' => 'Module not valid']));
        }
    }
}

/* End of file migrate.php */
/* Location: ./application/controllers/migrate.php */

