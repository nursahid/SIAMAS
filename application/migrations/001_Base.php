<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Base extends CI_Migration
{
    public function __construct()
    {
        $this->load->dbforge();
        $this->load->database();
    }

    public function up()
    {
        // Api
        $this->dbforge->add_field([
            'id_api' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '150'
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '150'
            ]
        ]);
        $this->dbforge->add_key('id_api', true);
        $this->dbforge->create_table('api', true);

        // Book
        $this->dbforge->add_field([
            'id_book' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'book_name' => [
                'type' => 'VARCHAR',
                'constraint' => '150'
            ],
            'rating' => [
                'type' => 'ENUM("1","2","3","4","5")',
            ],
            'content' => [
                'type' => 'TEXT'
            ]
        ]);
        $this->dbforge->add_key('id_book', true);
        $this->dbforge->create_table('book', true);

        // Groups
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '20'
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => '150'
            ]
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('groups', true);

        $data = [
            ['id' => 1, 'name' => 'admin', 'description' => 'Administrator'], 
            ['id' => 2, 'name' => 'members', 'description' => 'General User']
        ];
        $this->db->insert_batch('groups', $data);

        // Groups Menu
        $this->dbforge->add_field([
            'id_groups' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'id_menu' => [
                'type' => 'INT',
                'constraint' => 11,
            ]
        ]);
        $this->dbforge->create_table('groups_menu', true);

        $data = [
            ['id_groups' => 1, 'id_menu' => 1], 
            ['id_groups' => 2, 'id_menu' => 1],
            ['id_groups' => 1, 'id_menu' => 4],
            ['id_groups' => 2, 'id_menu' => 4],
            ['id_groups' => 1, 'id_menu' => 21],
            ['id_groups' => 2, 'id_menu' => 21],
            ['id_groups' => 1, 'id_menu' => 5],
            ['id_groups' => 2, 'id_menu' => 5],
            ['id_groups' => 1, 'id_menu' => 6],
            ['id_groups' => 2, 'id_menu' => 6],
            ['id_groups' => 1, 'id_menu' => 7],
            ['id_groups' => 2, 'id_menu' => 7],
            ['id_groups' => 1, 'id_menu' => 8],
            ['id_groups' => 2, 'id_menu' => 8],
            ['id_groups' => 1, 'id_menu' => 10],
            ['id_groups' => 2, 'id_menu' => 10],
            ['id_groups' => 1, 'id_menu' => 28],
            ['id_groups' => 2, 'id_menu' => 28],
            ['id_groups' => 1, 'id_menu' => 3],
            ['id_groups' => 2, 'id_menu' => 3],
            ['id_groups' => 1, 'id_menu' => 30],
            ['id_groups' => 2, 'id_menu' => 30]
        ];
        $this->db->insert_batch('groups_menu', $data);

        // Login Attempts
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => '15',
            ],
            'login' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'time' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true
            ]
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('login_attempts', true);

        // Menu
        $this->dbforge->add_field([
            'id_menu' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'sort' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => '99'
            ],  
            'level' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'parent_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => '0'
            ],
            'icon' => [
                'type' => 'VARCHAR',
                'constraint' => '125'
            ],
            'label' => [
                'type' => 'VARCHAR',
                'constraint' => '25'
            ],
            'link' => [
                'type' => 'VARCHAR',
                'constraint' => '125'
            ],
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => '25',
                'default' => '#'
            ],
            'id_menu_type' => [
                'type' => 'INT',
                'constraint' => 11
            ]
        ]);
        $this->dbforge->add_key('id_menu', true);
        $this->dbforge->create_table('menu', true);

        $data = [
            ['id_menu' => 1, 'sort' => 0, 'level' => 1, 'parent_id' => 0, 'icon' => '', 'label' => 'MAIN NAVIGATION', 'link' => '', 'id' => '#', 'id_menu_type' => 1],
            ['id_menu' => 3, 'sort' => 1, 'level' => 2, 'parent_id' => 1, 'icon' => 'dashboard', 'label' => 'Dashboard', 'link' => 'myigniter/dashboard', 'id' => '#', 'id_menu_type' => 1],
            ['id_menu' => 4, 'sort' => 2, 'level' => 2, 'parent_id' => 1, 'icon' => 'table', 'label' => 'CRUD Generator', 'link' => 'myigniter/crud_builder', 'id' => '', 'id_menu_type' => 1],
            ['id_menu' => 5, 'sort' => 5, 'level' => 2, 'parent_id' => 1, 'icon' => 'user', 'label' => 'Users', 'link' => '#', 'id' => '', 'id_menu_type' => 1],
            ['id_menu' => 6, 'sort' => 6, 'level' => 3, 'parent_id' => 5, 'icon' => 'circle-o', 'label' => 'Users', 'link' => 'myigniter/users', 'id' => '#', 'id_menu_type' => 1],
            ['id_menu' => 7, 'sort' => 7, 'level' => 3, 'parent_id' => 5, 'icon' => 'circle-o', 'label' => 'Groups', 'link' => 'myigniter/groups', 'id' => '#', 'id_menu_type' => 1],
            ['id_menu' => 8, 'sort' => 8, 'level' => 2, 'parent_id' => 1, 'icon' => 'bars', 'label' => 'Menu', 'link' => 'myigniter/menu/side-menu', 'id' => 'navMenu', 'id_menu_type' => 1],
            ['id_menu' => 10, 'sort' => 10, 'level' => 2, 'parent_id' => 1, 'icon' => 'cloud', 'label' => 'API', 'link' => 'api/user', 'id' => '#', 'id_menu_type' => 1],
            ['id_menu' => 19, 'sort' => 0, 'level' => 1, 'parent_id' => 0, 'icon' => '', 'label' => 'Home', 'link' => '', 'id' => '', 'id_menu_type' => 2],
            ['id_menu' => 20, 'sort' => 1, 'level' => 1, 'parent_id' => 0, 'icon' => '', 'label' => 'About', 'link' => 'page/about', 'id' => '', 'id_menu_type' => 2],
            ['id_menu' => 21, 'sort' => 3, 'level' => 2, 'parent_id' => 1, 'icon' => 'file-o', 'label' => 'Page Generator', 'link' => 'myigniter/page_builder', 'id' => '', 'id_menu_type' => 1],
            ['id_menu' => 28, 'sort' => 4, 'level' => 2, 'parent_id' => 1, 'icon' => 'th', 'label' => 'Module Extensions', 'link' => 'myigniter/modules', 'id' => 'module', 'id_menu_type' => 1],
            ['id_menu' => 29, 'sort' => 4, 'level' => 1, 'parent_id' => 0, 'icon' => '', 'label' => 'Dashboard', 'link' => 'myigniter/dashboard', 'id' => '', 'id_menu_type' => 2],
            ['id_menu' => 30, 'sort' => 9, 'level' => 2, 'parent_id' => 1, 'icon' => 'book', 'label' => 'Documentation', 'link' => 'documentation/welcome', 'id' => '', 'id_menu_type' => 1],
            ['id_menu' => 31, 'sort' => 2, 'level' => 1, 'parent_id' => 0, 'icon' => '', 'label' => 'Hello World', 'link' => 'example', 'id' => '', 'id_menu_type' => 2],
            ['id_menu' => 33, 'sort' => 3, 'level' => 1, 'parent_id' => 0, 'icon' => '', 'label' => 'Get Started', 'link' => 'documentation', 'id' => '', 'id_menu_type' => 2],
            ['id_menu' => 40, 'sort' => 12, 'level' => 1, 'parent_id' => 0, 'icon' => '', 'label' => 'MODULES', 'link' => '', 'id' => '', 'id_menu_type' => 1]
        ];
        $this->db->insert_batch('menu', $data);

        // Menu Type
        $this->dbforge->add_field([
            'id_menu_type' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'type' => [
                'type' => 'VARCHAR',
                'constraint' => '125',
            ]
        ]);
        $this->dbforge->add_key('id_menu_type', true);
        $this->dbforge->create_table('menu_type', true);

        $data = [
            ['id_menu_type' => 1, 'type' => 'Side menu'],
            ['id_menu_type' => 2, 'type' => 'Top menu']
        ];
        $this->db->insert_batch('menu_type', $data);

        // Page
        $this->dbforge->add_field([
            'id_page' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => '150',
            ],
            'featured_image' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => '150',
            ],
            'template' => [
                'type' => 'VARCHAR',
                'constraint' => '125',
            ],
            'breadcrumb' => [
                'type' => 'TEXT'
            ],
            'content' => [
                'type' => 'TEXT'
            ],
            'keyword' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'description' => [
                'type' => 'TEXT'
            ],
            'view' => [
                'type' => 'VARCHAR',
                'constraint' => '150',
                'default' => 'default'
            ]
        ]);
        $this->dbforge->add_key('id_page', true);
        $this->dbforge->create_table('page', true);

        $data = [
            ['id_page' => 1, 'title' => 'About', 'featured_image' => '', 'slug' => 'about', 'template' => 'frontend', 'breadcrumb' => '[{"label":"About","link":""}]', 'content' => '<p>Lorem ipsum Aliquip exercitation incididunt in ex eiusmod velit et aliqua minim dolore dolore dolor amet eu occaecat in anim et ea voluptate proident Ut Duis fugiat do minim Ut qui cupidatat in laborum consequat Ut do adipisicing in in. asdasd</p>\r\n', 'keyword' => '', 'description' => '', 'view' => 'default'],
            ['id_page' => 2, 'title' => 'Home', 'featured_image' => '', 'slug' => 'home', 'template' => 'frontend', 'breadcrumb' => '[{"label":"Home","link":""}]', 'content' => '<p>this is custom page can be found in <span class="marker">view/page</span></p>\r\n', 'keyword' => 'myIgniter', 'description' => 'myIgniter is custom framework based Codeigniter 3 with combine Grocery CRUD,AdminLTE,Ion auth,Gulp,and Bower. myIgniter for web developer who want to speed up their projects.', 'view' => 'home'],
            ['id_page' => 3, 'title' => 'Simple Backend', 'featured_image' => '', 'slug' => 'simple-backend', 'template' => 'backend', 'breadcrumb' => '[{"label":"Simple Backend","link":""}]', 'content' => '<p>This is simple example Page Builder for backend.</p>\r\n', 'keyword' => '', 'description' => '', 'view' => 'callout']
        ];

        $this->db->insert_batch('page', $data);

        // Table
        $this->dbforge->add_field([
            'id_table' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'table_name' => [
                'type' => 'VARCHAR',
                'constraint' => '150',
            ],
            'subject' => [
                'type' => 'VARCHAR',
                'constraint' => '150',
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => '150',
            ],
            'required' => [
                'type' => 'TEXT',
            ],
            'columns' => [
                'type' => 'TEXT',
            ],
            'field' => [
                'type' => 'TEXT',
            ],
            'uploads' => [
                'type' => 'TEXT',
            ],
            'relation_1' => [
                'type' => 'TEXT',
            ],
            'action' => [
                'type' => 'TEXT',
            ],
            'breadcrumb' => [
                'type' => 'TEXT',
            ],
            'table_config' => [
                'type' => 'TEXT'
            ]
        ]);
        $this->dbforge->add_key('id_table', true);
        $this->dbforge->create_table('table', true);

        $data = [
            ['id_table' => 1, 'table_name' => 'book', 'subject' => 'Book', 'title' => 'Book', 'required' => '["book_name","rating"]', 'columns' => '["book_name","rating"]', 'field' => '', 'uploads' => '', 'relation_1' => 'null', 'action' => '["Action","Create","Read","Update","Delete"]', 'breadcrumb' => '[{"label":"Book","link":""}]']
        ];
        $this->db->insert_batch('table', $data);

        // Users
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => '15'
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => '225'
            ],
            'salt' => [
                'type' => 'VARCHAR',
                'constraint' => '225'
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'activation_code' => [
                'type' => 'VARCHAR',
                'constraint' => '40'
            ],
            'forgotten_password_code' => [
                'type' => 'VARCHAR',
                'constraint' => '40'
            ],
            'forgotten_password_time' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'remember_code' => [
                'type' => 'VARCHAR',
                'constraint' => '40'
            ],
            'created_on' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'last_login' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'active' => [
                'type' => 'INT',
                'constraint' => 1
            ],
            'full_name' => [
                'type' => 'VARCHAR',
                'constraint' => '225'
            ],
            'photo' => [
                'type' => 'VARCHAR',
                'constraint' => '225'
            ],
            'additional' => [
                'type' => 'TEXT'
            ],
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('users', true);

        $data = [
            ['id' => 1, 'ip_address' => '127.0.0.1', 'username' => 'admin', 'password' => '$2y$08$0mQjC9osSWK/7TxNskCoZu/x4mxBOyxVFeAT5lqCGFwVPKAVmW8gO', 'salt' => NULL, 'email' => 'admin@admin.com', 'activation_code' => NULL, 'forgotten_password_code' => NULL, 'forgotten_password_time' => NULL, 'remember_code' => 'gOqL46/.mhzfuNC0pSFzY.', 'created_on' => 1268889823, 'last_login' => 1466391792, 'active' => 1, 'full_name' => 'Administrator', 'photo' => 'b9d76-avatar04.png', 'additional' => NULL],
            ['id' => 2, 'ip_address' => '127.0.0.1', 'username' => 'member', 'password' => '$2y$08$0wId8k6W86c1vfsiTuQlaOWhlMCeWdUEsPEa4VFNYGy9bNxTIn0qW', 'salt' => NULL, 'email' => 'member@member.com', 'activation_code' => NULL, 'forgotten_password_code' => NULL, 'forgotten_password_time' => NULL, 'remember_code' => NULL, 'created_on' => 1441451078, 'last_login' => 1442838976, 'active' => 1, 'full_name' => 'Member', 'photo' => '', 'additional' => NULL]
        ];

        $this->db->insert_batch('users', $data);

        // Users Group
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ], 
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ], 
            'group_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ]
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('users_groups', true);

        $data = [
            ['id' => 1, 'user_id' => 2, 'group_id' => 2],
            ['id' => 2, 'user_id' => 2, 'group_id' => 1],
            ['id' => 3, 'user_id' => 1, 'group_id' => 1]
        ];
        $this->db->insert_batch('users_groups', $data);
    }

    public function down()
    {
        $this->dbforge->drop_table('api');
        $this->dbforge->drop_table('book');
        $this->dbforge->drop_table('groups');
        $this->dbforge->drop_table('groups_menu');
        $this->dbforge->drop_table('login_attempts');
        $this->dbforge->drop_table('menu');
        $this->dbforge->drop_table('menu_type');
        $this->dbforge->drop_table('page');
        $this->dbforge->drop_table('table');
        $this->dbforge->drop_table('users');
        $this->dbforge->drop_table('users_groups');
    }
}

/* End of file api.php */
/* Location: ./application/migrations/api.php */
