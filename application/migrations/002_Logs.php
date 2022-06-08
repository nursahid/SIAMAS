<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Logs extends CI_Migration
{
    public function __construct()
    {
        $this->load->dbforge();
        $this->load->database();
    }

    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'action' => [
                'type' => 'VARCHAR',
                'constraint' => '125'
            ],
            'logs' => [
                'type' => 'TEXT'
            ],
            'createdAt timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('logs', true);
    }

    public function down()
    {
        $this->dbforge->drop_table('logs');
    }
}

/* End of file logs.php */
/* Location: ./application/migrations/logs.php */
