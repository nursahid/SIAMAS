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
		
			<div class="kop">
	<table class="table" width="90%" border="0">
		<tr>
			<td><img src="<?='./assets/uploads/image/'.$setting['logosekolah'];?>" alt="Logo Sekolah" width="75" height="75" /></td>
			<td>
				<h3>PEMERINTAH KABUPATEN <?php echo strtoupper($setting['kabupaten']);?></h3>
				<h2><?php echo strtoupper($setting['namasekolah']);?></h2>
				<p>Alamat : <?php echo $setting['alamat'].' '.$setting['kelurahan'].' Kecamatan '.$setting['kecamatan'].' Kodepos '.$setting['kodepos'];?></p>
			</td>
			<td><img src="<?='./assets/uploads/image/'.$setting['logokabupaten'];?>" alt="Logo Kabupaten" width="75" height="75" /></td>
		</tr>
	</table>
			</div>
			<div class="judul_tabel" align="center"> 
				<h4><?=$namasiswa?> <br />
					<?php echo strtoupper($namapembayaran);?></h4>
			</div>	
		<table class="table table-bordered" style="border-collapse:collapse;border:1px solid black;padding:6px;" width="88%">
			<tr>	
				<th style="border-collapse:collapse;border:1px solid black;padding:6px;">Nama</th>
				<th style="border-collapse:collapse;border:1px solid black;padding:6px;">Tanggal</th>
				<th style="border-collapse:collapse;border:1px solid black;padding:6px;">Nominal</th>	
				<th style="border-collapse:collapse;border:1px solid black;padding:6px;">Keterangan</th>
			</tr>

		<?php
		if ($result) {	
			echo '<tr>
					<td style="border-collapse:collapse;border:1px solid black;padding:6px;">'.@$result->nama.'</td>
					<td style="border-collapse:collapse;border:1px solid black;padding:6px;">'.tgl_indo(@$result->tgl_transaksi).'</td>
					<td style="border-collapse:collapse;border:1px solid black;padding:6px;">'.rupiah(@$result->nilai).'</td>
					<td style="border-collapse:collapse;border:1px solid black;padding:6px;">Sudah Bayar</td>
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
