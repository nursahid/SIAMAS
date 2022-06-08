<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Nilai Controller.
 */
class Nilai extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();
		$this->load->model('absensi/absensi_model', 'absensi');	
		$this->load->model('penilaian/nilai_model', 'nilai');
        $this->load->model(array('penilaian/jenispenilaian_model','seting/jurusan_model',
								 'pegawai/pegawai_model','pembelajaran/kelas_model'));
		$this->load->library(array('settings','fpdf','PdfGenerator'));
	}

    /**
     * Index
     */
	public function index()
    {
        $userid = $this->ion_auth->user()->row()->id;
		$dapeg = $this->pegawai_model->get_by('id_user', $userid);
		$id_pegawai = $dapeg->id;
        // layout view
		//cek group
		if($this->ion_auth->in_group(array('superadmin', 'admin'))) {
			$data['dropdown_prodi'] = $this->jurusan_model->dropdown_jurusan();
			$data['dropdown_tingkat'] = $this->kelas_model->get_data_tingkat();
			$data['dropdown_jenis'] = $this->jenispenilaian_model->get_dropdown();
			//$data['dropdown_kelas_bytingkat'] = $this->kelas_model->get_kelas_by_tingkat();
			$this->layout->set_wrapper('index_cetakpenilaian', $data);
		} 
		elseif($this->ion_auth->in_group('guru')) {
			//redirek
			//redirect('cetak/nilai/lihat');
			//dropdown mapel yang diampu
			$data['dropdown_kelas'] = $this->nilai->get_kelas_by_pegawai($id_pegawai);
			$data['dropdown_mapel'] = $this->nilai->get_dropdown_mapel_by_guru($id_pegawai);
			$data['dropdown_jenis'] = $this->jenispenilaian_model->get_dropdown();
			$data['id_guru'] 		= $id_pegawai;
			$data['nippegawai'] 	= $dapeg->nip;
			$data['namapegawai'] 	= $dapeg->nama;
			//view
			$this->layout->set_wrapper('index_cetakpenilaian_guru', $data);
		}
        $this->layout->auth();
        $template_data['title'] = 'Print';
		$template_data['subtitle'] = 'Cetak Data Nilai';
        $template_data['crumb'] = ['Cetak' => 'cetak', 'Nilai' => 'cetak/nilai'];
        $this->layout->setCacheAssets();
        $this->layout->render('admin', $template_data);		
	}
	//===================
	//2. pilih tingkat
	//-------------------
    function pilihkelas() {
		$id_jurusan = $_POST['jurusan'];
		$id_tingkat = $_POST['tingkat'];
		$id_tahun	= $this->sistem_model->get_tapel_aktif();
		$data['datakelas'] = $this->kelas_model->get_many_by(array('id_jurusan'=>$id_jurusan, 'id_tingkat'=>$id_tingkat, 'id_tahun'=>$id_tahun));
		$data['tgl'] = parseFormTgl('tanggal');
		$this->load->view('index_cetakpilihkelas', $data);
    }
	//===================
	// 1. Lihat Nilai
	//-------------------
    public function lihat($id_jnspenilaian='',$id_kelas='')
    {
        $userid = $this->ion_auth->user()->row()->id;
		$qy = $this->pegawai_model->get_by('id_user', $userid);
		$id_pegawai = $qy->id;
        // layout view
		//cek group
		if($this->ion_auth->in_group(array('superadmin', 'admin'))) {
			//$data['dropdown_tingkat'] = $this->kelas_model->get_data_tingkat(); 
			$data['dropdown_kelas'] = $this->kelas_model->dropdown_kelas($id_kelas);
			$data['datakelas'] = $this->kelas_model->get_by('id',$id_kelas);
			$this->layout->set_wrapper('nilai_index', $data);
		} 
		elseif($this->ion_auth->in_group('guru')) {
			//dropdown mapel yang diampu
			$data['dropdown_kelas'] = $this->nilai->get_kelas_by_pegawai($id_pegawai);
			$data['dropdown_mapel'] = $this->nilai->get_mapel_by_pegawai($id_pegawai);
			$data['dropdown_jenis'] = $this->jenispenilaian_model->get_dropdown();
			$data['id_guru'] 		= $id_pegawai;
			$data['nippegawai'] 	= $qy->nip;
			$data['namapegawai'] 	= $qy->nama;
			//view
			$this->layout->set_wrapper('nilai_guru', $data);
		}
        $this->layout->auth();
        $template_data['title'] = 'Penilaian';
        $template_data['crumb'] = ['Penilaian' => 'penilaian',];
        $this->layout->setCacheAssets();
        $this->layout->render('admin', $template_data);
    }

	//=============================
	//  2. List Data Siswa per kelas
	//=============================
    function lists() {
		$data['setting'] = $this->settings->get();
		$data['jenisnilai'] = $this->sistem_model->get_nama_penilaian($_POST['jenis_penilaian']);
		$data['namakelas'] = $this->sistem_model->get_nama_kelas($_POST['kelas']);
		$data['namamapel'] = $this->sistem_model->get_nama_mapel($_POST['mapel']);
		$data['datasiswa'] = $this->nilai->get_data_siswa_perkelas($_POST['kelas']);
		
		$data['tanggal'] = parseFormTgl('tanggal');
		$data['tanggalarr'] = date('Ymd',parseFormTgl('tanggal'));
		$data['post']	= $_POST;
		$this->load->view('nilai_listdatasiswa', $data);
    }
	//========================
	//    CETAK DATA   
	//========================
	public function cetak() {
		$kelas 	= $this->input->post('kelas');
		$cetak_ke 	= $this->input->post('cetak_ke');
		$namakelas 	= $this->kelas_model->get_by('id',$kelas)->kelas;
		//cek group
		if($this->ion_auth->in_group(array('superadmin', 'admin'))) {
			//buat admin
			$redirect_back	= 'cetak/index';
			$data['back_button']	= 'cetak/index';
		} 
		elseif($this->ion_auth->in_group('guru')) {
			//buat guru
			$redirect_back	= 'cetak/nilai';
			$data['back_button']	= 'cetak/nilai';
		}
		if($kelas == '' || $cetak_ke == ''){
			$this->session->set_flashdata('message5', '<div class="alert alert-danger">Data Kelas Belum Ada</div>');
			redirect(site_url($redirect_back));
		} else {
			if($cetak_ke == 'printer'){
				$data['setting'] = $this->settings->get();
				$data['namakelas'] 	= $namakelas;
				$data['datas'] 	= $this->kelas_model->get_data_siswa_perkelas($kelas);
				$data['post']	= $_POST;
				
				$data['pagination']= $this->pagination->create_links();
				$data['number']= (int)$this->uri->segment(3) +1;
				//View
				$template_data['title'] = 'Print ';
				$template_data['subtitle'] = 'Cetak Data Nilai';
				$template_data['crumb'] = ['Cetak Nilai' => 'cetak/nilai',];
				
				$this->layout->set_wrapper('pegawai_print', $data);
				$this->layout->auth();
				$this->layout->render('admin', $template_data);
			
			} elseif($cetak_ke == 'pdf') {
				$data['namakelas'] 	= $namakelas;
				$data['data'] 	= $this->kelas_model->get_data_siswa_perkelas($kelas);
				$data['number'] = (int)$this->uri->segment(3) +1;
				$data['setting'] = $this->settings->get();
				//use dompdf
				$pdf_filename 	= 'data_nilai_'.strtolower($namakelas).'_'.date('dmY').'.pdf';
				$paper 	  	  	= 'letter';
				$orientation  	= 'landscape';	//potrait		
				$html 			= $this->load->view('nilai_dompdf', $data, true);
				$this->pdfgenerator->generate($html,$pdf_filename, $paper, $orientation);
			
			} elseif($cetak_ke == 'excel') {
				$this->cetak_nilai_excel($kelas);
			}
		}
	}
	public function cetak_nilai_excel($kelas,$tanggalarr) {
		//$kelas = $this->input->post('kelas');
		//$tanggal = parseFormTgl('tanggal');
		$tanggal = date('Y-m-d',$tanggalarr);
		
		$datakelas	= $this->kelas_model->get_by('id',$kelas);
		$namakelas 	= $datakelas->kelas;

		//Ambil library
		$this->load->library('excel_2013');
		/** PHPExcel_IOFactory */
		require_once APPPATH."/third_party/PHPExcel/IOFactory.php";
		//load template excel
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load(APPPATH."../assets/templates/data_nilai_tpl.xls");
		//Ambil data
		$data = $this->kelas_model->get_data_siswa_perkelas($kelas)->result();		
		//ambil data setting program
		$setting = $this->settings->get();

		//logo
		$logosekolah 	= imagecreatefrompng(''.base_url('assets/uploads/image/'.$setting['logosekolah']).'');
		$logokabupaten 	= imagecreatefrompng(''.base_url('assets/uploads/image/'.$setting['logokabupaten']).'');
		
		$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
		$objDrawing->setImageResource($logosekolah);
		$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
		$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
		$objDrawing->setHeight(80);
		$objDrawing->setCoordinates('A2');
		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

		$objDrawing2 = new PHPExcel_Worksheet_MemoryDrawing();
		$objDrawing2->setImageResource($logokabupaten);
		$objDrawing2->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
		$objDrawing2->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
		$objDrawing2->setHeight(80);
		$objDrawing2->setCoordinates('F2');
		$objDrawing2->setWorksheet($objPHPExcel->getActiveSheet());
		
		//hari ini
		$hari_ini = tgl_indo(date('Y-m-d'));		
		$row2 = '2';
		 
		//Baris pengisian data
		$baseRow = 14;
		foreach($data as $r => $dataRow) {
			$row = $baseRow + $r;
			$row2 = $row+3;
			$row3 = $row+8;
			$row4 = $row+9;

			//get data by id
			$tanggal = parseFormTgl('tanggal');	
			$query = $this->db->get_where('nilai_penilaian', array('id_jnspenilaian' => $post['jenis_penilaian'], 'id_mapel'=>$post['mapel'],'tgl_penilaian'=>$tanggal,'id_kelas' => $post['kelas'],'id_siswa' => $dataRow->id, 'nip'=>$post['pengampu']));
			//$condition = "tanggal BETWEEN " . "'" . $tglawal . "'" . " AND " . "'" . $tglakhir . "'";
			//$this->db->select('*');
			//$this->db->from('nilai_penilaian');
			//$this->db->where('id_siswa',$dataRow->id);
			//$this->db->where($condition);
			//$query = $this->db->get();
			
			if ($query->num_rows() > 0) {
				$data2 = $query->row();
			}
			
			$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $r+1)
	                              ->setCellValue('B'.$row, $dataRow->nis)
	                              ->setCellValue('C'.$row, $dataRow->nama)
	                              ->setCellValue('D'.$row, $data2->nilai)
	                              ->setCellValue('E'.$row, $data2->keterangan)

	                              ->setCellValue('A2', 'PEMERINTAH KABUPATEN  '.strtoupper($setting['kabupaten']))
								  ->setCellValue('A3', ''.strtoupper($setting['namasekolah']))
								  ->setCellValue('A4', 'Alamat : '.strtoupper($setting['alamat']).' - Kecamatan '.strtoupper($setting['kecamatan']).' - Kodepos '.strtoupper($setting['kodepos']))
								  ->setCellValue('A8', ucwords($namakelas))
								  ->setCellValue('A9', 'TANGGAL ABSENSI : '.strtoupper(tgl_indo($tanggal)))
								  ->setCellValue('D'.$row2, ucwords($setting['kelurahan']).', '.$hari_ini)
	                              ->setCellValue('D'.$row3, ucwords($setting['namakepsek']))
								  ->setCellValue('D'.$row4, "NIP. ".$setting['nipkepsek']);
		}
     
		$objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		  
		$objWriter->save(APPPATH."../assets/templates/data_nilai_".strtolower($namakelas).".xls");    //Simpan sebagai apalah.xlsx 
		//Unduh file
		$this->load->helper('download'); 
		$data = file_get_contents(APPPATH."../assets/templates/data_nilai_".strtolower($namakelas).".xls");
		$name = 'data_nilai_'.strtolower($namakelas).'.xls';		  
		force_download($name, $data); 
		//var_dump($name);
		//delete
	}
	
	
	
	//========================
	// ALL AJAX
	//------------------------
 	function getTingkatAjax($id_jurusan){
		$query = $this->db->query("SELECT * FROM seting_tingkat WHERE id_jurusan=".$id_jurusan."");	    
		$data = $query->result();
		
		echo json_encode($data);
	}
	//ambil data by jurusan dan tingkat
 	function getKelasAjax($id_jurusan,$id_tingkat){
		$query = $this->db->query("SELECT * FROM kelas WHERE id_jurusan=".$id_jurusan." AND id_tingkat=".$id_tingkat." AND id_tahun=(SELECT id FROM seting_tahun_ajaran WHERE status='Y')");
	    
		$data = $query->result();
		
		echo json_encode($data);
	}

	//tampilkan semua mapel berdasarkan jurusan dan tingkat
	public function getMapelAjax($id_jurusan,$id_tingkat){ 
		// POST data 
		$this->db->select('seting_mapel.id, mapel');
		$this->db->from('mapel_tingkat');
		$this->db->join('seting_mapel', 'seting_mapel.id = mapel_tingkat.id_mapel');
		$this->db->where('id_jurusan', $id_jurusan);
		$this->db->where('id_tingkat', $id_tingkat);
		$data = $this->db->get()->result();
		
		echo json_encode($data); 
	}
 	function getKelas($id_tingkat){
		$query = $this->db->query("SELECT * FROM kelas WHERE id_tingkat=".$id_tingkat." AND id_tahun=(SELECT id FROM seting_tahun_ajaran WHERE status='Y')");
	    
		$data = "<option value=''> -- Pilih Kelas -- </option>";
	    foreach ($query->result() as $value) {
	        $data .= "<option value='".$value->id_tingkat."'>".$value->kelas."</option>";
	    }
	    echo $data;
	}
 	function getPengampu2($id_mapel){
	    $query = $this->nilai->get_pegawai_by_mapel($id_mapel);
	    $data  = "<input type='hidden' name='pengampu' id='pengampu' value='".$query->nip."' class='input-sm' />".$query->nama."";
	    if($data == NULL) {
			$info = "Blm Ada Pengampu";			
		} else {
			$info = $data;
		}
		echo $info;
	}
 	function getPengampu($id_mapel){
		$this->db->select('pegawai_mapel.id_pegawai as id, pegawai_mapel.id_mapel, pegawai.nama, pegawai.nip');
		$this->db->from('pegawai_mapel');
		$this->db->join('pegawai', 'pegawai.id = pegawai_mapel.id_pegawai');
		$this->db->where('id_mapel', $id_mapel);
		
		$arr = $this->db->get();
		$query = $arr->row();
	    if($arr->num_rows() > 0) {
			$data  = "<input type='hidden' name='pengampu' id='pengampu' value='".$query->nip."' class='input-sm' />".$query->nama."";
		} else {
			$data = "Belum ada pengampu";
		}
		echo $data;
	}
	//========================
	//PRIVATE FUNCTIONS
	//insert ignore
	protected function insert_ignore($table,array $data) {
        $_prepared = array();
         foreach ($data as $col => $val)
        $_prepared[$this->db->escape_str($col)] = $this->db->escape($val); 
        $this->db->query('INSERT IGNORE INTO '.$table.' ('.implode(',',array_keys($_prepared)).') VALUES('.implode(',',array_values($_prepared)).');');
    }
	
	//=====================
	// ALL CALLBACK
	//=====================
	//Jenis Penilaian KONSEP	
	function jnspenilaian_konsep_add_callback(){
		$res = $this->db->select('*')->where('id', '1')->get('nilai_jenis_penilaian')->row();
		return '<input type="hidden" name="id_jnspenilaian" value="'.$res->id.'"><strong>'.$res->nama_jnspenilaian.'</strong>';
	}
	//Jenis Penilaian PRAKTEK	
	function jnspenilaian_praktek_add_callback(){
		$res = $this->db->select('*')->where('id', '2')->get('nilai_jenis_penilaian')->row();
		return '<input type="hidden" name="id_jnspenilaian" value="'.$res->id.'"><strong>'.$res->nama_jnspenilaian.'</strong>';
	}
	function idsiswa_callback() {
		$id_penilaian = $this->uri->segment(6);		
		$q1 = $this->db->get_where('nilai_penilaian', array('id' => $id_penilaian), 1)->row();
		$q2 = $this->db->get_where('siswa', array('id' => $q1->id_siswa), 1)->row();
		return '<input type="hidden" name="id_siswa" value="'.$q2->id.'"><strong>'.$q2->nama.'</strong>';
	}
	function huruf_update_callback($post_array, $primary_key) {
		$data = array(
				'huruf' => ucwords(terbilang($post_array['nilai']))
			);
		$this->db->update('nilai_penilaian',$data,array('id'=>$primary_key));
		return true;
	}
}

/* End of file Nilai.php */
/* Location: ./application/modules/cetak/controllers/Nilai.php */