<div class="row">
	<div class="col-md-12">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#aplikasi" data-toggle="tab">Seting Website</a></li>
				<li class="<?php echo $this->uri->segment(2) == 'akunsosial' ? 'active':'';?>">
					<a href="<?php echo base_url('seting/akunsosial');?>">Akun Sosial</a>
				</li>
				<li class="<?php echo $this->uri->segment(2) == 'logowebsite' ? 'active':'';?>">
					<a href="<?php echo base_url('seting/logowebsite');?>">Logo Website</a>
				</li>
			</ul>
			<div class="tab-content">
				<div class="active tab-pane" id="aplikasi">	
				
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-table"></i> Konfigurasi Website</h3> 
						</div>
						<div class="box-body">
								<!--tab website-->
								<div id="message"><?php echo $this->session->userdata('message');?></div>
								<form action="" method="post" id="FormData" id="FormData" class="form-horizontal" enctype="multipart/form-data">
									<div class="form-group">
										<label for="namawebsite" class="col-sm-3 control-label">Nama Website</label>
										<div class="col-sm-8">
											<input type="text" name="namawebsite" id="namawebsite" class="form-control" placeholder="Nama Website" value="<?php echo $setting['website_name']; ?>" />
										</div>
									</div>
									<div class="form-group">
										<label for="deskripsi" class="col-sm-3 control-label">Deskripsi Website</label>
										<div class="col-sm-8">
										  <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3"><?=$setting['website_description']; ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label for="keywords" class="col-sm-3 control-label">Keywords</label>
										<div class="col-sm-8">
										  <input type="text" name="keywords" id="keywords" class="form-control" placeholder="Keywords" value="<?=$setting['website_keywords']; ?>" >
										</div>
									</div>
									<div class="form-group">
										<label for="slogan" class="col-sm-3 control-label">Slogan Website</label>
										<div class="col-sm-8">
											<input type="text" name="slogan" id="slogan" class="form-control" placeholder="Slogan Website" value="<?php echo $setting['website_slogan']; ?>" />
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
				

<script type="text/javascript">

    $(document).ready(function(){
        $("#insertData").click(function(){
            var namawebsite = $("#namawebsite").val();
            var deskripsi 	= $("#deskripsi").val();
            var keywords 	= $("#keywords").val();
			var slogan 		= $("#slogan").val();
			var user_file 	= $("#user_file").val();
            //pass object in post
            var dataString = {'namawebsite': namawebsite , 'deskripsi': deskripsi , 'keywords': keywords, 'slogan': slogan, 'user_file': user_file};
            
			if(namawebsite==''||deskripsi==''||keywords=='')
            {
                alertify.alert("<div class='text-red'><i class='fa fa-info'></i> Silakan isi semua</div>");
            }
            else
            {
                // AJAX Code To Submit Form.
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('seting/website/simpan'); ?>",
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
