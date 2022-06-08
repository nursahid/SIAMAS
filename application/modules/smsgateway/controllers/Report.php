<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model(array('inbox_model','outbox_model'));
		//$this->load->library('smsgateway');
    }
	
	public function index() {
		//variabel
		$data['sms_masuk'] = $this->inbox_model->count_all();
		$data['pesan_keluar'] = $this->outbox_model->total();
		
		$template_data['title'] = 'Laporan ';
		$template_data['subtitle'] = 'Laporan Pengiriman SMS ';
        $template_data['crumb'] = ['Report' => 'smsgateway/report',];
		//view
		$this->layout->set_wrapper('report_view', $data);
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
	//==============
	// Pesan Masuk
	//--------------
	public function inbox() {
		$crud = new grocery_CRUD();
		
		$crud->set_table("inbox");
		$crud->set_subject("Pesan Masuk ");

		// Show in
		$crud->columns(["ReceivingDateTime","SenderNumber", "TextDecoded"]);

		// Fields type
		$crud->field_type("SenderNumber", "string");
		$crud->field_type("TextDecoded", "text");
		$crud->field_type("RecipientID", "string");

		// Display As
		$crud->display_as("ReceivingDateTime", "Waktu Penerimaan");
		$crud->display_as("SenderNumber", "No. Pengirim");
		$crud->display_as("TextDecoded", "Pesan");
		$crud->display_as("RecipientID", "ID Penerima");
		
		//unset
		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_texteditor('deskripsi');
		
		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);
				
		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Pesan Masuk ";
		$template_data["crumb"] = ["SMS Gateway" => "smsgateway","Laporan" => "smsgateway/report/inbox"];
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
	//==============
	// Pesan Keluar
	//--------------
	public function outbox() {
		$crud = new grocery_CRUD();
		
		$crud->set_table("outbox");

		$crud->set_subject("Pesan Keluar ");

		// Show in
		$crud->add_fields(["SendingDateTime", "DestinationNumber", "TextDecoded", "CreatorID", "status"]);
		$crud->edit_fields(["SendingDateTime", "DestinationNumber", "TextDecoded", "status"]);

		$crud->columns(["SendingDateTime","DestinationNumber", "TextDecoded"]);

		// Display As
		$crud->display_as("SendingDateTime", "Waktu Penerimaan");
		$crud->display_as("DestinationNumber", "No. Tujuan");
		
		$crud->display_as("TextDecoded", "Pesan");
		$crud->display_as("RecipientID", "ID Penerima");

		// callback
		$crud->callback_column('SendingDateTime',array($this,'tglpengiriman_column_callback'));
		
		//unset
		$crud->unset_add();
		$crud->unset_edit();
		$crud->unset_texteditor('text');
		
		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);
				
		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Pesan Keluar ";
		$template_data["crumb"] = ["SMS Gateway" => "smsgateway","Laporan" => "smsgateway/report/outbox"];
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
	//GRAFIK
    public function grafik()
    {
        $settings 	= $this->settings->get();
		$data['setting'] = $settings;

        $this->layout->set_title('Grafik Laporan');
		
		$template_data['title'] = 'Laporan';
		$template_data['subtitle'] = 'Grafik Laporan';
        $template_data['crumb'] = ['Seting SMS' => 'smsgateway/seting',];
		
        $this->layout->set_wrapper('perancangan', $data); 

        $this->layout->setCacheAssets();

        $this->layout->render('admin', $template_data);
    }	
	//CETAK
    public function cetak()
    {
        $settings 	= $this->settings->get();
		$data['setting'] = $settings;

        $this->layout->set_title('Cetak Laporan');
		
		$template_data['title'] = 'Print';
		$template_data['subtitle'] = 'Cetak Laporan';
        $template_data['crumb'] = ['Seting SMS' => 'smsgateway/cetak',];
		
        $this->layout->set_wrapper('perancangan', $data); 

        $this->layout->setCacheAssets();

        $this->layout->render('admin', $template_data);
    }	

	//================
	// CALLBACKS
	
	//CALLBACK Tgl Pengiriman
	function tglpengiriman_column_callback($value, $row)
	{
		return tgl_indo_timestamp($row->SendingDateTime);
	}
	
}
/* End of file Report.php */
/* Location: ./application/modules/smsgateway/controllers/report.php */