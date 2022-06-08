<?php if (!defined('BASEPATH'))  exit('No direct script access allowed');

class Pegawai extends MY_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model(array('laporan_model','pegawai/pegawai_model','referensi/jabatan_model','settings_model'));
		$this->load->library(array('settings','fpdf','PdfGenerator'));
    }
	public function index() {
		$tapel_aktif	= $this->sistem_model->get_tapel_aktif();
		//cek groups
		if($this->ion_auth->in_group(array('superadmin', 'admin'))) {
			$this->layout->set_wrapper('pegawai_index', $data);
		} 						
		//View
		$template_data['title'] = 'Print ';
		$template_data['subtitle'] = 'Cetak Data Pegawai';
        $template_data['crumb'] = [' Cetak' => 'cetak', ' Data Pegawai' => '#'];
		
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
	//=============================
	//  2. List Data Pegawai
	//=============================
    function lists($status) {
		//$status = $_POST['status'];
		$data['setting'] = $this->settings->get();
		$data['datas'] 	 = $this->pegawai_model->get_many_by('status_kawin',$status);
		$data['status']  = $status;
		
		$data['tanggal'] = parseFormTgl('tanggal');
		$data['tanggalarr'] = date('Ymd',parseFormTgl('tanggal'));
		$data['post']	= $_POST;
		$this->load->view('pegawai_listdata', $data);
    }
	// 3a. Cetak PDF
	public function pdf($status) {
		$data['data'] 	= $this->pegawai_model->get_many_by('status_kawin',$status);
		$data['status'] = $status;
		$data['number'] = (int)$this->uri->segment(3) +1;
		$data['setting'] = $this->settings->get();
		//use dompdf
		$pdf_filename 	= 'datapegawai_'.strtolower($status).'_'.date('dmY').'.pdf';
		$paper 	  	  	= 'letter';
		$orientation  	= 'landscape';	//potrait		
		$html 			= $this->load->view('pegawai_dompdf', $data, true);
		$this->pdfgenerator->generate($html,$pdf_filename, $paper, $orientation);
	}
	//3b. Cetak Excel
	public function excel($status) {
		$this->_do_excel($status);
	}
	
	//----------------------
	//  CETAK
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
			$redirect_back	= 'cetak/pegawai';
			$data['back_button']	= 'cetak/pegawai';
		}
		if($status == '' || $cetak_ke == ''){
			$this->session->set_flashdata('message3', '<div class="alert alert-danger">Data Status Pegawai Belum Ada</div>');
			redirect(site_url($redirect_back));
		} else {
			if($cetak_ke == 'printer'){
				$data['data'] = $this->pegawai_model->get_many_by('status_kawin',$status);
				$data['status'] = $status;
				$data['setting'] = $this->settings->get();
				$data['pagination']= $this->pagination->create_links();
				$data['number']= (int)$this->uri->segment(3) +1;
				//View
				$template_data['title'] = 'Print ';
				$template_data['subtitle'] = 'Cetak Data';
				$template_data['crumb'] = ['Cetak' => 'cetak/siswa',];
				
				$this->layout->set_wrapper('pegawai_print', $data);
				$this->layout->auth();
				$this->layout->render('admin', $template_data);
			
			} elseif($cetak_ke == 'pdf') {
				$data['data'] 	= $this->pegawai_model->get_many_by('status_kawin',$status);
				$data['status'] = $status;
				$data['number'] = (int)$this->uri->segment(3) +1;
				$data['setting'] = $this->settings->get();
				//use dompdf
				$pdf_filename 	= 'datapegawai_'.strtolower($status).'_'.date('dmY').'.pdf';
				$paper 	  	  	= 'letter';
				$orientation  	= 'landscape';	//potrait		
				$html 			= $this->load->view('pegawai_dompdf', $data, true);
				$this->pdfgenerator->generate($html,$pdf_filename, $paper, $orientation);
			
			} elseif($cetak_ke == 'excel') {
				$this->_do_excel($status);
			}
		}
	}
	public function _do_excel($status) {
		//jika status kosong
		if ($status == '') {
			$status = $this->input->post('status');
		}		
		//Ambil library
		$this->load->library('excel_2013');
		/** PHPExcel_IOFactory */
		require_once APPPATH."/third_party/PHPExcel/IOFactory.php";
		//load template excel
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load(APPPATH."../assets/templates/data_pegawai_tpl.xls");
		//Ambil datanya pegawai
		$data = $this->pegawai_model->get_many_by('status_kawin',$status);		
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
		$row2 = '2';
		 
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
	                              ->setCellValue('C'.$row, "'".$dataRow->nipeg)
	                              ->setCellValue('D'.$row, "'".$dataRow->nik)
	                              ->setCellValue('E'.$row, $dataRow->kelamin)
	                              ->setCellValue('F'.$row, $dataRow->tempat_lahir)
	                              ->setCellValue('G'.$row, tgl_indo($dataRow->tgl_lahir))
	                              ->setCellValue('H'.$row, $dataRow->agama)
	                              ->setCellValue('I'.$row, $dataRow->pendidikan)
	                              ->setCellValue('J'.$row, $this->jabatan_model->get($dataRow->jabatan)->jabatan)
								  ->setCellValue('K'.$row, "".$dataRow->status_kepegawaian)
								  ->setCellValue('L'.$row, "".$dataRow->alamat)
	                              ->setCellValue('A2', 'PEMERINTAH KABUPATEN  '.strtoupper($setting['kabupaten']))
								  ->setCellValue('A3', ''.strtoupper($setting['namasekolah']))
								  ->setCellValue('A4', 'Alamat : '.strtoupper($setting['alamat']).' - Kecamatan '.strtoupper($setting['kecamatan']).' - Kodepos '.strtoupper($setting['kodepos']))
								  ->setCellValue('A7', 'DATA PEGAWAI '.strtoupper($status))
								  ->setCellValue('J'.$row2, ucwords($setting['kelurahan']).', '.$hari_ini)
	                              ->setCellValue('J'.$row3, ucwords($setting['namakepsek']))
								  ->setCellValue('J'.$row4, "NIP. ".$setting['nipkepsek']);
		}
     
		$objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		  
		$objWriter->save(APPPATH."../assets/templates/data_pegawai_".strtolower($status).".xls");    //Simpan sebagai apalah.xlsx 
		//Unduh file
		$this->load->helper('download'); 
		$data = file_get_contents(APPPATH."../assets/templates/data_pegawai_".strtolower($status).".xls");
		$name = 'data_pegawai_'.strtolower($status).'.xls';		  
		force_download($name, $data); 
		//var_dump($name);
		//delete
	}
	
} //end	