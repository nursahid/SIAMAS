<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

	// total aparat desa
    function count_personil() {
		$this->db->from('simduk_personil');
        return $this->db->count_all_results();
    }
	// total user
    function count_pengguna() {
		$this->db->from('users');
        return $this->db->count_all_results();
    }
    // total penduduk
    function count_penduduk() {
        $this->db->where('status', 'aktif');
		$this->db->from('simduk_penduduk');
        return $this->db->count_all_results();
    }
    // total kepala keluarga
    function count_kepalakeluarga() {
		$this->db->from('simduk_kepala_keluarga');
        return $this->db->count_all_results();
    }
	//total data kelahiran
	public function count_kelahiran() {
		$this->db->from('simduk_kelahiran');
        return $this->db->count_all_results();
	}
	//total data meninggal
	public function count_kematian() {
        //$this->db->where('jenis_mutasi', 'meninggal');
		$this->db->from('simduk_kematian');
        return $this->db->count_all_results();
	}
	//total mutasi masuk
	public function count_mutasimasuk() {
		$this->db->from('simduk_penduduk_masuk');
        return $this->db->count_all_results();
	}
	//total mutasi keluar
	public function count_mutasikeluar() {
		$this->db->from('simduk_penduduk_keluar');
        return $this->db->count_all_results();
	}
	//----------------------------
	//jumlah berdasarka kelamin
	//----------------------------
    // laki-laki
    function count_pria() {
        $this->db->where('status', 'aktif');
		$this->db->where('kelamin', 'Pria');
		$this->db->from('simduk_penduduk');
        return $this->db->count_all_results();
    }
    // wanita
    function count_wanita() {
        $this->db->where('status', 'aktif');
		$this->db->where('kelamin', 'Wanita');
		$this->db->from('simduk_penduduk');
        return $this->db->count_all_results();
    }	
	//===============
	// COUNT WHERE
	//==============
    // wanita
    function count_where($where,$data) {
		$this->db->where($where, $data);
		$this->db->from('simduk_penduduk');
        return $this->db->count_all_results();
    }
	//=============
	//total data mutasi
	public function count_eks_penduduk() {	  	
        $this->db->from('simduk_eks_penduduk');
        return $this->db->count_all_results();	
	}
	//total data pindah
	public function count_pindah() {	  	
        $this->db->where('jenis_mutasi', 'pindah');
		$this->db->from('simduk_eks_penduduk');
        return $this->db->count_all_results();	
	}	

	//============
	// MUTASI MASUK
	//===========
    //mutasi masuk 1 bln terakhir, m Y
    function one_month_masuk() {
		$kondisi = "tgl_pindah BETWEEN (CURRENT_DATE() - INTERVAL 1 MONTH) AND CURRENT_DATE()";
		//$kondisi = "tgl_pindah > (NOW() - INTERVAL 1 MONTH)";
		$this->db->where($kondisi);
		$this->db->from('simduk_penduduk_masuk');
		return $this->db->get();
	}	
    //jml mutasi masuk 1 bln terakhir, m Y
    function countlast_masuk() {
		$kondisi = "tgl_pindah BETWEEN (CURRENT_DATE() - INTERVAL 1 MONTH) AND CURRENT_DATE()";
		//$kondisi = "tgl_pindah > (NOW() - INTERVAL 1 MONTH)";
		$this->db->where($kondisi);
		$this->db->from('simduk_penduduk_masuk');
		return $this->db->count_all_results();
	} 
    //laki-laki mutasi masuk 1 bln terakhir, m Y
    function pria_one_month_masuk() {
		$kondisi = "tgl_pindah BETWEEN (CURRENT_DATE() - INTERVAL 1 MONTH) AND CURRENT_DATE()";
		$this->db->from('simduk_penduduk_masuk');
		$this->db->where($kondisi);
		$this->db->where('kelamin', 'Pria');
		$query = $this->db->get()->row();
		return $this->db->count_all_results();	
	}
    //wanita mutasi masuk 1 bln terakhir, m Y
    function wanita_one_month_masuk() {
		$kondisi = "tgl_pindah BETWEEN (CURRENT_DATE() - INTERVAL 1 MONTH) AND CURRENT_DATE()";
		$this->db->from('simduk_penduduk_masuk');
		$this->db->where($kondisi);
		$this->db->where('kelamin', 'Wanita');
		$query = $this->db->get()->row();
		return $this->db->count_all_results();	
	}
	//===================
	// LAPORAN 1 TAHUN  =
	//===================
    //kelahiran 1 thn terakhir, mounthname
    function oneyear_kelahiran() {
		$kondisi = "MONTH(simduk_kelahiran.tanggal_lahir) < DATE_SUB(CURDATE(), INTERVAL 12 MONTH)";
		$this->db->select("simduk_kelahiran.tanggal_lahir,COUNT(simduk_kelahiran.id) AS jumlah,MONTHNAME(simduk_kelahiran.tanggal_lahir) AS bulan");
		$this->db->where($kondisi);
		$this->db->from('simduk_kelahiran');
		$result = $this->db->get();
		return $result;
	}
    //kematian 1 thn terakhir, mounthname
    function oneyear_kematian() {
		//$kondisi = "tgl_meninggal BETWEEN (CURRENT_DATE() - INTERVAL 12 MONTH) AND CURRENT_DATE()";
		$kondisi = "MONTH(simduk_kematian.tgl_meninggal) < DATE_SUB(CURDATE(), INTERVAL 12 MONTH)";
		//$kondisi = "tgl_transaksi > (NOW() - INTERVAL 1 MONTH)";
		$this->db->select("simduk_kematian.tgl_meninggal,COUNT(simduk_kematian.id) AS jumlah,MONTHNAME(simduk_kematian.tgl_meninggal) AS bulan");
		//$this->db->select("DATE_FORMAT(simduk_kematian.tgl_meninggal, '%b-%y') AS bulan,COUNT(simduk_kematian.id) AS jumlah,simduk_kematian.tgl_meninggal");
		$this->db->where($kondisi);
		$this->db->from('simduk_kematian');
		$result = $this->db->get();
		return $result;
	}
    //mutasi masuk 1 thn terakhir, mounthname
    function oneyear_mutasimasuk() {
		$kondisi = "MONTH(simduk_penduduk_masuk.tgl_pindah) < DATE_SUB(CURDATE(), INTERVAL 12 MONTH)";
		$this->db->select("simduk_penduduk_masuk.tgl_pindah,COUNT(simduk_penduduk_masuk.id) AS jumlah,MONTHNAME(simduk_penduduk_masuk.tgl_pindah) AS bulan");
		$this->db->where($kondisi);
		$this->db->from('simduk_penduduk_masuk');
		$result = $this->db->get();
		return $result;
	}
    //mutasi keluar 1 thn terakhir, mounthname
    function oneyear_mutasikeluar() {
		$kondisi = "MONTH(simduk_penduduk_keluar.tgl_pindah) < DATE_SUB(CURDATE(), INTERVAL 12 MONTH)";
		$this->db->select("simduk_penduduk_keluar.tgl_pindah,COUNT(simduk_penduduk_keluar.id) AS jumlah,MONTHNAME(simduk_penduduk_keluar.tgl_pindah) AS bulan");
		$this->db->where($kondisi);
		$this->db->from('simduk_penduduk_keluar');
		$result = $this->db->get();
		return $result;
	}	
	
	
	//============
	// LAPORAN 1 BULAN
	//===========
    //mutasi 1 bln terakhir, m Y
    function last_mutasi() {
		$kondisi = "tgl_pindah BETWEEN (CURRENT_DATE() - INTERVAL 1 MONTH) AND CURRENT_DATE()";
		//$kondisi = "tgl_transaksi > (NOW() - INTERVAL 1 MONTH)";
		$this->db->where($kondisi);
		$this->db->from('simduk_eks_penduduk');
		$result = $this->db->get();
		return $result;
	}	
    //total mutasi 1 bln terakhir
    function countlast_penjualan() {
		$kondisi = "tgl_pindah BETWEEN (CURRENT_DATE() - INTERVAL 1 MONTH) AND CURRENT_DATE()";
		//$kondisi = "tgl_transaksi > (NOW() - INTERVAL 1 MONTH)";
		$this->db->where($kondisi);
		$this->db->from('simduk_eks_penduduk');
		return $this->db->count_all_results();
    }
    //rupiah penjualan 1 bln terakhir, m Y
    function rplast_penjualan() {
		$kondisi = "tgl_transaksi BETWEEN (CURRENT_DATE() - INTERVAL 1 MONTH) AND CURRENT_DATE()";
		$this->db->select('penjualan.*,SUM(penjualan.total_harga) AS total_pendapatan');
		$this->db->from('penjualan');
		$this->db->where($kondisi);
		$query = $this->db->get()->row();
		return $query->total_pendapatan;	
	}
	//persentase 5 penjualan terbesar
    function higestlast_penjualan() {
		$kondisi = "penjualan.jumlah_barang >= ANY (SELECT jumlah_barang FROM penjualan ORDER BY jumlah_barang)";
		$this->db->select('nama_barang AS label, jumlah_barang AS value');
		$this->db->where($kondisi);
		$this->db->from('penjualan');
		$this->db->join('barang', 'barang.kode_barang = penjualan.kode_barang');
		$this->db->limit(5, 0);
		$result = $this->db->get();
		return $result;
	}	
	
	//===========
	//PERBANDINGAN
	//===============
	
	//5 bln terakhir
	function limabulan() {
		$date = sprintf("'%03s-%04d'", date('F'), date('Y'));
		//$query = "SELECT penjualan.tgl_transaksi,SUM(penjualan.jumlah_barang) AS terjual FROM penjualan a WHERE penjualan.tgl_transaksi NOT IN (SELECT riwayat_pembelian.created_at FROM riwayat_pembelian b )";
		//$query1 = "SELECT * FROM penjualan WHERE MONTH(tgl_transaksi) < DATE_SUB(CURDATE(), INTERVAL 5 MONTH)";
		//$query2 = "SELECT * FROM riwayat_pembelian WHERE MONTH(created_at) < DATE_SUB(CURDATE(), INTERVAL 5 MONTH)";
		$kondisi1 = "MONTH(penjualan.tgl_transaksi) < DATE_SUB(CURDATE(), INTERVAL 5 MONTH)";
		$kondisi2 = "MONTH(riwayat_pembelian.created_at) < DATE_SUB(CURDATE(), INTERVAL 5 MONTH)";
		$this->db->select("DATE_FORMAT(penjualan.tgl_transaksi, '%b-%y') AS tgl_jual,SUM(penjualan.jumlah_barang) AS terjual, DATE_FORMAT(riwayat_pembelian.created_at, '%b-%y') AS tgl_beli,SUM(riwayat_pembelian.jumlah_barang) AS terbeli");
		$this->db->where($kondisi1);
		$this->db->where($kondisi2);
		$this->db->where_not_in('penjualan.tgl_transaksi', 'SELECT riwayat_pembelian.created_at FROM riwayat_pembelian');
		$this->db->from('penjualan');
		$this->db->join('riwayat_pembelian', 'riwayat_pembelian.kode_barang = penjualan.kode_barang');
		//$this->db->join('pembelian', 'pembelian.kode_ = pembelian.id');
		
		$result = $this->db->get();
		return $result;		
	}
	function perbandingan() {
		$res1 = $this->res_penjualan()->result_array();
		$res2 = $this->res_pembelian()->result_array();
		foreach($res1 as $res1) {
			$result1 = array(
						'bulan' => $res1['tgl_jual'],
						'terjual' => $res1['terjual'],
					  );
		}
		foreach($res2 as $res2) {
			$result2 = array(
						'terbeli' => $res2['terbeli'],
					  );
		}
		$result = array_merge($result1,$result2);
		return $result;
	}
	function res_penjualan() {
		$kondisi1 = "MONTH(penjualan.tgl_transaksi) < DATE_SUB(CURDATE(), INTERVAL 5 MONTH)";
		$this->db->where($kondisi1);
		$this->db->select("DATE_FORMAT(penjualan.tgl_transaksi, '%b-%y') AS tgl_jual,SUM(penjualan.jumlah_barang) AS terjual");
		$this->db->from('penjualan');
		$result = $this->db->get();
		return $result;			
	}
	function res_pembelian() {
		$kondisi2 = "MONTH(riwayat_pembelian.created_at) < DATE_SUB(CURDATE(), INTERVAL 5 MONTH)";
		$this->db->where($kondisi2);
		$this->db->select("DATE_FORMAT(riwayat_pembelian.created_at, '%b-%y') AS tgl_beli,SUM(riwayat_pembelian.jumlah_barang) AS terbeli");
		$this->db->from('riwayat_pembelian');
		$result = $this->db->get();
		return $result;			
	}	
}
/* End of file Laporan_model.php */
/* Location: ./application/models/Laporan_model.php */