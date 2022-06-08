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
					<p>Selamat menggunakan <strong>SiMapon</strong> (Sistem Manajemen Pondok Pesantren)</p>
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

				  </dl>
				  <a class='btn btn-block btn-lg btn-danger' href='<?=base_url('myigniter/users/profile/edit/'.$userdata->id);?>'>Edit Data Profile</a><br>
			</div>
		</div>
	</div>

	  <div class="col-lg-3 col-xs-4">
		<div class="small-box bg-aqua">
		  <div class="inner">
			<h3><?php echo ribuan($total_siswa_baru); ?></h3>

			<p>Santri Alumni</p>
		  </div>
		  <div class="icon">
			<i class="ion ion-ios-contact"></i>
		  </div>
			<?php if($this->ion_auth->is_admin()) {;?>
			<a href="<?php echo base_url('kesiswaan/alumni') ?>" class="small-box-footer">Lihat Detil <i class="fa fa-arrow-circle-right"></i></a>
			<?php }?>
		</div>
	  </div>
	  <div class="col-lg-3 col-xs-4">
		<div class="small-box bg-green">
		  <div class="inner">
			<h3><?php echo ribuan($total_siswa_aktif); ?></h3>

			<p>Santri Aktif</p>
		  </div>
		  <div class="icon">
			<i class="ion ion-ios-briefcase-outline"></i>
		  </div>
			<?php if($this->ion_auth->is_admin()) {;?>
			<a href="<?php echo base_url('kesiswaan/siswa') ?>" class="small-box-footer">Lihat Detil <i class="fa fa-arrow-circle-right"></i></a>
			<?php }?>
		</div>
	  </div>

	<a name="#riwayat_ukt"></a>
	<?php if($this->ion_auth->is_admin()) {;?>
	<div class="col-lg-6 col-xs-12">
        <!-- USERS LIST -->
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">User Terakhir</h3>
                <div class="box-tools pull-right">
                    <span class="label label-danger"><?php echo $total_users ?> New Users</span>
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
</div>

