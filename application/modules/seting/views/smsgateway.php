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
				<li class="<?php echo $this->uri->segment(2) == 'uploadlogo' ? 'active':'';?>">
					<a href="<?php echo base_url('seting/uploadlogo');?>">Upload Logo</a>
				</li>
			</ul>
			<div class="tab-content">

				<div class="active tab-pane" id="smsgateway">
					<!--tab sms gateway-->
					<div id="message2"><?php echo $this->session->userdata('message2');?></div>
					<form action="" method="post" name="FormGammu" id="SetingGammu" class="form-horizontal" >
						<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
						<div class="form-group">
							<label for="namamodem" class="col-sm-3 control-label">Nama Modem</label>
							<div class="col-sm-8">
								<input type="text" name="namamodem" id="namamodem" class="form-control" placeholder="Nama Modem" value="<?php echo $setting['gammu_namamodem']; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="portmodem" class="col-sm-3 control-label">Port Modem</label>
							<div class="col-sm-8">
							  <input type="text" name="portmodem" id="portmodem" class="form-control" placeholder="Nomor Port Modem" value="<?=$setting['gammu_port']; ?>" >
							</div>
						</div>
						<div class="form-group">
							<label for="koneksimodem" class="col-sm-3 control-label">Koneksi</label>
							<div class="col-sm-8">
							  <input type="text" name="koneksimodem" id="koneksimodem" class="form-control" placeholder="Nama Koneksi Modem" value="<?=$setting['gammu_connection']; ?>" >
							</div>
						</div>
						<div class="form-group">
							<label for="lokasiserver" class="col-sm-3 control-label">Lokasi Server Gammu</label>
							<div class="col-sm-8">
								<input type="text" name="lokasiserver" id="lokasiserver" class="form-control" placeholder="Nama Modem" value="<?php echo $setting['lokasi_server']; ?>" />
							</div>
						</div>
						
					  <div class="form-group">
						<div class="col-sm-offset-2 col-sm-8">
							<button type="submit" name="insertGammu" id="insertGammu" class="btn btn-danger"><i class="fa fa-save"></i> &nbsp;&nbsp;Submit</button>
						</div>
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
        $("#insertGammu").click(function(){
            var namamodem = $("#namamodem").val();
            var portmodem = $("#portmodem").val();
            var koneksimodem = $("#koneksimodem").val();
			var lokasiserver = $("#lokasiserver").val();
            //pass object in post
            var dataString = {'namamodem': namamodem , 'portmodem': portmodem , 'koneksimodem': koneksimodem, 'lokasiserver': lokasiserver};
            if(namamodem==''||portmodem==''||koneksimodem=='')
            {
                alertify.alert("<div class='text-red'><i class='fa fa-info'></i> Silakan isi semua</div>");
            }
            else
            {
                // AJAX Code To Submit Form.
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('seting/smsgateway/simpan'); ?>",
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