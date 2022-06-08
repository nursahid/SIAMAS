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
<hr />
</div>
<div class="judul_tabel"> 
	<h3>DATA SISWA <?php echo strtoupper($status);?></h3>
</div>
<br />
<div id="outtable">
	<?php if ($data) : ?>
	<table width="98%" align="center">
	  	<thead>
	  		<tr>
				<th class="header">No.</th>
                <th>Nama</th>
                <th>NIS/NISN</th>  
                <th>Kelamin</th> 
                <th>Tempat, Tgl. Lahir</th> 
                <th>Alamat</th> 
				<th>Tgl. Daftar</th>
	  		</tr>
	  	</thead>
	  	<tbody>
            <?php foreach ($data as $data) : ?>
			<tr>
				<td><?php echo $number++; ?> </td>
				<td><?php echo $data->nama; ?></td>
				<td><?php echo $data->nis."/".$data->nisn; ?></td>
				<td><?php echo $data->kelamin; ?></td>
				<td><?php echo $data->tempat_lahir; ?>, <?php echo tgl_indo($data->tgl_lahir); ?></td>
				<td><?php echo $data->alamat; ?></td>
				<td><?php echo tgl_indo($data->tgl_mutasi_masuk); ?></td>
			</tr>     
            <?php endforeach; ?>
	  	</tbody>
	</table>
    <?php else: ?>
        <?php  echo 'Data Siswa belum tersedia';?>
    <?php endif; ?>		  
</div>
</body>
</html>