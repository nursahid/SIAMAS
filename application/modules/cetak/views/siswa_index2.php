<div class="row">
	<div class="col-md-12">
	
	<!--<form id="formKlien" name="formKlien" method="post" action="<?=site_url('cetak/siswa/cetak')?>" role="form" class="form-horizontal" >-->
	<form id="formKlien" name="formKlien" method="post" action="" role="form" class="form-horizontal" >
		<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
		<input type="hidden" id="j_action" name="j_action" value="">
		<!--panel-->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-print"></i> Cetak Data Siswa</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" id="mini-refresh"><i class='fa fa-refresh'></i></button>
				</div>
			</div>
			<div class="box-body">
			
				<div id="message" align="center"><?php echo $this->session->userdata('message'); ?></div>
				<br />					
					<div class="form-group">
						<label for="id_dusun" class="col-sm-4 control-label">Pilih Status <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
							<select name="status" class="form-control input-sm required" id="status">
								<option value="" selected="selected">Pilih Status :</option>
								<option value="Aktif">Aktif</option>
								<option value="Alumni">Alumni</option>
							</select>
						 <?php echo form_error('status');?>
						</div>
					</div>
					<div class="form-group">
						<label for="cetak_ke" class="col-sm-4 control-label">Cetak Ke <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
							<select name="cetak_ke" class="form-control input-sm required" id="cetak_ke">
								<option value="" selected="selected">Pilih Tujuan Cetak :</option>
								<option value="printer">Preview</option>
								<option value="excel">Excel</option>
								<option value="pdf">PDF</option>
							</select>
						 <?php echo form_error('cetak_ke');?>
						</div>
					</div>
			</div>
			<div class="panel-footer" align="center">
 				<button type="submit" class="btn btn-sm btn-primary" name="post" id="btn_simpan">
                    <i class="fa fa-desktop"></i>&nbsp; &nbsp;  Cetak Sekarang 
                </button> 

			</div>
		</div>
		<br/><br/>
		<div id="tampil_siswa"></div>
		<?php echo form_close(); ?>
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
		//if ($('#kelas').val() == 0) { alertify.alert('Kelas harap dipilih'); return false; }
		if ($('select[name="status"]').val() == "") { alertify.alert('Pilih Status dulu'); return false; }
		//if ($('input[name="pengampu"]').val() == "") { alertify.alert('Belum ada pengampu mapel'); return false; }

		$('#j_action').val('add_kelas');
		$('#formKlien').submit();
	});	
	
	$('#formKlien').submit(function() {
		switch ($('#j_action').val()) {
			case 'add_kelas' :
				dest = '<?=base_url()?>cetak/siswa/lists/' + $('#status').val(); 
			break;
		}
		
		$.ajax({
			type	: 'POST',
			url		: dest,
			data	: $(this).serialize(),
			success	: function(data) {								
				if ($('#j_action').val() == 'add_kelas') {					
					$('#tampil_siswa').html(data);
				}
			}
		});
		return false;
	});	



});
</script>