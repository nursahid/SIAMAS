<div class="col-xs-12 col-md-8">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="fa fa-edit"></i> PENDAFTARAN ALUMNI</h3>
		</div>
		<div class="panel-body">
			
			<div id="message" class="alert alert-success" style="display:none"><?php echo $this->session->userdata('message');?></div>
			<div class="alert alert-danger" style="display:none"></div>
			
			<form method="post" role="form" class="form-horizontal" id="contactForm" action="<?=base_url('daftar-alumni/post')?>" class="contact-form">
				<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
				<div class="form-group">
					<label for="nis" class="col-sm-4 control-label">NIS (No. Induk Siswa)</label>
					<div class="col-sm-8">
						<input type="text" class="form-control input-sm" id="nis" name="nis">
					</div>
				</div>
				<div class="form-group">
					<label for="nama" class="col-sm-4 control-label">Nama Lengkap <span style="color: red">*</span></label>
					<div class="col-sm-8">
						<input type="text" class="form-control input-sm" id="nama" name="nama">
					</div>
				</div>
				<div class="form-group">
					<label for="kelamin" class="col-sm-4 control-label">Jenis Kelamin <span style="color: red">*</span></label>
					<div class="col-sm-8">
						<input type="radio" name="kelamin" id="kelamin" value="L" checked /> Laki-laki &nbsp;&nbsp;&nbsp;
						<input type="radio" name="kelamin" id="kelamin" value="P" /> Perempuan
					</div>
				</div>
				<div class="form-group">
					<label for="tempat_lahir" class="col-sm-4 control-label">Tempat Lahir <span style="color: red">*</span></label>
					<div class="col-sm-8">
						<input type="text" class="form-control input-sm" id="tempat_lahir" name="tempat_lahir">
					</div>
				</div>
				<div class="form-group">
				<label for="tanggal" class="col-sm-4 control-label">Tanggal Lahir <span style="color: red">*</span></label>
					<div class="col-sm-8">
						<?=htmlDateSelector('tanggal')?>
					</div>
				</div>
				<div class="form-group">
					<label for="alamat" class="col-sm-4 control-label">Alamat <span style="color: red">*</span></label>
					<div class="col-sm-8">
						<textarea rows="5" class="form-control input-sm" id="alamat" name="alamat"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label for="email" class="col-sm-4 control-label">Email <span style="color: red">*</span></label>
					<div class="col-sm-8">
						<input type="text" class="form-control input-sm" id="email" name="email">
					</div>
				</div>
				<div class="form-group">
					<label for="tgldaftar" class="col-sm-4 control-label">Tanggal Daftar</label>
					<div class="col-sm-8">
						<?=htmlDateSelector('tgldaftar')?>
					</div>
				</div>
				<div class="form-group">
					<label for="angkatan" class="col-sm-4 control-label">Tahun Lulus <span style="color: red">*</span></label>
					<div class="col-sm-8">
						<input type="text" class="form-control input-sm" id="angkatan" name="angkatan" placeholder="<?=date('Y')?>">
					</div>
				</div>

				<div class="form-group">
					<label for="phone" class="col-sm-4 control-label">Telepon</label>
					<div class="col-sm-8">
						<input type="text" class="form-control input-sm" id="phone" name="phone">
					</div>
				</div>
				<div class="form-group">
					<label for="agama" class="col-sm-4 control-label">Agama</label>
					<div class="col-sm-8">
						<?php
						$dropdown_agama = $this->sistem_model->dropdown_agama();
						echo form_dropdown('agama', $dropdown_agama,'','id="agama" class="form-control"');
						?>
					</div>
				</div>
				<div class="form-group">
					<label for="file" class="col-sm-4 control-label">Foto</label>
					<div class="col-sm-8">
						<input type="file" id="file" name="file">
		    			<p class="help-block">Foto harus JPG dan ukuran file maksimal 1 Mb</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Kode Keamanan <span style="color: red">*</span></label>
					<div class="col-sm-8">
						<span id="image_captcha"><?php echo $captchaImg; ?> </span> &nbsp;&nbsp;
						<a href="javascript:void(0);" class="captcha-refresh" ><span class="glyphicon glyphicon-refresh"></span> Refresh</a> &nbsp;&nbsp;
						<input type="text" class="form-control input-sm" id="captcha" name="captcha">
					</div>
				</div>
								
				<div class="form-group">
		 			<div class="col-sm-offset-4 col-sm-8">
		   			<button type="button" id="btn-submit" class="btn btn-success"><i class="fa fa-send"></i> SUBMIT</button>
		 			</div>
				</div>
			</form>
		</div>
	</div>
</div>

			<!-- Sidebar -->
			<div class="col-xs-12 col-md-4">
				<?=$this->load->view('template/sidebar_nokepsek');?>
			</div>
			
<script type="text/javascript">
	$( document ).ready( function() {
		$('#birth_date').datepicker({
			format: 'yyyy-mm-dd',
			todayBtn: 'linked',
			minDate: '0001-01-01',
			setDate: new Date(),
			todayHighlight: true,
			autoclose: true
		});
	});
</script>
<script type="text/javascript">
// baseURL variable
var baseURL= "<?php echo base_url();?>"; 

$(document).ready(function() {
	
    $("#btn-submit").click(function(e) {
	//function simpan_alumni() {
        e.preventDefault();
        var nis 	= $("input[name='nis']").val();
		var nama 	= $("input[name='nama']").val();
		var kelamin	= $("input[name='kelamin']").val();
        var email 	= $("input[name='email']").val();
        var tempat_lahir= $("input[name='tempat_lahir']").val();
		
		var thn_tanggal= $("select[name='thn_tanggal']").val();
		var bln_tanggal= $("select[name='bln_tanggal']").val();
		var tgl_tanggal= $("select[name='tgl_tanggal']").val();
		
		var alamat  = $("textarea[name='alamat']").val();
		
		var thn_tgldaftar= $("select[name='thn_tgldaftar']").val();
		var bln_tgldaftar= $("select[name='bln_tgldaftar']").val();
		var tgl_tgldaftar= $("select[name='tgl_tgldaftar']").val();
		
		var angkatan = $("input[name='angkatan']").val();
		var phone = $("input[name='phone']").val();
		var agama = $("select[name='agama']").val();
		var file = $("input[name='file']").val();
		var captcha = $("input[name='captcha']").val();
		
        console.log($(this).closest('form').attr('action'));
        $.ajax({
            url		: $(this).closest('form').attr('action'),
            type	: $(this).closest('form').attr('method'),
            dataType: "json",
            data	: {nis:nis, nama:nama, kelamin:kelamin, email:email, tempat_lahir:tempat_lahir, 
					   thn_tanggal:thn_tanggal, bln_tanggal:bln_tanggal, tgl_tanggal:tgl_tanggal, 
					   alamat:alamat, thn_tgldaftar:thn_tgldaftar, bln_tgldaftar:bln_tgldaftar, tgl_tgldaftar:tgl_tgldaftar, 
					   angkatan:angkatan, phone:phone, agama:agama, file:file, captcha:captcha},
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