<?php

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Smart model.
 */
class MY_Model extends CI_Model
{
    public $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = get_Class($this);
    }

    public function save($data, $tablename = '')
    {
        if ($tablename == '') {
            $tablename = $this->table;
        }
        $op = 'update';
        $keyExists = false;
        $fields = $this->db->field_data($tablename);

        foreach ($fields as $field) {
            if ($field->primary_key == 1) {
                $keyExists = true;
                if (isset($data[$field->name])) {
                    $this->db->where($field->name, $data[$field->name]);
                } else {
                    $op = 'insert';
                }
            }
        }
        if ($keyExists && $op == 'update') {
            $this->db->set($data);
            $this->db->update($tablename);
            if ($this->db->affected_rows() == 1) {
                return $this->db->affected_rows();
            }
        }
        $this->db->insert($tablename, $data);

        return $this->db->affected_rows();
    }

    public function search($conditions = null, $limit = 500, $offset = 0, $tablename = '')
    {
        if ($tablename == '') {
            $tablename = $this->table;
        }
        if ($conditions != null) {
            $this->db->where($conditions);
        }

        $query = $this->db->get($tablename, $limit, $offset);

        return $query->result();
    }

    public function single($conditions, $tablename = '')
    {
        if ($tablename == '') {
            $tablename = $this->table;
        }
        $this->db->where($conditions);
        
        return $this->db->get($tablename)->row();
    }

    public function insert($data, $tablename = '')
    {
        if ($tablename == '') {
            $tablename = $this->table;
        }
        $this->db->insert($tablename, $data);

        return $this->db->affected_rows();
    }

    public function update($data, $conditions, $tablename = '')
    {
        if ($tablename == '') {
            $tablename = $this->table;
        }
        $this->db->where($conditions);
        $this->db->update($tablename, $data);

        return $this->db->affected_rows();
    }

    public function delete($conditions, $tablename = '')
    {
        if ($tablename == '') {
            $tablename = $this->table;
        }
        $this->db->where($conditions);
        $this->db->delete($tablename);

        return $this->db->affected_rows();
    }

    public function count($conditions = null, $tablename = '')
    {
        if ($conditions != null) {
            $this->db->where($conditions);
        }

        if ($tablename == '') {
            $tablename = $this->table;
        }

        $this->db->select('1');
        return $this->db->get($tablename)->num_rows();
    }
}
