<div class="row">
	<div class="col-md-6">
		<?php echo form_open(site_url('cetak/pembayaran/cetak'),'role="form" class="form-horizontal" id="form_cetak2" parsley-validate'); ?>
		<div class="panel panel-default">
			<div class="panel-heading"><i class="fa fa-print"></i> Cetak Data Pembayaran</div>
				<div class="panel-body">
					<div class="message4"><?php echo $this->session->userdata('message4'); ?> </div>
					
					<div class="form-group">
						<label for="bulan" class="col-sm-5 control-label">Bulan <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
							<?=form_dropdown('bulan', $bln, date('m'),'class="input-sm required" id="bulan"')?>&nbsp;&nbsp;&nbsp;<?=htmlYearSelector('tahun')?>
						 <?php echo form_error('bulan');?>
						</div>
					</div>
					<div class="form-group">
						<label for="pembayaran" class="col-sm-5 control-label">Jenis Pembayaran <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
						  <?php                  
						   echo form_dropdown(
								   'pembayaran',
								   $dropdown_pembayaran,  
								   set_value('pembayaran'),
								   'class="form-control input-sm required" id="pembayaran"'
								   );             
						  ?>
						 <?php echo form_error('pembayaran');?>
						</div>
					</div>
					<div class="form-group">
						<label for="kelas2" class="col-sm-5 control-label">Kelas <span class="required-input">*</span> </label>
						<div class="col-sm-6">                                   
							<?=form_dropdown('kelas2', $kelas, '0', 'class="form-control input-sm required" id="kelas2"')?>
							<?php echo form_error('kelas2');?>
						</div>
					</div>
					<div class="form-group">
						<label for="idsiswa" class="col-sm-5 control-label">Nama Siswa <span class="required-input">*</span>&nbsp;&nbsp;[opsional]</label>
						<div class="col-sm-6"> 
							<select name="idsiswa" class="form-control input-sm required" id="idsiswa">
								<option value="" selected="selected">-- Pilih Siswa --</option>
							</select>						
						 <?php echo form_error('idsiswa');?>
						</div>
					</div>
					<div class="form-group">
						<label for="cetak_ke" class="col-sm-5 control-label">Cetak Ke <span class="required-input">*</span></label>
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
					<button type="submit" class="btn btn-sm btn-info" name="post" id="btn_simpan">
                        <i class="fa fa-desktop"></i>&nbsp; &nbsp;  Cetak Sekarang 
                    </button>  
				</div>
		</div>
		<?php echo form_close(); ?>
	</div>	
</div>

<script type="text/javascript">
// baseURL variable
var baseURL= "<?php echo base_url();?>"; 

$(document).ready(function() {
	
	$('#btn_simpan').click(function(){		
		if ($('#pembayaran').val() == 0) { alertify.alert('Jenis Pembayaran harap dipilih'); return false; }
		if ($('#kelas2').val() == 0) { alertify.alert('Kelas harap dipilih'); return false; }
		if ($('#cetak_ke').val() == '') { alertify.alert('Tujuan Cetak harap dipilih'); return false; }
		
	});	

	
	$('select[name="kelas2"]').on('change', function() {
        var url = "<?php echo site_url('cetak/getSiswa');?>/"+$(this).val();
        $('#idsiswa').load(url);
        return false;
    });
		
});
</script>