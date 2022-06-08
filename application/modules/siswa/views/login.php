<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Selamat Datang Members</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link href="<?=base_url('assets')?>/plugins/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- font Awesome -->
    <link href="<?=base_url('assets')?>/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?=base_url('assets')?>/plugins/AdminLTE/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
	<!-- iCheck -->
    <link rel="stylesheet" href="<?=base_url('assets')?>/plugins/iCheck/square/blue.css">
	
    <!-- jQuery 2.0.2 -->
    <script src="<?=base_url('assets')?>/plugins/jquery/dist/jquery.min.js" type="text/javascript"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="<?=base_url('assets')?>/js/html5shiv.min.js"></script>
        <script src="<?=base_url('assets')?>/js/respond.min.js"></script>
    <![endif]-->

</head>
<body class="hold-transition login-page">
	<div class="container">


<style type="text/css">
/*    --------------------------------------------------
	:: Login Section
	-------------------------------------------------- */
#login {
    padding-top: 50px
}
#login .form-wrap {
    width: 80%;
    margin: 0 auto;
}
#login h1 {
    color: #1fa67b;
    font-size: 18px;
    text-align: center;
    font-weight: bold;
    padding-bottom: 20px;
}
#login .form-group {
    margin-bottom: 25px;
}
#login .checkbox {
    margin-bottom: 20px;
    position: relative;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    -o-user-select: none;
    user-select: none;
}
#login .checkbox.show:before {
    content: '\e013';
    color: #1fa67b;
    font-size: 17px;
    margin: 1px 0 0 3px;
    position: absolute;
    pointer-events: none;
    font-family: 'Glyphicons Halflings';
}
#login .checkbox .character-checkbox {
    width: 25px;
    height: 25px;
    cursor: pointer;
    border-radius: 3px;
    border: 1px solid #ccc;
    vertical-align: middle;
    display: inline-block;
}
#login .checkbox .label {
    color: #6d6d6d;
    font-size: 13px;
    font-weight: normal;
}
#login .btn.btn-custom {
    font-size: 14px;
	margin-bottom: 20px;
}
#login .forget {
    font-size: 13px;
	text-align: center;
	display: block;
}

/*    --------------------------------------------------
	:: Inputs & Buttons
	-------------------------------------------------- */
.form-control {
    color: #212121;
}
.btn-custom {
    color: #fff;
	background-color: #1fa67b;
}
.btn-custom:hover,
.btn-custom:focus {
    color: #fff;
}

/*    --------------------------------------------------
    :: Footer
	-------------------------------------------------- */
#footer {
    color: #6d6d6d;
    font-size: 12px;
    text-align: center;
}
#footer p {
    margin-bottom: 0;
}
#footer a {
    color: inherit;
}
</style>


<div id="login">
    <div class="container">
    	<div class="row">
    	    <div class="col-md-12">
        	    <div class="form-wrap">
                <h1>Masuk dengan Username Anda</h1>
							<?php if(isset($error)): ?>
							<div style="color: red">
								<?php echo $error; ?>
							</div>
							<?php endif; ?>
							<?php
							if(!empty($success_msg)){
								echo '<p class="statusMsg">'.$success_msg.'</p>';
							} elseif(!empty($error_msg)){
								echo '<p class="statusMsg">'.$error_msg.'</p>';
							}
							?>
                    <form role="form" action="<?=base_url('siswa/login')?>" method="post" id="login-form" autocomplete="off">
						<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                        <div class="form-group">
                            <label for="username" class="sr-only">Username</label>
                            <input type="username" name="username" id="username" class="form-control" placeholder="Username">
                        </div>
                        <div class="form-group">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" name="password" id="key" class="form-control" placeholder="Password">
                        </div>
                        <div class="checkbox">
                            <span class="character-checkbox" onclick="showPassword()"></span>
                            <span class="label">Tampilkan password</span>
                        </div>
                        <input type="submit" id="btn-login" class="btn btn-danger btn-lg btn-block" value="Masuk">
                    </form>
					<br />
                    <a href="javascript:;" class="forget" data-toggle="modal" data-target=".forget-modal">Lupa password?</a>
                    <hr>
        	    </div>
    		</div> <!-- /.col-xs-12 -->
    	</div> <!-- /.row -->
    </div> <!-- /.container -->
</div>

<div class="modal fade forget-modal" tabindex="-1" role="dialog" aria-labelledby="myForgetModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">×</span>
					<span class="sr-only">Tutup</span>
				</button>
				<h4 class="modal-title">Kirim password</h4>
			</div>
			<div class="modal-body">
				<p>Masukkan Email saat mendaftar</p>
				<input type="email" name="recovery-email" id="recovery-email" class="form-control" autocomplete="off">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
				<button type="button" class="btn btn-custom">Kirim Password</button>
			</div>
		</div> <!-- /.modal-content -->
	</div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->

<script type="text/javascript">
function showPassword() {
    
    var key_attr = $('#key').attr('type');
    
    if(key_attr != 'text') {
        
        $('.checkbox').addClass('show');
        $('#key').attr('type', 'text');
        
    } else {
        
        $('.checkbox').removeClass('show');
        $('#key').attr('type', 'password');
        
    }
    
}
</script>

    <!-- Bootstrap 3.3.5 -->
    <script src="<?=base_url('assets')?>/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="<?=base_url('assets')?>/plugins/fastclick/fastclick.min.js"></script>

  </body>
</html>