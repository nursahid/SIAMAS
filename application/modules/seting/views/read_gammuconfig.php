<div class="row">
	<div class="col-md-12">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="<?php echo $this->uri->segment(2) == 'lembaga' ? 'active':'';?>">
					<a href="<?php echo base_url('seting/lembaga/');?>">Seting Aplikasi</a>
				</li>
				<li class="<?php echo $this->uri->segment(2) == 'smsgateway' ? 'active':'';?>">
					<a href="<?php echo base_url('seting/smsgateway');?>" data-toggle="tab">Server SMS</a>
				</li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane" id="aplikasi">

				</div>
				<div class="active tab-pane" id="smsserver">
					<!--tab sms gateway-->
					<div id="message"><?php echo $message;?></div>
					<form action="#" method="post" name="FormGammu" id="SetingGammu"  >
						<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
						<div class="form-group">
							<label>Nama Konfig: <?=$fileconfig;?></label>
							
						</div>
						<div class="form-group">
							<textarea name="fileconfig" id="fileconfig" cols="120" rows="20"><?=$configgammu;?></textarea>
						</div>
						
					  <div class="form-group">
						  <button type="submit" name="simpanData" id="simpanData" class="btn btn-danger"><i class="fa fa-save"></i> &nbsp;&nbsp;Submit</button>
					  </div>
					</form>
					<!--end sms gateway-->
				</div>
			</div>
		</div>
	</div>
</div>
				

<script type="text/javascript">

    $(document).ready(function(){
        $("#simpanData").click(function(){
            var fileconfig = $("#fileconfig").val();

            //pass object in post
            var dataString = {'fileconfig': fileconfig };
            if(fileconfig=='')
            {
                alertify.alert("<div class='text-red'><i class='fa fa-info'></i> Silakan diisi</div>");
            }
            else
            {
                // AJAX Code To Submit Form.
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('seting/smsgateway/write/'.$this->uri->segment(4)); ?>",
                    data: dataString,
                    dataType: 'json',
                    cache: false,
                    success: function(result){
                        //alertify.alert(result.pesan);
						if(result.status == 1){
							alertify.alert(result.pesan);
						}
						if(result.status == 0){
							alertify.alert(result.pesan);
						}
                    }
                });
            }
            return false;
        });
    }); 
 </script>
