<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
    <?php echo $this->layout->get_meta_tags() ?>
    <?php echo $this->layout->get_title() ?>
    <?php echo $this->layout->get_favicon() ?>
    <?php echo $this->layout->get_schema() ?>
    <?php
        $path = [
            'assets/plugins/bootstrap/dist/css/bootstrap.min.css',
            'assets/plugins/AdminLTE/dist/css/AdminLTE.min.css',
            'assets/plugins/AdminLTE/dist/css/skins/skin-'.$this->config->item('skin').'.min.css',
            'assets/plugins/font-awesome/css/font-awesome.min.css',
            'assets/plugins/alertify-js/build/css/alertify.min.css',
            'assets/plugins/alertify-js/build/css/themes/default.min.css',
            'assets/plugins/iCheck/skins/square/blue.css',
            'assets/plugins/flag-icon-css/css/flag-icon.min.css',
			'assets/plugins/toastr/toastr.min.css',
			'assets/plugins/datepicker/datepicker3.css'
        ];
        if (isset($grocery_css)) {
            foreach ($grocery_css as $key => $value) {
                $path[] = $value;
            }
        }
        if (isset($css_plugins)) {
            foreach ($css_plugins as $key => $value) {
                $path[] = $value;
            }
        }
        $path[] = 'assets/css/a-design.css';
    ?>
    <?php $this->layout->set_assets($path, 'styles') ?>
    <?php echo $this->layout->get_assets('styles') ?>
	
	
