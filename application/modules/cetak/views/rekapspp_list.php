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
		<h3 class="box-title"><i class="fa fa-list"></i> Print Pembayaran </h3>
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
				<h4>LAPORAN DATA PEMBAYARAN</br>
					<?php echo strtoupper($namapembayaran);?></br>
					<?=$kelas?></h4>
			</div>	
		
		<table class="table table-condensed" width="98%" align="center">
			<tr>
				<th>No.</th>
				<th>Nama</th>
				<th>Status</th>
				<th>Tanggal</th>
				<th>Nominal</th>	
			</tr>

		<?php
		if ($absen) {	
			$i = 1;
			
			foreach ($absen->result() as $q) {
				$arr = $this->db->get_where('pembayaran', array('id_siswa' => $q->id_siswa, 'id_jnspembayaran' => $pembayaran, 'bulan' => $post['bulan'], 'tahun' => $post['tahun']));
				
				if ($arr->num_rows() > 0) {
					$f = $arr->row();
					$def = 'sudah';			
				}
				else {
					$def = '<span class="badge btn-danger">Belum Bayar</span>';
				}
				
				echo '<tr>
						<td>'.$i.'</td>';
						
						if ($def == 'sudah') {
							echo '<td>'.$q->nama.'</td>';	
							echo '<td><span class="badge btn-success">Sudah Bayar</span></td>
								  <td>'.tgl_indo($f->tgl_transaksi).'</td>
								  <td>'.rupiah($f->nilai).'</td>';
					  
						}
						else {
							echo '<td>'.$q->nama.'</td>';
							echo '<td>'.$def.'</td>';
							echo '<td>-</td>
								  <td>-</td>';
						}				
				$i++;
				echo '</tr>';
			}	
		}
		else {
			echo '<tr>
					<td colspan="5"><center><span class="badge btn-danger">-- Data Siswa Tidak Ditemukan --</span></center></td>			
				  </tr>';
		}
		?>	
		</table>

		</div>
	</div>
		<div class="box-footer"> 
			<a href="<?php echo site_url('cetak'); ?>" class="btn btn-sm btn-info"><i class="fa fa-chevron-left"></i> Kembali</a> 
			<button id="print_button" class="btn btn-sm btn-danger" onclick="jQuery.print('#cetak')"><i class="fa fa-print"></i> Print Halaman ini</button>
		</div>
</div>

<script type="text/javascript">
$("#cetak").find('button').on('click', function() {
    //Print cetak with custom options
    $("#cetak").print({
        //Use Global styles
        globalStyles : false,
        //Add link with attrbute media=print
        mediaPrint : false,
        //Custom stylesheet
        stylesheet : "http://fonts.googleapis.com/css?family=Inconsolata",
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