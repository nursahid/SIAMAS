<?php $setting = $this->settings->get();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!--<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">-->
    <title><?=$setting['namasekolah'];?> | <?=$setting['website_slogan'];?></title>
	<meta name="description" content="<?=$setting['website_description'];?>">
	<meta name="keywords" content="<?=$setting['website_keywords'];?>">
	<meta name="author" content="Nur Sahid,<?=$setting['website_name'];?>">
	<link rel="shortcut icon" type="image/x-icon" href="<?=base_url('assets/uploads/image/').$setting['favicon'];?>">
	<?php //echo $this->layout->get_meta_tags() ?>
    <?php //echo $this->layout->get_title(); ?>
    <?php //echo $this->layout->get_favicon() ?>
    <?php echo $this->layout->get_schema() ?>
    <?php
        $path = [
            'assets/plugins/bootstrap/dist/css/bootstrap.min.css',
            'assets/plugins/AdminLTE/dist/css/AdminLTE.min.css',
            'assets/plugins/AdminLTE/dist/css/skins/skin-'.$this->config->item('skin').'.min.css',
            'assets/plugins/font-awesome/css/font-awesome.min.css',
            'assets/plugins/flag-icon-css/css/flag-icon.min.css',
			'assets/plugins/iCheck/skins/square/blue.css',
            'assets/plugins/alertify-js/build/css/alertify.min.css',
            'assets/plugins/alertify-js/build/css/themes/default.min.css',
			'assets/css/jssocials.css',
			'assets/css/jssocials-theme-flat.css',
			'assets/css/loading.css'
        ];
        if (isset($css_plugins)) {
            foreach ($css_plugins as $key => $value) {
                $path[] = $value;
            }
        }
        $path[] = 'assets/css/style.css';
    ?>
    <?php $this->layout->set_assets($path, 'styles') ?>
    <?php echo $this->layout->get_assets('styles') ?>
