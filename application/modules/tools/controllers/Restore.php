<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Restore extends MY_Controller{
    	
    public function __construct() {
        parent::__construct();
        $this->load->model('csvimport_model');
		$this->load->library('settings');
		$this->load->library('csvimport');
    }

	function index() {
		
		//View
		$template_data['title'] = 'Restore Database ';
		$template_data['subtitle'] = 'Restore';
        $template_data['crumb'] = ['Restore' => 'tools/restore',];

        //$this->layout->setCacheAssets();		
		$this->layout->set_wrapper('restore', $data);
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
  
	function load_data() {
		$result = $this->csvimport_model->select('settings');
		$output = '
			<h3 align="center">Impor Data from CSV File</h3>
				<div class="table-responsive">
				 <table class="table table-bordered table-striped">
				  <tr>
				   <th>No.</th>
				   <th>Name</th>
				   <th>Value</th>
				  </tr>
		';
		$count = 0;
		if($result->num_rows() > 0) {
			foreach($result->result() as $row)
			{
				$count = $count + 1;
				$output .= '
				<tr>
				 <td>'.$count.'</td>
				 <td>'.$row->name.'</td>
				 <td>'.$row->value.'</td>
				</tr>
				';
			}
		}
		else
		{
			$output .= '
			<tr>
			   <td colspan="5" align="center">Data not Available</td>
			  </tr>
			';
			}
		$output .= '</table></div>';
		echo $output;
	}

	function import() {
		$file_data = $this->csvimport->get_array($_FILES["csv_file"]["tmp_name"]);
		foreach($file_data as $row)
		{
			$data[] = array(
						'name' => $row["Name"],
						'value'  => $row["Value"]
				);
		}
		$this->csvimport_model->insert('settings',$data);
	}
	
  
}
/* End of file Restore.php */
/* Location: ./application/controllers/Restore.php */