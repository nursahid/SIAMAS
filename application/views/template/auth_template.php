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
            'assets/plugins/AdminLTE/dist/css/skins/skin-red.min.css',
            'assets/plugins/font-awesome/css/font-awesome.min.css',
            'assets/plugins/alertify-js/build/css/alertify.min.css',
            'assets/plugins/alertify-js/build/css/themes/default.min.css',
            'assets/plugins/iCheck/skins/square/blue.css'
        ];
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
<body class="login-page">
    <!-- Page Content -->
    <?php echo $this->layout->get_wrapper('page') ?>
    <!-- /#page-wrapper -->
    <?php
        $path = [
            'assets/plugins/jquery/dist/jquery.min.js',
            'assets/plugins/bootstrap/dist/js/bootstrap.min.js',
            'assets/plugins/iCheck/icheck.min.js',
            'assets/plugins/alertify-js/build/alertify.min.js',
            'assets/plugins/bootstrap-show-password/bootstrap-show-password.min.js'
        ];
        if (isset($js_plugins)) {
            foreach ($js_plugins as $key => $value) {
                $path[] = $value;
            }
        }
    ?>
    <?php $this->layout->set_assets($path, 'scripts') ?>
    <?php echo $this->layout->get_assets('scripts') ?>
</body>
</html>