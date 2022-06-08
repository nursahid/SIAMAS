<div class="row">
	<div class="col-md-12">
		
		<!--panel-->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-list"></i> Lihat Daftar Penilaian</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" id="mini-refresh"><i class='fa fa-refresh'></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="container">
					<div class="col-md-4"></div>
					<div class="alert alert-danger alert-dismissible col-md-5">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<i class="icon fa fa-ban"></i> Jika ingin mengganti Jurusan, harap melakukan refresh page dulu
					</div>
				</div>
				<div id="div">
					<form id="formKlien" name="formKlien" method="post" action="" role="form" class="form-horizontal" >
						<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
						<input type="hidden" id="j_action" name="j_action" value="">

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
							<label for="jurusan" class="col-sm-5 control-label">Program Studi/Jurusan <span class="required-input">*</span></label>
							<div class="col-sm-6">                                   
							  <?php
							   echo form_dropdown('jurusan',$dropdown_jurusan, set_value('jurusan'),'class="form-control input-sm required" id="jurusan"');
							  ?>
							 <?php echo form_error('jurusan');?>
							</div>
						</div>					
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
								<select name="mapel" class="form-control input-sm required" id="mapel">
									<option value="" selected="selected">-- Pilih Mata Pelajaran --</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="pengampu" class="col-sm-5 control-label">Guru Pengampu Mapel <span class="required-input">*</span></label>
							<div class="col-sm-6">                                   
								<div id="pengampu" class="badge btn-danger"/></div>
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
								&nbsp; &nbsp;  &nbsp; &nbsp; 
								<a href="javascript:window.location.reload(true);" class="btn btn-default btn-sm" ><span class="glyphicon glyphicon-refresh"></span>&nbsp; Refresh</a>
							</div>
						</div>
						
					<br/><br/>
					<div id="tampil_kelas"></div>
					<?php echo form_close(); ?>	
				</div><!--end DIV refresh-->
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
	
	//load kelas by id tingkat dan jurusan
	$('select[name="tingkat"]').on('change', function() {
        var tingkatID = $(this).val();
		var jurusanID = $('#jurusan').val();
		var UrlMapel = baseURL+'/penilaian/daftarnilai/getMapel/'+jurusanID+'/'+tingkatID;

        if(tingkatID) {
            $.ajax({
                url: baseURL+'/penilaian/daftarnilai/getKelasAjax/'+jurusanID+'/'+tingkatID,
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
		var jurusanID2 = $('#jurusan').val();
		
        if(tingkatID2) {
            $.ajax({
                url: baseURL+'/penilaian/daftarnilai/getMapelAjax/'+jurusanID2+'/'+tingkatID2,
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
	//load info pengampu
	$('select[name="mapel"]').on('change', function() {
		var pengampu = "<?php echo site_url('penilaian/daftarnilai/getPengampu');?>/"+$(this).val();
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
		if ($('#jenis_penilaian').val() == 0) { alertify.alert('Jenis Penilaian harap dipilih'); return false; }
		if ($('#jurusan').val() == 0) { alertify.alert('Jurusan harap dipilih'); return false; }
		if ($('#tingkat').val() == 0) { alertify.alert('Tingkat harap dipilih'); return false; }
		if ($('select[name="kelas"]').val() == "") { alertify.alert('Kelas dipilih dulu'); return false; }
		if ($('select[name="mapel"]').val() == "") { alertify.alert('Pilih Mata Pelajaran dulu'); return false; }

		$('#j_action').val('add_kelas');
		$('#formKlien').submit();
	});	
	
	$('#formKlien').submit(function() {
		$.ajax({
			type	: 'POST',
			url		: '<?=base_url()?>penilaian/daftarnilai/lists',
			data	: $(this).serialize(),
			success	: function(data) {								
				$('#tampil_kelas').html(data);				
			}
		});
		return false;
	});	

	$('#refresh').on('click', function(e){
		$('#div').load(" #div> *");
	});

});
</script>

