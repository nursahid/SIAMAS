<!--panel-->
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><i class="fa fa-list"></i> Rekap SPP</h3>
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
				<td>Periode </td>
				<td><?=form_dropdown('bulan', $bln, date('m'))?>&nbsp;&nbsp;&nbsp;<?=htmlYearSelector('tahun')?></td>
			</tr>
			<tr>
				<td>NIS </td>
				<td><input type="text" name="nis" id="nis" size="20" value="" />&nbsp;&nbsp;[opsional]</td>
			</tr>
			<tr>
				<td>Kelas </td>
				<td><?=form_dropdown('kelas', $kelas, '0', 'id="kelas"')?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class="table-common-links"><a class="btn btn-sm btn-danger" href='javascript: return void(0)' id='btn_simpan'>Pilih</a></td>		
			</tr>
		</table>
		<br/><br/>
		<div id="render_chart"></div>
		</form>
		
	</div>
</div>

<script>
$(function(){
	$("#self_loading").ajaxStart(function(){
		$(this).show();
	});
	$("#self_loading").ajaxStop(function(){
		$(this).hide();
	});
	
	$('#btn_simpan').click(function(){		
		if ($('#popupDatepicker').val() == '') { alert('Periode awal harap diisi'); return false;}
		if ($('#akhirDatepicker').val() == '') { alert('Periode akhir harap diisi'); return false;}
		//if ($('#akhirDatepicker').val() > $('#popupDatepicker').val()) { alert('Periode awal harus lebih kecil dari periode akhir'); return false;}
		if ($('#kelas').val() == 0) { alert('kelas harap dipilih'); return false;}
		if ($('#nis').val() == '') $('#nis').val('0');
		$('#j_action').val('add_rekap');
		$('#formKlien').submit();		
	}); 
	
	$('#popupDatepicker').datepick({dateFormat: 'yyyy-mm-dd'});
	$('#akhirDatepicker').datepick({dateFormat: 'yyyy-mm-dd'});
	
	$("#nis").autocomplete("<?=base_url()?>cetak/rekapspp/lookup", {
		width: 260,
		mustMatch: true,
		matchContains: true,
		selectFirst: false
	});
	
	$('#formKlien').submit(function() {
		$.ajax({
			type	: 'POST',
			url		: '<?=base_url()?>cetak/rekapspp/lists',
			data	: $(this).serialize(),
			success	: function(data) {					
				$('#render_chart').html(data);
			}
		});
		return false;
	});	 
});
</script>