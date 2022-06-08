  <style type="text/css">
 
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
		<h3 class="box-title"><i class="fa fa-table"></i> Soal : <?=$this->mapel_model->get_by('id',$mapel)->nama;?></h3>
		<div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			<button class="btn btn-box-tool" id="mini-refresh"><i class='fa fa-refresh'></i></button>
		</div>
	</div>
    <div class="box-body">
		<div id="cetak">
			<div class="kop">
				<table class="table" border="0">
					<tr>
						<td><img src="<?=base_url('assets/uploads/image/'.$setting['logosekolah']);?>" alt="Logo Sekolah" width="75" height="75" /></td>
						<td>
							<h3>PEMERINTAH KABUPATEN <?php echo strtoupper($setting['kabupaten']);?></h3>
							<h2><?php echo strtoupper($setting['namasekolah']);?></h2>
							<p>Alamat : <?php echo $setting['alamat'].' '.$setting['kelurahan'].' Kecamatan '.$setting['kecamatan'].' Kodepos '.$setting['kodepos'];?></p>
						</td>
						<td><img src="<?=base_url('assets/uploads/image/'.$setting['logokabupaten']);?>" alt="Logo Kabupaten" width="75" height="75" /></td>
					</tr>
				</table>
				<hr />
			</div>
			<div class="judul_tabel"> 
				<h3><?=$this->mapel_model->get_by('id',$mapel)->mapel;?> <br /><?=$jenjang;?> <br /><?=$namakelas;?> </h3>
			</div>
			<br />
			<table class="table table-condensed" width="98%" align="center">
				<tbody>
					<tr>
						<td><strong>A.</strong></td><td><strong>Pilihlah a, b, c, d dan e pada pilihan yang benar dibawah ini</strong></td>
					</tr>
				<?php
				foreach ($soalmultiple as $d) {
				?>
					<tr>
						<td valign="top"><?php echo $number++; ?>. </td>
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
						<td valign="top"><?php echo $number++; ?>. </td>
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
						<td width="25" valign="top"><strong>A.</strong></td><td><strong>Pilihan Ganda</strong> <br/>
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
										<td width="30"><?php echo $number2++; ?>. </td>
										<td><?php echo $jawaban;?></td>
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
										<td valign="top"><?php echo $k->answer_essay;?></td>
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
			
		</div>
		<div class="box-footer"> 
			<a href="<?php echo site_url($back_button); ?>" class="btn btn-sm btn-info"><i class="fa fa-chevron-left"></i> Kembali</a> 
			<button id="print_button" class="btn btn-sm btn-danger" onclick="jQuery.print('#cetak')"><i class="fa fa-print"></i> Print Halaman ini</button>
		</div>	
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
        prepend : "Soal : "<?=$this->mapel_model->get_by('id',$mapel)->nama;?>,
        //Add this on bottom
        append : "Nur Sahid, S.Kom, S.P.",
        //Log to console when printing is done via a deffered callback
        deferred: $.Deferred().done(function() { console.log('Printing done', arguments); })
    });
});
 </script>