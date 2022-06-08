  <style type="text/css">
    table {
      font-family: "Times New Roman", Times;
    }
    .short{
      width: 50px;
    }
 
    .normal{
      width: 150px;
    }

	.kop h3, h2, p {
		line-height: 15px;
		text-decoration: none;
		color: #000;
		text-align: center;
		vertical-align: middle;
	}
	.judul_tabel{
		line-height: 35px;
		text-decoration: none;
		color: #000;
		text-align: center;
		vertical-align: middle;
	}
	.kop hr {
		border: thin solid #000;
		line-height: 5px;
	}	
  </style>
<!--panel-->

    <div class="box-body">
		<div id="cetak">
			<div class="kop">
				<table class="table" width="98%" border="0">
					<tr>
						<td width="20%" align="center"><img src="<?='./assets/uploads/image/'.$setting['logosekolah'];?>" alt="Logo Sekolah" width="75" height="75" /></td>
						<td width="60%" align="center">
							<h3>PEMERINTAH KABUPATEN <?php echo strtoupper($setting['kabupaten']);?></h3>
							<h2><?php echo strtoupper($setting['namasekolah']);?></h2>
							<p>Alamat : <?php echo $setting['alamat'].' '.$setting['kelurahan'].' Kecamatan '.$setting['kecamatan'].' Kodepos '.$setting['kodepos'];?></p>
						</td>
						<td width="20%" align="center"><img src="<?='./assets/uploads/image/'.$setting['logokabupaten'];?>" alt="Logo Kabupaten" width="75" height="75" /></td>
					</tr>
				</table>
			</div>
			<div class="judul_tabel" align="center">
				<h4>LAPORAN DATA ABSENSI SISWA <br />
					An. <?=$namasiswa?></h4>
					<p>Kelas : <?=$namakelas?></p>
					<p>Periode : <?php echo tgl_indo($tglawal);?> sampai <?php echo tgl_indo($tglakhir);?></p>
					<hr />
			</div>	
		<table class="table table-condensed" width="98%" border="1">
			<tr>	
				<th>Tanggal</th>
				<th>Absensi</th>	
				<th>Keterangan</th>
			</tr>

		<?php
		if ($result) {	
			echo '<tr>
					<td>'.tgl_indo(@$result->tanggal).'</td>
					<td>'.@$result->absen.'</td>
					<td>'.@$result->keterangan.'</td>
				  </tr>';
			
			echo '</table>';
		}
		else {
			echo '<tr>
					<td colspan="5"><center><span class="badge btn-danger">-- Data Tidak Ditemukan --</span></center></td>			
				  </tr>';
		}
		?>
		</table>
		</div>
	</div>

<script>
$(function() {
	$("#self_loading").ajaxStart(function(){
		$(this).show();
	});
	
	$("#self_loading").ajaxStop(function(){
		$(this).hide();
	});

	$('#add').click(function(){			
		$('#j_action').val('add_absen');
		$('#formKlien').submit();
	});
	
	$('.status').change(function() {
		var id = $(this).attr('id');
		
		if ($('.status').val() == 'sudah') {			
			$('#datepick_'+id).datepick({dateFormat: 'yyyy-mm-dd'});
			$('#datepick_'+id).show();
			$('#nilai_'+id).show();
		}
		else {
			$('#datepick_'+id).hide();
			$('#nilai_'+id).hide();
		}
	});
	
	
});
</script>
