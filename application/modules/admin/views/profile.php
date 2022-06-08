<?php
$userdata = $this->ion_auth->user()->row();
?>
<div class="row">
	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-body box-profile">
				<!-- $userdata->photo -->
				<!-- profile-user-img img-responsive img-circle -->
				<?php if (filter_var($userdata->photo,FILTER_VALIDATE_URL)): ?>
					<img src="<?php echo $userdata->photo; ?>" class="profile-user-img img-responsive img-circle" alt="<?php echo $user->full_name ?>"/>
				<?php else: ?>
					<img src="<?php echo $userdata->photo == '' ? base_url('assets/img/default.png') : base_url('assets/uploads/image/'.$userdata->photo) ?>" class="profile-user-img img-responsive img-circle" alt="<?php echo $user->full_name ?>"/>
				<?php endif; ?>
				<h3 class="profile-username text-center"><?php echo $userdata->full_name ?></h3>
				<p class="text-muted text-center"><?php echo $userdata->email ?></p>

				<a class='btn btn-block btn-lg btn-danger' href='<?=site_url('admin/users/index/edit/'.$userdata->id);?>'>Edit Data Profile</a><br>
			</div><!-- /.box-body -->
		</div>
	</div>
	
    <!-- About Me Box -->
	<div class="col-md-6">
        <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Detail</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
				<dl class='dl-horizontal'>
					<dt>Username</dt>
					<dd><?=$userdata->username;?></dd>
					
					<dt>Nama Lengkap</dt>
					<dd><?=$userdata->full_name;?></dd>

					<dt>Alamat Email</dt>
					<dd><?=$userdata->email;?></dd>

					<dt>No. Handphone</dt>
					<dd><?=$userdata->phone;?></dd>

					<dt>Terdaftar Tanggal</dt>
					<dd><?=tgl_indo(date('Y-m-d',$userdata->created_on));?></dd>

					<dt>Login Terakhir</dt>
					<dd><?=tgl_indo(date('Y-m-d',$userdata->last_login));?></dd>
					
				</dl>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
	</div>
	
</div>
