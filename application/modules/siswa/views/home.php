<?php
if($userdata->foto == NULL) {
	if($userdata->kelamin == 'L'){
		$foto = base_url('assets/img/default.png');
	} elseif($userdata->kelamin == 'P') {
		$foto = base_url('assets/img/default_female.png');
	}
} else {
	$foto = base_url('assets/uploads/siswa/').$userdata->foto;
}
?>

<h3><?php echo $title ?></h3>
	
<div class="row">

	<div class="col-lg-6 col-xs-12">
		<div class="box box-info">

			<div class='box-header'>
			  <h3 class='box-title'>Identitas Login</h3>
			</div>
			<div class='box-body'>
			<div class='box box-widget widget-user'>
				<div class='widget-user-header bg-aqua-active'>
				  <h3 class='widget-user-username'><?=$userdata->nama_lengkap;?></h3>
				  <h5 class='widget-user-desc'>Siswa</h5>
				</div>
				<div class='widget-user-image'>
				  <img class='img-circle' src='<?=$foto;?>' alt='User Avatar'>
				</div>
				<div class='box-footer' style='padding-bottom:0px'>
				  <div class='row'>
					<center><br>
					<p>Selamat menggunakan <strong>SiAMAS</strong> (Sistem Informasi & Manajemen Sekolah)</p>
					<p style='width:90%'>Silahkan mengelola data-data melalui menu-menu yang sudah tersedia pada bagian sebelah kiri halaman ini,
						mulai dari atas. </br>
						Berikut data informasi detail akun anda saat ini : </p>
					</center>
				  </div>
				</div>
			  </div>
			  <dl class='dl-horizontal'>
					<dt>Nama Lengkap</dt>
					<dd><?=$userdata->nama;?></dd>

					<dt>Tempat, Tgl. Lahir</dt>
					<dd><?=$userdata->tempat_lahir ;?>, <?=tgl_indo($userdata->tgl_lahir);?></dd>

					<dt>Jurusan/Prodi</dt>
					<dd><?=$this->members_model->get_jurusan($userdata->id);?></dd>

					<dt>Kelas</dt>
					<dd><?=$this->kelas_model->get_kelas_by_siswa($userdata->id);?></dd>
					
					<dt>Alamat Lengkap</dt>
					<dd><?=$userdata->alamat;?></dd>

				  </dl>
				  <a class='btn btn-block btn-lg btn-danger' href='<?=base_url('siswa/profile');?>'>Edit Data Profile</a><br>
			</div>
		</div>
	</div>

	  <div class="col-lg-3 col-xs-4">
		<div class="small-box bg-aqua">
		  <div class="inner">
			<h3><?php echo $jml_penilaian; ?></h3>

			<p>Perolehan Nilai</p>
		  </div>
		  <div class="icon">
			<i class="ion ion-ios-contact"></i>
		  </div>
		  <a href="<?php echo base_url('siswa/nilai') ?>" class="small-box-footer">Lihat Detil <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	  </div>
	  <div class="col-lg-3 col-xs-4">
		<div class="small-box bg-green">
		  <div class="inner">
			<h3><?php echo "Rp. ".rupiah($jml_pembayaran); ?></h3>

			<p>Pembayaran</p>
		  </div>
		  <div class="icon">
			<i class="ion ion-ios-briefcase-outline"></i>
		  </div>
		  <a href="<?php echo base_url('siswa/pembayaran') ?>" class="small-box-footer">Lihat Detil <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	  </div>

	<a name="#riwayat_ukt"></a>
	<div class="col-lg-6 col-xs-12">
		<div class="box box-primary">
		  <div class="box-header with-border">
			<i class="fa fa-briefcase"></i>
			<h3 class="box-title">Riwayat <small>Absensi</small></h3>
		  </div>
		  <div class="box-body">
			<strong>A. ABSENSI HARIAN</strong> <hr/>
			<table class="table table-condensed">
				<thead>
					<tr>
						<th>No.</th><th>Tgl. Absensi</th><th>Keterangan</th>
					</tr>
				</thead>
				<tbody>
				<?php
					if (empty($absensi)) {
						echo "<tr><td colspan='3'>Data kosong</td></tr>";
					} else {						
						$nomer = 0;
						foreach ($absensi as $ru) {
							if($ru->absen=='hadir') {$absen ="<span class='badge btn-success'>Hadir</span>";}
							elseif($ru->absen=='alfa') {$absen ="<span class='badge btn-danger'>Alfa</span>";}
							$nomer++;
							echo "<tr><td>".$nomer."</td><td>".tgl_indo($ru->tanggal)."</td><td>".$absen."</td></tr>";
						}
					}
				?>
				</tbody>
			</table>

			<hr/><strong>B. ABSENSI MATA PELAJARAN</strong> <hr/>
			<table class="table table-condensed">
				<thead>
					<tr>
						<th>No.</th><th>Mata Pelajaran</th><th>Tgl. Absensi</th><th>Keterangan</th>
					</tr>
				</thead>
				<tbody>
				<?php
					if (empty($absensi_mapel)) {
						echo "<tr><td colspan='4'><span class='badge btn-danger'>Data kosong</span></td></tr>";
					} else {						
						$nomer2 = 0;
						foreach ($absensi_mapel as $am) {
							$nomer2++;
							if($am->absen=='hadir') {$absens ="<span class='badge btn-success'>Hadir</span>";}
							elseif($am->absen=='alfa') {$absens ="<span class='badge btn-danger'>Alfa</span>";}
							echo "<tr><td>".$nomer2."</td><td>".$this->members_model->get_nama_mapel($am->id_mapel)."</td><td>".tgl_indo($am->tanggal)."</td><td>".$absens."</td></tr>";
						}
					}
				?>
				</tbody>
			</table>
			<center><a href="<?php echo base_url('siswa/absensi') ?>" class="small-box-footer">Lihat Detil <i class="fa fa-arrow-circle-right"></i></a></center>


		  </div>
		</div>
	</div>
	  
</div>