
<!--panel-->
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><i class="fa fa-table"></i> Pembagian Kelas Tahun Pelajaran : <strong><?=$this->sistem_model->get_nama_tapel('Y');?></strong> </h3>
		<div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			<button class="btn btn-box-tool" id="mini-refresh"><i class='fa fa-refresh'></i></button>
		</div>
	</div>
    <div class="box-body">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">	
			<tr>		
				<td width="100%">

					<form id="frm_format" name="frm_format" method="post" action="" role="form" class="form-horizontal">
					<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
					<input type="hidden" id="j_action" name="j_action" value="">
					<table cellpadding="0" id="tbl_format"cellspacing="0" border="0" width="100%" class="standard_table_v4">
						<tbody>
							<tr>
								<td align="center" width="45%">
									<div class="form-group">
										<label for="db_idkelas" class="col-sm-3 control-label">Data Siswa <span class="required-input">*</span></label>
										<div class="col-sm-6"> 
											<div class="text-red">Silakan pilih siswa, lalu klik tombol berwarna merah untuk memindahkan siswa</div>
										</div>
									</div>
									<div class="form-group">
										<label for="db_idkelas" class="col-sm-3 control-label"></label>
										<div class="col-sm-6"> 
										<?php
										if ($result) {
											echo form_multiselect('firstList', $result, '', 'id="firstList" class="form-control" style="height:250px;"');
										}
										else {
										?>
											<select name="firstList" id="firstList" multiple="multiple" class="form-control" style="height:250px;" >
											</select>
										<?php
										}
										?>
										</div>
									</div>									
									
								</td>
								<td align="center" width="3%">
									<input id="to2" type="button" name="to2"  title='assign' class="btn btn-danger btn-xs" style="font-size: 18px;" value="&nbsp;&nbsp;&nbsp;&#8608&nbsp;&nbsp;" />
									<br/><br/>
									<input id="to1" type="button" name="to1" title='unassign' class="btn btn-success btn-xs" style="font-size: 18px;" value="&nbsp;&nbsp;&nbsp;&#8606&nbsp;&nbsp;">
								</td>
								<td align="center" width="40%">
								
									<div class="form-group">
										<label for="kelas" class="col-sm-3 control-label">Kelas <span class="required-input">*</span></label>
										<div class="col-sm-6"> 
											<?=form_dropdown('kelasakhir', $kelas, '0', "class='form-control input-sm required' id='akhir'");?>
										</div>
									</div>
									<div class="form-group">
										<label for="db_idkelas" class="col-sm-3 control-label"></label>
										<div class="col-sm-6"> 
											<select name="secondList[]" id="secondList" multiple="multiple" class="form-control" style="height:250px;" >
											</select>
										</div>
									</div>

									
								</td>						
							</tr>
							<tr>
								<td colspan="1"></td>
								<td class="table-common-links"><br/>
									<input type="hidden" name="id_siswa" id="nis" value="">
									<a href="javascript: return void(0)" id="add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i>  Simpan</a>
								</td>
							</tr>
						</tbody>
					</table>
					
					</form>
					
					
				</td>
			</tr>

		</table>
	
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
		if ($('#akhir').val() == 0) {
			alert('Kelas Baru Harap dipilih');
			return false;
		}
		
		$('#secondList').each(function(){
			$('#secondList option').attr("selected","selected");
		});
		$('#j_action').val('add_param');
		$('#frm_format').submit();
	});
		
	$('#awal').change(function() {
		if ($('#tahunajaran').val() == 0) {
			alert('Tahun Ajaran harap dipilih'); return false;
		}
		$('#renderkelasawal').load('<?=base_url()?>kesiswaan/siswaperkelas/loadkelas/' + $('#awal').val() + '/' + $('#tahunajaran').val());
	});	
		
	$('#tahunajaranakhir').change(function() {
		if (($('#tahunajaranakhir').val() == $('#tahunajaran').val()) || ($('#tahunajaranakhir').val() < $('#tahunajaran').val())) {
			alert('Harap pilih tahun ajaran yang aktif');
			return false;
		}
	});
	
	$('#akhir').change(function() {
		if (($('#akhir').val() == $('#awal').val()) || ($('#akhir').val() < $('#awal').val())) {
			alert('Harap pilih kelas yang lebih tinggi');
			return false;
		}
	});
	
	$('#to2').click(function() {
		return !$('#firstList option:selected').remove().appendTo('#secondList'); 		
	});

	$('#to1').click(function() {
		return !$('#secondList option:selected').remove().appendTo('#firstList'); 
	});
	
});
</script>