</head>
<body class="hold-transition skin-<?php echo $this->config->item('skin') ?> fixed layout-top-nav">
    <!-- Site wrapper -->
    <div class="wrapper"> 
        <header class="main-header">
            <nav class="navbar navbar-static-top" role="navigation">
                <div class="container">
                    <div class="navbar-header">

					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                      <?php $menus = $this->layout->get_menu('top menu'); ?>
                      <ul class="nav navbar-nav">
                        <?php foreach ($menus as $menu): ?>
                            <?php if (is_array($menu['children'])): ?>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-<?php echo $menu['icon'] ?>"></i> <?php echo $menu['label'] ?> <span class="caret"></span></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <?php foreach ($menu['children'] as $menu2): ?>
                                            <li><a href="<?php echo base_url($menu2['link']) ?>"><?php echo $menu2['label'] ?></a></li>
                                        <?php endforeach ?>
                                    </ul>
                                </li>
                            <?php else: ?>
                                <li><a href="<?php echo base_url($menu['link']) ?>"><i class="fa fa-<?php echo $menu['icon'] ?>"></i> <?php echo $menu['label'] ?></a></li>
                            <?php endif ?>
                        <?php endforeach ?>
                      </ul>
                    </div><!-- /.navbar-collapse -->

                    <!-- Navbar Right Menu -->
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <?php if (!$this->ion_auth->logged_in()): ?>
                                <li><a href="<?php echo site_url('login') ?>" title="Login"><i class="fa fa-sign-in fa-fw"></i> <?php echo lang('login') ?></a></li>
                                <!--<li><a href="<?php echo site_url('daftar-alumni') ?>" title="Sign Up">Registrasi Alumni</a></li>-->
                            <?php else: ?>
                                <li class="dropdown user user-menu">
                                    <?php $user = $this->ion_auth->user()->row() ?>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <?php if (filter_var($user->photo,FILTER_VALIDATE_URL)): ?>
                                            <img src="<?php echo $user->photo; ?>" class="user-image" alt="<?php echo $user->full_name ?>"/>
                                        <?php else: ?>
                                            <img src="<?php echo $user->photo == '' ? base_url('assets/img/default.png') : base_url('assets/uploads/image/'.$user->photo) ?>" class="user-image" alt="<?php echo $user->full_name ?>"/>
                                        <?php endif; ?>
                                        <span class="hidden-xs"><?php echo $user->username ?></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="user-header">
                                            <?php if (filter_var($user->photo,FILTER_VALIDATE_URL)): ?>
                                                <img src="<?php echo $user->photo; ?>" class="img-circle" alt="<?php echo $user->full_name ?>"/>
                                            <?php else: ?>
                                                <img src="<?php echo $user->photo == '' ? base_url('assets/img/default.png') : base_url('assets/uploads/image/'.$user->photo) ?>" class="img-circle" alt="<?php echo $user->full_name ?>"/>
                                            <?php endif; ?>
                                            <p>
                                              <?php echo $user->full_name ?>
                                              <small><?php echo lang('last_login') ?> <?php echo ' '.date('d/m/Y H:i', $user->last_login); ?></small>
                                            </p>
                                        </li>
                                        <li class="user-footer">
                                            <div class="pull-left">
                                                <a href="<?php echo  site_url('admin/profile')?>" class="btn btn-default btn-flat"><?php echo lang('profile') ?></a>
                                            </div>
                                            <div class="pull-right">
                                                <a href="<?php echo  site_url('logout')?>" class="btn btn-default btn-flat"><?php echo lang('logout') ?></a>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                            <?php endif ?>

                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Full Width Column -->
        <div class="content-wrapper">
		
			<!-- Header -->
			<div class="header">
				<div class="container">
					<div class="row">
						<div class="col-md-7 col-xs-12">
							<div class="header-logo">
								<a href=""><img src="<?=base_url('assets/uploads/image/').$setting['website_logo'];?>" width="100px" alt="<?=$setting['namasekolah'];?>" class="img-responsive"></a>
							</div>
							<div class="header-text">
								<h2 style="color: #ff0; font-weight: bold;"><?=$setting['namasekolah'];?></h2>
								<p><strong><?=$setting['alamat'];?></strong></p>
								<p><?=$setting['website_slogan'];?></p>
							</div>
						</div>
						<div class="col-md-5 col-xs-12">
							<ul class="social-network social-circle pull-right">
								<li><a href="https://www.facebook.com/<?=$setting['facebook'];?>" title="Facebook"><i class="fa fa-facebook"></i></a></li>
								<li><a href="https://twitter.com/<?=$setting['twitter'];?>" title="Twitter"><i class="fa fa-twitter"></i></a></li>
								<li><a href="https://google.com/+<?=$setting['gplus'];?>" title="Google +"><i class="fa fa-google-plus"></i></a></li>
								<li><a href="https://www.instagram.com/<?=$setting['instagram'];?>" title="Instagram"><i class="fa fa-instagram"></i></a></li>
								<li><a href="<?=base_url();?>feed"><i class="fa fa-rss"></i></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<!-- End Header -->
            <!-- Main content -->
            <section class="content exspan-bottom">
			<div class="container">
				<!-- Content -->
				<div class="row">
                <?php echo $this->layout->get_wrapper('page') ?>
				</div>
			</div>
            </section><!-- /.content -->
        </div><!-- ./wrapper -->
		
		<div class="copyright">
			<p>Copyright &copy; 2016 - <?php echo date('Y')?><a href=""> <?=$setting['namasekolah'];?></a> All rights reserved.</p>
		</div>
        <footer class="main-footer">
            <div class="container">            
                <div class="pull-right hidden-xs">
                    <b><a href="http://www.nursahid.com/siamas">SiAMAS</a> Version</b> <?php echo $this->config->item('version') ?>
                </div>
                <strong>Copyright &copy; <?php echo date('Y');?> <a href="http://<?=$setting['website_name'];?>"><?=$setting['namasekolah'];?></a>.</strong> All rights reserved.
            </div>
        </footer>
		
    </div>
    <?php
        $path = [
            'assets/plugins/jquery/dist/jquery.min.js',
            'assets/plugins/bootstrap/dist/js/bootstrap.min.js',
            'assets/plugins/AdminLTE/dist/js/app.min.js',
			'assets/plugins/iCheck/icheck.min.js',
            'assets/plugins/alertify-js/build/alertify.min.js',
			'assets/plugins/notify/notify.min.js',
        ];
        if (isset($js_plugins)) {
            foreach ($js_plugins as $key => $value) {
                $path[] = $value;
            }
        }
        $path[] = 'assets/js/a-design.js';
    ?>
    <?php $this->layout->set_assets($path, 'scripts') ?>
    <?php echo $this->layout->get_assets('scripts') ?>
	<script type="text/javascript">
	$(function () {
	  $('[title]').tooltip();
	  $('[data-toggle="tooltip"]').tooltip()
	})
	</script>
		<!-- Back to Top -->
		<a href="javascript:" id="return-to-top"><i class="fa fa-angle-double-up"></i></a>
	 	<script>
		  // Scroll Top
			$(window).scroll(function() {
				if ($(this).scrollTop() >= 50) {
					$('#return-to-top').fadeIn(200);
			 	} else {
					$('#return-to-top').fadeOut(200);
			 	}
			});
			$('#return-to-top').click(function() {
				$('body,html').animate({
					scrollTop : 0
			 	}, 500);
			});
		</script>
</body>
</html>