<div class="row">
	<div class="col-md-12">
		<?php echo form_open(site_url('cetak/soal/cetak'),'role="form" class="form-horizontal" id="form_cetak" parsley-validate'); ?>
		<div class="panel panel-danger">
			<div class="panel-heading"><i class="fa fa-print"></i> Cetak Data Soal</div>
				<div class="panel-body"> 
					<div class="col-md-12">
						<div class="row">
							<div class="alert alert-danger alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								Jika Mata Pelajaran kosong, berarti Anda belum mengampu Mata Pelajaran. Silakan hubungi Admin.
							</div>
						</div>
					</div>
					<div class="message3"><?php echo $this->session->userdata('message3'); ?> </div>
					<div class="form-group">
						<label for="mapel" class="col-sm-4 control-label">Pilih Mata Pelajaran <span class="required-input">*</span></label>
						<div class="col-sm-6"> 
						  <?php
						   echo form_dropdown('mapel', $dropdown_mapel, set_value('mapel'),'class="form-control input-sm required" id="mapel"');             
						  ?>
						 <?php echo form_error('mapel');?>
						</div>
					</div>
					<div class="form-group">
						<label for="kuis" class="col-sm-4 control-label">Pilih Soal <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
							<select name="kuis" class="form-control input-sm required" id="kuis">
								<option value="" selected="selected">-- Pilih Soal --</option>
								<div id="kuis"></div>
							</select>
							<?php echo form_error('kuis');?>
						</div>
					</div>
					<div class="form-group">
						<label for="info_soal" class="col-sm-4 control-label">Soal Tersedia <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
							<div id="info_soal" class="help-block badge" style="background-color: #5cb85c;"></div> Butir
						</div>
					</div>
					<div class="form-group">
						<label for="jml_cetak" class="col-sm-4 control-label">Jml. Soal dicetak <span class="required-input">*</span></label>
						<div class="col-sm-3">                                   
							<input type="number" name="jml_cetak" id="jml_cetak" class="form-control input-sm required" min="1" max="??" value="30" />
							<span id="msg_cetak"></span>
							<?php echo form_error('jml_cetak');?>
						</div>
						<label for="" class="col-sm-4">Pilihan Ganda</label>
					</div>
					<div class="form-group">
						<label for="jml_cetak_essay" class="col-sm-4 control-label"> </label>
						<div class="col-sm-3">                                   
							<input type="number" name="jml_cetak_essay" id="jml_cetak_essay" class="form-control input-sm required" min="1" max="??" value="10" />
							<?php echo form_error('jml_cetak_essay');?>
						</div>
						<label for="" class="col-sm-4">Essay</label>
					</div>
					<div class="form-group">
						<label for="cetak_ke" class="col-sm-4 control-label">Cetak Ke <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
							<select name="cetak_ke" class="form-control input-sm required" id="cetak_ke">
								<option value="" selected="selected">Pilih Tujuan Cetak :</option>
								<option value="printer">Preview</option>
								<option value="word">Word</option>
								<option value="pdf">PDF</option>
							</select>
						 <?php echo form_error('cetak_ke');?>
						</div>
					</div>
				</div>
				<div class="panel-footer" align="center">
					<input type="hidden" name="id_guru" value="<?=$id_guru;?>" />
					<button type="submit" class="btn btn-sm btn-info" name="post">
                        <i class="fa fa-desktop"></i>&nbsp; &nbsp;  Cetak Sekarang 
                    </button>  
				</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
// baseURL variable
var baseURL= "<?php echo base_url();?>"; 

$(document).ready(function() {
       
	$('select[name="mapel"]').on('change', function() {
        var stateID = $(this).val();
        if(stateID) {
            $.ajax({
                url: baseURL+'/cetak/getKuisAjax/'+stateID,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    //$('select[name="kuis"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="kuis"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                }
            });
        }else{
            $('select[name="kuis"]').empty();
        }
    });
		
	$('select[name="kuis"]').on('change', function() {
        var url = "<?php echo site_url('cetak/infoSoal');?>/"+$(this).val();
        $('#info_soal').load(url);
        return false;
    });

	
		
});
</script>