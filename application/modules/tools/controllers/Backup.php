<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Backup extends MY_Controller{
    	
    public function __construct() {
        parent::__construct();
        //$this->load->helper('form');
		$this->load->library('settings');
    }

	function index() {
		
		//View
		$template_data['title'] = 'Backup & Restore Database ';
		$template_data['subtitle'] = 'Backup';
        $template_data['crumb'] = ['Backup' => 'tools/backup',];

        //$this->layout->setCacheAssets();		
		$this->layout->set_wrapper('backup/backup', $data);
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
  
	//backup
	function backup_db() {
		$this->load->helper('download');
		$tanggal  = date('YmdHis');
		$namaFile = 'backup_'.$tanggal . '.zip';
		$this->load->dbutil();
		$backup=& $this->dbutil->backup();
		force_download($namaFile, $backup);
	}

	//Restore database
	function restore_db() {
		$this->form_validation->set_error_delimiters('<div class="text-red"><i class="fa fa-times"></i> ', '</div>');

		$this->form_validation->set_rules('datafile', 'File', 'required');
		
		// run validation
		if ($this->form_validation->run() == FALSE) {
			//$this->session->set_flashdata('message', 'Gagal Upload');
			echo json_encode(array('status'=>0, 'pesan' => validation_errors()));
			
		} else {
			// kerjakan proses restore//
			//========================//		  
			//1. upload dulu filenya
			$fupload = $_FILES['datafile'];
			$nama 	 = $_FILES['datafile']['name'];
			if(isset($fupload)){
				$lokasi_file = $fupload['tmp_name'];
				$direktori 	 = "./backups/$nama";
				move_uploaded_file($lokasi_file,"$direktori");
			}
			//restore database
			$isi_file 	  	= file_get_contents($direktori);
			$string_query 	= rtrim($isi_file, "\n;" );
			$array_query	= explode(";", $string_query);
		  
			foreach($array_query as $query){
				$this->db->query($query);
			}			  
			//$this->session->set_flashdata('message', '<div class="alert">Restore database berhasil!</div>');
			echo json_encode(array('status'=>1, 'pesan' => '<div class="text-red"><i class="fa fa-check"></i> Data Berhasil di Restore</div>'));
		}
		//redirect(site_url('backup'));
	}
	
	public function restore() {

        $this->load->helper('file');
        //$this->load->model('sismas_m');
        $config['upload_path']="./backups/";
        $config['allowed_types']="sql|x-sql";
        $this->load->library('upload',$config);
        $this->upload->initialize($config);

        if(!$this->upload->do_upload("datafile")){
			//$error = array('error' => $this->upload->display_errors());
			//echo "GAGAL UPLOAD";
			//var_dump($error);
			echo json_encode(array('status'=>0, 'pesan' => '<div class="text-red"><i class="fa fa-times"></i> '.$this->upload->display_errors().'</div>'));
			exit();
        }

        $file = $this->upload->data();  //DIUPLOAD DULU KE DIREKTORI assets/database/
        $fileupload = $file['file_name'];
                   
        $isi_file = file_get_contents('./backups/' . $fileupload); //PANGGIL FILE YANG TERUPLOAD
        $string_query = rtrim( $isi_file, "\n;" );
		//JALANKAN QUERY MERESTORE KEDATABASE
        $array_query = explode(";", $string_query);
		
        foreach($array_query as $query) {
            $this->db->query($query);
        }

        $path_to_file = './backups/' . $fileupload;
        if(unlink($path_to_file)) {   // HAPUS FILE YANG TERUPLOAD
            //redirect('home/setting');
        }
        else {
            //echo 'errors occured';
        }
		echo json_encode(array('status'=>1, 'pesan' => '<div class="text-red"><i class="fa fa-check"></i> Data Berhasil di Restore</div>'));
    }
	
  
}
/* End of file Backup.php */
/* Location: ./application/controllers/Backup.php */