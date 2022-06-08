<!--panel-->
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><i class="fa fa-plus"></i> Tambah Penilaian</h3>
		<div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			<button class="btn btn-box-tool" id="mini-refresh"><i class='fa fa-refresh'></i></button>
		</div>
	</div>
    <div class="box-body">
		<div class="alert alert-danger alert-dismissible col-md-12">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			Pastikan seluruh siswa sudah memiliki NIS. Jika ada yang kosong, silakan hubungi ADMIN untuk digenerate melalui Modul Kesiswaan
		</div>
			<div id="message" align="center"><?php echo $this->session->userdata('message'); ?></div>
			<br />
		
		<form id="formKlien" name="formKlien" method="post" action="" role="form" class="form-horizontal" >
		<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
		<input type="hidden" id="j_action" name="j_action" value="">
					
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
					<div class="form-group">
						<label for="pengampu" class="col-sm-5 control-label">Pengampu Mapel <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
							<input type="hidden" name="pengampu" value="<?=$nippegawai;?>" />	<?=$namapegawai;?>	| <?=$id_guru;?>				
							<?php echo form_error('pengampu');?>
						</div>
					</div>
					<div class="form-group">
						<label for="deskripsi" class="col-sm-5 control-label">Deskripsi <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
							<textarea name="deskripsi" id="deskripsi" cols="50" rows="2" class="form-control"></textarea>
							<?php echo form_error('deskripsi');?>
						</div>
					</div>
					<div class="form-group">
						<label for="tanggal" class="col-sm-5 control-label">Tanggal <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
							<?=htmlDateSelector('tanggal')?>
							<?php echo form_error('tanggal');?>
						</div>
					</div>
					
					<div class="form-group">
						<label for="mapel" class="col-sm-5 control-label"></label>
						<div class="col-sm-6">                                   
							<button type="submit" class="btn btn-sm btn-primary" name="post" id="btn_simpan">
								<i class="fa fa-desktop"></i>&nbsp; &nbsp;  Tampilkan Sekarang 
							</button> 
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
		if ($('select[name="mapel"]').val() == "") { alertify.alert('Pilih Mata Pelajaran dulu'); return false; }
		if ($('#deskripsi').val() == '') { alertify.alert('Isi deskripsi dulu'); return false; }

		$('#j_action').val('add_kelas');
		$('#formKlien').submit();
	});	
	
	$('#formKlien').submit(function() {
		switch ($('#j_action').val()) {
			case 'add_kelas' :
				dest = '<?=base_url()?>penilaian/lists'; 
			break;
			
			case 'add_nilai' :
				dest = '<?=base_url()?>penilaian/add'; 
			break;
		}
		
		$.ajax({
			type	: 'POST',
			url		: dest,
			data	: $(this).serialize(),
			success	: function(data) {								
				if ($('#j_action').val() == 'add_kelas') {					
					$('#tampil_kelas').html(data);
					//$('#lihat').html($('#kelas').val());
				}
				else if ($('#j_action').val() == 'add_nilai') {					
					alertify.alert('Data sukses disimpan');
					//then redirect
					var jenispenilaian = $('#jenis_penilaian').val();
					var kelas = $('#kelas').val();
					window.location.href="<?=base_url('penilaian/manage/');?>"+jenispenilaian+"/"+kelas+"";
				}
				
			}
		});
		return false;
	});	


});
</script>