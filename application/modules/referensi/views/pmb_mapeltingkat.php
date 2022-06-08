<div class="row">
	<div class="col-md-12">
		
		<!--panel-->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-list"></i> Daftar Mata Pelajaran tiap Tingkatan</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" id="mini-refresh"><i class='fa fa-refresh'></i></button>
				</div>
			</div>
			<div class="box-body">
				<form id="formKlien" name="formKlien" method="post" action="" role="form" class="form-horizontal" >
					<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
					<input type="hidden" id="j_action" name="j_action" value="">
					<input type="hidden" id="id_pegawai" name="id_pegawai" value="">
					<div class="form-group">
						<label for="tingkat" class="col-sm-4 control-label">Pilih Tingkat<span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
						  <?php
						   echo form_dropdown('tingkat',$dropdown_tingkat, '','class="form-control input-sm required" id="tingkat"');
						  ?>
						</div>
					</div>
					<div class="form-group">
						<label for="jurusan" class="col-sm-4 control-label">Pilih Jurusan<span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
						  <?php
						   echo form_dropdown('jurusan',$dropdown_jurusan, '','class="form-control input-sm required" id="jurusan"');
						  ?>
						</div>
					</div>
					
					<div class="form-group">
						<label for="" class="col-sm-4 control-label"></label>
						<div class="col-sm-6">                                   
							<button type="submit" class="btn btn-sm btn-primary" name="post" id="btn_simpan">
								<i class="fa fa-desktop"></i>&nbsp; &nbsp;  Tampilkan Sekarang 
							</button> 
						</div>
					</div>
					
				<br/><br/>
				<div id="tampil_mapel"></div>
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

	$("#self_loading").ajaxStart(function(){
		$(this).show();
	});
	$("#self_loading").ajaxStop(function(){
		$(this).hide();
	});

	$('#btn_simpan').click(function(){		
		if ($('#tingkat').val() == 0) { alertify.alert('Tingkat harap dipilih'); return false; }
		if ($('#jurusan').val() == 0) { alertify.alert('Jurusan harap dipilih'); return false; }
		$('#j_action').val('add_kelas');
		$('#formKlien').submit();
	});	
	
	$('#formKlien').submit(function() {
		var tingkatID = $('#tingkat').val();
		var jurusanID = $('#jurusan').val();
		$.ajax({
			type	: 'POST',
			url		: '<?=base_url()?>referensi/mapeltingkat/datamapel/',
			data	: $(this).serialize(),
			success	: function(data) {								
				$('#tampil_mapel').html(data);				
			}
		});
		return false;
	});	



});
</script>