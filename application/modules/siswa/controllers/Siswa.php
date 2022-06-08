<?php defined('BASEPATH') or exit('No direct script access allowed');

class Siswa extends Members_Controller
{
    private $title;
    private $front_template;
    private $admin_template;

    public function __construct()
    {
        parent::__construct();

        $this->load->model(array('members_model', 'bayar_model', 'pembelajaran/kelas_model')); // Load model
        $this->front_template = 'template/front_template'; // Set frontend template
        $this->admin_template = 'template/admin_template'; // Set backend template
    }
	
	public function index()
    {
		redirect('siswa/home');
	}
    /**
     * Frontend 
     */
    public function home()
    {
        // get current user id
        $id 	= $this->auth->userid();
		//get data siswa
		$rows = $this->members_model->get_by('id',$id);
		//$nis = $this->session->userdata('nis');
		$nis = $rows->nis;
		//$nis 	= $this->session->userdata('nis');
		$tapel 	= $this->sistem_model->get_tapel_aktif();
		$semester = $this->sistem_model->get_semester_aktif();
		//ambil data sesi
        $data['userdata'] 	= $this->members_model->get_by('id', $id);
		$data['nilai'] 		= $this->db->query('SELECT * FROM nilai_penilaian WHERE id_siswa="'.$id.'"')->result();
		$query = $this->db->get_where('absensi', array('nis' => $nis, 'tahun' => $tapel, 'smt' => $semester), 5, 0);
		$data['absensi'] 	= $query->result();
		$query2 = $this->db->get_where('absensi_mapel', array('nis' => $nis, 'tahun' => $tapel, 'smt' => $semester), 5, 0);
		$data['absensi_mapel'] 	= $query2->result();
		
		$data['jml_pembayaran']	= $this->bayar_model->total_pembayaran($id);
		$data['jml_penilaian']	= $this->bayar_model->total_penilaian($id);

        $template_data['grocery_css'] = [
            base_url('assets/plugins/highlightjs/styles/tomorrow-night-eighties.css'), // Load css from assets directory
            'assets/plugins/iCheck/skins/all.css' // Load css from cdn
        ];
        $template_data['grocery_js'] = [
            base_url('assets/plugins/highlightjs/highlight.pack.js'), // Load js from assets directory
            'assets/plugins/iCheck/icheck.min.js' // Load js from cdn
        ];
        $template_data['title'] = 'Selamat Datang'; // Data send to template
        $template_data['crumb'] = [
            'Dashboard' => 'members/home',
        ];
		
		$this->layout->set_title($template_data['title']); // Set title page
        $this->layout->set_wrapper('home', $data); // Set partial view

        $this->layout->setCacheAssets(); // Cache assets

        $this->layout->render('members', $template_data); // layout in template
    }


	
    /**
     * Backend CRUD Book Page.
     */
    public function crud()
    {
        $this->layout->auth(); // Login required

        $crud = new grocery_CRUD(); // Load library grocery CRUD

        $crud->set_table('book');
        $crud->set_subject('book');

        $data = (array) $crud->render();

        $template_data['grocery_css'] = $data['css_files'];
        $template_data['grocery_js'] = $data['js_files'];
        $template_data['title'] = 'CRUD';
        $template_data['crumb'] = [
            'CRUD' => '',
        ];

        $this->layout->set_wrapper('grocery', $data, 'page', false);

        $this->layout->setCacheAssets(); // Cache assets

        $this->layout->render('admin', $template_data);
    }
	//Logout
    public function logout() {
        // in case you did not autoload the library
        //$this->load->library('auth');
        
        //$this->auth->logout();
		$this->session->sess_destroy();
        redirect('');
    }
}

/* End of file Members.php */
/* Location: ./application/members/controllers/members.php */