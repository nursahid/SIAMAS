<?php defined('BASEPATH') or exit('No direct script access allowed');

class Sliders extends MY_Controller {
    private $title;
    private $front_template;
    private $admin_template;

    private $limit = 5;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Image_moo');
		$this->load->library('image_crud');
        $this->load->library('upload');

        $this->front_template = 'template/front_template';
        $this->admin_template = 'template/admin_template';
    }

	public function index() {
        last_url('set'); // save last url
        $crud = new grocery_CRUD();
		
        $crud->set_table('image_sliders');
        $crud->set_subject('Sliders');
		
		$crud->columns('image', 'caption', 'created_at');
		
		$crud->add_fields(["image", "caption"]);
		$crud->edit_fields(["image", "caption"]);
		
        $crud->required_fields('caption', 'slug', 'created_at');
        //display
 		$crud->display_as('caption', 'Caption');
		$crud->display_as('image', 'Gambar (840x450)');
		$crud->display_as('created_at', 'Tanggal Buat');
		//field type
		$crud->field_type("caption", "text");
		$crud->field_type("created_at", "hidden");
		$crud->set_field_upload('image', 'assets/uploads/sliders');
		//require
		$crud->required_fields('caption', 'image');
		//set validation
		$crud->set_rules("caption", "Caption", "required");
		$crud->set_rules("image", "Gambar", "required");
		
		//callback
        $crud->callback_before_insert(array($this, 'created_at_callback'));
        //$crud->callback_before_update(array($this, 'created_at_callback'));
				
		//unset
		$crud->unset_texteditor('caption', 'full_text');
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_read();

        // layout view
		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);
		
		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
        $template_data['caption'] = 'Media Sliders';
        $template_data['crumb'] = ['Media Sliders' => 'admin/sliders',];
		
		$this->layout->render('admin', $template_data);
	}
    public function index2()
    {
		$image_crud = new image_CRUD();
	
		$image_crud->set_primary_key_field('id');
		$image_crud->set_url_field('image');
		$image_crud->set_title_field('caption');
		$image_crud->set_table('image_sliders')
		->set_ordering_field('created_at')
		->set_image_path('assets/uploads/sliders');
		
		//layout
		$data = (array) $image_crud->render();
		
		$this->layout->set_wrapper( 'imagecrud', $data,'page', false);
		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
        $template_data['caption'] = 'Media Sliders';
        $template_data['crumb'] = ['Media Sliders' => 'admin/sliders',];
		$this->layout->auth();
        $this->layout->render('admin', $template_data);
    }

	function _example_output($output = null)
	{
		$this->load->view('example.php',$output);	
	}

    public function upload()
    {
        if (!empty($_FILES)) {
            $config['upload_path'] = './assets/uploads/sliders/';
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

                $this->load->model('sliders_model');
                $data = [
                    'name' => $fileData['raw_name'],
                    'file' => 'assets/uploads/sliders/' . $fileData['file_name'],
                    'ext' => $fileData['file_ext'],
                    'id_user' => $this->ion_auth->user()->row()->id,
                    'uploaded_at' => date('Y-m-d H:i:s')
                ];
                $this->sliders_model->save($data);
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
	
	//================
	//   ALL CALLBACK
	//================
	//Tanggal buat 
	//-----------------------
	public function created_at_callback($post_array, $primary_key) {
		$post_array['created_at'] = date('Y-m-d H:i:s'); 
		
		return $post_array;
	}
	
	
}

/* End of file Sliders.php */
/* Location: ./application/modules/admin/controllers/Sliders.php */
