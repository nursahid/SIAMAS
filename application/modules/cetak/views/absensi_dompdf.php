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
				<h4>LAPORAN DATA ABSENSI <br />
					<?=$namakelas?><br/>
					<small>Periode : <?php echo tgl_indo($tglawal);?> sampai <?php echo tgl_indo($tglakhir);?></small></h4>
					<hr />
			</div>	
		
		<table class="table table-condensed" width="98%" align="center" border="1">
			<tr>
				<th>No.</th>
				<th>Nama</th>
				<th>NIS</th>
				<th>Tanggal</th>
				<th>Absensi</th>	
				<th>Keterangan</th>
			</tr>

		<?php
		if ($datas) {	
			$i = 1;
			
			foreach ($datas->result() as $q) {
				//get data by nis
				$condition = "tanggal BETWEEN " . "'" . $tglawal . "'" . " AND " . "'" . $tglakhir . "'";
				$this->db->select('*');
				$this->db->from('absensi');
				$this->db->where('nis',$q->nis);
				$this->db->where($condition);
				$query = $this->db->get();
				if ($query->num_rows() > 0) {
					$t = $query->row();
				}
				
				echo '<tr>
						<td>'.$i.'</td>';
				echo '  <td>'.$q->nama.'</td>
					    <td>'.$q->nis.'</td>
						<td>'.tgl_indo($t->tanggal).'</td>
						<td>'.$t->absen.'</td>
						<td>'.$t->keterangan.'</td>';				
				echo '</tr>';
				$i++;
			}	
		}
		else {
			echo '<tr>
					<td colspan="5"><center><span class="badge btn-danger">-- Data Absensi Tidak Ditemukan --</span></center></td>			
				  </tr>';
		}
		?>	
		</table>

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