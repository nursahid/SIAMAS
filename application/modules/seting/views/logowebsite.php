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
					<a href="<?php echo base_url('seting/akunsosial');?>">Akun Sosial</a>
				</li>
				<li class="<?php echo $this->uri->segment(2) == 'logowebsite' ? 'active':'';?>">
					<a href="<?php echo base_url('seting/logowebsite');?>" data-toggle="tab">Logo Website</a>
				</li>
			</ul>
			<div class="tab-content">
				<div class="active tab-pane" id="logowebsite">

					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-table"></i> Logo Website <span class="label label-success">Ukuran maksimal 2 MB. Format file harus : png.</span></h3> 
							<div class="box-tools pull-right">
								<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								<button class="btn btn-box-tool" id="mini-refresh"><i class='fa fa-refresh'></i></button>
							</div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-5">
									<h3>Logo Website</h3>
									<div id="message"><?php echo $this->session->userdata('message'); ?></div>
									<div id="image_preview">
										<img id="logo1" width="180" src="<?=base_url('assets/uploads/image/').$setting['website_logo'];?>" class="img-thumbnail" alt="Logo Website" />
									</div>
									<?php echo form_open_multipart('seting/logowebsite/simpanlogo');?>
										<input type="hidden" name="namawebsite" value="<?php echo $setting['website_name']; ?>"  />
										<div class="form-group">
											<?php echo form_error('filefoto'); ?>

										</div>
										<div id="image-preview">
										<div class="input-group">
											<span class="input-group-btn"> 
												<div class="btn btn-default image-preview-input"> <span class="glyphicon glyphicon-folder-open"></span> <span class="image-preview-input-title">Browse</span>
													<input type="file" name="filefoto" id="image-upload" required />
												</div>
												<input type="submit" name="submit" id="upload" value="Upload Logo" class="btn btn-primary" /> 
											</span>
										</div>
										</div>
										<?php //echo form_submit('submit', 'Upload Logo', 'class="btn btn-primary"'); ?>
									<?php echo form_close(); ?>
								</div>
								<div class="col-md-1">
								</div>
								<div class="col-md-4">
									<h3>Logo Favicon</h3>
									<div id="message2"><?php echo $this->session->userdata('message2'); ?></div>
									<div class="uploaded_image">
										<img id="logo1" width="80" src="<?=base_url('assets/uploads/image/').$setting['favicon'];?>" class="img-thumbnail" alt="Logo Favicon" />
									</div>
									<br />
									<?php //echo form_open_multipart('seting/logowebsite/simpanfavicon');?>
									<form method="post" id="upload_form" align="center" enctype="multipart/form-data">
										<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">													
										<div class="input-group">
											<span class="input-group-btn"> 
												<div class="btn btn-default image-preview-input"> <span class="glyphicon glyphicon-folder-open"></span> <span class="image-preview-input-title">Browse</span>
													<input type="file" name="image_file" id="image_file" />
												</div>
												<input type="submit" name="upload" id="upload" value="Upload Logo" class="btn btn-danger" /> 
											</span>
										</div>
										<input type="hidden" name="namawebsite" value="<?php echo $setting['website_name']; ?>" />
									</form>
									<br />
								</div>						
						
						</div>
					</div><!--end-->

				</div>
			</div>
		</div>
	</div>
</div>
    <script type="text/javascript">
      $(document).ready(function() {
          $.uploadPreview({
              input_field: "#image-upload",
              preview_box: "#image-preview",
              label_field: "#image-label"
          });
      });
    </script>
<script type="text/javascript">
 $(document).ready(function(){ 
    $('#upload_form').on('submit', function(e){  
        e.preventDefault(); 
		var divload = $('#uploaded_image').html();
		
        if($('#image_file').val() == '')  
        {  
            alertify.alert("<div class='text-red'><i class='fa fa-times'></i> Mohon pilih satu file gambar format PNG dengan ukuran 64x64</div>"); 
        } 
		else  
        {  
            $.ajax({  
                url:"<?php echo base_url('seting/logowebsite/simpanfavicon'); ?>",
                method:"POST",  
                data:new FormData(this),  
                contentType: false,  
                cache: false,  
                processData:false,  
                success:function(data) {  
					$('#uploaded_image').html(data.preview); 
					//$('#uploaded_image').html('<p>Reloading image...</p>');
					refresh_files();
					//alertify.alert("Gambar berhasil diunggah");
					if(data.status == 'success'){
						alertify.alert(data.pesan);
					}
					if(data.status == 'error'){
						alertify.alert(data.pesan);
					}
                }
            });  
        }  
    }); 
	
	function refresh_files()
	{
		$.get('./assets/uploads/image/')
		.success(function (data){
			$('#uploaded_image').html(data.preview);
		});
	}
	
 });  
 </script>
