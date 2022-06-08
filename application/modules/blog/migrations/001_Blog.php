<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Blog extends CI_Migration
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
            'path' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'content' => [
                'type' => 'TEXT'
            ],
            'id_user' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('blog', true);

        // Category
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('category', true);
        
        // Categories blogs
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'id_category' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'id_blog' => [
                'type' => 'INT',
                'constraint' => 11,
            ]
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('categories_blogs', true);

        $data = [
            ['sort' => 15, 'level' => 2, 'parent_id' => 40, 'icon' => 'file-text-o', 'label' => 'Blog', 'link' => 'blog/manage', 'id' => '', 'id_menu_type' => 1],
            ['sort' => 16, 'level' => 2, 'parent_id' => 40, 'icon' => 'tag', 'label' => 'Category', 'link' => 'blog/category', 'id' => '', 'id_menu_type' => 1],
            ['sort' => 4, 'level' => 1, 'parent_id' => 0, 'icon' => '', 'label' => 'Blog', 'link' => 'blog', 'id' => '', 'id_menu_type' => 2]
        ];
        $this->db->insert_batch('menu', $data);
    }

    public function down()
    {
        $this->dbforge->drop_table('blog');
        $this->dbforge->drop_table('category');
        $this->dbforge->drop_table('categories_blogs');
    }
}

/* End of file blog.php */
/* Location: ./application/modules/blog/migrations/001_Blog.php */
