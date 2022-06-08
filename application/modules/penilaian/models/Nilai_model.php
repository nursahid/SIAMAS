<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nilai_model extends Base_Model {

	function __construct(){
        parent::__construct();
		$CI = & get_instance();
    }	

	// --------- Data Siswa Per Kelas -----
	function get_data_siswa_perkelas($id_kelas) {	
		$this->db->select('r.id, s.id as idsiswa, s.nis, s.nama, s.hp_ortu')->from('siswa_kelas k')->join('siswa s', 'k.id_siswa=s.id')->join('kelas r', 'k.id_kelas=r.id')->where('r.id', $id_kelas);
		$arr = $this->db->get();

		if ($arr->num_rows() > 0) return $arr;
		return FALSE;		
	}
	// --------- Data Siswa Per Kelas -----
	function get_data_siswa_perkelas1($id_kelas) {	
		$this->db->select('r.id as idkelas, s.nis, s.nama, s.hp_ortu')->from('siswa_kelas k')->join('siswa s', 'k.id_siswa=s.id')->join('kelas r', 'k.id_kelas=r.id')->where('r.id', $id_kelas);
		$arr = $this->db->get();

		if ($arr->num_rows() > 0) return $arr;
		return FALSE;		
	}	
	
	function get_detail_siswa($id) {
		$arr = $this->db->get_where('siswa', array('id' => $id));
		
		if ($arr->num_rows() > 0) return $arr->row();
		return FALSE;
	}	
	//---- Data Kelas ----------
	function get_kelas() {
		$arr = $this->db->query("SELECT id, kelas FROM kelas WHERE id_tahun=(SELECT id FROM seting_tahun_ajaran WHERE status='Y')");
				
		if ($arr->num_rows() > 0) {
			foreach ($arr->result() as $q) {
				$w[0] = '-- Pilih Kelas --';
				$w[$q->id] = $q->kelas;
			}
			return $w;
		}
		return FALSE;
	}
	//get pegawai by mapel
	function get_pegawai_by_mapel($id_mapel) {
		//$this->db->select('id')->from('seting_tahun_ajaran')->where('status', 'Y');
		$this->db->select('pegawai_mapel.id_pegawai as id, pegawai_mapel.id_mapel, pegawai.nama, pegawai.nip');
		$this->db->from('pegawai_mapel');
		$this->db->join('pegawai', 'pegawai.id = pegawai_mapel.id_pegawai');
		$this->db->where('id_mapel', $id_mapel);
		
		$arr = $this->db->get();
		
		return $arr->row();
	}
	//---- Data Mapel by Pegawai ----------
	function get_mapel_by_pegawai($id_pegawai) {	
		$this->db->select('pegawai_mapel.id_mapel, seting_mapel.id, mapel');
		$this->db->from('pegawai_mapel');
		$this->db->join('seting_mapel', 'seting_mapel.id = pegawai_mapel.id_mapel');
		$this->db->where('id_pegawai', $id_pegawai);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $q) {
				$w[0] = '-- Pilih Mata Pelajaran --';
				$w[$q->id] = $q->mapel;
			}
			return $w;
		} else {
			return 'Belum mengampu Mapel';
		}
		//return FALSE;
	}
	//dropdown mapel by id_pegawai
	function get_dropdown_mapel_by_guru($id_guru) {
		// get data 
		$this->db->select('pegawai_mapel.id_mapel, seting_mapel.id, mapel');
		$this->db->from('pegawai_mapel');
		$this->db->join('seting_mapel', 'seting_mapel.id = pegawai_mapel.id_mapel');
		$this->db->where('id_pegawai', $id_guru);
		$this->db->where('id_tahun', $this->get_tahun_ajaran());
		$query = $this->db->get();
				
		$data = array();
		$data[0] = '-- Pilih Pelajaran --';		
		foreach ($query->result() as $q) {
			$data[$q->id_mapel] = $q->mapel;
		}		
		return $data;
	}	
	
	//---- Data Mapel by Tingkat ----------
	function get_mapel_by_tingkat($id_tingkat) {	
		$this->db->select('mapel_tingkat.id_mapel, id_tingkat, seting_mapel.id, mapel');
		$this->db->from('mapel_tingkat');
		$this->db->join('seting_mapel', 'seting_mapel.id = mapel_tingkat.id_mapel');
		$this->db->where('id_tingkat', $id_tingkat);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $q) {
				$w[0] = '-- Pilih Mata Pelajaran --';
				$w[$q->id] = $q->mapel;
			}
			return $w;
		} else {
			return 'Belum Ada Mapel';
		}
		//return FALSE;
	}
	
	//---- Data Kelas by Pegawai ----------
	function get_kelas_by_pegawai($id_pegawai) {	
		//tapel aktif
		$arr = $this->db->get_where('seting_tahun_ajaran', array('status' => 'Y'))->row();
		$tapel_now = $arr->id;
		//query
		$this->db->select('pegawai_mapel.id_tingkat, kelas.id, kelas.kelas, kelas.id_tahun');
		$this->db->from('pegawai_mapel');
		$this->db->join('kelas', 'kelas.id_tingkat = pegawai_mapel.id_tingkat');
		$this->db->where('id_pegawai', $id_pegawai);
		//$this->db->where('kelas.id_tahun', $tapel_now);
		$this->db->where('kelas.id_tahun', $this->get_tahun_ajaran());
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $q) {
				$w[0] = '-- Pilih Kelas --';
				$w[$q->id] = $q->kelas;
			}
			return $w;
		} else {
			return 'Belum mengampu Mapel';
		}
		//return FALSE;
	}
	
	function get_total_mapel($id) {
		$arr = $this->db->query("SELECT COUNT(id_mapel) AS total FROM pegawai_mapel WHERE id_kelas='".$id."' AND id_tahun=(SELECT id FROM seting_tahun_ajaran WHERE status='Y')");		
		
		return $arr->row();
	}
	
	function get_jumlah_mapel($nis) {
		$arr = $this->db->query("SELECT COUNT(id_siswa) AS jumlah FROM nilai_penilaian WHERE id_siswa='".$nis."' AND id_tahun=(SELECT id FROM seting_tahun_ajaran WHERE status='Y')");
		
		return $arr->row();
	}
	
	function get_mapel_for_score($idtingkat) {
		$tahun_ajaran = $this->get_tahun_ajaran();
		
		$this->db->select('mp.id as idmapel, mp.kode, mp.mapel, g.nip, g.nama')->from('pegawai_mapel p')->join('pegawai g', 'p.id_pegawai=g.id')->join('seting_mapel mp', 'p.id_mapel=mp.id')->join('kelas r', 'p.id_tingkat=r.id_tingkat')->where(array('p.id_tahun' => $tahun_ajaran, 'p.id_tingkat' => $idtingkat));		
		$arr = $this->db->get();
		
		if ($arr->num_rows() > 0) return $arr->result();
		return FALSE;
	}
	
	function get_nilai_siswa($id_siswa) {
		$arr = $this->db->query("SELECT id_mapel, nilai FROM nilai_penilaian WHERE id_tahun=(SELECT id FROM seting_tahun_ajaran WHERE status='Y') AND id_siswa='".$id_siswa."' AND id_semester=(SELECT id FROM seting_semester WHERE status='Y') ORDER BY id_mapel ASC");
		
		if ($arr->num_rows() > 0) return $arr->result();
		return FALSE;
	}
	
	function get_report_nilai($nis) {
		$tahun_ajaran = $this->get_tahun_ajaran();
		$semester = $this->get_semester();
		
		$this->db->select('mp.KDMP, mp.MP, mp.ALIAS, nilai_penilaian.nilai')->from('nilai_penilaian')->join('mp', 'nilai_penilaian.kdmp=mp.KDMP')->where(array('idtahun' => $tahun_ajaran, 'sem' => $semester, 'nis' => $nis));
		$arr = $this->db->get();
		
		if ($arr->num_rows() > 0) return $arr;
		return FALSE;
	}
	
	function cek_nilai_siswa($idsiswa, $idmapel, $idtahun) {
		$arr = $this->db->get_where('nilai_penilaian', array('id_siswa' => $idsiswa, 'id_mapel' => $idmapel, 'id_tahun' => $idtahun));
		
		if ($arr->num_rows() > 0) return TRUE;
		return FALSE;
	}
	
	function get_telp_ortu($nis) {
		$this->db->select('no_hp')->from('siswa')->where('nis', $nis);
		$arr = $this->db->get();
		
		if ($arr->num_rows() > 0) return $arr->row();
		return FALSE;
	}


	//GET DATA NILAI
	public function get_penilaian($id_siswa) {
		//join tabel nilai_penilaian
		$this->db->select('*');
		$this->db->from('nilai_penilaian');
		$this->db->join('nilai_jenis_penilaian', 'nilai_jenis_penilaian.id = nilai_penilaian.id_jnspenilaian');
		$this->db->join('seting_mapel', 'seting_mapel.id = nilai_penilaian.id_mapel');
		$this->db->where('nilai_penilaian.id_siswa',$id_siswa);
		$query = $this->db->get();
		//handel jika tidak ditemukan data
		if($query->num_rows() >= 1) {
			$hasil = $query->result();
		} else {
			$hasil = "tidak ditemukan data";
		}

		return $hasil;
	}
	//get data JENIS PENILAIAN
	public function get_jenis_penilaian($id_siswa) {
		//join tabel nilai_penilaian
		$this->db->select('*');
		$this->db->from('nilai_jenis_penilaian');
		$this->db->join('nilai_penilaian', 'nilai_penilaian.id_jnspenilaian = nilai_jenis_penilaian.id');
		$this->db->where('nilai_penilaian.id_siswa',$id_siswa);
		$data = $this->db->get()->row();

		return $data->jenis_penilaian;
	}

	
	//---- Data TAPEL dan SEMESTER Aktif ------------
	function get_tahun_ajaran() {
		$arr = $this->db->query("SELECT * FROM seting_tahun_ajaran WHERE status='Y'");
		$b = $arr->row();
		
		return $b->id;
	}

	function get_semester() {
		$arr = $this->db->query("SELECT * FROM seting_semester WHERE status='Y'");
		$b = $arr->row();
		
		return $b->id;
	}	
}
