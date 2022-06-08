<div class="row">
	<div class="col-md-12">
		<?php echo form_open(site_url('manajemen/pengguna/simpanpegawai'),'role="form" class="form-horizontal" id="form_cetak" parsley-validate'); ?>
		<div class="panel panel-primary">
			<div class="panel-heading"><i class="fa fa-envelope"></i> Cek Koneksitas Data Pegawai</div>
				<div class="panel-body"> 
					<div class="col-md-12">
						<div class="row">

						</div>
					</div>
					<div class="message3"><?php echo $this->session->userdata('message3'); ?> </div>
					<div class="form-group">
						<label for="kuis" class="col-sm-4 control-label">ID User Saat ini <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
							<input type="text" name="id_user" value="<?=$id_user;?>" class="form-control input-sm required" readonly> 
						</div>
					</div>
					<div class="form-group">
						<label for="id_pegawai" class="col-sm-4 control-label">Pilih Pegawai <span class="required-input">*</span></label>
						<div class="col-sm-6"> 
						  <?php
						   echo form_dropdown('id_pegawai', $pegawais, set_value('id_pegawai'),'class="form-control input-sm required" id="id_pegawai"');             
						  ?>
						 <?php echo form_error('id_pegawai');?>
						</div>
					</div>

				</div>
				<div class="panel-footer" align="center">
					<input type="hidden" name="id_guru" value="<?=$id_guru;?>" />
					<button type="submit" class="btn btn-sm btn-danger" name="post">
                        <i class="fa fa-desktop"></i>&nbsp; &nbsp;  Simpan Sekarang 
                    </button>  
				</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">

</script>