<div class="row">
	<div class="col-md-12">
		
		<!--panel-->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-plus"></i> Tambah Penilaian</h3>
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
						<label for="jurusan" class="col-sm-5 control-label">Program Studi <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
						  <?php
						   echo form_dropdown('jurusan',$dropdown_prodi, set_value('jurusan'),'class="form-control input-sm required" id="jurusan"');
						  ?>
						 <?php echo form_error('jurusan');?>
						</div>
					</div>					
					<div class="form-group">
						<label for="tingkat" class="col-sm-5 control-label">Tingkat <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
							<select name="tingkat" class="form-control input-sm required">
								<option value="" selected="selected">-- Pilih Tingkat --</option>
								<div id="tingkat"></div>
							</select>
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
						<label for="jenis_penilaian" class="col-sm-5 control-label">Jenis Penilaian <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
						  <?php                  
						   echo form_dropdown('jenis_penilaian',$dropdown_jenis, set_value('jenis_penilaian'),'class="form-control input-sm required" id="jenis_penilaian"');
						  ?>
						 <?php echo form_error('jenis_penilaian');?>
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
	//load tingkat
	$('select[name="jurusan"]').on('change', function() {
        var jurusanID = $(this).val();
        if(jurusanID) {
            $.ajax({
                url: baseURL+'/penilaian/getTingkatAjax/'+jurusanID,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    //$('select[name="tingkat"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="tingkat"]').append('<option value="'+ value.id +'">'+ value.tingkat +'</option>');
                    });
                }
            });
        }else{
            $('select[name="tingkat"]').empty();
        }
    });	
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
		$.ajax({
			type	: 'POST',
			url		: '<?=base_url()?>penilaian/pilihkelas',
			data	: $(this).serialize(),
			success	: function(data) {								
				$('#tampil_kelas').html(data);				
			}
		});
		return false;
	});	



});
</script>