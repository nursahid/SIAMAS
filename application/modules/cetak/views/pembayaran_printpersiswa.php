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
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><i class="fa fa-list"></i> Laporan Pembayaran </h3>
		<div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			<button class="btn btn-box-tool" id="mini-refresh"><i class='fa fa-refresh'></i></button>
		</div>
	</div>
    <div class="box-body">
		<div id="cetak">
			<div class="kop">
				<table class="table" width="98%" border="0">
					<tr>
						<td width="20%" align="center"><img src="<?=base_url('assets/uploads/image/'.$setting['logosekolah']);?>" alt="Logo Sekolah" width="75" height="75" /></td>
						<td width="60%" align="center">
							<h3>PEMERINTAH KABUPATEN <?php echo strtoupper($setting['kabupaten']);?></h3>
							<h2><?php echo strtoupper($setting['namasekolah']);?></h2>
							<p>Alamat : <?php echo $setting['alamat'].' '.$setting['kelurahan'].' Kecamatan '.$setting['kecamatan'].' Kodepos '.$setting['kodepos'];?></p>
						</td>
						<td width="20%" align="center"><img src="<?=base_url('assets/uploads/image/'.$setting['logokabupaten']);?>" alt="Logo Kabupaten" width="75" height="75" /></td>
					</tr>
				</table>
			</div>
			<div class="judul_tabel" align="center"> 
				<h4><?=$namasiswa?></br>
					<?php echo strtoupper($namapembayaran);?></br></h4>
			</div>	
		<table class="table table-condensed" width="98%">
			<tr>	
				<th>Nama</th>
				<th>Tanggal</th>
				<th>Nominal</th>	
				<th>Keterangan</th>
			</tr>

		<?php
		if ($result) {	
			echo '<tr>
					<td>'.@$result->nama.'</td>
					<td>'.tgl_indo(@$result->tgl_transaksi).'</td>
					<td>'.rupiah(@$result->nilai).'</td>
					<td>Sudah Bayar</td>
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
		<div class="box-footer"> 
			<a href="<?php echo site_url($back_button); ?>" class="btn btn-sm btn-info"><i class="fa fa-chevron-left"></i> Kembali</a> 
			<button id="print_button" class="btn btn-sm btn-danger" onclick="jQuery.print('#cetak')"><i class="fa fa-print"></i> Print Halaman ini</button>
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
