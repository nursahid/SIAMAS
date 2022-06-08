<!--panel-->
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><i class="fa fa-list"></i> Daftar Absensi</h3>
		<div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			<button class="btn btn-box-tool" id="mini-refresh"><i class='fa fa-refresh'></i></button>
		</div>
	</div>
    <div class="box-body">
		<div class="alert alert-danger alert-dismissible col-md-12">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			Pastikan seluruh siswa sudah memiliki NIS. Jika ada yang kosong, harus digenerate melalui Modul Kesiswaan >> <a href="<?=base_url('kesiswaan/siswa');?>">KLIK DISINI</a>
		</div>
		<div id="message" align="center"><?php echo $this->session->userdata('message'); ?></div>
		<br />
		<form id="formKlien" name="formKlien" method="post" action="" class="form-horizontal">
			<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
			<input type="hidden" id="j_action" name="j_action" value="">
			<div class="row">
				<div class="form-group">
					<label for="kelas" class="col-sm-2 control-label">Tanggal </label>
					<div class="col-sm-4">                                   
						<?=htmlDateSelector('tanggal')?>
					</div>

					<label for="kelas" class="col-sm-2 control-label">Kelas <span class="required-input">*</span></label>
					<div class="col-sm-2">
						<?=form_dropdown('kelas', $kelas, '0', 'class="form-control" style="width:160px;" id="kelas"');?>
					</div>

					<label class="col-sm-1"></label>
					<div class="col-sm-2">
						<a class="btn btn-sm btn-primary" href='javascript: return void(0)' id='btn_simpan'><i class="fa fa-desktop"></i>&nbsp; Tampilkan Data</a>
					</div>
				</div>
			</div>
		<br/>
		<div id="render_chart"></div>
		</form>
		<br/>
		<br/>
	</div>
</div>
<script type="text/javascript">
$(function(){
	var dest;
	
	$("#self_loading").ajaxStart(function(){
		$(this).show();
	});
	$("#self_loading").ajaxStop(function(){
		$(this).hide();
	});
	
	$('#btn_simpan').click(function(){		
		if ($('#kelas').val() == 0) { alertify.alert('Kelas harap dipilih'); return false; }
		$('#j_action').val('add_kelas');
		$('#formKlien').submit();
	});	
	
	$('#formKlien').submit(function() {
		switch ($('#j_action').val()) {
			case 'add_kelas' :
				dest = '<?=base_url()?>absensi/lists'; 
			break;
			
			case 'add_absen' :
				dest = '<?=base_url()?>absensi/add'; 
			break;
		}
		
		$.ajax({
			type	: 'POST',
			url		: dest,
			data	: $(this).serialize(),
			success	: function(data) {								
				if ($('#j_action').val() == 'add_kelas') {					
					$('#render_chart').html(data);
				}
				else if ($('#j_action').val() == 'add_absen') {					
					alertify.alert('Data sukses disimpan');
					window.location.reload(true);
				}
				
			}
		});
		return false;
	});	
});
</script>