</head>
<body class="hold-transition skin-<?php echo $this->config->item('skin') ?> fixed">
    <!-- Site wrapper -->
    <div class="wrapper">  
        <header class="main-header">
            <a href="<?php echo site_url('siswa/home'); ?>" class="logo">
                <span class="logo-mini"><b>Si</b>Mas</span>
                <span class="logo-lg"><img src="<?=base_url('assets/img/logo/logo_small.png');?>" alt="SiMAS" height="35" /></span>
            </a>

             <nav class="navbar navbar-static-top" role="navigation">
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown user user-menu" >
                            <?php 
							$userdata = $this->members_model->get($this->auth->userid());
							if($userdata->kelamin=='L'){$foto=base_url('assets/img/default.png');}elseif($userdata->kelamin=='P'){$foto=base_url('assets/img/default_female.png');}
							?>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <?php if (filter_var($userdata->foto,FILTER_VALIDATE_URL)): ?>
                                    <img src="<?php echo $userdata->foto; ?>" class="user-image" alt="<?php echo $userdata->nama_lengkap ?>"/>
                                <?php else: ?>
                                    <img src="<?php echo $userdata->foto == NULL ? $foto : base_url('assets/uploads/siswa/'.$userdata->foto) ?>" class="user-image" alt="<?php echo $userdata->nama ?>"/>
                                <?php endif; ?>
                                <span class="hidden-xs"><?php echo $userdata->nama ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-header">
                                    <?php if (filter_var($userdata->foto,FILTER_VALIDATE_URL)): ?>
                                        <img src="<?php echo $userdata->foto; ?>" class="img-circle" alt="<?php echo $userdata->nama ?>"/>
                                    <?php else: ?>
                                        <img src="<?php echo $userdata->foto == NULL ? $foto : base_url('assets/uploads/siswa/'.$userdata->foto) ?>" class="img-circle" alt="<?php echo $userdata->nama ?>"/>
                                    <?php endif; ?>
                                    <p>
                                      <?php echo $userdata->nama ?>
                                      <small><?php echo lang('last_login') ?> <?php echo ' '.date('d/m/Y H:i', $userdata->last_login); ?></small>
                                  </p>
                                </li>
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="<?php echo  site_url('siswa/profile')?>" class="btn btn-default btn-flat"><?php echo lang('profile') ?></a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="<?php echo  site_url('siswa/logout')?>" class="btn btn-default btn-flat"><?php echo lang('logout') ?></a>
                                    </div>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </div>
            </nav>
        </header>

        <aside class="main-sidebar">
            <section class="sidebar" id="menuSidebar">
                <form action="#" method="get" class="sidebar-form">
                    <div class="input-group">
                        <input type="text" class="form-control searchlist" id="searchSidebar" placeholder="Search..." autocomplete="off"/>
                        <span class="input-group-btn">
                            <button type='button' name='seach' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </form>
                <ul class="sidebar-menu list" id="menuList">
                </ul>
				
                <ul class="sidebar-menu list" id="menuSub">
					<li class="header">MAIN NAVIGATION</li>
					<li class="active treeview">
					  <a href="<?=base_url('siswa/home');?>">
						<i class="fa fa-home"></i> <span>Dashboard</span>
					  </a>
					</li>
					<li class="treeview">
					  <a href="<?=base_url('siswa/profile');?>">
						<i class="fa fa-user"></i><span>Profil</span></i>
					  </a>
					</li>
					<li class="treeview">
					  <a href="<?=base_url('siswa/nilai');?>">
						<i class="fa fa-cogs"></i><span>Perolehan Nilai</span></i>
					  </a>
					</li>
					<li class="treeview">
					  <a href="<?=base_url('siswa/absensi');?>">
						<i class="fa fa-money"></i><span>Absensi</span></i>
					  </a>
					</li>
					<!--<li class="treeview">
					  <a href="#">
						<i class="fa fa-user"></i><span>Profil</span><i class="fa fa-angle-left pull-right"></i>
					  </a>
					  <ul class="treeview-menu">
						<li><a href="<?=base_url('siswa/profilupdate');?>"><i class="fa fa-user"></i> Update Profil</a></li>
						<li><a href="<?=base_url('siswa/gantipassword');?>"><i class="fa fa-circle-o"></i> Ganti Password</a></li>
						<li><a href="<?=base_url('siswa/uploadfoto');?>"><i class="fa fa-circle-o"></i> Upload Foto</a></li>
					 </ul>
					</li>
					<li>
					  <a href="#">
						<i class="fa fa-money"></i><span>Keuangan</span></i>
					  </a>
					</li>
					<li class="treeview">
					  <a href="<?=base_url('siswa/nilai');?>">
						<i class="fa fa-laptop"></i><span>Nilai</span></i>
					  </a>
					</li>
					<li class="treeview">
					  <a href="<?=base_url('siswa/absensi');?>">
						<i class="fa fa-edit"></i><span>Absensi</span></i>
					  </a>
					</li>
					<li class="treeview">
					  <a href="#">
						<i class="fa  fa-line-chart "></i> <span>Ujian</span> <i class="fa fa-angle-left pull-right"></i>
					  </a>
					  <ul class="treeview-menu">
						<li><a href="<?=base_url('siswa/ujian');?>"><i class="fa fa-circle-o"></i> Mulai Ujian</a></li>
						<li><a href="<?=base_url('siswa/ujian/report');?>"><i class="fa fa-circle-o"></i> Laporan Hasil Ujian</a></li>
					  </ul>
					</li>-->
					<li><a href="<?=base_url('siswa/logout');?>"><i class="fa fa-power-off"></i> <span>Keluar</span></a></li>
                </ul>
				
            </section>
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1><?php echo $title ?></h1>
                <?php $this->layout->breadcrumb($crumb) ?>
            </section>
            <!-- Main content -->
            <section class="content exspan-bottom">
                <?php echo $this->layout->get_wrapper('page') ?>
            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> <?php echo $this->config->item('version') ?>
            </div>
            <strong>Copyright &copy; <?php echo date('Y') ?> <a href="http://www.nursahid.com">NurSahid.com</a>.</strong> All rights reserved.
        </footer>
    </div><!-- ./wrapper -->
    <?php
        $baseJs = ['assets/plugins/jquery/dist/jquery.min.js'];
        if (isset($grocery_js)) {
            foreach ($grocery_js as $key => $value) {
                $baseJs[] = $value;
            }
        }
        $path = [
            'assets/plugins/bootstrap/dist/js/bootstrap.min.js',
            'assets/plugins/AdminLTE/dist/js/app.min.js',
            'assets/plugins/alertify-js/build/alertify.min.js',
            'assets/plugins/slimScroll/jquery.slimscroll.min.js',
            'assets/plugins/list.js/dist/list.min.js',
            'assets/plugins/iCheck/icheck.min.js',
			'assets/plugins/toastr/toastr.min.js',
			'assets/plugins/datepicker/bootstrap-datepicker.js'
        ];
        $path = array_merge($baseJs, $path);
        if (isset($js_plugins)) {
            foreach ($js_plugins as $key => $value) {
                $path[] = $value;
            }
        }
        $path[] = 'assets/js/a-design.js';
    ?>
    <?php $this->layout->set_assets($path, 'scripts') ?>
    <?php echo $this->layout->get_assets('scripts') ?>

<script>   
    $('#notifications').slideDown('slow').delay(3000).slideUp('slow');
</script>	
	</body>
</html>