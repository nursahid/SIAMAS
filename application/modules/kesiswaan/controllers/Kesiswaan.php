<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Kesiswaan Controller.
 */
class Kesiswaan extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Siswa";
		$this->load->model(array('siswa_model', 'siswakelas_model', 'siswamasuk_model', 'siswakeluar_model'));
    }

    /**
     * Index
     */
    public function index()
    {
		//view
		$this->layout->set_wrapper('index', $data);
		$template_data["title"] = "Kesiswaan";
		$template_data["subtitle"] = "Manajemen Data Siswa";
		$template_data["crumb"] = ["Kesiswaan" => "kesiswaan",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); 
	}

	//=====================
	// ALL CALLBACKS
	//--------------
	// kelamin
	function kelamin_callback($value)
	{
		if($value == 'L') {
			$data = '<input type="radio" name="kelamin" value="L" checked/> Laki-Laki &nbsp;
					 <input type="radio" name="kelamin" value="P" /> Perempuan';
		} elseif($value == 'P') {
			$data = '<input type="radio" name="kelamin" value="L" /> Laki-Laki &nbsp;
					 <input type="radio" name="kelamin" value="P" checked/> Perempuan';
		} else {
			$data = '<input type="radio" name="kelamin" value="L" checked/> Laki-Laki &nbsp;
					 <input type="radio" name="kelamin" value="P" /> Perempuan';
		}
		return $data;
	}
	// jenis_Kesiswaan
	function jenis_Kesiswaan_callback($value)
	{
		if($value == 'Pindah') {
			$data = '<input type="radio" name="jenis_Kesiswaan" value="Pindah" checked/> Pindah &nbsp;
					 <input type="radio" name="jenis_Kesiswaan" value="Mengundurkan Diri" /> Mengundurkan Diri';
		} elseif($value == 'Mengundurkan Diri') {
			$data = '<input type="radio" name="jenis_Kesiswaan" value="Pindah" /> Pindah &nbsp;
					 <input type="radio" name="jenis_Kesiswaan" value="Mengundurkan Diri" checked/> Mengundurkan Diri';
		} else {
			$data = '<input type="radio" name="jenis_Kesiswaan" value="Pindah" checked/> Pindah &nbsp;
					 <input type="radio" name="jenis_Kesiswaan" value="Mengundurkan Diri" /> Mengundurkan Diri';
		}
		return $data;
	}
	//pengelolaan
	function pengelolaan_column_callback($value, $row) {
		//get data by nisn
		$nisn = $this->siswa_model->get_by('nisn',$row->nisn)->nisn;
		//jika sudah ada, hilangkan tombol setsiswa
		if($nisn) {
			$view = "<span class='badge btn-danger'>Sudah masuk ke database</span>";
		} else {
			$view = "<a href='".site_url('kesiswaan/Kesiswaan/setsiswa/'.$row->id)."' class='btn btn-xs btn-primary' data-toggle='tooltip' data-placement='bottom' title='Masukkan ke Data Santri'><i class='fa fa-plus'></i> Masukkan ke Database</a>";		
		}		
		return $view;
	}
	//Kolom ID
	function idsiswa_callback() {
		$id_siswa = $this->uri->segment(5);
		$q = $this->db->get_where('siswa', array('id' => $id_siswa), 1)->row();
		return '<input type="hidden" name="id" value="'.$id_siswa.'"><strong>'.$q->nama.'</strong>';
	}
	//Kolom masuk_dikelas
	function masuk_dikelas_alumni_callback() {
		return '<input type="hidden" name="masuk_dikelas" value="Alumni"><strong>Alumni</strong>';
	}
	//setelah insert
	function update_datasiswa_callback($post_array, $primary_key) {
		$id_siswa = $post_array['id_siswa'];
		$tahunaktif = $this->sistem_model->get_tapel_aktif();

		//1. Data siswa jadi Kesiswaan
			$data = array('status'=>'Kesiswaan');
			$this->siswa_model->update($id_siswa, $data);
		
		//2. Hapus di data kelas, jika ada
		   //ambil data kelas terakhir (pada tahun aktif)
			$qry = $this->db->get_where('siswa_kelas', array('id_siswa' => $id_siswa, 'id_tahun' => $tahunaktif));
			if ($qry->num_rows() > 0) {
				$dt = $qry->row();
				//ambil variabel id_kelas
				$id_kelas = $dt->id_kelas;
				//hapus data
				$this->db->where(array( 'id_siswa' => $id_siswa, 'id_kelas' => $id_kelas, 'id_tahun' => $tahunaktif));
				$this->db->delete('siswa_kelas');
			}
		
		return true;
	}
	
}

/* End of file Kesiswaan.php */
/* Location: ./application/modules/siswa/controllers/Kesiswaan.php */