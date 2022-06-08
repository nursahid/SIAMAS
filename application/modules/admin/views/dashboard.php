<?php
$user_groups = $this->ion_auth->get_users_groups($userdata->id)->row()->id;
$user 	 = $this->pegawai_model->get_by('id_user', $userdata->id);
$jabatan = $user->jabatan;
//var_dump($user);
?>

<div class="row">

	<div class="col-lg-6 col-xs-12">
		<div class="box box-info">

			<div class='box-header'>
			  <h3 class='box-title'>Identitas Login</h3>
			</div>
			<div class='box-body'>
			<div class='box box-widget widget-user'>
				<div class='widget-user-header bg-aqua-active'>
				  <h3 class='widget-user-username'><?=$userdata->full_name;?></h3>
				  <h5 class='widget-user-desc'>Members</h5>
				</div>
				<div class='widget-user-image'>
				  <img class='img-circle' src='<?php echo $userdata->photo == '' ? base_url('assets/img/default.png') : base_url('assets/uploads/image/'.$userdata->photo) ?>' alt='User Avatar'>
				</div>
				<div class='box-footer' style='padding-bottom:0px'>
				  <div class='row'>
					<center><br>
					<p>Selamat menggunakan <strong>SiAMAS</strong> (Sistem Informasi dan Manajemen Sekolah)</p>
					<p style='width:90%'>Silahkan mengelola data-data melalui menu-menu yang sudah tersedia pada bagian sebelah kiri halaman ini,
						mulai dari atas. </br>
						Berikut data informasi detail akun anda saat ini : </p>
					</center>
				  </div>
				</div>
			  </div>
			  <dl class='dl-horizontal'>
					<dt>Nama Lengkap</dt>
					<dd><?=$userdata->full_name;?></dd>

					<dt>Alamat Email</dt>
					<dd><?=$userdata->email;?></dd>

					<dt>No. Handphone</dt>
					<dd><?=$userdata->phone;?></dd>
					
					<dt>Jabatan</dt>
					<dd><?=$this->sistem_model->get_nama_jabatan($jabatan);?></dd>
					
					<dt>User Akses</dt>
					<dd><?=$this->sistem_model->get_nama_grup($user_groups);?></dd>

				  </dl>
				  <a class='btn btn-block btn-lg btn-danger' href='<?=base_url('admin/users/index/edit/'.$userdata->id);?>'>Edit Data Profile</a><br>
			</div>
		</div>
	</div>
	
	<?php if($this->ion_auth->in_group(array('superadmin', 'admin'))) {;?>
	  <div class="col-lg-2 col-xs-4">
		<div class="small-box bg-aqua">
		  <div class="inner">
			<h3><?php echo ribuan($total_siswa_alumni); ?></h3>

			<p>Alumni</p>
		  </div>
		  <div class="icon">
			<i class="ion ion-ios-contact"></i>
		  </div>
			<?php if($this->ion_auth->is_admin()) {;?>
			<a href="<?php echo base_url('kesiswaan/alumni') ?>" class="small-box-footer">Lihat Detil <i class="fa fa-arrow-circle-right"></i></a>
			<?php }?>
		</div>
	  </div>
	  <div class="col-lg-2 col-xs-4">
		<div class="small-box bg-red">
		  <div class="inner">
			<h3><?php echo ribuan($total_siswa_mutasi); ?></h3>

			<p>Mutasi Keluar</p>
		  </div>
		  <div class="icon">
			<i class="ion ion-ios-contact"></i>
		  </div>
			<?php if($this->ion_auth->is_admin()) {;?>
			<a href="<?php echo base_url('kesiswaan/mutasi/keluar') ?>" class="small-box-footer">Lihat Detil <i class="fa fa-arrow-circle-right"></i></a>
			<?php }?>
		</div>
	  </div>
	  <div class="col-lg-2 col-xs-4">
		<div class="small-box bg-green">
		  <div class="inner">
			<h3><?php echo ribuan($total_siswa_aktif); ?></h3>
			<p>Siswa Aktif</p>
		  </div>
		  <div class="icon">
			<i class="ion ion-ios-briefcase-outline"></i>
		  </div>
			<?php if($this->ion_auth->is_admin()) {;?>
			<a href="<?php echo base_url('kesiswaan/siswa') ?>" class="small-box-footer">Lihat Detil <i class="fa fa-arrow-circle-right"></i></a>
			<?php }?>
		</div>
	  </div>
	<?php }?>
	
	<?php if($this->ion_auth->in_group(array('superadmin', 'admin'))) {;?>
	<div class="col-lg-6 col-xs-12">
        <!-- USERS LIST -->
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">User Terakhir</h3>
                <div class="box-tools pull-right">
                    <span class="label label-danger"><?php echo $total_users ?> User Baru</span>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <ul class="users-list clearfix">
                    <?php foreach ($users as $key => $user) { ?>
                    <li>
                        <?php if (filter_var($user->photo,FILTER_VALIDATE_URL)): ?>
                            <img src="<?php echo $user->photo; ?>" class="img-circle" alt="<?php echo $user->full_name ?>"/>
                        <?php else: ?>
                            <img src="<?php echo $user->photo == '' ? base_url('assets/img/default.png') : base_url('assets/uploads/image/'.$user->photo) ?>" class="img-circle" alt="<?php echo $user->full_name ?>"/>
                        <?php endif; ?>
                        <a class="users-list-name" title="<?php echo $user->full_name ?>" href="#"><?php echo $user->full_name ?></a>
                        <span class="users-list-date"><?php echo time_elapsed_string($user->created_on) ?></span>
                    </li>
                    <?php } ?>
                </ul>
                <!-- /.users-list -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
			<?php if($this->ion_auth->is_admin()) {;?>
                <a href="<?php echo site_url('myigniter/users') ?>" class="uppercase">View All Users</a>
			<?php }?>
            </div>
            <!-- /.box-footer -->
        </div>
        <!--/.box -->
    </div>
	<?php }?>
	
	  <div class="col-md-3 col-sm-6 col-xs-12">
		 <div class="info-box">
			<a href="<?=site_url('report/siswa');?>">
				<span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>
			</a>
			<div class="info-box-content">
			  <span class="info-box-text">Siswa</span>
			  <span class="info-box-number"><?=$jml_siswa;?></span>
			</div>
		 </div>
	  </div>
	  <div class="col-md-3 col-sm-6 col-xs-12">
		 <div class="info-box">
		 	<a href="<?=site_url('report/pegawai');?>">
				<span class="info-box-icon bg-green"><i class="fa fa-group"></i></span>
			</a>
			<div class="info-box-content">
			  <span class="info-box-text">Pegawai</span>
			  <span class="info-box-number"><?=$jml_pegawai;?></span>
			</div>
		 </div>
	  </div>
	  <div class="col-md-3 col-sm-6 col-xs-12">
		 <div class="info-box">
			<a href="<?=site_url('report/mutasi_masuk');?>">
				<span class="info-box-icon bg-red"><i class="fa fa-child"></i></span>
			</a>
			<div class="info-box-content">
			  <span class="info-box-text">Mutasi Masuk</span>
			  <span class="info-box-number"><?=$jml_mutasi_masuk;?></span>
			</div>
		 </div>
	  </div>
	  <div class="col-md-3 col-sm-6 col-xs-12">
		 <div class="info-box">
		 	<a href="<?=site_url('report/mutasi_keluar');?>">
				<span class="info-box-icon bg-green"><i class="fa fa-user-secret"></i></span>
			</a>
			<div class="info-box-content">
			  <span class="info-box-text">Mutasi Keluar</span>
			  <span class="info-box-number"><?=$jml_mutasi_keluar;?></span>
			</div>
		 </div>
	  </div>
	  
	  
	  <div class="col-md-3 col-sm-6 col-xs-12">
		 <div class="info-box">
		 	<a href="<?=site_url('report/kelas');?>">
				<span class="info-box-icon bg-yellow"><i class="fa fa-edit"></i></span>
			</a>
			<div class="info-box-content">
			  <span class="info-box-text">Kelas</span>
			  <span class="info-box-number"><?=$jml_kelas;?></span>
			</div>
		 </div>
	  </div>
	  <div class="col-md-3 col-sm-6 col-xs-12">
		 <div class="info-box">
		 	<a href="<?=site_url('report/jurusan');?>">
				<span class="info-box-icon bg-red"><i class="fa fa-file-o"></i></span>
			</a>
			<div class="info-box-content">
			  <span class="info-box-text">Jurusan</span>
			  <span class="info-box-number"><?=$jml_jurusan;?></span>
			</div>
		 </div>
	  </div>

</div>

<?php if($this->ion_auth->in_group(array('superadmin', 'kepsek'))) {;?>
<div class="row">	
	
	<div class="col-lg-6 col-xs-12">
		<div class="box box-primary">
		  <div class="box-header with-border">
			<i class="fa fa-envelope"></i>
			<h3 class="box-title">Pesan <small>Belum terbaca</small></h3>
		  </div>
		  <div class="box-body">
		  
		  </div>
		</div>
	</div>
</div>	
<?php }?>