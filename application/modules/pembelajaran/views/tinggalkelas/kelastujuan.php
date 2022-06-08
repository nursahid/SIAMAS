
<?php
if ($result) {
	echo form_dropdown("kelasakhir",$result,'',"class='form-control input-sm required' id='akhir'");
}
else {
	echo 'Kelas Untuk Tahun Ajaran sekarang belum dibuat.';
}