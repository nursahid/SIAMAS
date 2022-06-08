<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Login extends CI_Migration
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
                'constraint' => 30,
                'auto_increment' => true
            ],
            'loginId' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'loginType' => [
                'type' => 'INT',
                'constraint' => 20
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'firstName' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'lastName' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'gender' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'profilePic' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'location' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'created_on' => [
                'type' => 'DATETIME'
            ]
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('login', true);
    }

    public function down()
    {
        $this->dbforge->drop_table('login');
    }
}

/* End of file media.php */
/* Location: ./application/migrations/001_Media.php */
