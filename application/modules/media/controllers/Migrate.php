<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migrate extends MX_Controller
{
    const MODULE = 'media';

    public function index($version = 0)
    {
        $this->load->library('migration');

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
}

/* End of file migrate.php */
/* Location: ./application/controllers/migrate.php */

