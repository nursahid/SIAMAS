<?php defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends Members_Controller
{
    private $title;
    private $front_template;
    private $admin_template;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('members_model', 'pembelajaran/kelas_model')); // Load model
    }
	
    public function index() {
        // get current user id
        $id = $this->auth->userid(); 

        $data['userdata'] = $this->members_model->get_by('id', $id); // Find model via smart model

        $template_data['css_plugins'] = [
            base_url('assets/plugins/toastr/toastr.min.css'), // Load css from assets directory
            'assets/plugins/iCheck/skins/all.css' // Load css from cdn
        ];
        $template_data['js_plugins'] = [
            base_url('assets/plugins/toastr/toastr.min.js'), // Load js from assets directory
            'assets/plugins/iCheck/icheck.min.js' // Load js from cdn
        ];
        $template_data['title'] = 'Update Profil'; // Data send to template
        $template_data['crumb'] = [
			'Profil' => 'members/profile',
        ];

        $this->layout->set_title($template_data['title']); // Set title page
        $this->layout->set_wrapper('profile', $data); // Set partial view

        $this->layout->setCacheAssets(); // Cache assets

        $this->layout->render('members', $template_data); // layout in template
    }
	
	//update
	public function update() {
		//$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|max_length[15]');
		$this->form_validation->set_rules('nama', 'Nama Lengkap', 'trim|required');
		$this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'trim|required');
		$this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'trim|required');

		$id 	= $this->auth->userid();
		//$data = $this->input->post();
		$data = array(
					'nisn' => $this->input->post('nisn',TRUE),
					'nik' => $this->input->post('nik',TRUE),
					'nama' => $this->input->post('nama_lengkap',TRUE),
					'tempat_lahir' => $this->input->post('tmp_lahir',TRUE),
					'tgl_lahir' => $this->input->post('tgl_lahir',TRUE),
					'email' => $this->input->post('email',TRUE),
					'alamat' => $this->input->post('alamat',TRUE),
			);
		if ($this->form_validation->run() == TRUE) {
			$config['upload_path'] = './assets/uploads/siswa/';
			$config['allowed_types'] = 'jpg|png';
			
			$this->load->library('upload', $config);
			
			if (!$this->upload->do_upload('foto')){
				$error = array('error' => $this->upload->display_errors());
			}
			else{
				$data_foto = $this->upload->data();
				$data['foto'] = $data_foto['file_name'];
			}

			$result = $this->members_model->update($id, $data);
			if ($result > 0) {
				$this->updateProfil();
				$this->session->set_flashdata('alert', '<div class="alert alert-success">Data Profile Berhasil diubah</div>');
				redirect('siswa/profile');
			} else {
				$this->session->set_flashdata('alert', '<div class="alert alert-danger">Data Profile Gagal diubah</div>');
				redirect('siswa/profile');
			}
		} else {
			$this->session->set_flashdata('alert', validation_errors());
			redirect('siswa/profile');
		}
	}
	//update password
	public function ubah_password() {
		$this->form_validation->set_rules('passLama', 'Password Lama', 'trim|required');
		$this->form_validation->set_rules('passBaru', 'Password Baru', 'trim|required');
		$this->form_validation->set_rules('passKonf', 'Password Konfirmasi', 'trim|required');

		$id = $this->auth->userid();
		if ($this->form_validation->run() == TRUE) {
			if (md5($this->input->post('passLama')) == $this->userdata->password) {
				if ($this->input->post('passBaru') != $this->input->post('passKonf')) {
					$this->session->set_flashdata('alert', '<div class="alert alert-danger">Password Baru dan Konfirmasi Password harus sama</div>');
					redirect('siswa/profile');
				} else {
					$data = [
						//'password' => md5($this->input->post('passBaru'))
						//'password' =>$this->ion_auth_model->hash_password($this->input->post('passBaru'))
						'password' =>$this->members_model->hash($this->input->post('passBaru'))
					];

					$result = $this->members_model->update($id, $data);
					if ($result > 0) {
						$this->updateProfil();
						$this->session->set_flashdata('alert', '<div class="alert alert-success">Password Berhasil diubah</div>');
						redirect('siswa/profile');
					} else {
						$this->session->set_flashdata('alert', '<div class="alert alert-danger">Password Gagal diubah</div>');
						redirect('siswa/profile');
					}
				}
			} else {
				$this->session->set_flashdata('alert', '<div class="alert alert-danger">Password Salah</div>');
				redirect('siswa/profile');
			}
		} else {
			$this->session->set_flashdata('alert', validation_errors());
			redirect('siswa/profile');
		}
	}
	
}

/* End of file Profile.php */
/* Location: ./application/members/controllers/Profile.php */