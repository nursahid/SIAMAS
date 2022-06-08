<?php
/*
$object = ini adalah html, atau object text lain yang akan kita jadikan pdf
$filename = nama file untuk pdf yang jadi (contoh: hasil.pdf)
$direct_download = apakah akan didownload langsung?? direct download bila bernilai true maka akan menampilkan download dialog pada browser
*/

class PdfGenerator {
	
	function generate($object, $filename='', $paper, $orientation, $direct_download=TRUE)	{
		require_once("dompdf/dompdf_config.inc.php");
		$dompdf = new DOMPDF();
		$dompdf->set_paper($paper,$orientation);
		$dompdf->load_html($object);
		$dompdf->render();
		if ($direct_download == TRUE)
			$dompdf->stream($filename);
		else
			return $dompdf->output();
	}
}