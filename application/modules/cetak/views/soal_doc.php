<?php
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=".$namafile.".doc");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Soal <?=$this->mapel_model->get_by('id',$mapel)->nama;?> </title>
  <style type="text/css">
    body{
      font-family: "Times New Roman", Times, Serif;
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
</head>
<body>
<div class="kop">
	<table class="table" border="0">
		<tr>
			<td><img src="<?=base_url('assets/uploads/image/'.$setting['logosekolah']);?>" alt="Logo Sekolah" width="75" height="75" /></td>
			<td>
				<center><h4>PEMERINTAH KABUPATEN <?php echo strtoupper($setting['kabupaten']);?></h4></center>
				<h3><?php echo strtoupper($setting['namasekolah']);?></h3>
				<small>Alamat : <?php echo $setting['alamat'].' '.$setting['kelurahan'].' Kecamatan '.$setting['kecamatan'].' Kodepos '.$setting['kodepos'];?></small>
			</td>
			<td><img src="<?=base_url('assets/uploads/image/'.$setting['logokabupaten']);?>" alt="Logo Kabupaten" width="75" height="75" /></td>
		</tr>
	</table>
	<hr />
</div>
<div class="judul_tabel"> 
	<h3><?=$namamapel;?> <br /><?=$jenjang;?> <br /><?=$namakelas;?> </h3>
</div>
<br />
<div id="outtable">
	<?php if ($soalmultiple) : ?>
			<table class="table table-condensed" width="98%" align="center">
				<tbody>
					<tr>
						<td><strong>A.</strong></td><td><strong>Pilihlah a, b, c, d dan e pada pilihan yang benar dibawah ini</strong></td>
					</tr>
				<?php
				foreach ($soalmultiple as $d) {
				?>
					<tr>
						<td width="15" valign="top"><?php echo $number++; ?>. </td>
						<td valign="top"><?php 
							echo $d->question."<br/>";
							?>
							<table width="98%" >
								<tr>
									<td width="15">a.</td>
									<td><?=$d->answer1;?></td>
								</tr>
								<tr>
									<td>b.</td>
									<td><?=$d->answer2;?></td>
								</tr>
								<tr>
									<td>c.</td>
									<td><?=$d->answer3;?></td>
								</tr>
								<tr>
									<td>d.</td>
									<td><?=$d->answer4;?></td>
								</tr>
								<tr>
									<td>e.</td>
									<td><?=$d->answer5;?></td>
								</tr>
							</table>
						</td>
					</tr>     
				<?php 
				}
				?>
					<tr>
						<td><strong>B.</strong></td><td><strong>Isilah dengan jawaban yang benar</strong></td>
					</tr>
				<?php
				foreach ($soalessay as $e) {
				?>
					<tr>
						<td width="15" valign="top"><?php echo $number++; ?>. </td>
						<td valign="top"><?php 
							echo $e->question."<br/>";
							?>
						</td>
					</tr>     
				<?php 
				}
				?>
				</tbody>
			</table>
			<hr />
			<h4>KUNCI JAWABAN</h4>
			<table class="table table-condensed" width="98%" align="center">
				<tbody>
					<tr>
						<td width="20" valign="top"><strong>A.</strong></td><td><strong>Pilihan Ganda</strong> <br/>
						<?php
						foreach ($soalmultiple as $j) {
							if($j->answer == 1) {
								$jawaban = 'A';
							} elseif($j->answer == 2) {
								$jawaban = 'B';
							} elseif($j->answer == 3) {
								$jawaban = 'C';
							} elseif($j->answer == 4) {
								$j->jawaban = 'D';
							} elseif($answer == 5) {
								$j->jawaban = 'E';
							}
						?>
							<table width="98%">
								<tbody>
									<tr>
										<td width="20" valign="top"><?php echo $number2++; ?>. </td>
										<td valign="top"><?php echo $jawaban;?></td>
									</tr>
								</tbody>
							</table>						
						<?php 
						}
						?>
						</td>
					</tr>
					<tr>
						<td valign="top"><strong>B.</strong></td><td><strong>Essay</strong>
						<br />
						<?php
						foreach ($soalessay as $k) {
						?>
							<table width="98%">
								<tbody>
									<tr>
										<td width="30" valign="top"><?php echo $number2++; ?>. </td>
										<td><?php echo $k->answer_essay;?></td>
									</tr>
								</tbody>
							</table>						
						<?php 
						}
						?>
						</td>
					</tr>
				</tbody>
			</table>
    <?php else: ?>
        <?php  echo 'Data Siswa belum tersedia';?>
    <?php endif; ?>		  
</div>
</body>
</html>