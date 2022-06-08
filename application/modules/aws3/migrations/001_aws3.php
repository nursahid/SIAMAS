<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Aws3 extends CI_Migration
{
    public function __construct()
    {
        $this->load->dbforge();
        $this->load->database();
    }

    public function up()
    {
        // Blog
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'file' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'user_name' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'type' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => 'file'
            ],
            'created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('files', true);

        $data = [
            ['sort' => 17, 'level' => 2, 'parent_id' => 40, 'icon' => 'amazon', 'label' => 'AWS S3', 'link' => 'aws3/demo', 'id' => '', 'id_menu_type' => 1]
        ];
        $this->db->insert_batch('menu', $data);
    }

    public function down()
    {
        $this->dbforge->drop_table('blog');
    }
}

/* End of file blog.php */
/* Location: ./application/modules/blog/migrations/001_Blog.php */
