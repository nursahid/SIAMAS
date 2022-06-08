<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Tools Controller.
 */
class Tools extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        //$this->load->model(array('soal_model','seting/mapel_model','pembelajaran/kelas_model', 'pegawai/pegawai_model'));
		//$this->load->model('itemsoal_model');

    }
    /**
     * Index
     */
    public function index()
    {
		//view
		$this->layout->set_wrapper('index', $data);
		$template_data["title"] = "Tools";
		$template_data["subtitle"] = "Alat Bantu";
		$template_data["crumb"] = ["Tools" => "tools",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); 
	}
	//--- Data Soal -----
    public function data()
    {
		$tapel_aktif	= $this->sistem_model->get_tapel_aktif();

		//cek group
		if($this->ion_auth->in_group(array('superadmin', 'admin'))) {
			$data['dropdown_mapel'] = $this->soal_model->dropdown_mapel();
			$this->layout->set_wrapper('index_soal', $data);
		} 
		elseif($this->ion_auth->in_group('guru')) {
			//dropdown mapel yang diampu
			$user 	 = $this->ion_auth->user()->row()->id;
			$id_guru = $this->pegawai_model->get_by('id_user',$user)->id;
			$data['dropdown_mapel'] = $this->soal_model->get_dropdown_mapel_by_guru($id_guru);
			$data['id_guru'] 		=  $id_guru;
			//view
			$this->layout->set_wrapper('index_soal_guru', $data);
		}		
		//View
		$template_data['title'] = 'Soal ';
		$template_data['subtitle'] = 'Data Soal';
        $template_data['crumb'] = ['Soal' => 'soal', 'Data Soal' => 'soal/data'];

		$this->layout->auth();
		$this->layout->render('admin', $template_data);	
	}

}

/* End of file Tools.php */
/* Location: ./application/modules/tools/controllers/Tools.php */