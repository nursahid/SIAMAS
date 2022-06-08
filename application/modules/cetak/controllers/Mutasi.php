<?php if (!defined('BASEPATH'))  exit('No direct script access allowed');

class Mutasi extends MY_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model(array('laporan_model','settings_model','kesiswaan/siswa_model',
								 'kesiswaan/siswamasuk_model','kesiswaan/siswakeluar_model',
								 ));
		$this->load->library(array('settings','fpdf','PdfGenerator'));
    }
	public function index() {		
		$tapel_aktif	= $this->sistem_model->get_tapel_aktif();
		//cek groups
		if($this->ion_auth->in_group(array('superadmin', 'admin'))) {
			$this->layout->set_wrapper('mutasi_index', $data);
		} 						
		//View
		$template_data['title'] = 'Print ';
		$template_data['subtitle'] = 'Cetak Data Mutasi Siswa';
        $template_data['crumb'] = [' Cetak' => 'cetak', ' Data Siswa Mutasi' => 'cetak/mutasi'];
		
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
	
	public function cetak() {
		$status 	= $this->input->post('status');		
		$cetak_ke 	= $this->input->post('cetak_ke');
		//cek group
		if($this->ion_auth->in_group(array('superadmin', 'admin'))) {
			//buat admin
			$redirect_back	= 'cetak/index';
			$data['back_button']	= 'cetak/index';
		} 
		elseif($this->ion_auth->in_group('guru')) {
			//buat guru
			$redirect_back	= 'cetak/mutasi';
			$data['back_button']	= 'cetak/mutasi';
		}
		
		if($status == '' || $cetak_ke == ''){
			$this->session->set_flashdata('message1a', '<div class="alert alert-danger">Data Status Siswa Belum Ada</div>');
			redirect(site_url($redirect_back));
		} 
		elseif($status == 'masuk') {
			$query = $this->siswamasuk_model->get_all();
			if($cetak_ke == 'printer'){
				$data['data'] = $query;
				$data['status'] = 'mutasi masuk';
				$data['setting'] = $this->settings->get();
				$data['pagination']= $this->pagination->create_links();
				$data['number']= (int)$this->uri->segment(3) +1;
				//View
				$template_data['title'] = 'Print ';
				$template_data['subtitle'] = 'Cetak Data';
				$template_data['crumb'] = ['Cetak' => 'cetak/mutasi','Mutasi Masuk' => 'cetak/mutasi/cetak',];
				
				$this->layout->set_wrapper('siswamasuk_print', $data);
				$this->layout->auth();
				$this->layout->render('admin', $template_data);
			
			} elseif($cetak_ke == 'pdf') {
				$data['data'] 	= $query;
				$data['status'] = $infostatus;
				$data['number'] = (int)$this->uri->segment(3) +1;
				$data['setting'] = $this->settings->get();
				//use dompdf
				$pdf_filename 	= 'datasiswa_mutasimasuk_'.date('dmY').'.pdf';
				$paper 	  	  	= 'letter';
				$orientation  	= 'landscape';	//potrait		
				$html 			= $this->load->view('siswamasuk_dompdf', $data, true);
				$this->pdfgenerator->generate($html,$pdf_filename, $paper, $orientation);
			
			} elseif($cetak_ke == 'excel') {
				$this->_do_siswamasuk_excel($status);
			}
		} 
		elseif($status == 'keluar') {
			$query = $this->siswakeluar_model->get_data();
			if($cetak_ke == 'printer'){
				$data['data'] = $query;
				$data['status'] = 'mutasi masuk';
				$data['setting'] = $this->settings->get();
				$data['pagination']= $this->pagination->create_links();
				$data['number']= (int)$this->uri->segment(3) +1;
				//View
				$template_data['title'] = 'Print ';
				$template_data['subtitle'] = 'Cetak Data';
				$template_data['crumb'] = ['Cetak' => 'cetak/mutasi','Mutasi Keluar' => 'cetak/mutasi/cetak',];
				
				$this->layout->set_wrapper('siswakeluar_print', $data);
				$this->layout->auth();
				$this->layout->render('admin', $template_data);
			
			} elseif($cetak_ke == 'pdf') {
				$data['data'] 	= $query;
				$data['status'] = $infostatus;
				$data['number'] = (int)$this->uri->segment(3) +1;
				$data['setting'] = $this->settings->get();
				//use dompdf
				$pdf_filename 	= 'datasiswa_mutasikeluar_'.date('dmY').'.pdf';
				$paper 	  	  	= 'letter';
				$orientation  	= 'landscape';	//potrait		
				$html 			= $this->load->view('siswakeluar_dompdf', $data, true);
				$this->pdfgenerator->generate($html,$pdf_filename, $paper, $orientation);
			
			} elseif($cetak_ke == 'excel') {
				$this->_do_siswakeluar_excel($status);
			}			
		}
	}
	//excel mutasi masuk
	public function _do_siswamasuk_excel($status) {
		$status = $this->input->post('status');
		//Ambil library
		$this->load->library('excel_2013');
		/** PHPExcel_IOFactory */
		require_once APPPATH."/third_party/PHPExcel/IOFactory.php";
		//load template excel
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load(APPPATH."../assets/templates/datasiswa_mutasimasuk_tpl.xls");

		//database
		$data = $this->siswamasuk_model->get_all();		
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
		$objDrawing->setCoordinates('C2');
		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

		$objDrawing2 = new PHPExcel_Worksheet_MemoryDrawing();
		$objDrawing2->setImageResource($logokabupaten);
		$objDrawing2->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
		$objDrawing2->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
		$objDrawing2->setHeight(80);
		$objDrawing2->setCoordinates('J2');
		$objDrawing2->setWorksheet($objPHPExcel->getActiveSheet());
		
		//hari ini
		$hari_ini = tgl_indo(date('Y-m-d'));		
		//$row2 = '2';
		 
		//Baris pengisian data
		$baseRow = 11;
		foreach($data as $r => $dataRow) {
			$row = $baseRow + $r;
			$row2 = $row+4;
			$row3 = $row+9;
			$row4 = $row+10;
			
			$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $r+1)
	                              ->setCellValue('B'.$row, $dataRow->nama)
	                              ->setCellValue('C'.$row, "".$dataRow->nis."/".$dataRow->nisn)
	                              ->setCellValue('D'.$row, "'".$dataRow->nik)
	                              ->setCellValue('E'.$row, $dataRow->kelamin)
	                              ->setCellValue('F'.$row, $dataRow->tempat_lahir)
	                              ->setCellValue('G'.$row, tgl_indo($dataRow->tgl_lahir))
	                              ->setCellValue('H'.$row, $dataRow->agama)
	                              ->setCellValue('I'.$row, $dataRow->masuk_dikelas)
	                              ->setCellValue('J'.$row, tgl_indo($dataRow->tgl_mutasi_masuk))
								  ->setCellValue('K'.$row, "".$dataRow->keterangan)
	                              ->setCellValue('A2', 'PEMERINTAH KABUPATEN  '.strtoupper($setting['kabupaten']))
								  ->setCellValue('A3', ''.strtoupper($setting['namasekolah']))
								  ->setCellValue('A4', 'Alamat : '.strtoupper($setting['alamat']).' - Kecamatan '.strtoupper($setting['kecamatan']).' - Kodepos '.strtoupper($setting['kodepos']))
								  ->setCellValue('A7', 'DATA SISWA MUTASI '.strtoupper($status))
								  ->setCellValue('I'.$row2, ucwords($setting['kelurahan']).', '.$hari_ini)
	                              ->setCellValue('I'.$row3, ucwords($setting['namakepsek']))
								  ->setCellValue('I'.$row4, "NIP. ".$setting['nipkepsek']);
		}
     
		$objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		  
		$objWriter->save(APPPATH."../assets/templates/datasiswa_mutasimasuk_".strtolower($status).".xls");    //Simpan sebagai apalah.xlsx 
		//Unduh file
		$this->load->helper('download'); 
		$data = file_get_contents(APPPATH."../assets/templates/datasiswa_mutasimasuk_".strtolower($status).".xls");
		$name = 'datasiswa_mutasimasuk_'.strtolower($status).'.xls';		  
		force_download($name, $data); 
		//var_dump($name);
		//delete
	}
	
	//excel mutasi keluar
	public function _do_siswakeluar_excel($status) {
		$status = $this->input->post('status');
		//Ambil library
		$this->load->library('excel_2013');
		/** PHPExcel_IOFactory */
		require_once APPPATH."/third_party/PHPExcel/IOFactory.php";
		//load template excel
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load(APPPATH."../assets/templates/datasiswa_mutasikeluar_tpl.xls");
		//database
		$data = $this->siswakeluar_model->get_data();		
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
		$objDrawing->setCoordinates('C2');
		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

		$objDrawing2 = new PHPExcel_Worksheet_MemoryDrawing();
		$objDrawing2->setImageResource($logokabupaten);
		$objDrawing2->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
		$objDrawing2->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
		$objDrawing2->setHeight(80);
		$objDrawing2->setCoordinates('J2');
		$objDrawing2->setWorksheet($objPHPExcel->getActiveSheet());
		
		//hari ini
		$hari_ini = tgl_indo(date('Y-m-d'));		
		//$row2 = '2';
		 
		//Baris pengisian data
		$baseRow = 11;
		foreach($data as $r => $dataRow) {
			$row = $baseRow + $r;
			$row2 = $row+4;
			$row3 = $row+9;
			$row4 = $row+10;
			
			$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $r+1)
	                              ->setCellValue('B'.$row, $dataRow->nama)
	                              ->setCellValue('C'.$row, "".$dataRow->nis."/".$dataRow->nisn)
	                              ->setCellValue('D'.$row, "'".$dataRow->nik)
	                              ->setCellValue('E'.$row, $dataRow->kelamin)
	                              ->setCellValue('F'.$row, $dataRow->tempat_lahir)
	                              ->setCellValue('G'.$row, tgl_indo($dataRow->tgl_lahir))
	                              ->setCellValue('H'.$row, $dataRow->agama)
	                              ->setCellValue('I'.$row, $dataRow->jenis_mutasi)
	                              ->setCellValue('J'.$row, tgl_indo($dataRow->tanggal_mutasi))
								  ->setCellValue('K'.$row, "".$dataRow->keterangan)
	                              ->setCellValue('A2', 'PEMERINTAH KABUPATEN  '.strtoupper($setting['kabupaten']))
								  ->setCellValue('A3', ''.strtoupper($setting['namasekolah']))
								  ->setCellValue('A4', 'Alamat : '.strtoupper($setting['alamat']).' - Kecamatan '.strtoupper($setting['kecamatan']).' - Kodepos '.strtoupper($setting['kodepos']))
								  ->setCellValue('A7', 'DATA SISWA MUTASI '.strtoupper($status))
								  ->setCellValue('I'.$row2, ucwords($setting['kelurahan']).', '.$hari_ini)
	                              ->setCellValue('I'.$row3, ucwords($setting['namakepsek']))
								  ->setCellValue('I'.$row4, "NIP. ".$setting['nipkepsek']);
		}
     
		$objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		  
		$objWriter->save(APPPATH."../assets/templates/datasiswa_mutasikeluar_".strtolower($status).".xls");    //Simpan sebagai apalah.xlsx 
		//Unduh file
		$this->load->helper('download'); 
		$data = file_get_contents(APPPATH."../assets/templates/datasiswa_mutasikeluar_".strtolower($status).".xls");
		$name = 'datasiswa_mutasikeluar_'.strtolower($status).'.xls';		  
		force_download($name, $data); 
		//var_dump($name);
		//delete
	}
	
} //end	