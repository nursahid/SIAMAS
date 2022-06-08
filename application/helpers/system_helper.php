<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Method untuk mendapatkan link logo elearning
 *
 * @param  string $size pilihan small|medium|large
 * @return string link image
 */
function get_logo_url($size = 'small') {
    return base_url('assets/images/logo-'.strtolower($size).'.png');
}

/**
 * Method untuk mendapatkan css alert
 *
 * @param  string $notif
 * @param  string $msg
 * @return string
 */
function get_alert($notif = 'success', $msg = '')
{
    return '<div class="alert alert-'.$notif.'"><button type="button" class="close" data-dismiss="alert">×</button> '.$msg.'</div>';
}

/**
 * Method untuk mendapatkan link gambar
 *
 * @param  string $img
 * @param  string $size
 * @return string
 *
 */
function get_url_image($img, $size = '')
{
    if (empty($size)) {
        return base_url('assets/uploads/siswa/'.$img);
    } else {
        $pisah     = explode('.', $img);
        $ext       = end($pisah);
        $nama_file = $pisah[0];

        return base_url('assets/uploads/siswa/'.$nama_file.'_'.$size.'.'.$ext);
    }
}

/**
 * Method untuk mendapatkan link foto siswa
 *
 * @param  string $img
 * @param  string $size
 * @param  string $jk
 * @return string url
 */
function get_url_image_siswa($img = '', $size = 'medium', $jk = 'Laki-laki') {
    if (is_null($img) OR empty($img)) {
        if ($jk == 'Laki-laki') {
            $img = 'default_siswa.png';
        } else {
            $img = 'default_siswi.png';
        }
        return get_url_image($img);
    } else {
        return get_url_image($img, $size);
    }
}

/**
 * Method untuk mendapatkan link foto pengajar
 *
 * @param  string $img
 * @param  string $size
 * @param  string $jk
 * @return string url
 */
function get_url_image_pengajar($img = '', $size = 'medium', $jk = 'Laki-laki') {
    if (is_null($img) OR empty($img)) {
        if ($jk == 'Laki-laki') {
            $img = 'default_pl.png';
        } else {
            $img = 'default_pp.png';
        }
        return get_url_image($img);
    } else {
        return get_url_image($img, $size);
    }
}

/**
 * Method untuk mendapatkan link foto pengajar/admin/siswa ketika sudah login
 *
 * @param  string $img
 * @param  string $size
 * @param  string $jk
 * @return string url
 */
function get_url_image_session($img = '', $size = 'medium', $jk = 'Laki-laki') {
    if (is_pengajar() OR is_admin()) {
        return get_url_image_pengajar($img, $size, $jk);
    } elseif (is_siswa()) {
        return get_url_image_siswa($img, $size, $jk);
    }
}

/**
 * Method untuk mendapatkan path image
 *
 * @param  string $img
 * @param  string $size medium|small, kalau aslinya di kosongkan
 * @return string paht
 */
function get_path_image($img = '', $size = '')
{
    if (empty($size)) {
        return './userfiles/images/'.$img;
    } else {
        $pisah = explode('.', $img);
        $ext = end($pisah);
        $nama_file = $pisah[0];

        return './userfiles/images/'.$nama_file.'_'.$size.'.'.$ext;
    }
}

/**
 * Deklarasi path file
 *
 * @param  string $file
 * @return string
 */
function get_path_file($file = '')
{
    return './userfiles/files/'.$file;
}


/**
 * Method untuk mendapatkan flashdata
 *
 * @param  string $key
 * @return string
 */
function get_flashdata($key)
{
    $CI =& get_instance();

    return $CI->session->flashdata($key);
}

/**
 * Fungsi untuk mendapatkan bulan dengan nama indonesia
 *
 * @param  string $bln
 * @return string
 */
function get_indo_bulan($bln = '')
{
    $data = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
    if (empty($bln)) {
        return $data;
    } else {
        $bln = (int)$bln;
        return isset($data[$bln]) ? $data[$bln] : "";
    }
}

/**
 * Fungsi untuk mendapatkan nama hari indonesia
 *
 * @param  string $hari
 * @return string
 */
