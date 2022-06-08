  <style type="text/css">
    table {
      font-family: "Times New Roman", Times;
    }
	.table1 {
		border-collapse:collapse;
		border:1px solid black;
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
				<h4>LAPORAN DATA PEMBAYARAN <br />
					<?php echo strtoupper($namapembayaran);?> <br />
					<?=strtoupper($namakelas);?></h4>
			</div>	
		
		<table style="border-collapse:collapse;padding:8px;border:1px solid black;" width="88%" align="center">
			<tr> 
				<th style="border-collapse:collapse;border:1px solid black;padding:8px;">No.</th>
				<th style="border-collapse:collapse;border:1px solid black;padding:8px;">Nama</th>
				<th style="border-collapse:collapse;border:1px solid black;padding:8px;">Tanggal</th>
				<th style="border-collapse:collapse;border:1px solid black;padding:8px;">Nominal</th>	
				<th style="border-collapse:collapse;border:1px solid black;padding:8px;">Keterangan</th>
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
						<td style="border-collapse:collapse;border:1px solid black;padding:8px;">'.$i.'</td>';
						
						if ($def == 'sudah') {
							echo '<td style="border-collapse:collapse;border:1px solid black;padding:8px;">'.$q->nama.'</td>';	
							echo '<td style="border-collapse:collapse;border:1px solid black;padding:8px;">'.tgl_indo($f->tgl_transaksi).'</td>
								  <td style="border-collapse:collapse;border:1px solid black;padding:8px;">'.rupiah($f->nilai).'</td>
								  <td style="border-collapse:collapse;border:1px solid black;padding:8px;"><span class="badge btn-success">Sudah Bayar</span></td>';
					  
						}
						else {
							echo '<td style="border-collapse:collapse;border:1px solid black;padding:8px;">'.$q->nama.'</td>';
							echo '<td style="border-collapse:collapse;border:1px solid black;padding:8px;">-</td>
								  <td style="border-collapse:collapse;border:1px solid black;padding:8px;">-</td>';
							echo '<td style="border-collapse:collapse;border:1px solid black;padding:8px;">'.$def.'</td>';
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
