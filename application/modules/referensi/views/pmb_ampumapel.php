
<!--panel-->
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><i class="fa fa-table"></i> Pengampu Mata Pelajaran</h3>
		<div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			<button class="btn btn-box-tool" id="mini-refresh"><i class='fa fa-refresh'></i></button>
		</div>
	</div>
    <div class="box-body">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">	
			<tr>		
				<td width="100%">

					<form id="frm_format" name="frm_format" method="post" action="">
					<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
					<input type="hidden" id="j_action" name="j_action" value="">
					<table cellpadding="0" id="tbl_format"cellspacing="0" border="0" width="100%" class="standard_table_v4">	
						<thead>
						</thead>
						<tbody>
							<tr>
								<td align="center">
									<h4>Daftar Pegawai/Guru</h4>
									<?php
									if ($result) {
									?>
									<?=form_multiselect('firstList', $result, '', 'id="firstList" style="height:420px;width: 250px;"');?>							
									<?php
									}
									else {
									?>
									<select name="firstList" id="firstList" multiple="multiple" style="height:420px;width: 250px;" >
									</select>			
									<?php
									}
									?>
								</td>
								<td align="center">
									<input id="to2" type="button" name="to2"  title='assign' class="btn btn-danger btn-sm" value=">" /><br/><br/>

									<input id="to1" type="button" name="to1" title='unassign' class="btn btn-success btn-sm" value="<">
								</td>
								<td align="center">							
									&nbsp;&nbsp;&nbsp;&nbsp;Mata Pelajaran : <?=form_dropdown('mapelakhir', $mapel, '0', "id='akhir'")?><br /><br/>
									<div id="renderkelasakhir"><select name="secondList[]" id="secondList" multiple="multiple" style="height:420px;width: 250px;" >
									</select></div>							
								</td>						
							</tr>
							<tr>
								<td colspan="1"></td>
								<td class="table-common-links"><br/>
									<input type="hidden" name="id_siswa" id="nis" value=""><a href="javascript: return void(0)" id="add" class="btn btn-lg btn-primary">Simpan</a>
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
		$('#renderkelasawal').load('<?=base_url()?>seting/pmb/loadmapel/' + $('#awal').val() + '/' + $('#tahunajaran').val());
	});	

	$('#mp_id').change(function() {		
		var selectValues = $("#mp_id").val();
		if (selectValues == 0){				
			alert('Silakan pilih kelas terlebih dahulu');
			return false;
		}else{				
			$('#renderkelasakhir').load('<?=base_url()?>seting/pmb/loadmapelakhir/' + $('#mp_id').val() + '/' + $('#tahunajaranakhir').val());
		}		
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