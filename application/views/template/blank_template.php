<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <?php echo $this->layout->get_meta_tags() ?>
    <?php echo $this->layout->get_title() ?>
    <?php echo $this->layout->get_favicon() ?>
    <?php echo $this->layout->get_schema() ?>
    <?php
        $path = [
            'assets/plugins/font-awesome/css/font-awesome.min.css',
            'assets/plugins/flag-icon-css/css/flag-icon.min.css',
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
        $path[] = 'assets/css/front.css';
    ?>
    <?php $this->layout->set_assets($path, 'styles') ?>
    <?php echo $this->layout->get_assets('styles') ?>
</head>
<body>

        <!-- Full Width Column -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content exspan-bottom">
                <?php echo $this->layout->get_wrapper('page') ?>
            </section><!-- /.content -->
        </div><!-- ./wrapper -->

    </div>
    <?php
        $baseJs = ['assets/plugins/jquery/dist/jquery.min.js'];
        if (isset($grocery_js)) {
            foreach ($grocery_js as $key => $value) {
                $baseJs[] = $value;
            }
        }
        $path = [
            'assets/plugins/jquery/dist/jquery.min.js',
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
</body>
</html>