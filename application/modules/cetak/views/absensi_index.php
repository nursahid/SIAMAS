<div class="row">
	<div class="col-md-12">
		<?php echo form_open(site_url('cetak/absensi/cetak'),'role="form" class="form-horizontal" id="form_cetak2" parsley-validate'); ?>
		<div class="panel panel-default">
			<div class="panel-heading"><i class="fa fa-print"></i> Cetak Data Absensi Siswa</div>
				<div class="panel-body"> 
					<div class="message2a"><?php echo $this->session->userdata('message2a'); ?> </div>
					<div class="form-group">
						<label for="date_from" class="col-sm-4 control-label">Dari </label>
						<div class="col-sm-8">
							<?=htmlDateSelector('date_from')?>
						</div>
					</div>
					<div class="form-group">
						<label for="date_from" class="col-sm-4 control-label">Sampai </label>
						<div class="col-sm-8">
							<?=htmlDateSelector('date_to')?>
						</div>
					</div>
					
					<div class="form-group">
						<label for="kelas1" class="col-sm-4 control-label">Kelas <span class="required-input">*</span> </label>
						<div class="col-sm-7">                                   
							<?=form_dropdown('kelas1', $kelas,  set_value('kelas1'), 'class="form-control input-sm required" id="kelas1"')?>
							<?php echo form_error('kelas1');?>
						</div>
					</div>
					<div class="form-group">
						<label for="idsiswa1" class="col-sm-4 control-label">Nama Siswa <span class="required-input">*</span><br />&nbsp;&nbsp;[opsional]</label>
						<div class="col-sm-7"> 
							<select name="idsiswa1" class="form-control input-sm required" id="idsiswa1">
								<option value="" selected="selected">-- Pilih Siswa --</option>
							</select>						
						 <?php echo form_error('idsiswa1');?>
						</div>
					</div>
					<div class="form-group">
						<label for="cetak_ke" class="col-sm-4 control-label">Cetak Ke <span class="required-input">*</span></label>
						<div class="col-sm-7">                                   
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
					<button type="submit" class="btn btn-sm btn-info" name="post">
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
       
	//Siswa auto
	$("#idsiswa2").autocomplete("<?=base_url()?>cetak/rekapspp/lookup", {
		width: 260,
		mustMatch: true,
		matchContains: true,
		selectFirst: false
	});
	
	$('select[name="kelas1"]').on('change', function() {
        var url = "<?php echo site_url('cetak/getSiswa');?>/"+$(this).val();
        $('#idsiswa1').load(url);
        return false;
    });
		
});

	//Date picker
	$(function() {
		$('.datepicker input').datepicker({
			format: "yyyy-mm-dd",
			clearBtn: true,
			language: "id"
		});
		$('.datepicker2 input').datepicker({
			format: "yyyy-mm-dd",
			clearBtn: true,
			language: "id"
		});		
	});
	
</script>

