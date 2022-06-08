<?php if (!defined('BASEPATH'))  exit('No direct script access allowed');

class Pembayaran extends MY_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model(array('kesiswaan/siswa_model','keuangan/pembayaran_model','keuangan/jenispembayaran_model',
								 'pembelajaran/kelas_model','seting/mapel_model','seting/jurusan_model'));
		$this->load->model('rekapspp_model', 'rekapspp');
		$this->load->library(array('settings','fpdf','PdfGenerator'));
    }
	
	public function index() {
		//cek groups
		if($this->ion_auth->in_group(array('superadmin', 'bendahara'))) {
			$this->layout->set_wrapper('pembayaran_index', $data);
		} 		
		$tapel_aktif	= $this->sistem_model->get_tapel_aktif();
		//dropdown pembayaran siswa
		$data['dropdown_pembayaran'] = $this->jenispembayaran_model->get_jenispembayaran();
		$bln = array(1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
		for ($i=1;$i<=12;$i++){
			$aBln= (strlen($i)==1 ? '0'.$i : $i);
			$dBln[$aBln] = $bln[$i]; 
		}		
		$data['bln']	= $dBln;
		$data['kelas'] = $this->rekapspp->get_kelas();
						
		//View
		$template_data['title'] = 'Print ';
		$template_data['subtitle'] = 'Cetak Data Pembayaran';
        $template_data['crumb'] = ['Cetak' => 'cetak',' Pembayaran' => 'cetak/pembayaran'];
		
		//$this->layout->set_wrapper('pembayaran_index', $data);
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}

	public function cetak() {
		$pembayaran = $this->input->post('pembayaran');
		$kelas 		= $this->input->post('kelas2');
		$siswa		= $this->input->post('idsiswa');
		$cetak_ke 	= $this->input->post('cetak_ke');
		$namasiswa	= $this->siswa_model->get_by('id',$siswa)->nama;
		$namakelas 	= $this->kelas_model->get_by('id',$kelas)->kelas;
		//cek group
		if($this->ion_auth->in_group(array('superadmin', 'admin'))) {
			//buat admin
			$redirect_back	= 'cetak/index';
			$data['back_button']	= 'cetak/index';
		} 
		elseif($this->ion_auth->in_group('bendahara')) {
			//buat bendahara
			$redirect_back	= 'cetak/pembayaran';
			$data['back_button']	= 'cetak/pembayaran';
		}
		if($pembayaran == '0' || $cetak_ke == ''){
			$this->session->set_flashdata('message4', '<div class="alert alert-danger">Data Pembayaran Belum Ada</div>');
			redirect(site_url($redirect_back));
		} else {
			if($cetak_ke == 'printer'){
				$data['setting'] 	= $this->settings->get();
				$data['namapembayaran'] = $this->jenispembayaran_model->get_by('id',$pembayaran)->nama_jenispembayaran;
				$data['pembayaran']	= $pembayaran;
				$data['number'] 	= (int)$this->uri->segment(3) +1;
				//View
				$template_data['title'] = 'Print ';
				$template_data['subtitle'] = 'Cetak Data Pembayaran';
				$template_data['crumb'] = ['Cetak' => 'cetak', 'Pembayaran' => 'cetak/pembayaran/cetak',];

				//cek inputan Siswa
				if (!$siswa) {
					$data['namakelas'] 	= $this->kelas_model->get_by('id',$kelas)->kelas;
					$data['absen'] 	= $this->kelas_model->get_data_siswa_perkelas($kelas);
					$data['post']	= $_POST;
					//ewv
					$this->layout->set_wrapper('pembayaran_print', $data);					
				} else {
					$data['kelas'] 		= $kelas;
					$data['namasiswa']	= $this->siswa_model->get_by('id',$siswa)->nama;
					$data['result'] 	= $this->rekapspp->get_rekap_spp_persiswa($_POST);
					//view
					$this->layout->set_wrapper('pembayaran_printpersiswa', $data);					
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
					$data['absen'] 		= $this->kelas_model->get_data_siswa_perkelas($kelas);
					$data['post']		= $_POST;
					//ewv
					$pdf_filename 	= 'pembayaran_'.strtolower($namakelas).'_'.date('dmY').'.pdf';	
					$html 			= $this->load->view('pembayaran_dompdf', $data, true);
				} else {
					$data['kelas'] 		= $kelas;
					$data['namasiswa']	= $namasiswa;
					$data['result'] 	= $this->rekapspp->get_rekap_spp_persiswa($_POST);
					//view
					$pdf_filename 	= 'pembayaran_'.strtolower($namasiswa).'_'.date('dmY').'.pdf';	
					$html 			= $this->load->view('pembayaran_dompdfpersiswa', $data, true);
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
		$kelas 	= $this->input->post('kelas2');
		$siswa	= $this->input->post('idsiswa');
		$pembayaran = $this->input->post('pembayaran');
		$bulan 	= $this->input->post('bulan');
		$tahun 	= $this->input->post('tahun');
		
		$datakelas	= $this->kelas_model->get_by('id',$kelas);
		$namakelas 	= $datakelas->kelas;
		$namapembayaran = $this->jenispembayaran_model->get_by('id',$pembayaran)->nama_jenispembayaran;
		//Ambil library
		$this->load->library('excel_2013');
		/** PHPExcel_IOFactory */
		require_once APPPATH."/third_party/PHPExcel/IOFactory.php";
		//load template excel
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load(APPPATH."../assets/templates/data_pembayaran_tpl.xls");
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
			
			$data2 = $this->db->get_where('pembayaran', array('id_siswa' => $dataRow->id_siswa, 'id_jnspembayaran' => $pembayaran, 
															 'bulan' => $bulan, 'tahun' => $tahun))->row();
			
			if($data2->tgl_transaksi == 'belum ada data') {
				$tgl_transaksi = '-';
			} else {
				$tgl_transaksi = $data2->tgl_transaksi;
			}
			
			$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $r+1)
	                              ->setCellValue('B'.$row, $dataRow->nama)
	                              ->setCellValue('C'.$row, tgl_indo($tgl_transaksi))
	                              ->setCellValue('D'.$row, $data2->nilai)
	                              ->setCellValue('E'.$row, $data2->keterangan)

	                              ->setCellValue('A2', 'PEMERINTAH KABUPATEN  '.strtoupper($setting['kabupaten']))
								  ->setCellValue('A3', ''.strtoupper($setting['namasekolah']))
								  ->setCellValue('A4', 'Alamat : '.strtoupper($setting['alamat']).' - Kecamatan '.strtoupper($setting['kecamatan']).' - Kodepos '.strtoupper($setting['kodepos']))
								  ->setCellValue('A8', ucwords($namapembayaran))
								  ->setCellValue('A9', ucwords($namakelas))
								  ->setCellValue('D'.$row2, ucwords($setting['kelurahan']).', '.$hari_ini)
	                              ->setCellValue('D'.$row3, ucwords($setting['namakepsek']))
								  ->setCellValue('D'.$row4, "NIP. ".$setting['nipkepsek']);
		}
     
		$objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		  
		$objWriter->save(APPPATH."../assets/templates/data_pembayaran_".strtolower($namakelas).".xls");    //Simpan sebagai apalah.xlsx 
		//Unduh file
		$this->load->helper('download'); 
		$data = file_get_contents(APPPATH."../assets/templates/data_pembayaran_".strtolower($namakelas).".xls");
		$name = 'data_pembayaran_'.strtolower($namakelas).'.xls';		  
		force_download($name, $data); 
		//var_dump($name);
		//delete
	}
	//persiswa
	public function _do_excel_persiswa($kuis) {
		$kelas 	= $this->input->post('kelas2');
		$siswa	= $this->input->post('idsiswa');
		$pembayaran = $this->input->post('pembayaran');
		$bulan 	= $this->input->post('bulan');
		$tahun 	= $this->input->post('tahun');
		
		$datakelas	= $this->kelas_model->get_by('id',$kelas);
		$namakelas 	= $datakelas->kelas;
		$namasiswa	= $this->siswa_model->get_by('id',$siswa)->nama;
		$namapembayaran = $this->jenispembayaran_model->get_by('id',$pembayaran)->nama_jenispembayaran;
		//Ambil library
		$this->load->library('excel_2013');
		/** PHPExcel_IOFactory */
		require_once APPPATH."/third_party/PHPExcel/IOFactory.php";
		//load template excel
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load(APPPATH."../assets/templates/data_pembayaran_tpl.xls");
		//Ambil datanya 
		//$data = $this->kelas_model->get_data_siswa_perkelas($kelas)->result();
		$data = $this->pembayaran_model->get_rekap_spp_persiswa($pembayaran, $siswa, $bulan, $tahun);

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
	                              ->setCellValue('B'.$row, $dataRow->no_referensi)
	                              ->setCellValue('C'.$row, tgl_indo($dataRow->tgl_transaksi))
	                              ->setCellValue('D'.$row, $dataRow->nilai)
	                              ->setCellValue('E'.$row, $dataRow->keterangan)

	                              ->setCellValue('A2', 'PEMERINTAH KABUPATEN  '.strtoupper($setting['kabupaten']))
								  ->setCellValue('A3', ''.strtoupper($setting['namasekolah']))
								  ->setCellValue('A4', 'Alamat : '.strtoupper($setting['alamat']).' - Kecamatan '.strtoupper($setting['kecamatan']).' - Kodepos '.strtoupper($setting['kodepos']))
								  ->setCellValue('A8', ucwords($namapembayaran))
								  ->setCellValue('A9', ucwords($namasiswa))
								  ->setCellValue('D'.$row2, ucwords($setting['kelurahan']).', '.$hari_ini)
	                              ->setCellValue('D'.$row3, ucwords($setting['namakepsek']))
								  ->setCellValue('D'.$row4, "NIP. ".$setting['nipkepsek']);
		}
     
		$objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		  
		$objWriter->save(APPPATH."../assets/templates/data_pembayaran_".strtolower($namasiswa).".xls");    //Simpan sebagai apalah.xlsx 
		//Unduh file
		$this->load->helper('download'); 
		$data = file_get_contents(APPPATH."../assets/templates/data_pembayaran_".strtolower($namasiswa).".xls");
		$name = 'data_pembayaran_'.strtolower($namasiswa).'.xls';		  
		force_download($name, $data); 
		//var_dump($name);
		//delete
	}
	
} //end	