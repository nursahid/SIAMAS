
	<?php
	if ($datanilai) {
		echo '<div id="cetak">
				<table class="table kop" border="0" align="center">
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
			<h3 align="center">DAFTAR NILAI KELAS '.$namakelas.'</h3>
			<h4 align="center">MAPEL : '.strtoupper($namamapel).'</h4>
			<table class="table table-responsive" width="100%">
				<tr>
					<th>No.</th>
					<th>NIS</th>
					<th>Nama</th>
					<th>Nilai</th>
					<th>Huruf</th>
					<th>Keterangan</th>
				</tr>';
			$i = 1;
			
			foreach ($datanilai->result() as $q) {
				//ambil data nilai by siswa, jenispenilaian, mapel, guru dan tanggal
				$tapel = $this->sistem_model->get_tapel_aktif();
				$semester = $this->sistem_model->get_semester_aktif();
				$tanggal = parseFormTgl('tanggal');
				
				$arr = $this->db->get_where('nilai_penilaian', array('id_jnspenilaian' => $post['jenis_penilaian'], 'id_mapel'=>$post['mapel'],'tgl_penilaian'=>$tanggal,'id_kelas' => $post['kelas'],'id_siswa' => $q->idsiswa, 'nip'=>$post['pengampu']));
				
				if ($arr->num_rows() > 0) {
					$f = $arr->row();
					$def = 'sudah';		
				}
				else {
					$def = 'belum';
				}
				
				echo '<tr>
						<td>'.$i.'</td>';
					if ($def == 'sudah') {	
						echo '	<td>'.$q->nis.'</td>
						<td>'.$q->nama.'</td>
						<td>'.$f->nilai.'</td>
						<td>'.$f->huruf.'</td>
						<td>'.$f->deskripsi.'</td>';
					} else {
						echo '	<td>'.$q->nis.'</td>
						<td>'.$q->nama.'</td>
						<td><span class="badge btn-danger">Belum Ada Nilai</span></td>
						<td>-</td>
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

<script type="text/javascript">
$(function() {
	$("#self_loading").ajaxStart(function(){
		$(this).show();
	});
	
	$("#self_loading").ajaxStop(function(){
		$(this).hide();
	});

	$('#add').click(function(){			
		$('#j_action').val('add_nilai');
		$('#formKlien').submit();
	});
});

</script>
