<div class="row">
	<div class="col-md-12">
		
		<!--panel-->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-list"></i> Daftar Penilaian</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" id="mini-refresh"><i class='fa fa-refresh'></i></button>
				</div>
			</div>
			<div class="box-body">
				<form id="formKlien" name="formKlien" method="post" action="" role="form" class="form-horizontal" >
					<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
					<input type="hidden" id="j_action" name="j_action" value="">
		
					<div class="form-group">
						<label for="tingkat" class="col-sm-5 control-label">Tingkat <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
						  <?php                  
						   echo form_dropdown('tingkat',$dropdown_tingkat, set_value('tingkat'),'class="form-control input-sm required" id="tingkat"');
						  ?>
						 <?php echo form_error('tingkat');?>
						</div>
					</div>
					<div class="form-group">
						<label for="kelas" class="col-sm-5 control-label">Kelas <span class="required-input">*</span> </label>
						<div class="col-sm-6">                                   
							<select name="kelas" class="form-control input-sm required">
								<option value="" selected="selected">-- Pilih Kelas --</option>
								<div id="kelas"></div>
							</select>
							<?php echo form_error('kelas');?>
						</div>
					</div>	

					<div class="form-group">
						<label for="mapel" class="col-sm-5 control-label">Mata Pelajaran <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
							<select name="mapel" class="form-control input-sm required">
								<option value="" selected="selected">-- Pilih Pelajaran --</option>
								<div id="mapel"></div>
							</select>
							<?php echo form_error('mapel');?>
						</div>
					</div>
					<div class="form-group">
						<label for="pengampu" class="col-sm-5 control-label">Pengampu Mapel <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
							<div id="pengampu"/></div>							
							<?php echo form_error('pengampu');?>
						</div>
					</div>
					<div class="form-group">
						<label for="deskripsi" class="col-sm-5 control-label">Deskripsi <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
							<textarea name="deskripsi" id="deskripsi" cols="50" rows="2" class="form-control input-sm required"></textarea>
							<?php echo form_error('deskripsi');?>
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
	//load kelas
	$('select[name="tingkat"]').on('change', function() {
        var tingkatID = $(this).val();
        if(tingkatID) {
            $.ajax({
                url: baseURL+'/penilaian/getKelasAjax/'+tingkatID,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    //$('select[name="kelas"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="kelas"]').append('<option value="'+ value.id +'">'+ value.kelas +'</option>');
                    });
                }
            });
        }else{
            $('select[name="kelas"]').empty();
        }
    });
	//load mapel
	$('select[name="tingkat"]').on('change', function() {
        var tingkatID2 = $(this).val();
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
		$('select[name="pengampu"]').empty();
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
		if ($('#kelas').val() == 0) { alert('Kelas harap dipilih'); return false; }
		$('#j_action').val('add_kelas');
		$('#formKlien').submit();
	});	
	
	$('#formKlien').submit(function() {
		switch ($('#j_action').val()) {
			case 'add_kelas' :
				dest = '<?=base_url()?>penilaian/lists'; 
			break;
			
			case 'add_nilai' :
				dest = '<?=base_url()?>penilaian/add'; 
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
				else if ($('#j_action').val() == 'add_nilai') {					
					alertify.alert('Data sukses disimpan');
					//then redirect
					window.location.href="<?=base_url('penilaian/konsep');?>";
				}
				
			}
		});
		return false;
	});	



});
</script>