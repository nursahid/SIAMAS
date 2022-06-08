<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Database_manager extends CI_Migration
{
    public function __construct()
    {
        $this->load->dbforge();
        $this->load->database();
    }

    public function up()
    {
        $data = [
            ['sort' => 3, 'level' => 2, 'parent_id' => 1, 'icon' => 'database', 'label' => 'Database manager', 'link' => 'myigniter/database', 'id' => '', 'id_menu_type' => 1]
        ];
        $this->db->insert_batch('menu', $data);

        $data = [
            ['id_groups' => 1, 'id_menu' => $this->db->insert_id()], 
        ];
        $this->db->insert_batch('groups_menu', $data);
    }

    public function down()
    {
        return true;
    }
}

/* End of file database_manager.php */
/* Location: ./application/migrations/database_manager.php */
