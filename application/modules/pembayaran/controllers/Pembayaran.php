<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Pembayaran Controller.
 */
class Pembayaran extends MY_Controller
{
    private $title;

    public function __construct()
    {
        parent::__construct();

        $this->title = "Pembayaran";
    }

    /**
     * Index
     */
	public function index()
	{
		$crud = new grocery_CRUD();
		
		$crud->set_table("pembayaran");
		$crud->set_subject("Pembayaran");

		// Show in
		$crud->add_fields(["no_referensi", "tgl_transaksi", "id_jnspembayaran", "id_siswa", "nilai", "id_semester", "id_tahun", "keterangan"]);
		$crud->edit_fields(["no_referensi", "tgl_transaksi", "id_jnspembayaran", "id_siswa", "nilai", "id_semester", "id_tahun", "keterangan"]);
		$crud->columns(["no_referensi", "id_jnspembayaran", "id_siswa", "nilai"]);

		// Fields type
		$crud->field_type("id", "integer");
		$crud->field_type("no_referensi", "string");
		$crud->field_type("tgl_transaksi", "date");
		$crud->set_relation("id_jnspembayaran", "pembayaran_jenis", "nama_jenispembayaran");
		$crud->set_relation("id_siswa", "siswa", "nama");
		$crud->field_type("nilai", "string");
		$crud->field_type("bulan", "integer");
		$crud->field_type("tahun", "integer");
		$crud->field_type("id_semester", "true_false");
		$crud->set_relation("id_tahun", "seting_tahun_ajaran", "tahun");
		$crud->unset_texteditor("keterangan", 'full_text');
		$crud->field_type("keterangan", "text");

		// Relation n-n

		// Validation
		$crud->set_rules("tgl_transaksi", "Tgl transaksi", "required");
		$crud->set_rules("id_jnspembayaran", "Jns Pembayaran", "required");
		$crud->set_rules("id_siswa", "siswa", "required");
		$crud->set_rules("nilai", "Nilai", "required");
		$crud->set_rules("id_semester", "Semester", "required");

		// Display As
		$crud->display_as("no_referensi", "No. Referensi");
		$crud->display_as("tgl_transaksi", "Tgl. Transaksi");
		$crud->display_as("id_jnspembayaran", "Jenis Pembayaran");
		$crud->display_as("id_siswa", "Siswa");
		$crud->display_as("nilai", "Nominal");
		$crud->display_as("bulan", "Bulan");
		$crud->display_as("tahun", "Tahun");
		$crud->display_as("id_semester", "Semester");
		$crud->display_as("id_tahun", "Tahun");
		$crud->display_as("keterangan", "Keterangan");

		// Unset action

		$data = (array) $crud->render();

		$this->layout->set_wrapper( 'grocery', $data,'page', false);

		$template_data['grocery_css'] = $data['css_files'];
		$template_data['grocery_js']  = $data['js_files'];
		$template_data["title"] = "Pembayaran";
		$template_data["crumb"] = ["Pembayaran" => "#",];
		$this->layout->auth();
		$this->layout->render('admin', $template_data); // front - auth - admin
	}
}

/* End of file example.php */
/* Location: ./application/modules/pembayaran/controllers/Pembayaran.php */