<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Logs extends CI_Model
{
    private $table = 'logs';

    /**
     * Add logs
     * @param String $action
     * @param Array $logs
     */
    public function addLogs($action, $logs)
    {
        if ($action) {
            $data = [
                'action' => $action,
                'logs' => json_encode($logs),
            ];
            if ($this->db->insert($this->table, $data)) {
                return $this->db->insert_id();
            } else {
                show_error("Can't save!", '500', 'An Error Was Encountered');
            }
        } else {
            show_error('Action logs is required!', '500', 'An Error Was Encountered');
        }
    }

    public function getLogs($id = null, $action = null, $limit = 10, $offset = 0)
    {
        if ($id) {
            $this->db->where('id', $id);
        }
        if ($action) {
            $this->db->where('action', $action);
        }
        return $this->db->get($this->table, $limit, $offset)->result();
    }
}

/* End of file logs.php */
/* Location: ./application/models/logs.php */
