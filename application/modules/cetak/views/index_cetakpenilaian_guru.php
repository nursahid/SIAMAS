<!--panel-->
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><i class="fa fa-print"></i> Cetak Data Nilai</h3>
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
		
			<div class="col-md-6">
					<div class="form-group">
						<label for="jenis_penilaian" class="col-sm-5 control-label">Jenis Penilaian <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
						  <?php                  
						   echo form_dropdown('jenis_penilaian',$dropdown_jenis, set_value('jenis_penilaian'),'class="form-control" id="jenis_penilaian"');
						  ?>
						</div>
					</div>
					<div class="form-group">
						<label for="kelas" class="col-sm-5 control-label">Kelas <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
						  <?php
						   echo form_dropdown('kelas',$dropdown_kelas, set_value('kelas'),'class="form-control" id="kelas"');
						  ?>
						</div>
					</div>
					<div class="form-group">
						<label for="mapel" class="col-sm-5 control-label">Mata Pelajaran <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
							<?php
							echo form_dropdown('mapel',$dropdown_mapel, set_value('mapel'),'class="form-control" id="mapel"');
							?>
						</div>
					</div>		
			</div>
			<div class="col-md-6">
					<div class="form-group">
						<label for="pengampu" class="col-sm-4 control-label">Pengampu Mapel <span class="required-input">*</span></label>
						<div class="col-sm-7">                                   
							<input type="hidden" name="pengampu" value="<?=$nippegawai;?>" />	<?=$namapegawai;?>					
						</div>
					</div>

					<div class="form-group">
						<label for="tanggal" class="col-sm-4 control-label">Tanggal <span class="required-input">*</span></label>
						<div class="col-sm-8">                                   
							<?=htmlDateSelector('tanggal')?>
						</div>
					</div>
					<!--<div class="form-group">
						<label for="cetak_ke" class="col-sm-4 control-label">Cetak Ke <span class="required-input">*</span></label>
						<div class="col-sm-8">
							<input type="radio" name="cetak_ke" id="cetak_ke" value="printer" checked /> Preview &nbsp;&nbsp;&nbsp;
							<input type="radio" name="cetak_ke" id="cetak_ke" value="excel" /> Excel &nbsp;&nbsp;&nbsp;
							<input type="radio" name="cetak_ke" id="cetak_ke" value="pdf" /> PDF
						</div>
					</div>-->
					<div class="form-group">
						<label for="mapel" class="col-sm-4 control-label"></label>
						<div class="col-sm-8">                                   
							<button type="submit" class="btn btn-sm btn-primary" name="post" id="btn_simpan">
								<i class="fa fa-desktop"></i>&nbsp; &nbsp;  Cetak Sekarang 
							</button> 
						</div>
					</div>		
			</div>

		<br/><br/>
		<div id="lihat"></div>
		<div id="tampil_kelas"></div>
		</form>
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
		if ($('#jenis_penilaian').val() == 0) { alertify.alert('Jenis Penilaian harap dipilih'); return false; }
		if ($('#kelas').val() == 0) { alertify.alert('Kelas harap dipilih'); return false; }
		if ($('select[name="mapel"]').val() == 0) { alertify.alert('Pilih Mata Pelajaran dulu'); return false; }
		//if ($('#cetak_ke').val() == '') { alertify.alert('Pilih Tujuan Mencetak'); return false; }

			//tombol ubah menjadi cetak_preview
			$('#j_action').val('cetak_preview');
			$('#formKlien').submit();
		
	});
	
	$('#formKlien').submit(function() {
		switch ($('#j_action').val()) {
			case 'cetak_preview' :
				dest = '<?=base_url()?>cetak/nilai/lists'; 
			break;
			case 'cetak_excel' :
				dest = '<?=base_url()?>cetak/nilai/lists2'; 
			break;
		}
		
		$.ajax({
			type	: 'POST',
			url		: dest,
			data	: $(this).serialize(),
			success	: function(data) {								
				if ($('#j_action').val() == 'cetak_preview') {					
					$('#tampil_kelas').html(data);
					//$('#lihat').html($('#kelas').val());
				}
			}
		});
		return false;
	});	


});
</script>