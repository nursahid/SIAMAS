<div class="row">
	<div class="col-md-12">
		
		<!--panel-->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-print"></i> Cetak Data Nilai</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" id="mini-refresh"><i class='fa fa-refresh'></i></button>
				</div>
			</div>
			<div class="box-body">
			
			<div id="message" align="center"><?php echo $this->session->userdata('message'); ?></div>
			<br />
				<form id="formKlien" name="formKlien" method="post" action="" role="form" class="form-horizontal" >
					<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
					<input type="hidden" id="j_action" name="j_action" value="">
					
					<input type="hidden" name="jenis_penilaian" id="jenis_penilaian" value="<?=$this->uri->segment(3);?>">
		
					<div class="form-group">
						<label for="kelas" class="col-sm-5 control-label">Kelas <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
						  <?php
						   echo form_dropdown('kelas',$dropdown_kelas, set_value('kelas'),'class="form-control input-sm required" id="kelas"');
						  ?>
						 <?php echo form_error('kelas');?>
						</div>
					</div>

					<div class="form-group">
						<label for="mapel" class="col-sm-5 control-label">Mata Pelajaran <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
							<select name="mapel" class="form-control input-sm required">
								<option value="" selected="selected">-- Pilih Pelajaran --</option>
							</select>
							<?php echo form_error('mapel');?>
						</div>
					</div>
					<div class="form-group">
						<label for="pengampu" class="col-sm-5 control-label">Pengampu Mapel <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
							<div id="pengampu"/><input type="hidden" name="pengampu" value="" /></div>
							<?php echo form_error('pengampu');?>
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
				<div id="tampil_kelas"></div>
				<?php echo form_close(); ?>	
		
			</div>
			<div class="panel-footer" align="center">
 
			</div>
		</div>
		
	</div>
</div>
<script type="text/javascript">
// baseURL variable
var baseURL= "<?php echo base_url();?>"; 

$(document).ready(function() {
	var dest;

	//load mapel
	$('select[name="kelas"]').on('change', function() {
        //var tingkatID2 = $(this).val();
		var tingkatID2 = <?=$datakelas->id_tingkat?>;
        if(tingkatID2) {
            $.ajax({
                url: baseURL+'/penilaian/getMapelAjax/'+tingkatID2,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    //$('select[name="mapel"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="mapel"]').append('<option value="'+ value.id +'">'+ value.mapel +'</option>');
                    });
                }
            });
        }else{
            $('select[name="mapel"]').empty();
        }
    });
	//info pengampu
	$('select[name="mapel"]').on('change', function() {
		var pengampu = "<?php echo site_url('penilaian/getPengampu');?>/"+$(this).val();
		$('input[name="pengampu"]').empty();
		$('#pengampu').load(pengampu);
		return false;
    });
	
	$("#self_loading").ajaxStart(function(){
		$(this).show();
	});
	$("#self_loading").ajaxStop(function(){
		$(this).hide();
	});

	$('#btn_simpan').click(function(){		
		if ($('#kelas').val() == 0) { alertify.alert('Kelas harap dipilih'); return false; }
		if ($('select[name="mapel"]').val() == "") { alertify.alert('Pilih Mata Pelajaran dulu'); return false; }
		if ($('input[name="pengampu"]').val() == "") { alertify.alert('Belum ada pengampu mapel'); return false; }

		$('#j_action').val('add_kelas');
		$('#formKlien').submit();
	});	
	
	$('#formKlien').submit(function() {
		switch ($('#j_action').val()) {
			case 'add_kelas' :
				dest = '<?=base_url()?>cetak/nilai/lists'; 
			break;
		}
		
		$.ajax({
			type	: 'POST',
			url		: dest,
			data	: $(this).serialize(),
			success	: function(data) {								
				if ($('#j_action').val() == 'add_kelas') {					
					$('#tampil_kelas').html(data);
				}
			}
		});
		return false;
	});	



});
</script>