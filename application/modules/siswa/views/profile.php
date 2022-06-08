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
<div class="row">
  <div class="col-md-3">
    <!-- Profile Image -->
    <div class="box box-primary">
      <div class="box-body box-profile">
        <img class="profile-user-img img-responsive img-circle" src="<?php echo $foto; ?>" alt="User profile picture">

        <h3 class="profile-username text-center"><?php echo $userdata->nama; ?></h3>

        <p class="text-muted text-center">Siswa </p>

        <ul class="list-group list-group-unbordered">
          <li class="list-group-item">
            <b>NIS</b> <a class="pull-right"><?php echo $userdata->nis; ?></a>
          </li>
		  <li class="list-group-item">
            <b>Kelas</b> <a class="pull-right"><?=$this->kelas_model->get_kelas_by_siswa($userdata->id);?></a>
          </li>
		  <li class="list-group-item">
            <b>Jurusan</b> <a class="pull-right"><?=$this->members_model->get_jurusan($userdata->id);?></a>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="col-md-9">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#settings" data-toggle="tab">Settings</a></li>
        <li><a href="#password" data-toggle="tab">Ubah Password</a></li>
      </ul>
      <div class="tab-content">
        <div class="active tab-pane" id="settings">
          <form class="form-horizontal" action="<?php echo base_url('siswa/profile/update') ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
			<div class="form-group">
              <label for="inputUsername" class="col-sm-3 control-label">Username</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id= placeholder="Username" name="username" value="<?php echo $userdata->username; ?>" readonly>
              </div>
            </div>
            <div class="form-group">
              <label for="nisn" class="col-sm-3 control-label">NISN</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="NISN" name="nisn" value="<?php echo $userdata->nisn; ?>">
              </div>
            </div>
            <div class="form-group">
              <label for="nik" class="col-sm-3 control-label">NIK</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="NIK" name="nik" value="<?php echo $userdata->nik; ?>">
              </div>
            </div>
			
            <div class="form-group">
              <label for="inputNama" class="col-sm-3 control-label">Nama Lengkap</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="Nama Lengkap" name="nama_lengkap" value="<?php echo $userdata->nama; ?>">
              </div>
            </div>

            <div class="form-group">
              <label for="tempat_lahir" class="col-sm-3 control-label">Tempat, Tgl. Lahir</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" placeholder="Tempat Lahir" name="tmp_lahir" value="<?php echo $userdata->tempat_lahir; ?>">
              </div>

              <div class="col-sm-4">

					<div class="input-group date" data-provide="datepicker">
						<input type="text" class="form-control" name="tgl_lahir" id="tgl_lahir" data-date-format="yyyy-mm-dd" data-date-end-date="0d" value='<?php echo date("m/d/Y", strtotime($userdata->tgl_lahir)); ?>'>
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-th"></span>
						</div>
					</div>
				
			  </div>
            </div>
            <div class="form-group">
              <label for="email" class="col-sm-3 control-label">E-Mail</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="E-Mail" name="email" value="<?php echo $userdata->email; ?>">
              </div>
            </div>

            <div class="form-group">
              <label for="alamat" class="col-sm-3 control-label">Alamat</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="Alamat Lengkap" name="alamat" value="<?php echo $userdata->alamat; ?>">
              </div>
            </div>			
			
            <div class="form-group">
              <label for="inputFoto" class="col-sm-3 control-label">Foto</label>
              <div class="col-sm-8">
                <input type="file" class="form-control" placeholder="Foto" name="foto">
              </div>
            </div>
            
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-8">
                <button type="submit" class="btn btn-danger">Submit</button>
              </div>
            </div>
          </form>
        </div>
        <div class="tab-pane" id="password">
          <form class="form-horizontal" action="<?php echo base_url('siswa/profile/ubah_password') ?>" method="POST">
			<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="form-group">
              <label for="passLama" class="col-sm-3 control-label">Password Lama</label>
              <div class="col-sm-8">
                <input type="password" class="form-control" placeholder="Password Lama" name="passLama">
              </div>
            </div>
            <div class="form-group">
              <label for="passBaru" class="col-sm-3 control-label">Password Baru</label>
              <div class="col-sm-8">
                <input type="password" class="form-control" placeholder="Password Baru" name="passBaru">
              </div>
            </div>
            <div class="form-group">
              <label for="passKonf" class="col-sm-3 control-label">Konfirmasi Password</label>
              <div class="col-sm-8">
                <input type="password" class="form-control" placeholder="Konfirmasi Password" name="passKonf">
              </div>
            </div>
            
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-8">
                <button type="submit" class="btn btn-danger">Submit</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div id="notifications"><?php echo $this->session->flashdata('alert'); ?></div>
  </div>
</div>

<?php
$path = ['assets/plugins/highlightjs/styles/tomorrow-night-eighties.css',
		 'assets/plugins/datepicker/datepicker3.css',
		 'assets/plugins/iCheck/skins/all.css'
      ];
$path2 = ['assets/plugins/highlightjs/highlight.pack.js',
		  'assets/plugins/datepicker/bootstrap-datepicker.js',
		  'assets/plugins/jquery/dist/jquery.min.js',
		 'assets/plugins/iCheck/icheck.min.js'
      ];
?>
<?php 
$this->layout->set_assets($path, 'styles');
$this->layout->set_assets($path2, 'scripts');
echo $this->layout->get_assets('styles');
echo $this->layout->get_assets('scripts');
?>
  
<script type="application/javascript">
$(document).ready(function() {

   //Date picker
	$(function() {
		$('.datepicker input').datepicker({
			format: "yyyy-mm-dd",
			clearBtn: true,
			language: "id"
		});
	});   

 	// show the alert
	setTimeout(function() {
		$(".alert").alert('close');
	}, 4000);
	
});
</script>
