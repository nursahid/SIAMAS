<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Media extends CI_Migration
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
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'file' => [
                'type' => 'TEXT'
            ],
            'ext' => [
                'type' => 'VARCHAR',
                'constraint' => '50'
            ],
            'id_user' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'uploaded_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('media', true);

        $data = [
            ['sort' => 12, 'level' => 2, 'parent_id' => 40, 'icon' => 'image', 'label' => 'Media', 'link' => 'media/index', 'id' => '', 'id_menu_type' => 1]
        ];
        $this->db->insert_batch('menu', $data);
    }

    public function down()
    {
        $this->dbforge->drop_table('media');
    }
}

/* End of file media.php */
/* Location: ./application/migrations/001_Media.php */
