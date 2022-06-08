<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Pembayaran_model extends Base_Model {
	
	public $_table = 'pembayaran';
	
	function __construct(){
        parent::__construct();
    }	
	
	//data jenis pembayaran
	function get_jenispembayaran() {
		$query = $this->db->get($this->_table);	
		$data = array();
		$data[0] = '-- Pilih Pembayaran --';
		
		foreach ($query->result() as $q) {
			$data[$q->id] = $q->nama_jenispembayaran;
		}
		
		return $data;
	}
	
	function get_rekap_spp_persiswa($pembayaran, $idsiswa, $bulan, $tahun) {
		$this->db->select('sw.nis as nis, sw.nama as nama, sp.no_referensi, tgl_transaksi, nilai, keterangan')->from('pembayaran sp')->join('siswa sw', 'sw.id=sp.id_siswa')->where(array('sp.id_jnspembayaran' => $pembayaran, 'sp.id_siswa' => $idsiswa, 'sp.bulan' => $bulan, 'sp.tahun' => $tahun));
		$arr = $this->db->get();
		
		if ($arr->num_rows() > 0) return $arr->result();
		return FALSE;
	}
	
}