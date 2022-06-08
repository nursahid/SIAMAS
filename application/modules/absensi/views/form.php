<!--panel-->
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><i class="fa fa-list"></i> Data Absensi</h3>
		<div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			<button class="btn btn-box-tool" id="mini-refresh"><i class='fa fa-refresh'></i></button>
		</div>
	</div>
    <div class="box-body">
		<h3>Tambah Absensi</h3>
		<br/>
		<?=validation_errors();?>

		<form id="formKlien" name="formKlien" method="post" action="" autocomplete="off">
		<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
		<input type="hidden" id="j_action" name="j_action" value="">
		<table width="100%" class="table-form">	
			<tr>
				<td>Tanggal </td>
				<td><input type="text" name="db_tanggal" id="popupDatepicker" size="20" value="" /></td>
			</tr>		  
			<tr>
				<td>NIS </td>
				<td><input type="text" name="db_NIS" id="nis" size="20" value="" /></td>
			</tr>		  
			<tr>
				<td>Nama </td>
				<td><input type="text" id="nama" readonly="true" size="40" /></div></td>
			</tr>
			<tr>
				<td>Kelas </td>        
				<td><input type="text" id="kelas" readonly="true" size="40" /></div></td>
			</tr>
			<tr>
				<td>No. Hp Orang Tua </td>        
				<td><input type="text" id="telp" readonly="true" size="40" /></div></td>
			</tr>
			<tr>
				<td>Alasan Absen </td>        
				<td><input type="radio" id="radio" name="db_absen" value="sakit" checked="checked" />Sakit &nbsp;&nbsp;&nbsp; <input type="radio" id="radio" name="db_absen" value="izin" />Izin</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class="table-common-links"><input type="hidden" name="db_tahun" value="<?=@$ta->id?>" /><input type="hidden" name="db_smt" value="<?=@$sem->id?>" />
				<?php				
					echo "<a class='btn btn-sm btn-info' href='javascript: return void(0)' id='btn_simpan'>Simpan Data</a>";
					echo '<a class="btn btn-sm btn-daner" href="javascript: return void(0)" id="btn_reset">Reset Data</a> </td>';
				?>		
			  </tr>
		</table>
	
	</div>
</div>

<script type="text/javascript">
$(this).ready( function() {
	$("#nis").autocomplete("<?=base_url()?>absensi/lookup", {
		width: 260,
		mustMatch: true,
		matchContains: true,
		selectFirst: false
	});
	
	$("#nis").result(function(event, data, formatted) {
		if (data) {			
			$('#nis').val(data[0]);
			$('#result').html(data[1]);
			$('#nama').val(data[1]);
			$('#kelas').val(data[2]);
			$('#telp').val(data[3]);
		}
	});
	
	$('#btn_simpan').click(function(){	
		$('#j_action').val('add_param');
		$('#formKlien').submit();
	});
	
	$('#btn_reset').click(function(){
		resetForm();		
	});
	
	$('#popupDatepicker').datepick({dateFormat: 'yyyy-mm-dd'});
	$('#inlineDatepicker').datepick({onSelect: showDate});
});

function resetForm(){
	$('form :input').val("");		
}

</script>