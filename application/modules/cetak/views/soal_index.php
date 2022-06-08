<div class="row">
	<div class="col-md-6">
		<?php echo form_open(site_url('cetak/soal/cetak'),'role="form" class="form-horizontal" id="form_cetak" parsley-validate'); ?>
		<div class="panel panel-danger">
			<div class="panel-heading"><i class="fa fa-print"></i> Cetak Data Soal</div>
				<div class="panel-body"> 
					<div class="message3"><?php echo $this->session->userdata('message3'); ?> </div>
					<div class="form-group">
						<label for="tingkat" class="col-sm-4 control-label">Pilih Tingkat <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
						  <?php                  
						   echo form_dropdown('tingkat',$dropdown_tingkat,'','class="form-control input-sm required" id="tingkat"');
						  ?>
						 <?php echo form_error('tingkat');?>
						</div>
					</div>
					<div class="form-group">
						<label for="mapel" class="col-sm-4 control-label">Pilih Mata Pelajaran <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
							<select name="mapel" class="form-control input-sm required">
								<option value="" selected="selected">-- Pilih Mata Pelajaran --</option>
								<div id="mapel"></div>
							</select>
						 <?php echo form_error('mapel');?>
						</div>
					</div>
					<div class="form-group">
						<label for="kuis" class="col-sm-4 control-label">Pilih Soal <span class="required-input">*</span></label>
						<div class="col-sm-6">                                   
							<select name="kuis" class="form-control input-sm required" id="kuis">
								<option value="" selected="selected">-- Pilih Soal --</option>
							</select>
							<?php echo form_error('kuis');?>
						</div>
					</div>
					<div class="form-group">
						<label for="jml_cetak" class="col-sm-4 control-label">Soal Tersedia <span class="required-input">*</span></label>
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
       
	   $('select[name="tingkat"]').on('change', function() {
            var stateID = $(this).val();
            if(stateID) {
                $.ajax({
                    url: baseURL+'/cetak/getMapelAjax/'+stateID,
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
		
		$('select[name="mapel"]').on('change', function() {
            var url = "<?php echo site_url('cetak/getKuis');?>/"+$(this).val();
            $('#kuis').load(url);
            return false;
        });
		
	$('select[name="kuis"]').on('change', function() {
		var info_soal = "<?php echo site_url('cetak/infoSoal');?>/"+$(this).val();
		$('#info_soal').load(info_soal);
        //tambahan
		var nMinimal = 1;
		var nMaximal = info_soal;
		$('#jml_cetak').on('keydown keyup change', function(){
			var char = $(this).val();
			//var PanjangAngka = $(this).val().length;
			var PanjangAngka = $(this).val();
			if(PanjangAngka < nMinimal){
				$('#msg_cetak').text('Panjang minimum '+nMinimal+'.');
			}else if(PanjangAngka > nMaximal){
				$('#msg_cetak').text('Panjang melebihi '+nMaximal+'.');
				//$(this).val(char.substring(0, nMaximal));
				$(this).val(nMaximal);
			}else{
				$('#msg_cetak').text('Cocok');
			}
		});	//end
		return false;
    });	
	
		
});
</script>