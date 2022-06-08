<h3><?php echo $q->nama_lengkap; ?> <small>Edit Data Siswa </small></h3>
<div class="row">

  <div class="col-md-12">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#settings" data-toggle="tab">Settings</a></li>
        <li><a href="#password" data-toggle="tab">Ubah Password</a></li>
      </ul>
      <div class="tab-content">
        <div class="active tab-pane" id="settings">
          <form class="form-horizontal" action="<?php echo base_url('kesiswaan/siswa/update') ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
			<input type="hidden" name="id_siswa" value="<?=$q->id;?>">
			<div class="form-group">
				<label for="inputUsername" class="col-sm-3 control-label">NIS</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="nis" placeholder="NIS" name="nis" value="<?php echo $q->nis; ?>" readonly>
				</div>
            </div>
            <div class="form-group">
				<label for="inputNama" class="col-sm-3 control-label">Nama Lengkap</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" placeholder="Nama Lengkap" name="nama" value="<?php echo $q->nama; ?>">
				</div>
            </div>

            <div class="form-group">
				<label for="tempat_lahir" class="col-sm-3 control-label">Tempat, Tgl. Lahir</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" placeholder="Tempat Lahir" name="tempat_lahir" value="<?php echo $q->tempat_lahir; ?>">
				</div>
				<div class="col-sm-4">
					<div class="input-group date" data-provide="datepicker">
						<input type="text" class="form-control" name="tgl_lahir" id="tgl_lahir" data-date-format="yyyy-mm-dd" data-date-end-date="0d" value='<?php echo date("m/d/Y", strtotime($q->tgl_lahir)); ?>'>
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-th"></span>
						</div>
					</div>				
				</div>
            </div>
            <div class="form-group">
				<label for="email" class="col-sm-3 control-label">E-Mail</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" placeholder="E-Mail" name="email" value="<?php echo $q->email; ?>">
				</div>
            </div>
			
            <div class="form-group">
				<label for="email" class="col-sm-3 control-label">Kelamin</label>
				<div class="col-sm-8">
				<?php
				//echo $q->kelamin;
				if($q->kelamin == 'L') {
					echo '<input type="radio" name="kelamin" value="L" checked/> Laki-laki &nbsp;
						  <input type="radio" name="kelamin" value="P" /> Perempuan';
				} elseif($q->kelamin == 'P') {
					echo '<input type="radio" name="kelamin" value="L"/> Laki-Laki &nbsp;
						  <input type="radio" name="kelamin" value="P" checked/> Perempuan';
				}
				?>
								
				</div>
            </div>			
					 
            <div class="form-group">
				<label for="telepon" class="col-sm-3 control-label">Telepon</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" placeholder="No. Telepon" name="telepon" value="<?php echo $q->telepon; ?>">
				</div>
            </div>
            <div class="form-group">
				<label for="alamat" class="col-sm-3 control-label">Alamat</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" placeholder="Alamat Lengkap" name="alamat" value="<?php echo $q->alamat; ?>">
				</div>
            </div>			
			
            <div class="form-group">
				<label for="inputFoto" class="col-sm-3 control-label">Foto</label>
				<div class="col-sm-8">
					<img class="thumbnail thumbnail-responsive col-md-4" src="<?php echo base_url('assets/uploads/siswa/').$q->foto; ?>" alt="User profile picture">				
					<input type="file" class="form-control" placeholder="Foto" name="foto">
				</div>
            </div>
            
            <div class="form-group">
				<label for="email" class="col-sm-3 control-label">Status</label>
				<div class="col-sm-8">
				<?php
				if($q->status == 'Aktif') {
					echo '<input type="radio" name="is_active" value="Aktif" checked/> Aktif &nbsp;
						  <input type="radio" name="is_active" value="Alumni" /> Alumni &nbsp;
						  <input type="radio" name="is_active" value="Mutasi" /> Mutasi';
				} elseif($q->status == 'Alumni') {
					echo '<input type="radio" name="is_active" value="Aktif"/> Aktif &nbsp;
						  <input type="radio" name="is_active" value="Alumni" checked/> Alumni &nbsp;
						  <input type="radio" name="is_active" value="Alumni" /> Alumni';
				} elseif($q->status == 'Mutasi') {
					echo '<input type="radio" name="is_active" value="Aktif"/> Aktif &nbsp;
						  <input type="radio" name="is_active" value="Mutasi" checked/> Mutasi';
				}
				?>								
				</div>
            </div>	
            <div class="form-group">
				<label for="tgl_daftar" class="col-sm-3 control-label">Tgl. Daftar</label>
				<div class="col-sm-8">                
					<div class="input-group date" data-provide="datepicker">
						<input type="text" class="form-control" name="tgl_daftar" id="tgl_daftar" data-date-format="yyyy-mm-dd" data-date-end-date="0d" value='<?php echo date("m/d/Y", strtotime($q->tgl_daftar)); ?>' />
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-th"></span>
						</div>
					</div>
				</div>
            </div>
			
			<br />
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-8">
                <button type="submit" class="btn btn-lg btn-danger">Simpan Data</button>
              </div>
            </div>
          </form>
        </div>
        <div class="tab-pane" id="password">
          <form class="form-horizontal" action="<?php echo base_url('kesiswaan/siswa/ubah_password') ?>" method="POST">
			<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
			<input type="hidden" name="id_siswa" value="<?=$q->id;?>">
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
