<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sistem_model extends CI_Model 
{
	/*	Tahun Pelajaran Aktif*/
	function get_tapel_aktif() {
		$arr = $this->db->query("SELECT * FROM seting_tahun_ajaran WHERE status='Y'");
		$b = $arr->row();
		
		return $b->id;
	}
	/*	Semester Aktif*/
	function get_semester_aktif() {
		$arr = $this->db->query("SELECT * FROM seting_semester WHERE status='Y'");
		$sem = $arr->row();
		
		return $sem->id;
	}
	/* Ambil Nama Kelas */
	function get_nama_kelas($id) {
		$arr = $this->db->get_where('kelas', array('id' => $id));
		
		if ($arr->num_rows() > 0) {
			$q = $arr->row();
			return $q->kelas;
		} else {
			return FALSE;
		}
	}
	/* Ambil Nama Mapel */
	function get_nama_mapel($id) {
		$arr = $this->db->get_where('seting_mapel', array('id' => $id));
		
		if ($arr->num_rows() > 0) {
			$q = $arr->row();
			return $q->mapel;
		} else {
			return FALSE;
		}
	}	
	/* Ambil Nama Siswa */
	function get_nama_siswa($id) {
		$arr = $this->db->get_where('siswa', array('id' => $id));
		
		if ($arr->num_rows() > 0) return $arr->row();
		return FALSE;
	}
	/* Ambil Nama Jenis Penilaian */
	function get_nama_penilaian($id) {
		$arr = $this->db->get_where('nilai_jenis_penilaian', array('id' => $id));
		
		if ($arr->num_rows() > 0) {
			$q = $arr->row();
			return $q->jenis_penilaian;
		} else {
			return FALSE;			
		}
	}
	/* Ambil Nama Jenis Pembayaran */
	function get_nama_pembayaran($id) {
		$arr = $this->db->get_where('pembayaran_jenis', array('id' => $id));
		
		if ($arr->num_rows() > 0) {
			$q = $arr->row();
			return $q->nama_jenispembayaran;
		} else {
			return FALSE;			
		}
	}
	/* Ambil Nama Tahun Ajaran */
	function get_nama_tapel($status) {
		$arr = $this->db->get_where('seting_tahun_ajaran', array('status' => $status));
		
		if ($arr->num_rows() > 0) {
			$q = $arr->row();
			return $q->tahun;
		} else {
			return FALSE;			
		}
	}

	/* Ambil Nama Tingkat */
	function get_nama_tingkat($id) {
		$arr = $this->db->get_where('seting_tingkat', array('id' => $id));
		
		if ($arr->num_rows() > 0) {
			$q = $arr->row();
			return 'Tingkat '.$q->tingkat;
		} else {
			return FALSE;
		}
	}
	/* Ambil Nama Jurusan */
	function get_nama_jurusan($id) {
		$arr = $this->db->get_where('ref_jurusan', array('id' => $id));
		
		if ($arr->num_rows() > 0) {
			$q = $arr->row();
			return $q->jurusan;
		} else {
			return FALSE;
		}
	}
	/* Ambil Nama Jabatan */
	function get_nama_jabatan($id) {
		$arr = $this->db->get_where('ref_jabatanpegawai', array('id' => $id));
		
		if ($arr->num_rows() > 0) {
			$q = $arr->row();
			return $q->jabatan;
		} else {
			return FALSE;
		}
	}
	/* Ambil Nama Group */
	function get_nama_grup($id) {
		$arr = $this->db->get_where('groups', array('id' => $id));
		
		if ($arr->num_rows() > 0) {
			$q = $arr->row();
			return $q->name;
		} else {
			return FALSE;
		}
	}	
	
	//Dropdown Agama
	function dropdown_agama() {
		$arr 	= $this->db->query("SELECT * FROM ref_agama");				
		$data 	= array();
		$data[''] = '-- Pilih Agama --';		
		foreach ($arr->result() as $q) {
			$data[$q->agama] = $q->agama;
		}		
		return $data;
	}
	
	//data kelas
	function get_kelas() {
		$arr = $this->db->query("SELECT id, kelas FROM kelas WHERE id_tahun=(SELECT id FROM seting_tahun_ajaran WHERE status='Y')");
				
		if ($arr->num_rows() > 0) {
			foreach ($arr->result() as $q) {
				$w[''] = '-- Pilih Kelas --';
				$w[$q->id] = $q->kelas;
			}
			return $w;
		}
		return FALSE;
	}
	//---- Data Kelas by Pegawai ----------
	function get_datakelas_by_pegawai($id_pegawai) {	
		//tapel aktif
		$arr = $this->db->get_where('seting_tahun_ajaran', array('status' => 'Y'))->row();
		$tapel_now = $arr->id;
		//query
		$this->db->select('pegawai_mapel.id_tingkat, kelas.id, kelas.kelas, kelas.id_tahun');
		$this->db->from('pegawai_mapel');
		$this->db->join('kelas', 'kelas.id_tingkat = pegawai_mapel.id_tingkat');
		$this->db->where('id_pegawai', $id_pegawai);
		$this->db->where('kelas.id_tahun', $tapel_now);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $q) {
				$w[0] = '-- Pilih Kelas --';
				$w[$q->id] = $q->kelas;
			}
			return $w;
		}
		return FALSE;
	}
	
	//get where
    public function view_where($table,$data){
        $this->db->where($data);
        return $this->db->get($table);
    }
	//join 2 tabel
    public function get_join_where($table1,$table2,$fieldTbl1,$fieldTbl2,$where,$order,$ordering,$baris,$dari){
        $this->db->select('*');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$fieldTbl1.'='.$table2.'.'.$fieldTbl2);
        $this->db->where($where);
        $this->db->order_by($order,$ordering);
        $this->db->limit($dari, $baris);
        return $this->db->get();
    }
    public function get_join($table1,$table2,$fieldTbl1,$fieldTbl2,$order,$ordering,$baris,$dari){
        $this->db->select('*');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$fieldTbl1.'='.$table2.'.'.$fieldTbl2);
        $this->db->order_by($order,$ordering);
        $this->db->limit($dari, $baris);
        return $this->db->get();
    }
	//cari berita
    function cari_berita($kata){
        $pisah_kata = explode(" ",$kata);
        $jml_katakan = (integer)count($pisah_kata);
        $jml_kata = $jml_katakan-1;
        $cari = "SELECT * FROM blog a join users b on a.id_user=b.id
                    join category c on a.id_category=c.id
                       WHERE a.is_active='Y' AND";
            for ($i=0; $i<=$jml_kata; $i++){
              $cari .= " a.title LIKE '%".$pisah_kata[$i]."%'";
                if ($i < $jml_kata ){
                    $cari .= " OR "; 
                } 
            }
        $cari .= " ORDER BY a.id DESC LIMIT 15";
        return $this->db->query($cari);
    }
	//insert ignore
	protected function insert_ignore($table,array $data) {
        $_prepared = array();
         foreach ($data as $col => $val)
        $_prepared[$this->db->escape_str($col)] = $this->db->escape($val); 
        $this->db->query('INSERT IGNORE INTO '.$table.' ('.implode(',',array_keys($_prepared)).') VALUES('.implode(',',array_values($_prepared)).');');
    }

	
}
/* End of file Sistem_model.php */
/* Location: ./application/models/Sistem_model.php */ 
?>