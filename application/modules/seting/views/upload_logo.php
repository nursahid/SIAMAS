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
				<li class="<?php echo $this->uri->segment(2) == 'lembaga' ? 'active':'';?>">
					<a href="<?php echo base_url('seting/lembaga/');?>">Seting Sekolah</a>
				</li>
				<li class="<?php echo $this->uri->segment(2) == 'aplikasi' ? 'active':'';?>">
					<a href="<?php echo base_url('seting/aplikasi');?>" >Seting Aplikasi</a>
				</li>
				<li class="<?php echo $this->uri->segment(2) == 'uploadlogo' ? 'active':'';?>">
					<a href="<?php echo base_url('seting/uploadlogo');?>" data-toggle="tab">Upload Logo</a>
				</li>
			</ul>
			<div class="tab-content">
				<div class="active tab-pane" id="uploadlogo">

					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-table"></i> Upload Logo <span class="label label-success">Ukuran maksimal 2 MB. Format file harus : png.</span></h3> 
							<div class="box-tools pull-right">
								<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								<button class="btn btn-box-tool" id="mini-refresh"><i class='fa fa-refresh'></i></button>
							</div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-4">
									<h3>Logo Sekolah</h3>
									<div id="message"><?php echo $this->session->userdata('message'); ?></div>
									<div id="image_preview">
										<img id="logo1" width="180" src="<?=base_url('assets/uploads/image/').$setting['logosekolah'];?>" class="img-thumbnail" alt="Logo Sekolah" />
									</div>
									<?php echo form_open_multipart('seting/uploadlogo/store');?>
										<input type="hidden" name="nama_file" value="<?php echo $setting['namasekolah']; ?>"  />
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
									<h3>Logo Kabupaten</h3>
									<div id="message2"><?php echo $this->session->userdata('message2'); ?></div>
									<div class="uploaded_image">
										<img id="logo1" width="180" src="<?=base_url('assets/uploads/image/').$setting['logokabupaten'];?>" class="img-thumbnail" alt="Logo Kabupaten" />
									</div>
									<br />
									<?php //echo form_open_multipart('seting/uploadlogo/logokabupaten');?>
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
										<input type="hidden" name="kabupaten" value="<?php echo $setting['kabupaten']; ?>" />
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
            alertify.alert("Mohon pilih satu file gambar");  
        } 
		else  
        {  
            $.ajax({  
                url:"<?php echo base_url('seting/uploadlogo/logokabupaten'); ?>",
                method:"POST",  
                data:new FormData(this),  
                contentType: false,  
                cache: false,  
                processData:false,  
                success:function(data) {  
					//$('#uploaded_image').html(data); 
					$('#uploaded_image').html('<p>Reloading image...</p>');
					refresh_files();
					alertify.alert("Gambar berhasil diunggah");
                }
            });  
        }  
    }); 
	
	function refresh_files()
	{
		$.get('./assets/uploads/image/')
		.success(function (data){
			$('#uploaded_image').html(data);
		});
	}
	
 });  
 </script>
