<!--panel-->
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><i class="fa fa-table"></i> Tinggal Kelas Tahun Pelajaran : <strong><?=$this->sistem_model->get_nama_tapel('Y');?></strong></h3>
		<div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			<button class="btn btn-box-tool" id="mini-refresh"><i class='fa fa-refresh'></i></button>
		</div>
	</div>
	
	<form id="frm_format" name="frm_format" method="post" action="" role="form" class="form-horizontal" >
	<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
	<input type="hidden" id="j_action" name="j_action" value="">

    <div class="box-body">

			<div id="message" align="center"><?php echo $this->session->userdata('message'); ?></div>
			<br />
				<div class="form-group">
					<label for="tahunajaranakhir" class="col-sm-4 control-label">Tahun Ajaran Awal <span class="required-input">*</span></label>
					<div class="col-sm-6"> 
						<input type="text" name="tahunajaranakhir" value="<?=@$thnsesudah->tahun?>" id="ti" class="form-control" readonly="true" />
						<input type="hidden" name="db_idtahun" value="<?=@$thnsebelum->id?>" id="tahunajaranakhir" />
					</div>
				</div>
				<div class="form-group">
					<label for="db_idkelas" class="col-sm-4 control-label">Kelas Tahun Lalu <span class="required-input">*</span></label>
					<div class="col-sm-6"> 
						<?php
						echo form_dropdown('db_idkelas', $kelastahunlalu, '0','class="form-control input-sm required" id="awal"');             
						?>
					</div>
				</div>
				
				<div class="form-group">
					<label for="kelasakhir" class="col-sm-4 control-label">Kelas Saat Ini <span class="required-input">*</span></label>
					<div class="col-sm-6">                                   
						<div id="renderkelasakhir">
							<?=form_dropdown("kelasakhir",array(''=>'-- Pilih Kelas --'),'','class="form-control input-sm required" id="akhir" disabled');?>
						</div>
					</div>
				</div>
				
				<div class="form-group">
					<label for="db_idkelas" class="col-sm-4 control-label">Data Siswa <span class="required-input">*</span></label>
					<div class="col-sm-6"> 
						<div id="renderkelasawal">
							<select id="firstList" name="firstList[]" multiple="multiple" class="form-control" style="height:250px;" >
							</select>
						</div>
					</div>
				</div>
			
	</div>
    <div class="box-footer">
		<div class="form-group">
			<div class="col-sm-6"> 
			<a class="btn btn-sm btn-danger pull-right" href="javascript: return void(0)" id="simpan"><i class="fa fa-plus"></i> TINGGAL KELAS</a>
			</div>
		</div>
	</div>	
	</form>
	
</div>
<script type="text/javascript">
$(function(){
	$("#self_loading").ajaxStart(function(){
		$(this).show();
	});
	$("#self_loading").ajaxStop(function(){
		$(this).hide();
	});
	
	$('#simpan').click(function(){			
				
		$('#firstList').each(function(){
			$('#firstList option').attr("selected","selected");
		});
		$('#j_action').val('add_param');
		$('#frm_format').submit();
	});
	
	$('#awal').change(function() {		
		var selectValues = $("#awal").val();
		var selectAkhir = $("#akhir").val();
			if (selectValues == '0'){				
				alertify.alert('Kelas Awal Harap dipilih');
				return false;
			} 
			if (selectAkhir == '0'){				
				alertify.alert('Kelas Tujuan Harap dipilih');
				return false;
			}
		$('#renderkelasawal').load('<?=base_url()?>pembelajaran/tinggalkelas/loadkelas/' + $('#awal').val() + '/' + $('#tahunajaranakhir').val());
		$('#renderkelasakhir').load('<?=base_url()?>pembelajaran/tinggalkelas/pilihkelas2/' + selectValues);
	});		
});
</script>