<div class="row">
	<div class="col-md-12">
		
		<!--panel-->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-print"></i> Cetak Data Siswa</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" id="mini-refresh"><i class='fa fa-refresh'></i></button>
				</div>
			</div>
			<div class="box-body">
			
			<div id="message" align="center"><?php echo $this->session->userdata('message'); ?></div>
			<br />
				<form id="formKlien" name="formKlien" method="post" action="" role="form" class="form-horizontal" >
					<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
					<input type="hidden" id="j_action" name="j_action" value="">
					
					<input type="hidden" name="jenis_penilaian" id="jenis_penilaian" value="<?=$this->uri->segment(3);?>">

					<div class="form-group">
						<label for="status" class="col-sm-5 control-label">Status Siswa <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
							<select name="status" class="form-control input-sm required">
								<option value="" selected="selected">-- Pilih Status --</option>
								<option value="Aktif">Aktif</option>
								<option value="Alumni">Alumni</option>
							</select>
							<?php echo form_error('status');?>
						</div>
					</div>
					
					<div class="form-group">
						<label for="tampil" class="col-sm-5 control-label"></label>
						<div class="col-sm-6">                                   
							<button type="submit" class="btn btn-sm btn-primary" name="post" id="btn_simpan">
								<i class="fa fa-desktop"></i>&nbsp; &nbsp;  Tampilkan Sekarang 
							</button> 
						</div>
					</div>
					
				<br/><br/>
				<div id="tampil_kelas"></div>
				<?php echo form_close(); ?>	
		
			</div>
			<div class="panel-footer" align="center">
 
			</div>
		</div>
		
	</div>
</div>
<script type="text/javascript">
// baseURL variable
var baseURL= "<?php echo base_url();?>"; 

$(document).ready(function() {
	var dest;

	$("#self_loading").ajaxStart(function(){
		$(this).show();
	});
	$("#self_loading").ajaxStop(function(){
		$(this).hide();
	});

	$('#btn_simpan').click(function(){		
		//if ($('#kelas').val() == 0) { alertify.alert('Kelas harap dipilih'); return false; }
		if ($('select[name="status"]').val() == "") { alertify.alert('Pilih Status Siswa dulu'); return false; }
		//if ($('input[name="pengampu"]').val() == "") { alertify.alert('Belum ada pengampu mapel'); return false; }

		$('#j_action').val('add_kelas');
		$('#formKlien').submit();
	});	
	
	$('#formKlien').submit(function() {
		switch ($('#j_action').val()) {
			case 'add_kelas' :
				var status = $('select[name="status"]').val();
				dest = '<?=base_url()?>cetak/siswa/lists/'+status; 
			break;
		}
		
		$.ajax({
			type	: 'POST',
			url		: dest,
			data	: $(this).serialize(),
			success	: function(data) {								
				if ($('#j_action').val() == 'add_kelas') {					
					$('#tampil_kelas').html(data);
				}
			}
		});
		return false;
	});	



});
</script>