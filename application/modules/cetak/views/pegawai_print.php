<!DOCTYPE html>
<html>
<head>
  <title>Report Table</title>
  <style type="text/css">
    #outtable1{
      padding: 20px;
      border:1px solid #e3e3e3;
      width:100%;
      border-radius: 5px;
    }
 
    .short{
      width: 50px;
    }
 
    .normal{
      width: 150px;
    }
 
    table{
      border-collapse: collapse;
      font-family: arial;
    }
 
    thead th{
      text-align: left;
      padding: 10px;
	  border-top: 1px solid #e3e3e3;
    }
 
    tbody td{
      border-top: 1px solid #e3e3e3;
      padding: 10px;
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
<!--panel-->
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><i class="fa fa-table"></i> Data Pegawai <?=$status;?></h3>
		<div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			<button class="btn btn-box-tool" id="mini-refresh"><i class='fa fa-refresh'></i></button>
		</div>
	</div>
    <div class="box-body">
		<div id="cetak">
			<div class="kop-print">
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
				<hr class="kop-print-hr" />
			</div>
			<div class="judul_tabel"> 
				<h3>DATA PEGAWAI <?php echo strtoupper($status);?></h3>
			</div>
			<br />
			<div id="outtable" class="print">
				<?php if ($data) : ?>
				<table class="table table-bordered table-striped" width="98%" align="center">
					<thead>
						<tr>
							<th class="header">No.</th>
							<th>Nama</th>
							<th>NIP</th>  
							<th>Kelamin</th> 
							<th>Tempat, Tgl. Lahir</th> 
							<th>Jabatan</th> 
							<th>Alamat</th> 
						</tr>
					</thead>
					<tbody>
						<?php foreach ($data as $q) : ?>
						<tr>
							<td><?php echo $number++; ?> </td>
							<td><?php echo $q->nama; ?></td>
							<td><?php echo $q->nipeg; ?></td>
							<td><?php echo $q->kelamin; ?></td>
							<td><?php echo $q->tempat_lahir; ?>, <?php echo tgl_indo($q->tgl_lahir); ?></td>
							<td><?php echo $this->jabatan_model->get($q->jabatan)->jabatan; ?></td>
							<td><?php echo $q->alamat; ?></td>
						</tr>     
						<?php endforeach; ?>
					</tbody>
				</table>
				<?php else: ?>
					<?php  echo '<span class"label label-error">Data Pegawai belum tersedia</span>';?>
				<?php endif; ?>		  
			</div>
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
        prepend : "Data Siswa "<?=$status;?>,
        //Add this on bottom
        append : "Nur Sahid, S.Kom, S.P.",
        //Log to console when printing is done via a deffered callback
        deferred: $.Deferred().done(function() { console.log('Printing done', arguments); })
    });
});
</script>	
</body>
</html>