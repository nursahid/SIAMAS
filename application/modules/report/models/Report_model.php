<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_model extends CI_Model {
	
    public function __construct()
    {
        parent::__construct();
    }
	//Total Siswa Aktif
	function total_siswa(){
		$this->db->select('*');
		$this->db->from('siswa');
		$this->db->where('status','Aktif');
		$data = $this->db->get();
		
		$jumlah = $data->num_rows();
		$jumlah = !empty($jumlah)?$jumlah:'0';
		return $jumlah;
	}
	//Total Pegawai Aktif
	function total_pegawai(){
		$this->db->select('*');
		$this->db->from('pegawai');
		$data = $this->db->get();
		
		$jumlah = $data->num_rows();
		$jumlah = !empty($jumlah)?$jumlah:'0';
		return $jumlah;
	}

	//Total Mutasi Masuk
	function total_mutasi_masuk(){
		$this->db->select('*');
		$this->db->from('mutasi_masuk');
		$this->db->where('tahun',$this->get_tahun_ajaran());
		$this->db->where('semester',$this->get_semester());
		$data = $this->db->get();
		
		$jumlah = $data->num_rows();
		$jumlah = !empty($jumlah)?$jumlah:'0';
		return $jumlah;
	}

	//Total Mutasi Keluar
	function total_mutasi_keluar(){
		$this->db->select('*');
		$this->db->from('mutasi_keluar');
		$this->db->where('tahun',$this->get_tahun_ajaran());
		$this->db->where('semester',$this->get_semester());
		$data = $this->db->get();
		
		$jumlah = $data->num_rows();
		$jumlah = !empty($jumlah)?$jumlah:'0';
		return $jumlah;
	}

	//Total Mutasi Keluar
	function total_kelas(){
		$this->db->select('*');
		$this->db->from('kelas');
		$this->db->where('id_tahun',$this->get_tahun_ajaran());
		$data = $this->db->get();
		
		$jumlah = $data->num_rows();
		$jumlah = !empty($jumlah)?$jumlah:'0';
		return $jumlah;
	}

	//Total Mutasi Keluar
	function total_jurusan(){
		$this->db->select('*');
		$this->db->from('ref_jurusan');
		$data = $this->db->get();
		
		$jumlah = $data->num_rows();
		$jumlah = !empty($jumlah)?$jumlah:'0';
		return $jumlah;
	}	
	
	//-=====Get tahun dan semester aktif
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
	
	//============
	// PENJUALAN
	//===========
    //penjualan 1 tahun terakhir, m Y
    function last_orders_all() {
		$kondisi = "order_date BETWEEN (CURRENT_DATE() - INTERVAL 12 MONTH) AND CURRENT_DATE()";
		$this->db->select('order_date, order_status');
		$this->db->where($kondisi);
		$this->db->from('orders');
		return $this->db->get();
	}	
    function last_orders_complete() {
		$kondisi = "order_date BETWEEN (CURRENT_DATE() - INTERVAL 12 MONTH) AND CURRENT_DATE()";
		//$kondisi = "order_date > (NOW() - INTERVAL 12 MONTH)";
		$this->db->where($kondisi);
		$this->db->where('order_status','complete');
		$this->db->from('orders');
		return $this->db->get();
	}
	//
	function order_counts() {
		$table = 'order_counts';
		$year = date('Y');
		// ifnull((SELECT SUM(counts) FROM (".$table.")WHERE((Month(order_date)=1)AND (YEAR(order_date)=".$year."))),0) AS 'Januari',
		// ifnull((SELECT count(counts) FROM (".$table.")WHERE((Month(order_date)=1)AND (YEAR(order_date)=".$year."))),0) AS 'Januari',
		$query = $this->db->query("SELECT
					  ifnull((SELECT SUM(counts) FROM (".$table.") WHERE((MONTH(order_date)=1) AND (YEAR(order_date)=".$year."))),0) AS 'Januari',
					  ifnull((SELECT SUM(counts) FROM (".$table.") WHERE((MONTH(order_date)=2) AND (YEAR(order_date)=".$year."))),0) AS 'Februari',
					  ifnull((SELECT SUM(counts) FROM (".$table.") WHERE((MONTH(order_date)=3) AND (YEAR(order_date)=".$year."))),0) AS 'Maret',
					  ifnull((SELECT SUM(counts) FROM (".$table.") WHERE((MONTH(order_date)=4) AND (YEAR(order_date)=".$year."))),0) AS 'April',
					  ifnull((SELECT SUM(counts) FROM (".$table.") WHERE((MONTH(order_date)=5) AND (YEAR(order_date)=".$year."))),0) AS 'Mei',
					  ifnull((SELECT SUM(counts) FROM (".$table.") WHERE((MONTH(order_date)=6) AND (YEAR(order_date)=".$year."))),0) AS 'Juni',
					  ifnull((SELECT SUM(counts) FROM (".$table.") WHERE((MONTH(order_date)=7) AND (YEAR(order_date)=".$year."))),0) AS 'Juli',
					  ifnull((SELECT SUM(counts) FROM (".$table.") WHERE((MONTH(order_date)=8) AND (YEAR(order_date)=".$year."))),0) AS 'Agustus',
					  ifnull((SELECT SUM(counts) FROM (".$table.") WHERE((MONTH(order_date)=9) AND (YEAR(order_date)=".$year."))),0) AS 'September',
					  ifnull((SELECT SUM(counts) FROM (".$table.") WHERE((MONTH(order_date)=10) AND (YEAR(order_date)=".$year."))),0) AS 'Oktober',
					  ifnull((SELECT SUM(counts) FROM (".$table.") WHERE((MONTH(order_date)=11) AND (YEAR(order_date)=".$year."))),0) AS 'November',
					  ifnull((SELECT SUM(counts) FROM (".$table.") WHERE((MONTH(order_date)=12) AND (YEAR(order_date)=".$year."))),0) AS 'Desember'
				FROM ".$table." GROUP BY YEAR(order_date) ");				
		return $query;
	}
	function order_counts2() {
		$year = date('Y');
		$kondisi = "order_date BETWEEN (CURRENT_DATE() - INTERVAL 12 MONTH) AND CURRENT_DATE()";
		$kondisi2 = "MONTH(order_date) AND (YEAR(order_date)=".$year.")";
		$this->db->select('order_date, counts');
		$this->db->where($kondisi);
		$this->db->where($kondisi2);
		$this->db->from('order_counts');
				
		return $this->db->get();
	}
	
	//===========
	//PERBANDINGAN
	//===============
	
	//5 bln terakhir
	function perbandingan() {
		$res1 = $this->res_complete()->result_array();
		$res2 = $this->res_pending()->result_array();
		foreach($res1 as $res1) {
			$result1 = array(
						'transaksi' => $res1['tgl_jual'],
						'complete' => $res1['terjual'],
					  );
		}
		foreach($res2 as $res2) {
			$result2 = array(
						'pending' => $res2['terpending'],
					  );
		}
		$result = array_merge($result1,$result2);
		return $result;
	}
	function res_complete() {
		$kondisi1 = "MONTH(order_date) < DATE_SUB(CURDATE(), INTERVAL 5 MONTH)";
		$this->db->where($kondisi1);
		$this->db->where('order_status','complete');
		$this->db->select("DATE_FORMAT(order_date, '%b-%y') AS tgl_jual, COUNT(order_status) AS terjual");
		$this->db->from('orders');
		$result = $this->db->get();
		return $result;			
	}
	function res_pending() {
		$kondisi2 = "MONTH(order_date) < DATE_SUB(CURDATE(), INTERVAL 5 MONTH)";
		$this->db->where($kondisi2);
		$this->db->where('order_status','pending');
		$this->db->select("DATE_FORMAT(order_date, '%b-%y') AS tgl_order, COUNT(order_status) AS terpending");
		$this->db->from('orders');
		$result = $this->db->get();
		return $result;			
	}

	
}  //end