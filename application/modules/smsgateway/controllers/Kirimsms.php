<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Kirimsms extends MY_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model(array('sms_model','PbkGroups_model','kesiswaan/siswa_model','pegawai/pegawai_model'));
		//$this->load->library('smsgateway');
    }
	
	public function index() {
		//variabel
		$data['datas'] = $this->siswa_model->get_many_by('status','Aktif');
		$data['totalphones'] = $this->siswa_model->count_by('status','Aktif');
		
		$template_data['title'] = 'SMS Gateway ';
		$template_data['subtitle'] = 'Kirim SMS';
        $template_data['crumb'] = ['SMS Gateway' => 'smsgateway', 'Kirim SMS' => 'smsgateway/kirimsms',];
		//view
		$this->layout->set_wrapper('kirimsms_index', $data);
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
	
	public function sending_phonebook() {
		
		$no_hp = $this->input->post('no_hp');
		$numbers = array();
		foreach ($no_hp as $key => $value) {
			if ($value != '') {
				array_push($numbers, $value);
			}
		}
		$deviceID = 94555;
		$message = $this->input->post('pesan');
		
		//$result = $this->smsgateway->sendMessageToManyNumbers($numbers, $message, $deviceID);
		for ($i = 0; $i < sizeof($_POST['no_hp']); $i++) {
			$data 	 = array('DestinationNumber'=>$_POST['no_hp'][$i], 'TextDecoded'=>$_POST['pesan']);
			$result	 = $this->db->insert('outbox', $data);
		}
		
		//if (count($result['response']['result']['success']) > 0) {
		if (count($result) > 0) {
			$this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade in" role="alert">
														<button type="button" class="close" data-dismiss="alert" aria-label="Close">
														<span aria-hidden="true">×</span></button> <strong>Sukses!</strong> - 
														Berhasil mengirim sms</div>');
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade in" role="alert">
														<button type="button" class="close" data-dismiss="alert" aria-label="Close">
														<span aria-hidden="true">×</span></button> <strong>Error!</strong> - 
														Gagal mengirim sms</div>');
		}
		redirect('smsgateway/kirimsms','refresh');
	}
	//==================
	//kirim ke groups
	//------------------
	public function groups() {
		//variabel
		$data['datas'] = $this->PbkGroups_model->get_all();
		
		$template_data['title'] = 'SMS Gateway ';
		$template_data['subtitle'] = 'Kirim SMS';
        $template_data['crumb'] = ['SMS Gateway' => 'smsgateway', 'SMS Groups' => 'smsgateway/kirimsms/groups',];
		//view
		$this->layout->set_wrapper('groups_form', $data);
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
	public function sending_groups() {
		
		//cek masukan groupID
		for ($i = 0; $i < sizeof($_POST['groupID']); $i++) {
			//ambil data hp pegawai berdasarkan groupID
			$query = $this->pegawai_model->get_many_by('pbk_GroupID',$_POST['groupID']);
            if($query != 0){
                foreach($query as $row){
					$data 	 = array('DestinationNumber'=>$row->hp, 'TextDecoded'=>$_POST['pesan']);
					$result	 = $this->db->insert('outbox', $data);
                }
			} 
			else {
                $query = $this->pegawai_model->get_many_by(array('pbk_GroupID !=' => $_POST['groupID']));
                foreach($query as $row){
					$data 	 = array('DestinationNumber'=>$row->hp, 'TextDecoded'=>$_POST['pesan']);
					$result	 = $this->db->insert('outbox', $data);
				}
            }
		}
		if (count($result) > 0) {
			$this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade in" role="alert">
														<button type="button" class="close" data-dismiss="alert" aria-label="Close">
														<span aria-hidden="true">×</span></button> <strong>Sukses!</strong> - 
														Berhasil mengirim sms</div>');
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade in" role="alert">
														<button type="button" class="close" data-dismiss="alert" aria-label="Close">
														<span aria-hidden="true">×</span></button> <strong>Error!</strong> - 
														Gagal mengirim sms</div>');
		}
		redirect('smsgateway/kirimsms/groups','refresh');
	}
	
	//========================
	//kirim ke nomor baru
	//------------------------
	public function nomor_baru() {
		//variabel
		$data['datas'] = $this->siswa_model->get_many_by('status','Aktif');
		//select2 js
		$template_data['js_plugins'] = [
			base_url('assets/plugins/select2/select2.js'),
			base_url('assets/js/sms_select2.js')
		];
		$template_data['css_plugins'] = [
            base_url('assets/plugins/select2/select2.css')
        ];
		$template_data['title'] 	= 'SMS Gateway ';
		$template_data['subtitle'] 	= 'Kirim SMS';
        $template_data['crumb'] 	= ['SMS Gateway' => 'smsgateway','Nomor Baru' => 'smsgateway/kirimsms/nomor_baru',];
		//view
		$this->layout->set_wrapper('nomorbaru_form', $data);
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
	public function sending_nomorbaru() {
		$this->form_validation->CI =& $this;
		$this->form_validation->set_rules('no_hp','No. HP','required|htmlspecialchars|trim');
		$this->form_validation->set_rules('pesan','Pesan','required|htmlspecialchars|trim');
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> <strong>Error!</strong> - ', '</div> ');  

		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('message',validation_errors());
		} else {
			//bukan multiple
			$data 	 = array('DestinationNumber'=>$_POST['no_hp'], 'TextDecoded'=>$_POST['pesan']);		
			$result	 = $this->db->insert('outbox', $data);			
			if (count($result) > 0) {
				$this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> <strong>Sukses!</strong> - Berhasil mengirim sms</div>');
			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> <strong>Error!</strong> - Gagal mengirim sms</div>');
			}
		}
		redirect('smsgateway/kirimsms/nomor_baru','refresh');
	}
	
	//===================
	//------------------
	function status(){
		$oa  = strtotime(date('Y-m-d'));
		$sa  = date('Y-m-d',strtotime('-1 week',$oa));
		$tgl = explode("-",$sa);
		$da  = date('Y-m-d',strtotime('this week',$oa));
		//inbox
		$datas = "<tr><td>inbox</td>";
		for($i=0;$i<=7;$i++){
			$x = mktime(0,0,0,$tgl[1],$tgl[2]+$i,$tgl[0]);
			$y = date('Y-m-d',$x);
			$datas .= '<td>'.$this->sms_model->cari_datainbox($y).'</td>';
		}
		
		$data['inbox']=$datas.'</tr>';
		//sent item
		$datas="<tr><td>Sent Item</td>";
		for($i=0;$i<=7;$i++){
			$x = mktime(0,0,0,$tgl[1],$tgl[2]+$i,$tgl[0]);
			$y = date('Y-m-d',$x);
			$datas .= '<td>'.$this->sms_model->cari_datasitem($y).'</td>';
		}
		$data['sentitem'] = $datas.'</tr>';
		//tanggal
		$datas = "<tr><th>Tgl</th>";
		
		$sa = date('Y-m-d',strtotime('-1 week',$oa));
		$tgl = explode("-",$sa);
		for($i=0;$i<=7;$i++){
			$x = mktime(0,0,0,$tgl[1],$tgl[2]+$i,$tgl[0]);
			$y = date('d',$x);
			$datas .='<th>'.$y.'</th>';
		}
		$data['tgl'] = $datas.'</tr>';
		
		//mencari tanggal -7 dan tanggal sekarang!
		$oa = strtotime(date('Y-m-d'));
		$sa = date('d-m-Y',strtotime('-1 week',$oa));
		$da = date('d-m-Y',strtotime('now'));
		
		$data['last'] = $sa;
		$data['now'] = $da;
		$data['jumlahinbox'] = $this->sms_model->cari_totalinbox();
		$data['jumlahsitem'] = $this->sms_model->cari_totalsitem();
		$this->load->view('view_status',$data);
	}

	
}
/* End of file Kirimsms.php */
/* Location: ./application/modules/smsgateway/controllers/Kirimsms.php */