<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Tag Controller.
 */
class Tag extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

		$this->load->model(array('tag_model'));
    }

    /**
     * Tag Index
     */
	public function index() {
		//buka form pengisian tahun kelulusan
		$data['id_siswa'] = $this->uri->segment(4);
		//$data['status'] = "Alumni";
		//Update field password
		//$this->db->where('id', $id_siswa)->update('siswa', $data);
		//redirect('kesiswaan/siswa');

        // layout view
        $this->layout->set_wrapper('tags_view', $data);
        $this->layout->auth();
        $template_data['title'] = 'Tags';
        $template_data['crumb'] = ['Blog' => 'blog/tags/','Tags' => 'blog/tags/'];
        $this->layout->setCacheAssets();
        $this->layout->render('admin', $template_data);		
	}

	
}

/* End of file Tags.php */
/* Location: ./application/modules/blog/controllers/Tags.php */