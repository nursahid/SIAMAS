<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Semester_model extends CI_Model {

    public $_tabel = 'seting_semester';

    // mengaktifkan semester
    public function activate($id)
    {
        $sql1 = "UPDATE ".$this->_tabel."
				 SET status = 'Y'
				 WHERE id = '$id'";

        $sql2 = "UPDATE ".$this->_tabel."
				 SET status = 'N'
				 WHERE id != '$id'";

        // lakukan update dengan metode transaksi
        // memastikan 2 operasi berjalan semua
        $this->db->trans_start();
        $this->db->query($sql1);
        $this->db->query($sql2);
        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }	
	
}
/* End of file tahunajaran_model.php */
/* Location: ./application/models/tahunajaran_model.php */