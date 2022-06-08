<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Media extends MY_Controller
{
    private $title;
    private $front_template;
    private $admin_template;

    private $limit = 5;

    /**
     * Modules instalation.
     *
     * @return JSON
     **/
    public function module()
    {
        $module = [
            'name' => 'Media',
            'menu_link' => ['media/index' => 'View media'],
            'table' => '',
            'description' => 'Module media',
        ];

        return $module;
    }

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Image_moo');
        $this->load->library('upload');

        $this->title = 'Media Library';
        $this->front_template = 'template/front_template';
        $this->admin_template = 'template/admin_template';
        $this->layout->auth();
    }

    public function index()
    {
        $this->load->model('mediaModel');

        $this->db->order_by('id', 'desc');
        $this->db->join('users', 'users.id = media.id_user', 'left');
        $this->db->select('users.id AS user_id, media.*, users.username');
        $data['media'] = $this->mediaModel->search(null, $this->limit);
        $data['count'] = $this->mediaModel->count() <= $this->limit;

        $template_data['js_plugins'] = [
            base_url('assets/plugins/dropzone/dist/min/dropzone.min.js'),
            base_url('assets/js/media.js')
        ];
        $template_data['title'] = 'Media Library';
        $template_data['crumb'] = [
            'Media Library' => '',
        ];

        $this->layout->set_wrapper('list', $data);
        $this->layout->render('admin', $template_data);
    }

    public function loadMore()
    {
        $start = $this->input->get('start');
    
        if ($this->input->is_ajax_request()) {
            $this->load->model('mediaModel');
    
            $this->db->order_by('id', 'desc');
            $this->db->join('users', 'users.id = media.id_user', 'left');
            $this->db->select('users.id AS user_id, media.*, users.username');
            $media = $this->mediaModel->search(null, $this->limit, $start);
            $count = $this->mediaModel->count();

            $image = [];
            foreach ($media as $key => $value) {
                $image[] = [
                    'id' => $value->id,
                    'src' => base_url('assets/uploads/image/' . $value->name . '-thumb' . $value->ext),
                    'file' => $value->file,
                    'name' => $value->name,
                    'uploaded_at' => $value->uploaded_at,
                    'user' => $value->username,
                    'ext' => $value->ext
                ];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode(['count' => $count, 'image' => $image]));
        } else {
            $this->output->set_content_type('application/json')->set_output(json_encode(false));
        }
    }

    public function upload()
    {
        if (!empty($_FILES)) {
            $config['upload_path'] = './assets/uploads/image/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['overwrite'] = FALSE;
            $config['max_size'] = '2000';
            $this->upload->initialize($config);
            if ($this->upload->do_upload('file')) {
                $fileData = $this->upload->data();
                $thumbnail = $fileData['raw_name'] . '-thumb' . $fileData['file_ext'];
                $fullPath = $fileData['full_path'];
                $newPath = $config['upload_path'] . $thumbnail;
                $this->image_moo->load($fullPath)->resize_crop(300, 200)->save($newPath, FALSE);

                $this->load->model('mediaModel');
                $data = [
                    'name' => $fileData['raw_name'],
                    'file' => 'assets/uploads/image/' . $fileData['file_name'],
                    'ext' => $fileData['file_ext'],
                    'id_user' => $this->ion_auth->user()->row()->id,
                    'uploaded_at' => date('Y-m-d H:i:s')
                ];
                $this->mediaModel->save($data);
                $this->output->set_content_type('application/json')->set_output(json_encode([
                    'thumbnail' => $thumbnail,
                    'id' => $this->db->insert_id(),
                    'username' => $this->ion_auth->user()->row()->username,
                    'data' => $data
                ]));
            } else {
                $this->output->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(false));
            }
        }
    }

    public function delete()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            $name = $this->input->post('name');
            $ext = $this->input->post('ext');

            if ($id) {
                $this->load->model('mediaModel');
                try {
                    $this->mediaModel->delete(['id' => $id]);
                    unlink('./assets/uploads/image/' . $name . $ext);
                    unlink('./assets/uploads/image/' . $name . '-thumb' . $ext);

                    $this->output->set_content_type('application/json')->set_output(json_encode($id));
                } catch (Exception $e) {
                    $this->output->set_content_type('application/json')->set_output(json_encode(false));
                }
            }
        }
    }

    public function multiDelete()
    {
        if ($this->input->is_ajax_request()) {
            $multi = $this->input->post('multi');
            $multi = json_decode($multi);
            if (!empty($multi)) {
                $id = [];

                $this->load->model('mediaModel');
                try {
                    foreach ($multi as $key => $value) {
                        if ($value->id) {
                            $id[] = $value->id;
                            unlink('./assets/uploads/image/' . $value->name . $value->ext);
                            unlink('./assets/uploads/image/' . $value->name . '-thumb' . $value->ext);
                        }
                    }
                    $this->mediaModel->delete('id IN(' . implode(',', $id) . ')');

                    $this->output->set_content_type('application/json')->set_output(json_encode(true));
                } catch (Exception $e) {
                    $this->output->set_content_type('application/json')->set_output(json_encode(false));
                }
            }
        }
    }
}

/* End of file media.php */
/* Location: ./application/controllers/media.php */
