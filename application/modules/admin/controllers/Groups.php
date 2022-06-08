<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Groups Controller.
 */

class Groups extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->library('Grocery_CRUD');
        // models
        //$this->load->model('groups_model');
        // helpers
        $this->load->helper('utility_helper');
    }

	public function index() {
        last_url('set'); // save last url
        $this->load->library('Grocery_CRUD');
        $crud = new grocery_CRUD();

        $crud->set_table('groups');
        $crud->set_subject('Groups');
        //$crud->set_theme('flexigrid');
        $crud->callback_after_insert(array($this, 'afterInsertGroup'));
        $crud->callback_after_update(array($this, 'afterUpdateGroup'));
        $crud->callback_after_delete(array($this, 'afterDeleteGroup'));

        $data = (array) $crud->render();

        $this->layout->set_privilege(1);
        $this->layout->set_wrapper('grocery', $data, 'page', false);
        $this->layout->auth();

        $template_data['grocery_css'] = $data['css_files'];
        $template_data['grocery_js'] = $data['js_files'];

        $template_data['title'] = 'Groups';
        $template_data['crumb'] = [
            'Groups' => '',
        ];

        $this->layout->setCacheAssets();

        $this->layout->render('admin', $template_data);
	}
    /**
     * Log group
     * @param  Array $post_array
     * @param  Integer $primary_key
     * @return Boolean
     */
    public function afterInsertGroup($post_array, $primary_key)
    {
        $this->load->model('logs');
        $dataLog = [
        'status' => true,
        'id' => $primary_key,
        'name' => $post_array['name'],
        'ip' => $this->input->ip_address()
        ];
        $this->logs->addLogs('insert_group', $dataLog);

        return true;
    }

    public function afterUpdateGroup($post_array, $primary_key)
    {
        $this->load->model('logs');
        $dataLog = [
        'status' => true,
        'id' => $primary_key,
        'name' => $post_array['name'],
        'ip' => $this->input->ip_address()
        ];
        $this->logs->addLogs('update_group', $dataLog);

        return true;
    }

    public function afterDeleteGroup($primary_key)
    {
        $this->load->model('logs');
        $dataLog = [
        'status' => true,
        'id' => $primary_key,
        'ip' => $this->input->ip_address()
        ];
        $this->logs->addLogs('delete_group', $dataLog);

        return true;
    }
	
}

/* End of file Groups.php */
/* Location: ./application/modules/admin/controllers/Groups.php */