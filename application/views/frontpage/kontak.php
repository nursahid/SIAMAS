<style type="text/css">
.contact-form{ margin-top:15px;}
.contact-form .textarea{ min-height:220px; resize:none;}
.form-control{ box-shadow:none; border-color:#eee; height:49px;}
.form-control:focus{ box-shadow:none; border-color:#00b09c;}
.form-control-feedback{ line-height:50px;}
.main-btn{ background:#00b09c; border-color:#00b09c; color:#fff;}
.main-btn:hover{ background:#00a491;color:#fff;}
.form-control-feedback {
line-height: 50px;
top: 0px;
}
</style>
	<div class="col-xs-12 col-md-8">
		<div class="row">
			<div id="message" class="alert alert-success" style="display:none"><?php echo $this->session->userdata('message');?></div>
			<div class="alert alert-danger" style="display:none"></div>
			<form method="post" role="form" id="contactForm" action="<?=base_url('ajax-kontak/post')?>" class="contact-form">
				<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<input type="text" class="form-control" name="nama" id="nama" autocomplete="off" placeholder="Nama Lengkap">
						</div>
						<div id="nama_error" class="text-red"></div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<input type="email" class="form-control" name="email" id="email" autocomplete="off" placeholder="E-mail">
						</div>
						<div id="email_error" class="text-red"></div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<input type="text" class="form-control" name="subjek" id="subjek" autocomplete="off" placeholder="Subjek Pesan">
						</div>
						<div id="subjek_error" class="text-red"></div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<textarea class="form-control textarea" rows="3" name="pesan" id="pesan" placeholder="Pesan Anda ...."></textarea>
						</div>
						<div id="pesan_error" class="text-red"></div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<div> <span id="image_captcha"><?php echo $captchaImg; ?> </span> &nbsp;&nbsp;
								<a href="javascript:void(0);" class="captcha-refresh" ><span class="glyphicon glyphicon-refresh"></span> Refresh</a>
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<input type="text" class="form-control input-sm" name="captcha" id="captcha" value="">
						</div>
					</div>
					<div class="col-md-6">
						<button type="submit" id="btn-submit" class="btn btn-lg main-btn pull-right">Kirim Pesan</button>
					</div>
				</div>
	 
			</form>
		</div>
	</div>						
<script type="text/javascript">
// baseURL variable
var baseURL= "<?php echo base_url();?>"; 

$(document).ready(function() {
	
    $("#btn-submit").click(function(e) {
        e.preventDefault();
        var nama 	= $("input[name='nama']").val();
        var email 	= $("input[name='email']").val();
		var subjek 	= $("input[name='subjek']").val();
        var pesan 	= $("textarea[name='pesan']").val();
		var captcha = $("input[name='captcha']").val();
		
        console.log($(this).closest('form').attr('action'));
        $.ajax({
            url		: $(this).closest('form').attr('action'),
            type	: $(this).closest('form').attr('method'),
            dataType: "json",
            data	: {nama:nama, email:email, subjek:subjek, pesan:pesan, captcha:captcha},
            success	: function(data) {
                if($.isEmptyObject(data.error)){
                    $(".alert-danger").css('display','none');
                    //alertify.alert(data.success);
					$(".alert-success").css('display','block');
					$('#message').html(data.success);
                }
				else {
                    $(".alert-danger").css('display','block');
                    $(".alert-danger").html(data.error);
                }
              }
		});


    });
	
    $('.captcha-refresh').on('click', function(){ 
        $.get('<?php echo base_url().'kontak/refresh'; ?>', function(data){ 
            $('#image_captcha').html(data); 
        }); 
    });
	

	function checkEmail() {
		//<input type='submit' name='submit' onclick='Javascript:checkEmail();'/>
		var email = document.getElementById('email');
		var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

		if (!filter.test(email.value)) {
			//alert('Masukkan alamat email yang valid');
			$('#message').html('Masukkan alamat email yang valid');
			email.focus;
			return false;
		}
	}
	
});
</script>