function get_indo_hari($hari = '')
{
    $data = array(1 => 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu', 'Minggu');
    if (empty($hari)) {
        return $data;
    } else {
        $hari = (int)$hari;
        return $data[$hari];
    }
}

/**
 * Method untuk memformat tanggal ke indonesia
 *
 * @param  string $tgl
 * @return string
 */
function tgl_indo_cms($tgl = '')
{
    if (!empty($tgl)) {
        $pisah = explode('-', $tgl);
        return $pisah[2].' '.get_indo_bulan($pisah[1]).' '.$pisah[0];
    }
}

/**
 * Method untuk memformat tanggal dan jam ke format indonesia
 *
 * @param  string $tgl_jam
 * @return string
 */
function tgl_jam_indo($tgl_jam = '')
{
    if (!empty($tgl_jam)) {
        $pisah = explode(' ', $tgl_jam);
        return tgl_indo($pisah[0]).' '.date('H:i', strtotime($tgl_jam));
    }
}

/**
 * Method untuk memforamt tanggal dan jam supaya lebih enak dibaca
 * @param  datetime $datetime
 * @return string
 */
function format_datetime($datetime)
{
    # format tanggal, jika hari ini
    if (date('Y-m-d') == date('Y-m-d', strtotime($datetime))) {
        $selisih = time() - strtotime($datetime) ;

        $detik = $selisih ;
        $menit = round($selisih / 60);
        $jam   = round($selisih / 3600);

        if ($detik <= 60) {
            if ($detik == 0) {
                $waktu = "baru saja";
            } else {
                $waktu = $detik.' detik yang lalu';
            }
        } else if ($menit <= 60) {
            $waktu = $menit.' menit yang lalu';
        } else if ($jam <= 24) {
            $waktu = $jam.' jam yang lalu';
        } else {
            $waktu = date('H:i', strtotime($datetime));
        }

        $datetime = $waktu;
    }
    # kemarin
    elseif (date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d')))) == date('Y-m-d', strtotime($datetime))) {
        $datetime = 'Kemarin ' . date('H:i', strtotime($datetime));
    }
    # lusa
    elseif (date('Y-m-d', strtotime('-2 day', strtotime(date('Y-m-d')))) == date('Y-m-d', strtotime($datetime))) {
        $datetime = '2 hari yang lalu ' . date('H:i', strtotime($datetime));
    }
    else {
        $datetime = tgl_jam_indo($datetime);
    }

    return $datetime;
}

/**
 * Metho untuk mendapatkan array post
 *
 * @param  string $key
 * @return string
 */
function get_post_data($key = '')
{
    if (isset($_POST[$key])) {
        return $_POST[$key];
    }

    return;
}

/**
 * Method untuk mendapatkan huruf berdasarkan nomornya
 *
 * @param  integer $index
 * @return string
 */
function get_abjad($index)
{
    $abjad = array(1 => 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
    return $abjad[$index];
}

/**
 * Method untuk enkripsi url
 *
 * @param  string $current_url
 * @return string
 */
function enurl_redirect($current_url)
{
    return str_replace(array("%2F","%5C"), array("%252F","%255C"), urlencode($current_url));
}

/**
 * Method untuk deskripsi url
 *
 * @param  string $url
 * @return string
 */
function deurl_redirect($url)
{
    return urldecode(urldecode($url));
}

function pr($array) {
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}

function get_data_array($array, $index1, $index2) {
    return $array[$index1][$index2];
}

/**
 * Fungsi untuk mendapatkan nama panggilan
 *
 * @param  string $str_nama
 * @return string
 */
function nama_panggilan($str_nama) {
    $split = explode(" ", $str_nama);
    return $split[0];
}

/**
 * Method untuk mengaktifkan natif session
 * http://stackoverflow.com/questions/6249707/check-if-php-session-has-already-started
 */
function start_native_session()
{
    if (session_id() == '') {
        session_start();
    }
}

/**
 * Method untuk membuat session kcfinder, karena kcfinder masih menggunakan natif session
 *
 * @param  integer $login_id
 */
function create_sess_kcfinder($login_id)
{
    if (is_login()) {
        # start natif session
        start_native_session();

        $_SESSION['E-LEARNING']['KCFINDER']              = array();
        $_SESSION['E-LEARNING']['KCFINDER']['disabled']  = false;
        $_SESSION['E-LEARNING']['KCFINDER']['uploadDir'] = "";
        if (is_admin()) {
            $_SESSION['E-LEARNING']['KCFINDER']['uploadURL'] = base_url('userfiles/uploads/');
        } else {
            $user_folder = './userfiles/uploads/' . $login_id;
            if (!is_dir($user_folder)) {
                mkdir($user_folder, 0755);
                chmod($user_folder, 0755);
            }
            $_SESSION['E-LEARNING']['KCFINDER']['uploadURL'] = base_url('userfiles/uploads/' . $login_id);
        }
    }
}

/**
 * Method untuk mendapatkan satu record tambahan
 *
 * @param  string $id
 * @return array
 */
function retrieve_field($id)
{
    return get_row_data('config_model', 'retrieve_field', array('id' => $id));
}

/**
 * Method untuk update field tambahan
 *
 * @param  string $id
 * @param  string $nama
 * @param  string $value
 * @return boolean
 */
function update_field($id, $nama = null, $value = null)
{
    return get_row_data('config_model', 'update_field', array($id, $nama, $value));
}

/**
 * Method untuk menghapus field tambahan berdasarkan id
 *
 * @param  string $id
 * @return boolean
 */
function delete_field($id)
{
    return get_row_data('config_model', 'delete_field', array('id' => $id));
}

/**
 * Method untuk membuat field tambahan
 *
 * @param  string $id
 * @param  string $nama
 * @param  string $value
 * @return boolean
 */
function create_field($id, $nama = null, $value = null)
{
    return get_row_data('config_model', 'create_field', array('id' => $id, 'nama' => $nama, 'value' => $value));
}

/**
 * Method untuk ngecek apakah pilihan ganda pertanyaan terpilih atau tidak
 *
 * @param  array    $array
 * @param  integer  $pertanyaan_id
 * @param  integer  $pilihan_id
 * @return boolean
 */
function is_pilih($array, $pertanyaan_id, $pilihan_id)
{
    if (isset($array[$pertanyaan_id]) AND $array[$pertanyaan_id] == $pilihan_id) {
        return true;
    }
    return false;
}

/**
 * Method untuk mendapatkan ip pengakses
 * @return string
 */
function get_ip()
{
    return $_SERVER['REMOTE_ADDR'];
}

/**
 * Method untuk mendapatkan semua data email user yang berkedudukan sebagai admin
 *
 * @return array
 */
function get_email_admin()
{
    $results = array();

    $retrieve_all = get_row_data('login_model', 'retrieve_all', array(10, 1, 1, false));
    foreach ($retrieve_all as $login) {
        # cari pengajar
        $pengajar = get_row_data('pengajar_model', 'retrieve', array($login['pengajar_id']));
        if ($pengajar['status_id'] != 1) {
            continue;
        }

        $results[] = array(
            'nama'  => $pengajar['nama'],
            'email' => $login['username']
        );
    }

    return $results;
}

/**
 * Method untuk mengirimkan email
 *
 * @param  string $nama_email
 * @param  string $to
 * @param  array  $array_data
 * @return boolean
 */
function kirim_email($nama_email, $to, $array_data = array())
{
    # cari email
    $template = get_pengaturan($nama_email, 'value');
    $template = json_decode($template, 1);
    if (empty($template)) {
        return false;
    }

    $arr_old = array();
    $arr_new = array();
    foreach ((array)$array_data as $key => $value) {
        $arr_old[] = '{$'.$key.'}';
        $arr_new[] = $value;
    }

    $email_subject = str_replace($arr_old, $arr_new, $template['subject']);
    $email_body    = str_replace($arr_old, $arr_new, $template['body']);
    $email_server  = get_pengaturan('email-server', 'value');
    $nama_sekolah  = get_pengaturan('nama-sekolah', 'value');

    $CI =& get_instance();
    $CI->email->clear(true);

    $config['mailtype'] = 'html';
    # cek pakai smtp tidak
    $smtp_host = get_pengaturan('smtp-host', 'value');
    $smtp_user = get_pengaturan('smtp-username', 'value');
    $smtp_pass = get_pengaturan('smtp-pass', 'value');
    $smtp_port = get_pengaturan('smtp-port', 'value');
    if (!empty($smtp_host)) {
        $config['protocol']  = 'smtp';
        $config['smtp_host'] = $smtp_host;
        $config['smtp_user'] = $smtp_user;
        $config['smtp_pass'] = $smtp_pass;

        # cek port
        if (!empty($smtp_port)) {
            $config['smtp_port'] = $smtp_port;
        }
    }
    $CI->email->initialize($config);

    $CI->email->to($to);
    $CI->email->from($email_server, '[E-learning] - ' . $nama_sekolah);
    $CI->email->subject($email_subject);
    $CI->email->message($email_body);
    $CI->email->send();
    $CI->email->clear(true);

    return true;
}

/**
 * Method untuk mengirimkan email approve siswa
 *
 * @param  string $siswa_id
 */
function kirim_email_approve_siswa($siswa_id)
{
    $retrieve_siswa = get_row_data('siswa_model', 'retrieve', array($siswa_id));
    $login = get_row_data('login_model', 'retrieve', array(null, null, null, $siswa_id));

    $tabel_profil = '<table border="1" cellspacing="0" cellpadding="5">
        <tr>
            <td valign="top">NIS</td>
            <td>' . $retrieve_siswa['nis'] . '</td>
        </tr>
        <tr>
            <td valign="top">Nama</td>
            <td>' . $retrieve_siswa['nama'] . '</td>
        </tr>
        <tr>
            <td valign="top">Jenis kelamin</td>
            <td>' . $retrieve_siswa['jenis_kelamin'] . '</td>
        </tr>
        <tr>
            <td valign="top">Tempat lahir</td>
            <td>' . $retrieve_siswa['tempat_lahir'] . '</td>
        </tr>
        <tr>
            <td valign="top">Tgl. Lahir</td>
            <td>' . tgl_indo($retrieve_siswa['tgl_lahir']) . '</td>
        </tr>
        <tr>
            <td valign="top">Alamat</td>
            <td>' . $retrieve_siswa['alamat'] . '</td>
        </tr>
    </table>';

    @kirim_email('email-template-approve-siswa', $login['username'], array(
        'nama'         => $nama,
        'nama_sekolah' => get_pengaturan('nama-sekolah', 'value'),
        'tabel_profil' => $tabel_profil,
        'url_login'    => site_url('login')
    ));
}

/**
 * Method untuk mengirimkan email approve pengajar
 *
 * @param  integer $pengajar_id
 */
function kirim_email_approve_pengajar($pengajar_id)
{
    $pengajar = get_row_data('pengajar_model', 'retrieve', array($pengajar_id));
    $login    = get_row_data('login_model', 'retrieve', array(null, null, null, null, $pengajar_id));

    $tabel_profil = '<table border="1" cellspacing="0" cellpadding="5">
        <tr>
            <td valign="top">NIP</td>
            <td>' . $pengajar['nip'] . '</td>
        </tr>
        <tr>
            <td valign="top">Nama</td>
            <td>' . $pengajar['nama'] . '</td>
        </tr>
        <tr>
            <td valign="top">Jenis kelamin</td>
            <td>' . $pengajar['jenis_kelamin'] . '</td>
        </tr>
        <tr>
            <td valign="top">Tempat lahir</td>
            <td>' . $pengajar['tempat_lahir'] . '</td>
        </tr>
        <tr>
            <td valign="top">Tgl. Lahir</td>
            <td>' . tgl_indo($pengajar['tgl_lahir']) . '</td>
        </tr>
        <tr>
            <td valign="top">Alamat</td>
            <td>' . $pengajar['alamat'] . '</td>
        </tr>
    </table>';

    @kirim_email('email-template-approve-pengajar', $login['username'], array(
        'nama'         => $nama,
        'nama_sekolah' => get_pengaturan('nama-sekolah', 'value'),
        'tabel_profil' => $tabel_profil,
        'url_login'    => site_url('login')
    ));
}


/**
 * Method untuk mendapatkan email dari string
 *
 * @param  string $str
 * @return array
 */
function get_email_from_string($str)
{
    $pattern = '/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i';

    preg_match_all($pattern, $str, $results);

    return $results[0];
}

/**
 * Method untuk ngecek sedang demo aplikasi atau tidak
 *
 * @return boolean
 */
function is_demo_app()
{
    $CI =& get_instance();
    $CI->load->config();
    return $CI->config->item('is_demo_app');
}

/**
 * Method untuk mendapatkan pesan jika sedang demo
 * @return string
 */
function get_demo_msg()
{
    return "Maaf, untuk keperluan demo aplikasi, halaman ini tidak dapat diperbaharui.";
}

/**
 * http://stackoverflow.com/questions/3475646/undefined-date-diff
 */
if (!function_exists('date_diff')) {
    function date_diff($date1, $date2)
    {
        $current = $date1;
        $datetime2 = date_create($date2);
        $count = 0;
        while(date_create($current) < $datetime2){
            $current = gmdate("Y-m-d", strtotime("+1 day", strtotime($current)));
            $count++;
        }
        return $count;
    }
}

/**
 * Method untuk mendapatkan data dari url
 *
 * @param  string $url
 * @return string body
 */
function get_url_data($url)
{
    # jika curl hidup
    if (function_exists('curl_version')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $response    = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header      = substr($response, 0, $header_size);
        $body        = substr($response, $header_size);
        $code        = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    } else {
        $body = file_get_contents($url);
    }

    return $body;
}

/**
 * Method untuk cek apakah password siswa sama dengan nis
 *
 * @return boolean
 */
function pass_siswa_equal_nis()
{
    if (is_siswa()) {
        $nis_siswa = get_sess_data('user', 'nis');
        if (empty($nis_siswa)) {
            return false;
        }

        # ambil nis siswa
        $nis = get_row_data('siswa_model', 'retrieve', array(
            'id' => get_sess_data('user', 'id')
        ), 'nis');
        if (empty($nis)) {
            return false;
        }

        $md5_nis = md5($nis);

        # dapatkan password siswa
        $md5_pass = get_row_data('login_model', 'retrieve', array(
            'id' => get_sess_data('login', 'id')
        ), 'password');

        if ($md5_nis == $md5_pass) {
            return true;
        }
    }

    return false;
}
