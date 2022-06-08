<div class="login-box">
  <div class="login-logo">
    <img src="<?php echo base_url($this->layout->get_logo()) ?>" height="100px">
    <br>
    <a href="<?php echo site_url() ?>"><?php echo $this->layout->get_title('true') ?></a>
  </div><!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg"><?php echo lang('login_desc') ?></p>
    <?php echo $message;?>
    <?php echo form_open('login','','id="loginForm"'); ?>
    <div class="form-group has-feedback">
      <label><?php echo lang('login_identity_label') ?></label>
      <input type="email" name="identity" class="form-control" placeholder="<?php echo lang('login_identity_label') ?>" required="required" autofocus />
      <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    </div>
    <div class="form-group has-feedback">
      <label><?php echo lang('login_password_label') ?></label>
      <input type="password" id="password" data-toggle="password" name="password" class="form-control" placeholder="<?php echo lang('login_password_label') ?>" required="required" />
    </div>
    <?php if ($features['google_recaptcha']): ?>
      <div class="row">
        <div class="col-xs-12">
          <input type="hidden" name="google_rechapatcha" id="googleRechapatcha" value=""/>
          <div class="g-recaptcha" data-sitekey="<?php echo $google_recaptcha['site_key'] ?>"></div>    
          <br/>
        </div><!-- /.col -->
      </div>
    <?php endif ?>
    <div class="row">
      <div class="col-xs-8">
        <div class="checkbox icheck">
          <label>
            <input name="remember" type="checkbox" value="1"> <?php echo lang('login_remember_label') ?>
          </label>
        </div>                    
      </div><!-- /.col -->
      <div class="col-xs-4">
        <input type="submit" class="btn btn-primary btn-block btn-flat" id="loginBtn" value="<?php echo lang('login_submit_btn') ?>" />
      </div><!-- /.col -->
    </div>
    <div class="social-auth-links text-center">
      <?php if ($features['disable_all_social_logins'] == false): ?>
        <p>- <?php echo lang('or') ?> -</p>
      <?php endif ?>
      <?php if ($features['disable_all_social_logins'] == false): ?>
        <?php if ($features['login_via_facebook']): ?>
          <a href="<?php echo $login_url; ?>" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> <?php echo lang('signin_using') ?> Facebook</a>
        <?php endif ?>
        <?php if ($features['login_via_google']): ?>
          <a href="<?php echo $googlelogin; ?>" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google"></i> <?php echo lang('signin_using') ?>  Google+</a>
        <?php endif ?>
        <?php if ($features['login_via_twitter']): ?>
          <a href="<?php echo $twitter; ?>" class="btn btn-block btn-social btn-twitter btn-flat"><i class="fa fa-twitter"></i> <?php echo lang('signin_using') ?>  Twitter</a>
        <?php endif ?>
        <?php if ($features['login_via_linkedin']): ?>
          <a href="<?php echo base_url('myigniter/link')?>" class="btn btn-block btn-social btn-linkedin btn-flat"><i class="fa fa-linkedin"></i> <?php echo lang('signin_using') ?>  LinkedIn</a>
        <?php endif ?>
      <?php endif ?>
    </div>
    <?php echo form_close(); ?>
    <a href="<?php echo site_url('forgot-password') ?>" title="Sign Up"><?php echo lang('login_forgot_password') ?></a><br>
    <a href="<?php echo site_url('register') ?>" title="Sign Up"><?php echo lang('register_new') ?></a>
  </div><!-- /.login-box-body -->
  <br>
  <div class="callout callout-info">
    <h4>Demo Login</h4>
    <p>Email : <strong>admin@admin.com</strong><br>Password : <strong>password</strong></p>
  </div>
</div><!-- /.login-box -->
