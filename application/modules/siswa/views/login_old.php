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



				<div class="login-box">
					<div class="login-logo">Login <b>Siswa</b></div><!-- /.login-logo -->
					<div class="login-box-body">
						<p class="login-box-msg">
						silakan masuk
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
						</p>
						<form action="<?=base_url('siswa/login')?>" method="post">
							<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
							<div class="form-group has-feedback">
								<input type="text" name="username" class="form-control" placeholder="Username">
								<span class="glyphicon glyphicon-user form-control-feedback"></span>
							</div>
							<div class="form-group has-feedback">
								<input type="password" name="password" class="form-control" placeholder="Password">
								<span class="glyphicon glyphicon-lock form-control-feedback"></span>
							</div>
							<div class="row">
								<div class="col-md-6">
								<button type="submit" class="btn btn-primary btn-block btn-flat">Masuk Log</button>
								</div><!-- /.col -->
							</div>
						</form>

					</div><!-- /.login-box-body -->
				</div><!-- /.login-box -->
				
	</div>

    <!-- Bootstrap 3.3.5 -->
    <script src="<?=base_url('assets')?>/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="<?=base_url('assets')?>/plugins/fastclick/fastclick.min.js"></script>
    <!-- iCheck -->
    <script src="<?=base_url('assets')?>/plugins/iCheck/icheck.min.js"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>
</html>