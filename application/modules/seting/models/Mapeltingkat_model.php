<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mapeltingkat_model extends Base_Model {

    public $_table = 'mapel_tingkat';

    private function _get_datatables_query($tingkat, $jurusan)
    {
         
        $this->db->where(array('id_tingkat'=>$tingkat,'id_jurusan'=>$jurusan));
		$this->db->from($this->_table);
 
        $i = 0;
     
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    function get_datatables($tingkat, $jurusan)
    {
        $this->_get_datatables_query($tingkat, $jurusan);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered($tingkat, $jurusan)
    {
        $this->_get_datatables_query($tingkat, $jurusan);
        $query = $this->db->get();
        return $query->num_rows();
    }
	
    public function count_all()
    {
        $this->db->from($this->_table);
        return $this->db->count_all_results();
    }
 
    public function get_by_id($id)
    {
        $this->db->from($this->_table);
        $this->db->where('id',$id);
        $query = $this->db->get();
 
        return $query->row();
    }
    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
 
    public function update($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }
 
    public function delete_by_id($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
    }

	
}
/* End of file Kelasmapel_model.php */
/* Location: ./application/seting/models/Kelasmapel_model.php */