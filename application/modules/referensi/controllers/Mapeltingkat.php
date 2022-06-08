<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Mapeltingkat Controller.
 */
class Mapeltingkat extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Tahun Ajaran";
		$this->load->model(array('pegawai/pegawai_model', 'pegawai/ampumapel_model','pembelajaran/kelas_model'));
		$this->load->model(array('tingkat_model','mapeltingkat_model','jurusan_model'));


    }

    /**
     * Index
     */
    public function index()
    {
		$tapel_aktif	= $this->sistem_model->get_tapel_aktif();
		//variabel view
		$data['dropdown_tingkat'] = $this->tingkat_model->dropdown_tingkat();
		$data['dropdown_jurusan'] = $this->jurusan_model->dropdown_jurusan();
		//wrapper view
		$this->layout->set_wrapper('pmb_mapeltingkat', $data);
				
		//View
		$template_data['title'] = 'Mata Pelajaran ';
		$template_data['subtitle'] = 'Data Mapel per Tingkat';
        $template_data['crumb'] = ['Mapel Tingkat' => 'seting/pmb/mapeltingkat',];

		$this->layout->auth();
		$this->layout->render('admin', $template_data);	
	}
	//===================
	//2. Data Mapel
	//-------------------
    function datamapel() {

		$data['id_tingkat'] = $_POST['tingkat'];
		$data['id_jurusan'] = $_POST['jurusan'];
		$data['id_tahun']	= $this->sistem_model->get_tapel_aktif();
		$data['namatingkat'] = $this->sistem_model->get_nama_tingkat($_POST['tingkat']);
		$data['namajurusan'] = $this->sistem_model->get_nama_jurusan($_POST['jurusan']);
		$data['datamapel'] 	 = $this->mapeltingkat_model->get_many_by(array('id_tingkat'=>$_POST['tingkat'],'id_jurusan'=>$_POST['jurusan']));
		$data['jumlahmapel'] = $this->mapeltingkat_model->count_by(array('id_tingkat'=>$_POST['tingkat'],'id_jurusan'=>$_POST['jurusan']));
		
		//dropdown mapel where not in
		$query = $this->db->query('SELECT `s`.`id`, `s`.`mapel`, `k`.`id_mapel` FROM (`seting_mapel` s) LEFT JOIN `mapel_tingkat` k ON `k`.`id_mapel`=`s`.`id` WHERE `s`.`id`  NOT IN ( SELECT id_mapel FROM mapel_tingkat WHERE id_jurusan="'.$id_jurusan.'" AND id_tingkat="'.$id_tingkat.'") ORDER BY id');
		if ($query->num_rows() > 0) {
			$datamapel = array();
			foreach ($query->result() as $q) {
				$datamapel[''] = '-- Pilih Mata Pelajaran --';
				$datamapel[$q->id] = $q->mapel;
			}
			//return $d;
		}
		$data['dropdown_mapel'] = $datamapel;
		//set
		$this->layout->set_assets('assets/plugins/datatables/js/jquery.dataTables.min.js', 'scripts');
		$this->layout->set_assets('assets/plugins/datatables/js/dataTables.bootstrap.js', 'scripts');
		//view
		$this->load->view('pmb_datamapel', $data);
    }
    public function mapeltingkat_ajaxlist($tingkat, $jurusan)
    {
        $list = $this->mapeltingkat_model->get_datatables($tingkat, $jurusan);
        $data = array();
        $no = $_POST['start']+1;
        foreach ($list as $dt) {
            //$no++;
            $row = array();
            $row[] = $no++;
			$row[] = $this->sistem_model->get_nama_mapel($dt->id_mapel);
            //$row[] = $dt->id_jurusan;
            //$row[] = $dt->id_tingkat;
 
            //add html for action
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$dt->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
                  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$dt->id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
 
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->mapeltingkat_model->count_by($tingkat, $jurusan),
                        "recordsFiltered" => $this->mapeltingkat_model->count_filtered($tingkat, $jurusan),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    public function mapeltingkat_ajaxedit($id)
    {
		$data = $this->mapeltingkat_model->get_by_id($id);
        echo json_encode($data);
    }	
    public function mapeltingkat_ajaxadd()
    {
        $data = array(
                'id_jurusan' => $this->input->post('id_jurusan'),
                'id_tingkat' => $this->input->post('id_tingkat'),
                'id_mapel' => $this->input->post('id_mapel'),
            );
        $insert = $this->mapeltingkat_model->insert($data);
        echo json_encode(array("status" => TRUE));
    }
 
    public function mapeltingkat_ajaxupdate()
    {
        $data = array(
                'id_mapel' => $this->input->post('id_mapel'),
            );
        $this->mapeltingkat_model->update(array('id' => $this->input->post('id')), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_delete($id)
    {
        $this->mapeltingkat_model->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }

	
	
	public function mapeltingkat2()
	{
		$crud = new grocery_CRUD();
		
		$crud->set_table("seting_tingkat");
		$crud->set_subject("Mapel Tingkat");

		// Show in
		$crud->columns(["tingkat", "Jml. Mapel", "kelola"]);

		// callback
		$crud->callback_column('kelola',array($this,'kelolamapel_column_callback'));
		$crud->callback_column('Jml. Mapel',array($this,'jmlmapel_column_callback'));

		// Unset action
		$crud->unset_operations();

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Mata Pelajaran tiap Tingkat";
		$template_data["crumb"] = ["Seting" => "#","Mapel Kelas" => "seting/pmb/mapeltingkat",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
	
	public function mapeltingkatke($id_tingkat)
	{
		$id_tingkat = $this->uri->segment(4);
		
		$crud = new grocery_CRUD();
		
		$crud->set_table('mapel_tingkat'); //Change to your table name
		$crud->where('id_tingkat',$id_tingkat);
		
		$crud->set_subject("Mata Pelajaran");

		// Show in
		$crud->add_fields(["id_jurusan", "id_tingkat", "id_mapel"]);
		$crud->edit_fields(["id_jurusan", "id_tingkat", "id_mapel"]);
		$crud->columns([ "id_jurusan", "id_tingkat", "id_mapel"]);

		// Fields type
		$crud->set_relation("id_jurusan", "ref_jurusan", "jurusan");
		$crud->set_relation("id_tingkat", "seting_tingkat", "tingkat");
		//$crud->set_relation("id_mapel", "seting_mapel", "mapel", ['id NOT IN ( SELECT id_mapel FROM mapel_tingkat WHERE id_tingkat='.$tingkat.')']);
		
		//dropdown mapel where not in
		$query = $this->db->query('SELECT `s`.`id`, `s`.`mapel`, `k`.`id_mapel` FROM (`seting_mapel` s) LEFT JOIN `mapel_tingkat` k ON `k`.`id_mapel`=`s`.`id` WHERE `s`.`id`  NOT IN ( SELECT id_mapel FROM mapel_tingkat WHERE id_tingkat="'.$id_tingkat.'") ORDER BY id');
		if ($query->num_rows() > 0) {
			$datamapel = array();
			foreach ($query->result() as $q) {
				$datamapel[$q->id] = $q->mapel;
			}
			//return $d;
		}
		$crud->field_type('id_mapel','dropdown', $datamapel);
		
		//JIKA MODE EDIT
		
		
		
		
		// Relation n-n

		//CALLBACKS
		//$crud->callback_add_field('id_jurusan',array($this,'addfield_jurusan_callback'));
		//$crud->callback_edit_field('id_jurusan',array($this,'addfield_jurusan_callback'));
		
		$crud->callback_add_field('id_tingkat',array($this,'addfield_tingkat_callback'));
		$crud->callback_edit_field('id_tingkat',array($this,'addfield_tingkat_callback'));
		
		$crud->callback_column('id_mapel',array($this,'mapel_column_callback'));
		
		// Validation
		$crud->set_rules("id_jurusan", "Jurusan", "required");
		$crud->set_rules("id_tingkat", "Tingkat", "required");
		$crud->set_rules("id_mapel", "Mata Pelajaran", "required");

		// Display As
		$crud->display_as("id_jurusan", "Jurusan");
		$crud->display_as("id_tingkat", "Tingkat");
		$crud->display_as("id_mapel", "Mata Pelajaran");

		// Unset action

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);
		//ambil data tingkat
		$this->load->model('tingkat_model');
		$query = $this->tingkat_model->get_by('id', $id_tingkat);
		//nama tingkat
		$nama_tingkat = $query->nama;
		//view
		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Mata Pelajaran Tingkat ".$nama_tingkat;
		$template_data["crumb"] = ["Seting" => "#","Mapel Tingkat" => "seting/pmb/mapeltingkat",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data);
	}
	function test() {
		$tingkat = $this->uri->segment(5);
		$not_in	 = "SELECT id_mapel FROM kelas_mapel WHERE id_tingkat=".$tingkat."";
		//NOT IN (SELECT `id_mapel` FROM kelas_mapel WHERE id_tingkat= $tingkat
		$query = $this->db->query("SELECT id, nama, kelas_mapel.id_mapel FROM seting_mapel JOIN kelas_mapel ON kelas_mapel.id_mapel=seting_mapel.id WHERE id NOT IN ( SELECT id_mapel FROM kelas_mapel WHERE id_tingkat=".$jurusan." AND id_tingkat=".$tingkat.") ORDER BY id");
		//$query = $this->db->query("SELECT * FROM seting_mapel JOIN kelas_mapel ON kelas_mapel.id_mapel=seting_mapel.id WHERE id NOT IN ( SELECT id_mapel FROM kelas_mapel WHERE id_tingkat=".$tingkat.") ORDER BY id");
		
		$this->db->select('id_mapel, seting_mapel.id, nama');
		$this->db->join('seting_mapel', 'seting_mapel.id = kelas_mapel.id_mapel');
		$this->db->where_not_in('id_mapel', $not_in);
        $this->db->order_by('id','asc');
        //$query = $this->db->get('kelas_mapel');

		$datamapel = $this->kelas_model->get_mapel_no_class($tingkat);
		echo form_dropdown('id_mapel', $datamapel, '', 'class="form-control"');
		
	}

	//=========================
	// ALL CALLBACK
	//=========================
	//Tahun Ajaran Aktif
	function tapel_aktif_callback() {
		$id_tahun = $this->sistem_model->get_tapel_aktif();
		$q = $this->db->get_where('seting_tahun_ajaran', array('id' => $id_tahun), 1)->row();  
		return '<input type="hidden" name="id_tahun" value="'.$id_tahun.'"><strong>'.$q->tahun.'</strong>';
	}
	
	//pengelolaan
	function kelolamapel_column_callback($value, $row) {
		//jurusan/tingkat
		$view = "<a href='".site_url('seting/pmb/mapeltingkatke/'.$row->id)."' class='btn btn-sm btn-success' data-toggle='tooltip' data-placement='bottom' title='Data Mata Pelajaran'><i class='fa fa-book'></i> &nbsp;&nbsp;Mata Pelajaran</a> &nbsp;";
		
		return $view;
	}
	//kolom status
	function status_column_callback($value, $row) {
		//jumlah data kelas by kolom
		if ($row->status == 'Y') {
			$val = "<span class='badge btn-danger'>Aktif</span>";
		}elseif ($row->status == 'N'){
			$val = "-";
		}
		return $val;
	}
	//kolom rombel
	function rombel_column_callback($value, $row) {
		$jml_kelas = $this->kelas_model->count_by('id_tahun',$row->id);
		return $jml_kelas." rombel";
	}
	//jmlsiswa
	function jmlsiswa_column_callback($value, $row) {
		$this->load->model('kesiswaan/siswakelas_model');
		$id_tapel = $this->uri->segment(4);
		$jmlsiswa = $this->siswakelas_model->count_by(array('id_tahun'=>$id_tapel,'id_kelas'=>$row->id));
		return $jmlsiswa." siswa";
	}
	//manajemen data siswa
	public function datasiswa_column_callback($value, $row) {
		$id_tapel = $this->uri->segment(4);
		return "<a class='btn btn-sm btn-danger' href='".site_url('seting/pmb/itemsiswa/'.$id_tapel.'/'.$row->id)."'><i class='fa fa-list'></i>&nbsp; Data Siswa</a>";
	}
	//kolom jml mapel
	function jmlmapel_column_callback($value, $row) {
		$this->load->model('mapeltingkat_model');
		$jml_kelas = $this->mapeltingkat_model->count_by('id_tingkat',$row->id);
		return $jml_kelas." mapel";
	}
	//kolom id_mapel
	function mapel_column_callback($value, $row) {
		$q = $this->db->get_where('seting_mapel', array('id' => $row->id_mapel), 1)->row();  
		return $q->mapel;
	}

	//Tahun Ajaran
	function addfield_tapel_callback() {
		$id_tahun = $this->uri->segment(4);
		$q = $this->db->get_where('seting_tahun_ajaran', array('id' => $id_tahun), 1)->row();  
		return '<input type="hidden" name="id_tahun" value="'.$id_tahun.'"><strong>'.$q->tahun.'</strong>';
	}
	//Jurusan Kelas TAPEL
	function editfield_jurusan_kelastapel_callback() {
		$d = $this->db->get_where('kelas', array('id' => $this->uri->segment(6)), 1)->row();
		$q = $this->db->get_where('ref_jurusan', array('id' => $d->id_jurusan), 1)->row();  
		return '<input type="hidden" name="id_jurusan" value="'.$d->id_jurusan.'"><strong>'.$q->jurusan.'</strong>';
	}
	//Jurusan
	function addfield_jurusan_callback() {
		$id_jurusan = $this->uri->segment(4);
		$q = $this->db->get_where('ref_jurusan', array('id' => $id_jurusan), 1)->row();  
		return '<input type="hidden" name="id_jurusan" value="'.$id_jurusan.'"><strong>'.$q->jurusan.'</strong>';
	}
	//Tingkat
	function addfield_tingkat_callback() {
		$id_tingkat = $this->uri->segment(4);
		$q = $this->db->get_where('seting_tingkat', array('id' => $id_tingkat), 1)->row();  
		return '<input type="hidden" name="id_tingkat" value="'.$id_tingkat.'"><strong>'.$q->nama.'</strong>';
	}
	
	//kelastapel_afterinsert_callback
	//--Hanya untuk SIMAPON
	function kelastapel_afterinsert_callback($post_array, $primary_key) {
		//jika pilih tingkat 7-9, maka jenjangnya adalah MTs
		if( $post_array['id_tingkat'] == '1' || $post_array['id_tingkat'] == '2' || $post_array['id_tingkat'] == '3' ) {
			$jenjang = '1';
		} elseif( $post_array['id_tingkat'] == '4' || $post_array['id_tingkat'] == '5' || $post_array['id_tingkat'] == '6' ) {
			$jenjang = '2';
		}
		$data = array('id_jurusan' => $jenjang);		
		$this->db->where('id', $primary_key)->update('kelas', $data);
		
		return TRUE;
	}
	
}

/* End of file Mapeltingkat.php */
/* Location: ./application/modules/referensi/controllers/Mapeltingkat.php */