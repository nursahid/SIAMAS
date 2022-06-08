<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Keuangan Controller.
 */
class Aset extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

		$this->load->model('spp_model', 'spp');
    }

    /**
     * Index
     */
    public function index()
    {
		//view
		$template_data["title"] = "Keuangan";
		$template_data["subtitle"] = "Manajemen Keuangan";
		$template_data["crumb"] = ["Keuangan" => "keuangan",];
		$this->layout->auth();
		$this->layout->set_wrapper('perancangan', $data);
		$this->layout->render('admin', $template_data); 
	}

	public function manajemen_callback($value, $row)
	{
		return "<a data-toggle='tooltip' data-placement='bottom' title='Data Pembayaran' class='btn btn-sm btn-danger' href='".site_url('pembayaran/bayar/'.$row->id)."'><i class='fa fa-money'></i>&nbsp; Bayar Sekarang</a>";
	}

	
	function data($id = '') {		
				
		$this->form_validation->set_rules('db_tanggal', 'Tanggal', 'required');
		$this->form_validation->set_rules('db_NIS', 'NIS', 'required');
				
		if ($this->form_validation->run() === FALSE) {	
			
			$this->data['ta']  = $this->sistem_model->get_tapel_aktif();
			$this->data['sem'] = $this->sistem_model->get_semester_aktif();
			$this->LoadView('spp/form', $this->data);
		}
		else {
			
			if (isset($_POST['j_action']) AND $_POST['j_action'] !== '') {
				if ($_POST['j_action'] == 'add_param') {
					$d = parseForm($_POST);
					
					$this->db->insert('spp', $d);
					$this->data['msg'] = setMessage('insert', 'spp');
					$this->LoadView('template/msg', $this->data);
				}
			} 
			else
				redirect('spp');
		}	
	}

	
	//=====================
	// ALL CALLBACK
	//=====================
	//jenispembayaran	
	function jenispembayaran_callback(){
		$id_jnspembayaran = $this->uri->segment(3);
		$res = $this->db->select('*')->where('id',$id_jnspembayaran)->get('pembayaran_jenis')->row();
		return '<input type="hidden" name="id_jnspembayaran" value="'.$res->id.'"><span class="text-red"><strong>'.$res->nama_jenispembayaran.'</strong></span>';
	}
	//pembayaran_afterinsert_callback
	function pembayaran_afterinsert_callback($post_array, $primary_key) {
		$trx = explode('/',$post_array['tgl_transaksi']);
		$bulan = $trx[1];
		$tahun = $trx[2];
		$data = array(
				'bulan' => $bulan,
				'tahun' => $tahun,
				'id_semester' => $this->sistem_model->get_semester_aktif(),
				'id_tahun' => $this->sistem_model->get_tapel_aktif()
		);		
		$this->db->where('id', $primary_key)->update('pembayaran', $data);
		
		return TRUE;
	}
	//rupiahin
	function rupiahin($value, $row)
	{
		return 'Rp '.number_format($value,0, ',', '.');
	}
	function tanggalin($value, $row){
		return tgl_indo($value);
	}
	
}

/* End of file example.php */
/* Location: ./application/modules/pembayaran/controllers/Pembayaran.php */