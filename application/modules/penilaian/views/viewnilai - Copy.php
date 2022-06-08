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
				<td>Kelas </td>
				<td><?=form_dropdown('kelas', $kelas, '0', 'class="input-sm" id="kelas"');?></td>
			</tr>					
			<tr>
				<td>&nbsp;</td>
				<td class="table-common-links"><a class="btn btn-sm btn-danger" href='javascript: return void(0)' id='btn_simpan'>Pilih</a></td>		
			</tr>
		</table>
		<br/><br/>
		<div id="tampil_kelas"></div>
		</form>
		
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$("#self_loading").ajaxStart(function(){
		$(this).show();
	});
	$("#self_loading").ajaxStop(function(){
		$(this).hide();
	});
	
	$('#btn_simpan').click(function(){			
		$('#j_action').val('add_param');
		$('#formKlien').submit();
	});
	
	$('#formKlien').submit(function() {
		$.ajax({
			type	: 'POST',
			url		: '<?=base_url()?>penilaian/lists',
			data	: $(this).serialize(),
			success	: function(data) {				
				$('#tampil_kelas').html(data);
			}
		});
		return false;
	});	

});
</script>