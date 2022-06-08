<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Import Controller.
 */
class Import extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Import";
		$this->load->model('siswa_model');
		//$this->load->model('csv_import_model');
		//$this->load->library('csvimport');
		$this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
		//$this->load->library('excel');
    }

    /**
     * Import
     */
    public function index() {
		//VIEW
		$this->layout->set_title('Import Data');
		$this->layout->set_wrapper('siswa_import_excel', $data);			
		$template_data['title'] = 'Import Data';
		$template_data['subtitle'] = 'Import Data Siswa';
		$template_data['crumb'] = ['Kesiswaan' => 'kesiswaan','Import' => 'kesiswaan/import',];
		$this->layout->render('admin', $template_data); 		   
	}
	//---------------
	// Import Excel
	//---------------
    public function uploadexcel(){
        $fileName = $_FILES['userfile']['name'];
         
        $config['upload_path'] = './assets/uploads/excel/'; //buat folder dengan nama assets di root folder
        $config['file_name'] = $fileName;
        $config['allowed_types'] = 'xls|xlsx|csv';
		$config['overwrite'] = true;
        $config['max_size'] = 20480;
         
        $this->load->library('upload');
        $this->upload->initialize($config);
         
        if(! $this->upload->do_upload('userfile') ) {
			$this->session->set_flashdata('message','<b>Error: </b>'.$this->upload->display_errors().''); 
			redirect('kesiswaan/import/','refresh');
		} else {
			$media = $this->upload->data();
			$inputFileName = FCPATH.'assets/uploads/excel/'.$media['file_name'];
			if (! is_readable($inputFileName)) die('cant read file, check permissions');
			try {
				$inputFileType = IOFactory::identify($inputFileName);
				$objReader = IOFactory::createReader($inputFileType);
				$objPHPExcel = $objReader->load($inputFileName);
			} catch(Exception $e) {
				$error = ('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
				$this->session->set_flashdata('message','<b>Error: </b>'.$error.''); 
				redirect('kesiswaan/import/','refresh');
			}
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
             
            for ($row = 2; $row <= $highestRow; $row++){                  //  Read a row of data into an array                 
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                                NULL,
                                                TRUE,
                                                FALSE);
                                                 
                //Sesuaikan sama nama kolom tabel di database
				$d = explode('/', $rowData[0][4]);
				$date = $d[2].'-'.$d[1].'-'.$d[0];
                 $data = array(
                    "nama"			=> $rowData[0][0],
                    "nis"			=> $rowData[0][1],
                    "nisn"			=> $rowData[0][2],
                    "nik"			=> $rowData[0][3],
                    "tempat_lahir"	=> $rowData[0][4],
					"tgl_lahir"		=> $rowData[0][5],
					"kelamin"		=> $rowData[0][6],
					"agama"			=> $rowData[0][7],
					"alamat"		=> $rowData[0][8],
					"anak_ke"		=> $rowData[0][9],
					"jml_saudara"	=> $rowData[0][10],
					"hp_ortu"		=> $rowData[0][11],
					"program_studi"	=> $rowData[0][12],
					"tgl_daftar"	=> $rowData[0][13]
					//"tgl_daftar"	=> date("Y-m-d",strtotime($rowData[0][11]))
                );
                 
                //sesuaikan nama dengan nama tabel
                $insert = $this->insert_ignore("siswa",$data);
				//$this->db->insert_batch('siswa', $data);
                unlink($media['file_path']);
            }			
		}
        //pesan sukses
		$count = $highestRow;
		$this->session->set_flashdata('message','Upload berhasil, Total: <b>'.$count.'</b> data.'); 
		redirect('kesiswaan/import/','refresh');
    }
	public function ExcelDataAdd()	{  
		//Path of files were you want to upload on localhost (C:/xampp/htdocs/ProjectName/uploads/excel/)	 
        $configUpload['upload_path'] = FCPATH.'assets/uploads/excel/';
        $configUpload['allowed_types'] = 'xls|xlsx|csv';
        $configUpload['max_size'] = '50000';
        
		$this->load->library('upload', $configUpload);
        $this->upload->do_upload('file');
		
        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
        $file_name 	 = $upload_data['file_name']; //uploded file name
		$extension	 = $upload_data['file_ext'];    // uploded file extension
		
		//$objReader = PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003 
		$objReader	 = PHPExcel_IOFactory::createReader('Excel2007');	// For excel 2007 	  
        //Set to read only
        $objReader->setReadDataOnly(true); 		  
        //Load excel file
		$objPHPExcel	= $objReader->load(FCPATH.'assets/uploads/excel/'.$file_name);		 
        $totalrows		= $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel      	 
        $objWorksheet	= $objPHPExcel->setActiveSheetIndex(0);                
        //loop from first data untill last data
        for($i=2;$i<=$totalrows;$i++)
          {
              $nama	= $objWorksheet->getCellByColumnAndRow(0,$i)->getValue();			
              $nisn		= $objWorksheet->getCellByColumnAndRow(1,$i)->getValue(); //Excel Column 1
			  $nik		= $objWorksheet->getCellByColumnAndRow(2,$i)->getValue(); //Excel Column 2
			  $tempat_lahir		= $objWorksheet->getCellByColumnAndRow(3,$i)->getValue(); //Excel Column 3
			  $tgl_lahir		= $objWorksheet->getCellByColumnAndRow(4,$i)->getValue(); //Excel Column 4
			  
			  $data_user	= array('nama'=>$nama,
									'nis'=>$nisn,
									'nik'=>$nik,
									'tempat_lahir'=>$tempat_lahir, 
									'tgl_lahir'=>$tgl_lahir
								);
			  
			  $this->insert_ignore('siswa', $data_user);
              
						  
        }
        unlink('././assets/uploads/excel/'.$file_name); //File Deleted After uploading in database .			 
        redirect('kesiswaan/import/','refresh');
	           
       
    }
	
	//=============
	function fetch() {
		$data = $this->siswa_model->select();
		$output = '
		<h3 align="center">Total Data - '.$data->num_rows().'</h3>
		<table class="table table-striped table-bordered">
			<tr>
				<th>Nama</th>
				<th>NISN</th>
				<th>NIK</th>
				<th>Tempat Lahir</th>
				<th>Tgl. Lahir</th>
			</tr>
		';
		foreach($data->result() as $row) {
			$output .= '
		   <tr>
				<td>'.$row->nama.'</td>
				<td>'.$row->nis.'</td>
				<td>'.$row->nik.'</td>
				<td>'.$row->tempat_lahir.'</td>
				<td>'.$row->tgl_lahir.'</td>
		   </tr>
		   ';
		}
		$output .= '</table>';
		echo $output;
	}
	function import() {
		if(isset($_FILES["file"]["name"])) {
			$path = $_FILES["file"]["tmp_name"];
			$object = PHPExcel_IOFactory::load($path);
			foreach($object->getWorksheetIterator() as $worksheet) {
				$highestRow = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();
				for($row=2; $row<=$highestRow; $row++) {
					$nama = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					$nisn = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
					$nik = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
					$tempat_lahir = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
					$tgl_lahir = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
					$data[] = array(
					  'Nama'  => $nama,
					  'nisn'   => $nisn,
					  'nik'    => $nik,
					  'TempatLahir'  => $tempat_lahir,
					  'tgl_lahir'   => $tgl_lahir
					);
				}
			}
			//$this->insert_ignore("siswa",$data);
			$this->db->insert_batch('siswa', $data);
			echo 'Data Imported successfully';
		} 
	}

	
	//insert ignore
	protected function insert_ignore($table,array $data) {
        $_prepared = array();
         foreach ($data as $col => $val)
        $_prepared[$this->db->escape_str($col)] = $this->db->escape($val); 
        $this->db->query('INSERT IGNORE INTO '.$table.' ('.implode(',',array_keys($_prepared)).') VALUES('.implode(',',array_values($_prepared)).');');
    }
	
}

/* End of file Import.php */
/* Location: ./application/modules/siswa/controllers/Import.php */