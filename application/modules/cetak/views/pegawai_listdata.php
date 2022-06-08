
	<?php
	if ($datas) {
		echo '<div id="cetak">
				<table class="table" border="0" align="center">
					<tr>
						<td><img align="center" src="'.base_url('assets/uploads/image/'.$setting['logosekolah']).'" alt="Logo Sekolah" width="75" height="75" /></td>
						<td>
							<h3 align="center">PEMERINTAH KABUPATEN '.strtoupper($setting['kabupaten']).'</h3>
							<h2 align="center">'.strtoupper($setting['namasekolah']).'</h2>
							<p align="center">Alamat : '.$setting['alamat'].' '.$setting['kelurahan'].' Kecamatan '.$setting['kecamatan'].' Kodepos '.$setting['kodepos'].'</p>
						</td>
						<td><img align="center" src="'.base_url('assets/uploads/image/'.$setting['logokabupaten']).'" alt="Logo Kabupaten" width="75" height="75" /></td>
					</tr>
				</table>
			<hr class="kop-print-hr" />
			<h3 align="center">DATA PEGAWAI '.strtoupper($status).'</h3>

		<table class="table table-responsive" width="100%">
				<tr>
					<th>No.</th>
					<th>Nama / NIP</th>
					<th>Kelamin</th>
					<th>Tempat, Tgl. Lahir</th>
					<th>Jabatan</th> 
					<th>Alamat</th>
				</tr>';
			$i = 1;
			
			foreach ($datas as $q) {
				echo '<tr>
						<td>'.$i.'</td>
						<td>'.$q->nama.'<br />NIP. '.$q->nipeg.'</td>
						<td>'.$q->kelamin.'</td>
						<td>'.$q->tempat_lahir.", ".tgl_indo($q->tgl_lahir).'</td>
						<td>'.$this->jabatan_model->get($q->jabatan)->jabatan.'</td>
						<td>'.$q->alamat.'</td>
					</tr>';
				$i++;
			}
		echo '</table>
		<br /></div>';
		?>
		<button id="btn_cetak" class="btn btn-sm btn-danger" onclick="jQuery.print('#cetak')"><i class="fa fa-print"></i> Print Halaman ini</button>
		&nbsp; &nbsp; 
		<a href="<?=base_url('cetak/pegawai/pdf/').$status;?>" class="btn btn-sm btn-default" ><i class="fa fa fa-file-pdf-o" style="font-size:28px;color:red"></i> PDF</a>
		&nbsp; &nbsp; 
		<a href="<?=base_url('cetak/pegawai/excel/').$status;?>" class="btn btn-sm btn-default" ><i class="fa fa fa-file-excel-o" style="font-size:28px;color:green"></i> Excel</a>
	<?php
		echo'<br />';
	}
	else {
		echo "<div class='col-md-6'><span class='badge btn-danger'>Belum ada Data</span></div> <br />";
	}
	?>	


<script type="text/javascript">
$(function() {
	$("#self_loading").ajaxStart(function(){
		$(this).show();
	});
	
	$("#self_loading").ajaxStop(function(){
		$(this).hide();
	});

});

$("#cetak").find('button').on('click', function() {
    //Print cetak with custom options
    $("#cetak").print({
        //Use Global styles
        globalStyles : false,
        //Add link with attrbute media=print
        mediaPrint : false,
        //Custom stylesheet
        stylesheet : "<?=base_url('assets/css/style_printing.css')?>",
        //Print in a hidden iframe
        iframe : false,
        //Don't print this
        noPrintSelector : ".avoid-this",
        //Add this at top
        prepend : "Data Siswa "<?=$status;?>,
        //Add this on bottom
        append : "Nur Sahid, S.Kom, S.P.",
        //Log to console when printing is done via a deffered callback
        deferred: $.Deferred().done(function() { console.log('Printing done', arguments); })
    });
});
</script>