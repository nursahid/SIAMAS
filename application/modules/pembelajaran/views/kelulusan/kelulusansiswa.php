
<!--panel-->
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><i class="fa fa-table"></i> KELULUSAN SISWA - Tahun Pelajaran : <strong><?=$this->sistem_model->get_nama_tapel('Y');?></strong></h3>
		<div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			<button class="btn btn-box-tool" id="mini-refresh"><i class='fa fa-refresh'></i></button>
		</div>
	</div>
    <div class="box-body">

		<form id="frm_format" name="frm_format" method="post" action="" role="form" class="form-horizontal">
		<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
		<input type="hidden" id="j_action" name="j_action" value="">
		<table cellpadding="0" id="tbl_format"cellspacing="0" border="0" width="100%" class="standard_table_v4">	
			<tbody>
				<tr>
					<td align="center">
					
						<div class="form-group">
							<label for="tahunajaranawal" class="col-sm-4 control-label">Tahun Ajaran <span class="required-input">*</span></label>
							<div class="col-sm-6"> 
								<input type="text" name="tahunajaran" value="<?=@$thnsekarang->tahun?>" id="tahunajaran" class="form-control" readonly="false" />
								<input type="hidden" name="ta" value="<?=@$thnsekarang->id?>" id="ta" />
							</div>
						</div>
						<div class="form-group">
							<label for="kelas" class="col-sm-4 control-label">Kelas Awal <span class="required-input">*</span></label>
							<div class="col-sm-6"> 
								<?php
								echo form_dropdown('kelas', $dropdownkelas, '0','class="form-control input-sm required" id="awal"');             
								?>
							</div>
						</div>
						<div class="form-group">
							<label for="db_idkelas" class="col-sm-4 control-label">Data Siswa <span class="required-input">*</span></label>
							<div class="col-sm-6"> 
								<div id="renderkelasawal">
									<select id="firstList" multiple="multiple" class="form-control" style="height:250px;" >
									</select>
								</div>
							</div>
						</div>
									
					</td>
					<td align="center">
						<input type="button" name="to2" id="to2" class="btn btn-danger btn-xs" title='assign' title='assign' style="font-size: 18px;" value="&nbsp;&nbsp;&nbsp;&#8608&nbsp;&nbsp;" /><br/><br/>
					</td>
					<td align="center">
					
						<div class="form-group">
							<label for="tahunajaranawal" class="col-sm-4 control-label">Tahun Lulus <span class="required-input">*</span></label>
							<div class="col-sm-6"> 
								<input type="text" name="tahunkelulusan" value="<?=date('Y');?>" id="ti"  />
								<input type="hidden" name="to" value="<?=@$thnsekarang->id?>" id="tahunkelulusan" />
							</div>
						</div>
						<div class="form-group">
							<label for="db_idkelas" class="col-sm-4 control-label">Data Siswa <span class="required-input">*</span></label>
							<div class="col-sm-6"> 
								<div id="renderkelasakhir">
									<select name="secondList[]" id="secondList" multiple="multiple" class="form-control" style="height:250px;" >
									</select>
								</div>
							</div>
						</div>

					</td>						
				</tr>
				<tr>
					<td colspan="1"></td>
					<td class="table-common-links"><br/>
						<input type="hidden" name="id_siswa" id="nis" value="">
						<a class="btn btn-sm btn-primary" href="javascript: return void(0)" id="add"><i class="fa fa-plus"></i>   LULUSKAN SEKARANG </a>
					</td>
				</tr>
			</tbody>
		</table>
					
		</form>
					

		
	</div>
</div>
<script type="text/javascript">
$(function(){
	$("#self_loading").ajaxStart(function(){
		$(this).show();
	});
	$("#self_loading").ajaxStop(function(){
		$(this).hide();
	});
	
	$('#add').click(function(){			
		//if ($('#akhir').val() == 0) {
		//	alert('Kelas Baru Harap dipilih');
		//	return false;
		//}
		
		$('#secondList').each(function(){
			$('#secondList option').attr("selected","selected");
		});
		$('#j_action').val('add_param');
		$('#frm_format').submit();
	});
	
	//$('#noadd').click(function(){			
	//	if ($('#awal').val() == 0) {
	//		alert('Kelas Harap dipilih');
	//		return false;
	//	}
		
	//	$('#firstList').each(function(){
	//		$('#firstList option').attr("selected","selected");
	//	});
	//	$('#j_action').val('add_tinggal');
	//	$('#frm_format').submit();
	//});
	
	$('#awal').change(function() {		
		var selectValues = $("#awal").val();
			//if (selectValues == 0){				
			//	var msg = '<select name="kelasakhir" disabled><option value="Pilih Kelas">-- Pilih Kelas --</option></select>';
			//	$('#mapel').html(msg);
			//}else{				
			//	var mp = {mp:$("#awal").val()};
				
			//	$('#mp_id').attr("disabled",true);
			//	$.ajax({
			//			type: "POST",
			//			url : '<?=base_url()?>pembelajaran/kelulusan/pilihkelas',
			//			data: mp,
			//			success: function(msg){							
			//				$('#mapel').html(msg);
			//			}
			//	});
			//}
		$('#renderkelasawal').load('<?=base_url()?>pembelajaran/kelulusan/loadkelas/' + $('#awal').val() + '/' + $('#ta').val());
	});	
	
	//$('#mp_id').change(function() {		
	//	var selectValues = $("#mp_id").val();
	//	if (selectValues == 0){				
	//		alert('Silakan pilih kelas terlebih dahulu');
	//		return false;
	//	}else{				
	//		$('#renderkelasakhir').load('<?=base_url()?>pembelajaran/kelulusan/loadkelasakhir/' + $('#tahunkelulusan').val());
	//	}		
	//});	
	
	//$('#tahunkelulusan').change(function() {
	//	if (($('#tahunkelulusan').val() == $('#ta').val()) || ($('#tahunkelulusan').val() < $('#ta').val())) {
	//		alert('Harap pilih tahun ajaran yang aktif');
	//		return false;
	//	}
	//});
	
	//$('#akhir').change(function() {
	//	if (($('#akhir').val() == $('#awal').val()) || ($('#akhir').val() < $('#awal').val())) {
	//		alert('Harap pilih kelas yang lebih tinggi');
	//		return false;
	//	}
	//});
	
	$('#to2').click(function() {
		return !$('#firstList option:selected').remove().appendTo('#secondList'); 		
	});

	$('#to1').click(function() {
		return !$('#secondList option:selected').remove().appendTo('#firstList'); 
	});
	
});
</script>