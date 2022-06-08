<div class="row">
	<div class="col-md-12">
	
	<form id="formKlien" name="formKlien" method="post" action="<?=site_url('cetak/mutasi/cetak')?>" role="form" class="form-horizontal" >
	<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
		
		<!--panel-->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-print"></i> Cetak Data Mutasi Siswa</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" id="mini-refresh"><i class='fa fa-refresh'></i></button>
				</div>
			</div>
			<div class="box-body">
			
				<div id="message" align="center"><?php echo $this->session->userdata('message'); ?></div>
				<br />
				
					<div class="form-group">
						<label for="id_dusun" class="col-sm-4 control-label">Pilih Status <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
							<select name="status" class="form-control input-sm required" id="status">
								<option value="" selected="selected">Pilih Status :</option>
								<option value="masuk">Mutasi Masuk</option>
								<option value="keluar">Mutasi Keluar</option>
							</select>
						 <?php echo form_error('status');?>
						</div>
					</div>
					<div class="form-group">
						<label for="cetak_ke" class="col-sm-4 control-label">Cetak Ke <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
							<select name="cetak_ke" class="form-control input-sm required" id="cetak_ke">
								<option value="" selected="selected">Pilih Tujuan Cetak :</option>
								<option value="printer">Preview</option>
								<option value="excel">Excel</option>
								<option value="pdf">PDF</option>
							</select>
						 <?php echo form_error('cetak_ke');?>
						</div>
					</div>
			</div>
			<div class="panel-footer" align="center">
				<button type="submit" class="btn btn-sm btn-primary" name="post">
                    <i class="fa fa-desktop"></i>&nbsp; &nbsp;  Cetak Sekarang 
                </button> 
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>
