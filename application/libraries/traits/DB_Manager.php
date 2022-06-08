<?php

defined('BASEPATH') or exit('No direct script access allowed');

trait DB_Manager
{
    protected function drop_table($table_name = '')
    {
        $this->load->dbforge();
        $this->dbforge->drop_table($table_name, true);
    }

    protected function create_table($table_name='')
    {
        $this->load->dbforge();
        $this->dbforge->create_table($table_name, true);
    }

    protected function write_migration_file($table_name = '', $version = 1)
    {
        // load data
        $this->load->dbforge();
        $vars['table_name'] = $table_name;
        $version = str_pad($version, 3, '0', STR_PAD_LEFT);
        $vars['version'] = $version;
        $vars['file_name'] = $table_name;

        // write fields string
        $fields = $this->db->field_data($table_name);
        
        $vars['fields'] = [];
        $vars['field_name'] = [];
        
        foreach ($fields as $field) {
            $vars['fields'][] = '$fields["'.$field->name.'"]["type"] = "'.$field->type.'";';
            $vars['fields'][] = '$fields["'.$field->name.'"]["constraint"] = "'.$field->max_length.'";';
            $vars['fields'][] = '$fields["'.$field->name.'"]["default"] = "'.$field->default.'";';
            if ($field->primary_key == 1) {
                $vars['fields'][] = '$this->dbforge->add_key("'.$field->name.'");';
            }else{
                $vars['field_name'][] = $field->name;
            }
        }

        if ($this->input->post('migrate_data') == 1) {
            $vars['data'] = $this->db->get($table_name, $limit, $offset)->result_array();
        }

        $migration = $this->load->view('code_templates/migration', $vars, true);
        
        // write
        $file_name = $version . '_Add_' . $vars['file_name'] . '.php';
        $structure = './application/migrations';
        $myfile = fopen($structure.'/'.$file_name, "w");
        fwrite($myfile, $migration);
        fclose($myfile);

        return $status = ['status' => true,'table_name' => $table_name];
    }
}
