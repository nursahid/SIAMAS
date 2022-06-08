
	<?php
	if ($absen) {
	echo '<div id="cetak">
				<table class="table table-responsive" border="0" align="center">
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
			<h4 align="center">DAFTAR ABSENSI KELAS '.$namakelas.'</h4>
			<h5 align="center">TANGGAL ABSENSI : '.strtoupper(tgl_indo($tgl)).'</h5>
	
	<table class="table table-responsive" width="100%">
	<tr>
		<th>No.</th>
		<th>NIS</th>
		<th>Nama</th>
		<th>Absensi</th>
		<th>Keterangan</th>
	</tr>';
		$i = 1;		
		foreach ($absen->result() as $q) {
			$tapel = $this->sistem_model->get_tapel_aktif();
			$semester = $this->sistem_model->get_semester_aktif();
			//Data Absesi
			if($this->ion_auth->in_group(array('superadmin', 'admin'))) {
				//absensi harian
				$arr = $this->db->get_where('absensi', array('nis' => $q->nis, 'tanggal' => $tgl, 'tahun' => $tapel, 'smt' => $semester));				
			}
			elseif($this->ion_auth->in_group('guru')) {
				//absensi per mapel
				$arr = $this->db->get_where('absensi_mapel', array('id_mapel' => $id_mapel, 'nis' => $q->nis, 'tanggal' => $tgl, 'tahun' => $tapel, 'smt' => $semester));			
			}
			
			if ($arr->num_rows() > 0) {
				$r = $arr->row();
				$def = $r->absen;
			}
			else {
				$def = 'belum';
			}
			if($q->nis == '') {
				$nis = "<span class='badge btn-danger'>Belum ada NIS</span>";
			} else {
				$nis = $q->nis;
			}

				echo '<tr>
						<td>'.$i.'</td>';
					if ($def == 'sudah') {	
						echo '	<td>'.$nis.'</td>
						<td>'.$q->nama.'</td>
						<td>'.$f->absen.'</td>
						<td>'.$f->keterangan.'</td>';
					} else {
						echo '<td>'.$nis.'</td>
						<td>'.$q->nama.'</td>
						<td><span class="badge btn-danger">'.$def.'</span></td>
						<td>-</td>';
					}
				echo '	  </tr>';
			$i++;
		}
	echo '</table>
		<br /></div>';
		?>
		<button id="btn_cetak" class="btn btn-sm btn-danger" onclick="jQuery.print('#cetak')"><i class="fa fa-print"></i> Print Halaman ini</button>
	<?php
		echo'<br />';
	}

else {
	echo "<div class='col-md-6'><span class='badge btn-danger'>Belum ada siswa di kelas ini</span></div> <br />";
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

	$('#add').click(function(){			
		var namasiswa = $('input[name="nama[]"]').val();
		if ($('input[name="nis[]"]').val() == "") { alertify.alert('Siswa '+namasiswa+' belum punya NIS'); return false; }
		$('#j_action').val('add_absen');
		$('#formKlien').submit();
	});
});
</script>
<script type="text/javascript">
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