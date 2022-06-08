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

		<form id="formKlien" name="formKlien" method="post" action="">
		<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
		<input type="hidden" id="j_action" name="j_action" value="">
		<table width="100%" class="table table-responsive">	
			<tr>
				<td>Tanggal </td>
				<td><?=htmlDateSelector('tanggal')?></td>
			</tr>
			<tr>
				<td>Kelas </td>
				<td><?=form_dropdown('kelas', $kelas, '0', 'class="form-control" style="width:260px;" id="kelas"');?></td>
			</tr>					
			<tr>
				<td>&nbsp;</td>
				<td class="table-common-links"><a class="btn btn-sm btn-primary" href='javascript: return void(0)' id='btn_simpan'><i class="fa fa-desktop"></i>&nbsp; Tampilkan Data</td>		
			</tr>
		</table>
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
				dest = '<?=base_url()?>absensi/datalists'; 
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
			}
		});
		return false;
	});	
});
</script>