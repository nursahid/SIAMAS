    <style>

.image-preview-input {
    position: relative;
    overflow: hidden;
    margin: 0px;    
    color: #333;
    background-color: #fff;
    border-color: #ccc;    
}
.image-preview-input input[type=file] {
	position: absolute;
	top: 0;
	right: 0;
	margin: 0;
	padding: 0;
	font-size: 20px;
	cursor: pointer;
	opacity: 0;
	filter: alpha(opacity=0);
}
.image-preview-input-title {
    margin-left:2px;
}
    </style>
<!--panel-->
<div class="row">
	<div class="col-md-12">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="<?php echo $this->uri->segment(2) == 'website' ? 'active':'';?>">
					<a href="<?php echo base_url('seting/website');?>">Seting Website</a>
				</li>
				<li class="<?php echo $this->uri->segment(2) == 'akunsosial' ? 'active':'';?>">
					<a href="<?php echo base_url('seting/akunsosial');?>" data-toggle="tab">Akun Sosial</a>
				</li>
				<li class="<?php echo $this->uri->segment(2) == 'logowebsite' ? 'active':'';?>">
					<a href="<?php echo base_url('seting/logowebsite');?>">Logo Website</a>
				</li>
			</ul>
			<div class="tab-content">
				<div class="active tab-pane" id="akunsosial">

					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-table"></i> Konfigurasi Website</h3> 
						</div>
						<div class="box-body">
								<!--tab website-->
								<div id="message"><?php echo $this->session->userdata('message');?></div>
								<form action="" method="post" id="FormData" id="FormData" class="form-horizontal" enctype="multipart/form-data">
									<div class="form-group">
										<label for="facebook" class="col-sm-3 control-label">Facebook</label>
										<div class="col-sm-8">
											<input type="text" name="facebook" id="facebook" class="form-control" placeholder="Nama Website" value="<?php echo $setting['facebook']; ?>" />
										</div>
									</div>
									<div class="form-group">
										<label for="twitter" class="col-sm-3 control-label">Twitter</label>
										<div class="col-sm-8">
										  <input type="text" name="twitter" id="twitter" class="form-control" placeholder="twitter" value="<?=$setting['twitter']; ?>" >
										</div>
									</div>
									<div class="form-group">
										<label for="gplus" class="col-sm-3 control-label">Google Plus</label>
										<div class="col-sm-8">
										  <input type="text" name="gplus" id="gplus" class="form-control" placeholder="gplus" value="<?=$setting['gplus']; ?>" >
										</div>
									</div>
									<div class="form-group">
										<label for="instagram" class="col-sm-3 control-label">Instagram</label>
										<div class="col-sm-8">
											<input type="text" name="instagram" id="instagram" class="form-control" placeholder="instagram Website" value="<?php echo $setting['instagram']; ?>" />
										</div>
									</div>						
									<div class="form-group">
										<label for="youtube" class="col-sm-3 control-label">Youtube Channel</label>
										<div class="col-sm-8">
											<input type="text" name="youtube" id="youtube" class="form-control" placeholder="Youtube Channel" value="<?php echo $setting['youtube']; ?>" />
										</div>
									</div>
									
									<hr />
								  <div class="form-group">
									<div class="col-sm-offset-2 col-sm-8">
										<button type="submit" name="insertData" id="insertData" class="btn btn-danger"><i class="fa fa-save"></i> &nbsp;&nbsp;Simpan</button>
									</div>
								  </div>
								</form>
								<!--end-->
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#insertData").click(function(){
            var facebook 	= $("#facebook").val();
            var twitter 	= $("#twitter").val();
            var gplus 		= $("#gplus").val();
			var instagram 	= $("#instagram").val();
			var youtube 	= $("#youtube").val();
            //pass object in post
            var dataString = {'facebook': facebook , 'twitter': twitter , 'gplus': gplus, 'instagram': instagram, 'youtube': youtube};
            
			if(facebook==''||twitter==''||gplus=='')
            {
                alertify.alert("<div class='text-red'><i class='fa fa-info'></i> Silakan isi semua</div>");
            }
            else
            {
                // AJAX Code To Submit Form.
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('seting/akunsosial/simpan'); ?>",
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
