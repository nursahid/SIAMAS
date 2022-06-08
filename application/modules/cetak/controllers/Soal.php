<?php if (!defined('BASEPATH'))  exit('No direct script access allowed');

class Soal extends MY_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model(array('soal/soal_model','soal/itemsoal_model','pegawai/pegawai_model',
								 'pembelajaran/kelas_model','seting/mapel_model','seting/jurusan_model'));
		$this->load->library(array('settings','fpdf','PdfGenerator'));
    }
	public function index() {		
		$tapel_aktif	= $this->sistem_model->get_tapel_aktif();
		//dropdown kelas pada tapel aktif
		$data['dropdown_tingkat'] 	= $this->kelas_model->get_data_tingkat();
		//View
		$template_data['title'] 	= 'Print ';
		$template_data['subtitle'] 	= 'Cetak Data Soal';
        $template_data['crumb'] 	= ['Cetak' => 'cetak', 'Soal' => 'cetak/soal'];
		//cek group
		if($this->ion_auth->in_group(array('superadmin', 'admin'))) {
			$this->layout->set_wrapper('soal_index', $data);
		} 
		elseif($this->ion_auth->in_group('guru')) {
			//dropdown mapel yang diampu
			$user 	 = $this->ion_auth->user()->row()->id;
			$id_guru = $this->pegawai_model->get_by('id_user',$user)->id;
			$data['dropdown_mapel'] = $this->kelas_model->get_dropdown_mapel_by_guru($id_guru);
			$data['id_guru'] 		=  $id_guru;
			$data['id_user'] 		=  $user;
			//view
			$this->layout->set_wrapper('soal_index_guru', $data);
		}		
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
	public function cetak() {		
		//cek dulu
		if(!$_POST['tingkat']) {
			//get id_tingkat by id_mapel, id_guru
			$id_tingkat 	= $this->kelas_model->get_idkelas_by_mapelguru($_POST['mapel'], $_POST['id_guru'] );
		} else {
			$id_tingkat 	= $_POST['tingkat'];
		}
		$mapel 	= $_POST['mapel'];
		$kuis 	= $_POST['kuis'];
		//$acak 	= $_POST['acak'];
		$limit 	= $_POST['jml_cetak'];
		$limit_essay = $_POST['jml_cetak_essay'];
		$cetak_ke 	= $_POST['cetak_ke'];

		$datakelas	= $this->kelas_model->get_by('id_tingkat',$id_tingkat);
		$datamapel	=$this->mapel_model->get_by('id',$mapel);
		//cek group
		if($this->ion_auth->in_group(array('admin', 'members'))) {
			//buat admin
			$redirect_back	= 'cetak/index';
			$data['back_button']	= 'cetak/index';
		} 
		elseif($this->ion_auth->in_group('guru')) {
			//buat guru
			$redirect_back	= 'cetak/soal';
			$data['back_button']	= 'cetak/soal';
		}
		if($mapel == '' || $kuis == '' || $cetak_ke == ''){
			$this->session->set_flashdata('message3', '<div class="alert alert-danger">Data Mapel / Kuis Belum Ada</div>');
			redirect(site_url($redirect_back));
		} else {
			if($cetak_ke == 'printer'){
				//tampilkan dulu daftar kuis
				$data['soalmultiple'] 	= $this->soal_model->get_soal_multiple($mapel,$kuis,$limit);
				$data['soalessay'] 		= $this->soal_model->get_soal_essay($mapel,$kuis,$limit_essay);
				
				$data['mapel'] 		= $mapel;
				$data['namakelas'] 	= $datakelas->kelas;
				$data['jenjang'] 	= $this->jurusan_model->get_by('id',$datakelas->id_jurusan)->jurusan;
				$data['setting'] 	= $this->settings->get();
				$data['pagination']	= $this->pagination->create_links();
				$data['number']= (int)$this->uri->segment(3) +1;
				$data['number2']= (int)$this->uri->segment(3) +1;
				//View
				$template_data['title'] = 'Print ';
				$template_data['subtitle'] = 'Cetak Data';
				$template_data['crumb'] = ['Cetak' => 'cetak', 'Cetak Soal' => 'cetak/soal/cetak',];
				
				$this->layout->set_wrapper('soal_print', $data);
				$this->layout->auth();
				$this->layout->render('admin', $template_data);
			
			} elseif($cetak_ke == 'pdf') {
				$data['soalmultiple'] 	= $this->soal_model->get_soal_multiple($mapel,$kuis,$limit);
				$data['soalessay'] 		= $this->soal_model->get_soal_essay($mapel,$kuis,$limit);

				$data['mapel'] 	= $mapel;
				$data['namakelas'] = $datakelas->kelas;
				$data['namamapel']	= $datamapel->mapel;
				$data['jenjang'] = $this->jurusan_model->get_by('id',$datakelas->id_jurusan)->jurusan;
				$data['number'] = (int)$this->uri->segment(3) +1;
				$data['number2']= (int)$this->uri->segment(3) +1;
				$data['setting'] = $this->settings->get();
				//use dompdf
				$namapelajaran 	= strtolower($this->mapel_model->get_by('id',$mapel)->mapel);
				$pdf_filename 	= 'soal_'.$namapelajaran.'_'.date('dmY').'.pdf';
				//$pdf_filename 	= = 'soal_'.strtolower(str_replace(" ","-",$datamapel->nama)).'_'.date('dmY');
				
				$paper 	  	  	= 'letter';
				$orientation  	= 'landscape';	//potrait		
				$html 			= $this->load->view('soal_dompdf', $data, true);
				$this->pdfgenerator->generate($html,$pdf_filename, $paper, $orientation);
			
			} elseif($cetak_ke == 'word'){
				//tampilkan dulu daftar kuis
				$data['soalmultiple'] 	= $this->soal_model->get_soal_multiple($mapel,$kuis,$limit);
				$data['soalessay'] 		= $this->soal_model->get_soal_essay($mapel,$kuis,$limit);

				$data['mapel'] 		= $mapel;
				$data['namakelas']	= $datakelas->kelas;
				$data['namamapel']	= $datamapel->mapel;
				$data['jenjang'] 	= $this->jurusan_model->get_by('id',$datakelas->id_jurusan)->jurusan;
				$data['setting'] 	= $this->settings->get();
				$data['pagination']	= $this->pagination->create_links();
				$data['number']= (int)$this->uri->segment(3) +1;
				$data['number2']= (int)$this->uri->segment(3) +1;
				//nama file
				$data['namafile'] 	= 'soal_'.strtolower(str_replace(" ","-",$datamapel->mapel)).'_'.date('dmY');
				//View
				$template_data['title'] = 'Print ';
				$template_data['subtitle'] = 'Cetak Data';
				$template_data['crumb'] = ['Cetak' => 'cetak/siswa',];
				
				$this->layout->set_wrapper('soal_doc', $data);
				$this->layout->auth();
				$this->layout->render('blank', $template_data);
			
			} elseif($cetak_ke == 'excel') {
				$this->_do_excel($kuis);
			}
		}
	}
	public function _do_excel($kuis) {
		$kelas 	= $this->input->post('kelas');
		$mapel 	= $this->input->post('mapel');
		$kuis 	= $this->input->post('kuis');
		//$acak 	= $this->input->post('acak');
		$datakelas	= $this->kelas_model->get_by('id',$kelas);
		$namamapel	= $this->mapel_model->get_by('id',$mapel)->nama;
		$namakelas 	= $datakelas->kelas;
		$jenjang	= $this->jurusan_model->get_by('id',$datakelas->id_jurusan)->nama;
		$limit 		= $this->input->post('jml_cetak');
		//Ambil library
		$this->load->library('excel_2013');
		/** PHPExcel_IOFactory */
		require_once APPPATH."/third_party/PHPExcel/IOFactory.php";
		//load template excel
		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load(APPPATH."../assets/templates/data_soal_tpl.xls");
		//Ambil datanya pegawai
		$data = $this->kuissoal_model->limit($limit,0)->get_many_by('id_kuis',$kuis);		
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
		$objDrawing2->setCoordinates('D2');
		$objDrawing2->setWorksheet($objPHPExcel->getActiveSheet());
		
		//hari ini
		$hari_ini = tgl_indo(date('Y-m-d'));		
		$row2 = '2';
		 
		//Baris pengisian data
		$baseRow = 12;
		foreach($data as $r => $dataRow) {
			$row = $baseRow + $r;
			$row2 = $row+1;
			$row3 = $row+2;
			$row4 = $row+3;
			$row5 = $row+4;
			$row6 = $row+5;
			
			$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $r+1)
	                              ->setCellValue('B'.$row, $dataRow->question)
	                              ->setCellValue('C'.$row2, $dataRow->answer1)
	                              ->setCellValue('C'.$row3, $dataRow->answer2)
	                              ->setCellValue('C'.$row4, $dataRow->answer3)
	                              ->setCellValue('C'.$row5, $dataRow->answer4)
	                              ->setCellValue('C'.$row6, $dataRow->answer5)

	                              ->setCellValue('A2', 'PEMERINTAH KABUPATEN  '.strtoupper($setting['kabupaten']))
								  ->setCellValue('A3', ''.strtoupper($setting['namasekolah']))
								  ->setCellValue('A4', 'Alamat : '.strtoupper($setting['alamat']).' - Kecamatan '.strtoupper($setting['kecamatan']).' - Kodepos '.strtoupper($setting['kodepos']))
								  ->setCellValue('A7', ucwords($namamapel))
								  ->setCellValue('A8', ucwords($jenjang))
								  ->setCellValue('A9', ucwords($namakelas));
		}
     
		$objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		  
		$objWriter->save(APPPATH."../assets/templates/data_soal_".strtolower($mapel).".xls");    //Simpan sebagai apalah.xlsx 
		//Unduh file
		$this->load->helper('download'); 
		$data = file_get_contents(APPPATH."../assets/templates/data_soal_".strtolower($mapel).".xls");
		$name = 'data_pegawai_'.strtolower($mapel).'.xls';		  
		force_download($name, $data); 
		//var_dump($name);
		//delete
	}
	
} //end	