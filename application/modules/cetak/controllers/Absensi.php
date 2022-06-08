<?php if (!defined('BASEPATH'))  exit('No direct script access allowed');

class Absensi extends MY_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model(array('kesiswaan/siswa_model','absensi/absensi_model','pembelajaran/kelas_model',
								 'keuangan/pembayaran_model','keuangan/jenispembayaran_model',
								 'pegawai/pegawai_model','seting/mapel_model','seting/jurusan_model'));
		$this->load->model('penilaian/nilai_model', 'nilai');
		$this->load->library(array('settings','fpdf','PdfGenerator'));
    }
	
	public function index() {		
		$tapel_aktif	= $this->sistem_model->get_tapel_aktif();
		//array bulan
		$bln = array(1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
		for ($i=1;$i<=12;$i++){
			$aBln= (strlen($i)==1 ? '0'.$i : $i);
			$dBln[$aBln] = $bln[$i]; 
		}		
		$data['bln']	= $dBln;
		//cek groups
		if($this->ion_auth->in_group(array('superadmin', 'admin'))) {
			$data['kelas'] = $this->nilai->get_kelas();
			$this->layout->set_wrapper('absensi_index', $data);
		} 
		elseif($this->ion_auth->in_group('guru')) {
			//dropdown mapel yang diampu
			$user 	 = $this->ion_auth->user()->row()->id;
			$id_guru = $this->pegawai_model->get_by('id_user',$user)->id;
			$data['kelas'] = $this->nilai->get_kelas_by_pegawai($id_guru);
			$data['id_guru'] =  $id_guru;
			//view
			$this->layout->set_wrapper('absensi_index_guru', $data);
		}
		//$data['kelas'] = $this->rekapspp->get_kelas();
						
		//View
		$template_data['title'] = 'Print ';
		$template_data['subtitle'] = 'Cetak Data Absensi';
        $template_data['crumb'] = ['Cetak' => 'cetak',' Absensi' => 'cetak/absensi',];
		
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}

	public function cetak() {
		$kelas 		= $_POST['kelas1'];
		$siswa		= $_POST['idsiswa1'];
		$cetak_ke 	= $this->input->post('cetak_ke');
		//variabel data siswa
		$qsiswa = $this->siswa_model->get_by('id',$siswa);

		$namakelas 	= $this->kelas_model->get_by('id',$kelas)->kelas;
		//variabel		
		$date_from = parseFormTgl('date_from');
		$date_to = parseFormTgl('date_to');
		//$date_from = $_POST['thn_date_from'].'-'.$_POST['bln_date_from'].'-'.$_POST['tgl_date_from'];
		//$date_to = $_POST['thn_date_to'].'-'.$_POST['bln_date_to'].'-'.$_POST['tgl_date_to'];
		
		$data['tglawal'] 	= $date_from;
		$data['tglakhir'] 	= $date_to;
		$data['qsiswa'] 	= $qsiswa;
		//cek group
		if($this->ion_auth->in_group(array('superadmin', 'admin'))) {
			//buat admin
			$redirect_back	= 'cetak/index';
			$data['back_button']	= 'cetak/index';
		} 
		elseif($this->ion_auth->in_group('guru')) {
			//buat guru
			$redirect_back	= 'cetak/absensi';
			$data['back_button']	= 'cetak/absensi';
		}		
		if($kelas == '' || $cetak_ke == ''){
			$this->session->set_flashdata('message2a', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Data Absensi Belum Ada</div>');
			redirect(site_url($redirect_back));
		} else {
			if($cetak_ke == 'printer'){
				$data['setting']= $this->settings->get();
				$data['bulan']	= $bulan;
				$data['number'] = (int)$this->uri->segment(3) +1;
				//View
				$template_data['title'] = 'Print ';
				$template_data['subtitle'] = 'Cetak Data Pembayaran';
				$template_data['crumb'] = ['Cetak' => 'cetak/absensi','Absensi' => 'cetak/absensi/cetak',];

				//cek inputan Siswa
				if (!$siswa) {
					$data['namakelas'] 	= $namakelas;
					$data['datas'] 	= $this->absensi_model->get_data_siswa_perkelas($kelas);
					$data['post']	= $_POST;
					//view
					$this->layout->set_wrapper('absensi_print', $data);					
				} else {
					//cek NIS
					if($qsiswa->nis == '' || $qsiswa->nis == NULL || $qsiswa->nis == '-'){
						$this->session->set_flashdata('message2a', '<div class="alert alert-danger">Data NIS siswa '.$qsiswa->nama.' belum Ada</div>');
						redirect(site_url($redirect_back));
					} else {
						//get data siswa
						$data['nis'] 		= $qsiswa->nis;
						$data['namasiswa']	= $qsiswa->nama;
						$data['namakelas'] 	= $namakelas;
						$data['result'] 	= $this->absensi_model->get_absensi_persiswa($qsiswa->nis,$date_from,$date_to);
						//view
						$this->layout->set_wrapper('absensi_printpersiswa', $data);	
					}				
				}

				//$this->layout->set_wrapper('soal_print', $data);
				$this->layout->auth();
				$this->layout->render('admin', $template_data);
			
			} elseif($cetak_ke == 'pdf') {
				$data['setting'] = $this->settings->get();
				$data['namapembayaran'] = $this->jenispembayaran_model->get_by('id',$pembayaran)->nama_jenispembayaran;
				$data['pembayaran']	= $pembayaran;				
				$data['number'] = (int)$this->uri->segment(3) +1;
				//cek inputan Siswa
				if (!$siswa) {
					$data['namakelas'] 	= $namakelas;
					$data['datas'] 		= $this->absensi_model->get_data_siswa_perkelas($kelas);
					$data['post']		= $_POST;
					//ewv
					$pdf_filename 	= 'absensi_'.strtolower($namakelas).'_'.date('dmY').'.pdf';	
					$html 			= $this->load->view('absensi_dompdf', $data, true);
				} else {
					$data['kelas'] 		= $kelas;
					$data['namakelas'] 	= $namakelas;
					$data['namasiswa']	= $qsiswa->nama;
					$data['result'] 	= $this->absensi_model->get_absensi_persiswa($qsiswa->nis,$date_from,$date_to);
					//view
					$pdf_filename 	= 'absensi_'.strtolower($qsiswa->nama).'_'.date('dmY').'.pdf';	
					$html 			= $this->load->view('absensi_dompdfpersiswa', $data, true);
				}
				
				//use dompdf
				//$pdf_filename 	= 'pembayaran_'.$this->mapel_model->get_by('id',$mapel)->nama.'_'.date('dmY').'.pdf';
				$paper 	  	  	= 'letter';
				$orientation  	= 'landscape';	//potrait		
				//$html 			= $this->load->view('pembayaran_dompdf', $data, true);
				$this->pdfgenerator->generate($html,$pdf_filename, $paper, $orientation);
			
			} elseif($cetak_ke == 'excel') {				
				//cek inputan Siswa
				if (!$siswa) {
					$this->_do_excel($kuis);					
				} else {
					$this->_do_excel_persiswa($kuis);				
				}
			}
		}
	}
	public function _do_excel($kuis) {
		$kelas 	= $this->input->post('kelas1');
		$siswa	= $this->input->post('idsiswa1');
		//variabel
		$date1 = $this->input->post('date_from');
		$date2 = $this->input->post('date_to');
		//UBAH INPUTAN m/d/y menjadi d/m/y
		$ubah1 = date("d/m/Y", strtotime($date1));
		$hasilubah1 = str_replace('/', '-', $ubah1);
		$tglawal = date('Y-m-d', strtotime($hasilubah1));

		$ubah2 = date("d/m/Y", strtotime($date2));
		$hasilubah2 = str_replace('/', '-', $ubah2);
		$tglakhir = date('Y-m-d', strtotime($hasilubah2));
		
		$datakelas	= $this->kelas_model->get_by('id',$kelas);
		$namakelas 	= $datakelas->kelas;
		//Ambil library
		$this->load->library('excel_2013');
		/** PHPExcel_IOFactory */
		require_once APPPATH."/third_party/PHPExcel/IOFactory.php";
		//load template excel
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load(APPPATH."../assets/templates/data_absensi_tpl.xls");
		//Ambil datanya 
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
		//$row2 = '2';
		 
		//Baris pengisian data
		$baseRow = 14;
		foreach($data as $r => $dataRow) {
			$row = $baseRow + $r;
			$row2 = $row+3;
			$row3 = $row+8;
			$row4 = $row+9;
			
			//get data by nis
			$condition = "tanggal BETWEEN " . "'" . $tglawal . "'" . " AND " . "'" . $tglakhir . "'";
			$this->db->select('*');
			$this->db->from('absensi_mapel');
			$this->db->where('nis',$dataRow->nis);
			$this->db->where($condition);
			$query = $this->db->get();
			
			if ($query->num_rows() > 0) {
				$data2 = $query->row();
			}
			
			$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $r+1)
	                              ->setCellValue('B'.$row, $dataRow->nama)
	                              ->setCellValue('C'.$row, tgl_indo($data2->tanggal))
	                              ->setCellValue('D'.$row, $data2->absen)
	                              ->setCellValue('E'.$row, $data2->keterangan)

	                              ->setCellValue('A2', 'PEMERINTAH KABUPATEN  '.strtoupper($setting['kabupaten']))
								  ->setCellValue('A3', ''.strtoupper($setting['namasekolah']))
								  ->setCellValue('A4', 'Alamat : '.strtoupper($setting['alamat']).' - Kecamatan '.strtoupper($setting['kecamatan']).' - Kodepos '.strtoupper($setting['kodepos']))
								  ->setCellValue('A8', ucwords($namakelas))
								  ->setCellValue('A9', 'Periode : '.tgl_indo($tglawal).' Sampai '.tgl_indo($tglakhir))
								  ->setCellValue('D'.$row2, ucwords($setting['kelurahan']).', '.$hari_ini)
	                              ->setCellValue('D'.$row3, ucwords($setting['namakepsek']))
								  ->setCellValue('D'.$row4, "NIP. ".$setting['nipkepsek']);
		}
     
		$objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		  
		$objWriter->save(APPPATH."../assets/templates/data_absensi_".strtolower($namakelas).".xls");    //Simpan sebagai apalah.xlsx 
		//Unduh file
		$this->load->helper('download'); 
		$data = file_get_contents(APPPATH."../assets/templates/data_absensi_".strtolower($namakelas).".xls");
		$name = 'data_absensi_'.strtolower($namakelas).'.xls';		  
		force_download($name, $data); 
		//var_dump($name);
		//delete
	}
	//persiswa
	public function _do_excel_persiswa($kuis) {
		$kelas 	= $this->input->post('kelas1');
		$siswa	= $this->input->post('idsiswa1');
		//variabel
		$date1 = $this->input->post('date_from');
		$date2 = $this->input->post('date_to');
		//UBAH INPUTAN m/d/y menjadi d/m/y
		$ubah1 = date("d/m/Y", strtotime($date1));
		$hasilubah1 = str_replace('/', '-', $ubah1);
		$tglawal = date('Y-m-d', strtotime($hasilubah1));

		$ubah2 = date("d/m/Y", strtotime($date2));
		$hasilubah2 = str_replace('/', '-', $ubah2);
		$tglakhir = date('Y-m-d', strtotime($hasilubah2));
		
		$datakelas	= $this->kelas_model->get_by('id',$kelas);
		$namakelas 	= $datakelas->kelas;
		//ambil data siswa
		$qsiswa	= $this->siswa_model->get_by('id',$siswa);
		$namasiswa	= $qsiswa->nama;
		$nis	= $qsiswa->nis;
		//Ambil library
		$this->load->library('excel_2013');
		/** PHPExcel_IOFactory */
		require_once APPPATH."/third_party/PHPExcel/IOFactory.php";
		//load template excel
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load(APPPATH."../assets/templates/data_absensi_siswa_tpl.xls");
		//Ambil datanya 
		//$data = $this->absensi_model->get_absensi_persiswa($nis,$tglawal,$tglakhir);
			//get data by nis
			$condition = "tanggal BETWEEN " . "'" . $tglawal . "'" . " AND " . "'" . $tglakhir . "'";
			$this->db->select('*');
			$this->db->from('absensi_mapel');
			$this->db->where('nis',$nis);
			$this->db->where($condition);
			$query = $this->db->get();
			
			if ($query->num_rows() > 0) {
				$data = $query->result();
			}
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
		//$row2 = '2';
		 
		//Baris pengisian data
		$baseRow = 14;
		foreach($data as $r => $dataRow) {
			$row = $baseRow + $r;
			$row2 = $row+3;
			$row3 = $row+8;
			$row4 = $row+9;
			
			$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $r+1)
	                              ->setCellValue('B'.$row, tgl_indo($dataRow->tanggal))
	                              ->setCellValue('C'.$row, $namakelas)
	                              ->setCellValue('D'.$row, $dataRow->absen)
	                              ->setCellValue('E'.$row, $dataRow->keterangan)

	                              ->setCellValue('A2', 'PEMERINTAH KABUPATEN  '.strtoupper($setting['kabupaten']))
								  ->setCellValue('A3', ''.strtoupper($setting['namasekolah']))
								  ->setCellValue('A4', 'Alamat : '.strtoupper($setting['alamat']).' - Kecamatan '.strtoupper($setting['kecamatan']).' - Kodepos '.strtoupper($setting['kodepos']))
								  ->setCellValue('A8', ucwords($namasiswa))
								  ->setCellValue('A9', 'Periode : '.tgl_indo($tglawal).' Sampai '.tgl_indo($tglakhir))
								  ->setCellValue('D'.$row2, ucwords($setting['kelurahan']).', '.$hari_ini)
	                              ->setCellValue('D'.$row3, ucwords($setting['namakepsek']))
								  ->setCellValue('D'.$row4, "NIP. ".$setting['nipkepsek']);
		}
     
		$objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		  
		$objWriter->save(APPPATH."../assets/templates/data_absensi_".strtolower($namasiswa).".xls");    //Simpan sebagai apalah.xlsx 
		//Unduh file
		$this->load->helper('download'); 
		$data = file_get_contents(APPPATH."../assets/templates/data_absensi_".strtolower($namasiswa).".xls");
		$name = 'data_absensi_'.strtolower($namasiswa).'.xls';		  
		force_download($name, $data); 
		//var_dump($name);
		//delete
	}
	
} //end	