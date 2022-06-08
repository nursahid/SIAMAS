<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kontak_model extends Base_model{
	
	public $_table = "kontak";
	
    public function __construct()
    {
        parent::__construct();
    }
	
    function pesan_masuk(){
        return $this->db->query("SELECT * FROM kontak ORDER BY id DESC");
    }

    function pesan_baru($limit){
        return $this->db->query("SELECT * FROM kontak ORDER BY id DESC LIMIT $limit");
    }

    function pesan_masuk_view($id){
        return $this->db->query("SELECT * FROM kontak where id='$id'");
    }

    function pesan_masuk_kirim(){
        $nama           = $this->input->post('a');
        $email           = $this->input->post('b');
        $subject         = $this->input->post('c');
        $message         = $this->input->post('isi')." <br><hr><br> ".$this->input->post('d');

        $rows = $this->model_users->users_edit($this->session->username)->row_array();
        $iden = $this->model_identitas->identitas()->row_array();
        $this->email->from($rows['email'], $iden['nama_website']);
        $this->email->to($email);
        $this->email->cc('');
        $this->email->bcc('');

        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->set_mailtype("html");
        $this->email->send();
        
        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'utf-8';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';
        $this->email->initialize($config);

    }

    function kirim_Pesan(){
        $nama           = $this->input->post('a');
        $email           = $this->input->post('b');
        $subjek         = $this->input->post('c');
        $pesan         = $this->input->post('d');
            $datadb = array('nama'=>$nama,
                            'email'=>$email,
                            'subjek'=>$subjek,
                            'pesan'=>$pesan,
                            'tanggal'=>date('Y-m-d'));
        $this->db->insert('kontak',$datadb);
    }
}