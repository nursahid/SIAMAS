<div class="row">

  <div class="col-md-12">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#settings" data-toggle="tab">Data Siswa</a></li>
      </ul>
      <div class="tab-content">
        <div class="active tab-pane" id="settings">
          <form class="form-horizontal" action="<?php echo base_url('personil/siswa/tambahdata') ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
			<input type="hidden" name="id_dojo" value="<?=$id_dojo;?>">
			<div class="form-group">
              <label for="inputUsername" class="col-sm-3 control-label">Username</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="username" placeholder="Username" name="username" readonly>
              </div>
            </div>
            <div class="form-group">
              <label for="inputNama" class="col-sm-3 control-label">Nama Lengkap</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="Nama Lengkap" name="nama_lengkap" />
              </div>
            </div>

            <div class="form-group">
              <label for="tempat_lahir" class="col-sm-3 control-label">Tempat, Tgl. Lahir</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" placeholder="Tempat Lahir" name="tmp_lahir"/>
              </div>

              <div class="col-sm-4">
					<div class="input-group date" data-provide="datepicker">
						<input type="text" class="form-control" name="tgl_lahir" id="tgl_lahir" data-date-format="yyyy-mm-dd" data-date-end-date="0d" />
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-th"></span>
						</div>
					</div>				
			  </div>
            </div>
            <div class="form-group">
              <label for="email" class="col-sm-3 control-label">E-Mail</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="E-Mail" name="email" />
              </div>
            </div>
			
            <div class="form-group">
              <label for="email" class="col-sm-3 control-label">Kelamin</label>
              <div class="col-sm-8">
					<input type="radio" name="kelamin" value="Laki-laki" checked/> Laki-laki &nbsp;
					<input type="radio" name="kelamin" value="Perempuan" /> Perempuan
              </div>
            </div>			
					 
            <div class="form-group">
              <label for="telepon" class="col-sm-3 control-label">Telepon</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="No. Telepon" name="telepon" />
              </div>
            </div>
            <div class="form-group">
              <label for="alamat" class="col-sm-3 control-label">Alamat</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="Alamat Lengkap" name="alamat" />
              </div>
            </div>			
			
            <div class="form-group">
              <label for="inputFoto" class="col-sm-3 control-label">Foto</label>
              <div class="col-sm-8">
				<img class="thumbnail thumbnail-responsive col-md-4" src="<?php echo base_url('assets/uploads/profil/default.jpg'); ?>" alt="User profile picture">
				
                <input type="file" class="form-control" placeholder="Foto" name="foto">
              </div>
            </div>

            <div class="form-group">
              <label for="inputFoto" class="col-sm-3 control-label">Posisi</label>
              <div class="col-sm-8">
					<input type="radio" name="posisi" value="siswa" checked/> Siswa &nbsp;
					<input type="radio" name="posisi" value="asisten" /> Asisten &nbsp;
					<input type="radio" name="posisi" value="pelatih" /> Pelatih
              </div>
            </div>
            
            <div class="form-group">
              <label for="email" class="col-sm-3 control-label">Status</label>
              <div class="col-sm-8">
					<input type="radio" name="is_active" value="1" checked/> Aktif &nbsp;
					<input type="radio" name="is_active" value="0" /> Tdk. Aktif
              </div>
            </div>	
            <div class="form-group">
              <label for="tgl_daftar" class="col-sm-3 control-label">Tgl. Daftar</label>
              <div class="col-sm-8">
                
					<div class="input-group date" data-provide="datepicker">
						<input type="text" class="form-control" name="tgl_daftar" id="tgl_daftar" data-date-format="yyyy-mm-dd" data-date-end-date="0d" />
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-th"></span>
						</div>
					</div>					
				
              </div>
            </div>
			
			<br />
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-8">
                <button type="submit" class="btn btn-lg btn-danger">Tambah Data</button>
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
