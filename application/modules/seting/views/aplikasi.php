<div class="row">
	<div class="col-md-12">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="<?php echo $this->uri->segment(2) == 'lembaga' ? 'active':'';?>">
					<a href="<?php echo base_url('seting/lembaga/');?>">Seting Sekolah</a>
				</li>
				<li class="<?php echo $this->uri->segment(2) == 'aplikasi' ? 'active':'';?>">
					<a href="<?php echo base_url('seting/aplikasi');?>" data-toggle="tab">Seting Aplikasi</a>
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
							<label for="angkaawal_nis" class="col-sm-3 control-label">Angka Awal NIS</label>
							<div class="col-sm-8">
								<input type="text" name="angkaawal_nis" id="angkaawal_nis" class="form-control" placeholder="Masukkan Angka..." value="<?php echo $setting['angkaawal_nis']; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="perpage" class="col-sm-3 control-label">Tampilan Data Per Halaman</label>
							<div class="col-sm-8">
							  <input type="text" name="perpage" id="perpage" class="form-control" placeholder="Masukkan Angka..." value="<?=$setting['perpage']; ?>" >
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
            var angkaawal_nis = $("#angkaawal_nis").val();
            var perpage = $("#perpage").val();
            //pass object in post
            var dataString = {'angkaawal_nis': angkaawal_nis , 'perpage': perpage };
            if(angkaawal_nis==''||perpage=='')
            {
                alertify.alert("<div class='text-red'><i class='fa fa-info'></i> Silakan isi semua</div>");
            }
            else
            {
                // AJAX Code To Submit Form.
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('seting/aplikasi/simpan'); ?>",
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
