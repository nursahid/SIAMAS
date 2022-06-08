<div class="row">
	<div class="col-md-12">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#aplikasi" data-toggle="tab">Seting Sekolah</a></li>
				<li class="<?php echo $this->uri->segment(2) == 'aplikasi' ? 'active':'';?>">
					<a href="<?php echo base_url('seting/aplikasi');?>">Seting Aplikasi</a>
				</li>
				<li class="<?php echo $this->uri->segment(2) == 'uploadlogo' ? 'active':'';?>">
					<a href="<?php echo base_url('seting/uploadlogo');?>">Upload Logo</a>
				</li>
			</ul>
			<div class="tab-content">
				<div class="active tab-pane" id="aplikasi">
					<!--tab aplikasi-->
					<div id="message"><?php echo $this->session->userdata('message');?></div>
					<form action="" method="post" name="FormData" id="FormData" enctype= "multipart/form-data" class="form-horizontal" >
						<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
						<div class="form-group">
							<label for="namasekolah" class="col-sm-4 control-label">Nama Sekolah</label>
							<div class="col-sm-6">
								<input type="text" name="namasekolah" id="namasekolah" class="form-control" placeholder="Nama Sekolah" value="<?php echo $setting['namasekolah']; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="namakepsek" class="col-sm-4 control-label">Nama Kepala Sekolah</label>
							<div class="col-sm-6">
								<input type="text" name="namakepsek" id="namakepsek" class="form-control" value="<?php echo $setting['namakepsek']; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="nipkepsek" class="col-sm-4 control-label">NIP Kepala Sekolah</label>
							<div class="col-sm-6">
								<input type="text" name="nipkepsek" id="nipkepsek" class="form-control" value="<?php echo $setting['nipkepsek']; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="alamat" class="col-sm-4 control-label">Alamat Jalan</label>
							<div class="col-sm-6">
								<input type="text" name="alamat" id="alamat" class="form-control" placeholder="Alamat Sekolah" value="<?php echo $setting['alamat']; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="kelurahan" class="col-sm-4 control-label">Kelurahan</label>
							<div class="col-sm-6">
								<input type="text" name="kelurahan" id="kelurahan" class="form-control" value="<?php echo $setting['kelurahan']; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="kecamatan" class="col-sm-4 control-label">Kecamatan</label>
							<div class="col-sm-6">
								<input type="text" name="kecamatan" id="kecamatan" class="form-control" value="<?php echo $setting['kecamatan']; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="kabupaten" class="col-sm-4 control-label">Kabupaten</label>
							<div class="col-sm-6">
								<input type="text" name="kabupaten" id="kabupaten" class="form-control" value="<?php echo $setting['kabupaten']; ?>" />
							</div>
						</div>						
						<div class="form-group">
							<label for="provinsi" class="col-sm-4 control-label">Provinsi</label>
							<div class="col-sm-6">
								<input type="text" name="provinsi" id="provinsi" class="form-control" value="<?php echo $setting['provinsi']; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="kodepos" class="col-sm-4 control-label">Kode Pos</label>
							<div class="col-sm-6">
								<input type="text" name="kodepos" id="kodepos" class="form-control" value="<?php echo $setting['kodepos']; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="telepon" class="col-sm-4 control-label">Telepon</label>
							<div class="col-sm-6">
								<input type="text" name="telepon" id="telepon" class="form-control" value="<?php echo $setting['telepon']; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-sm-4 control-label">Email</label>
							<div class="col-sm-6">
								<input type="text" name="email" id="email" class="form-control" value="<?php echo $setting['email']; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="website_name" class="col-sm-4 control-label">Website</label>
							<div class="col-sm-6">
								<input type="text" name="website_name" id="website_name" class="form-control" value="<?php echo $setting['website_name']; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="npsn" class="col-sm-4 control-label">NPSN</label>
							<div class="col-sm-6">
								<input type="text" name="npsn" id="npsn" class="form-control" value="<?php echo $setting['npsn']; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="nss" class="col-sm-4 control-label">NSS</label>
							<div class="col-sm-6">
								<input type="text" name="nss" id="nss" class="form-control" value="<?php echo $setting['nss']; ?>" />
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-6">
								<button type="submit" name="saveData" id="saveData" class="btn btn-danger"><i class="fa fa-save"></i> &nbsp;&nbsp;Submit</button>
							</div>
						</div>

					</form>

					<!--end tab-->
				</div>
				<div class="tab-pane" id="smsserver">

				</div>
			</div>
		</div>
	</div>
</div>
				

<script type="text/javascript">

    $(document).ready(function(){
        $("#saveData").click(function(){
            var namasekolah = $("#namasekolah").val();
            var alamat 		= $("#alamat").val();
            var namakepsek 	= $("#namakepsek").val();
			var nipkepsek 	= $("#nipkepsek").val();
			
			var email 		= $("#email").val();
			var telepon 	= $("#telepon").val();
			var kelurahan 	= $("#kelurahan").val();
			var kecamatan 	= $("#kecamatan").val();
			var kabupaten 	= $("#kabupaten").val();
			var provinsi 	= $("#provinsi").val();
			var kodepos 	= $("#kodepos").val();
			var npsn 		= $("#npsn").val();
			var nss 		= $("#nss").val();
			var website_name = $("#website_name").val();
			//var telepon 	= $("#telepon").val();
            //pass object in post
            var dataString = {'namasekolah': namasekolah , 'alamat': alamat , 'namakepsek': namakepsek , 'nipkepsek': nipkepsek , 'email': email , 'telepon': telepon,
							  'kelurahan': kelurahan, 'kecamatan': kecamatan, 'kabupaten': kabupaten, 'provinsi': provinsi, 'kodepos': kodepos,
							  'npsn': npsn, 'nss': nss, 'website_name': website_name};
            if(namasekolah==''||alamat==''||namakepsek==''||nipkepsek==''||email==''||telepon=='')
            {
                alertify.alert("<div class='text-red'><i class='fa fa-info'></i> Wajib diisi Nama Sekolah, Alamat, Email, Telepon, dan Nama & NIP Kepala Sekolah</div>");
            }
            else
            {
                // AJAX Code To Submit Form.
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('seting/lembaga/update'); ?>",
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
