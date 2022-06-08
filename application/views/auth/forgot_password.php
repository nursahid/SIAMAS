<div class="login-box">
  <div class="login-logo">
    <img src="<?php echo base_url($this->layout->get_logo()) ?>" height="100px">
    <br>
    <a href="<?php echo site_url() ?>"><?php echo $this->layout->get_title('true') ?></a>
  </div><!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg"><?php echo lang('forgot_password_heading') ?></p>
    <?php echo $message;?>
    <?php echo form_open('forgot_password'); ?>
      <div class="form-group has-feedback">
        <label><?php echo lang('login_identity_label') ?></label>
        <input type="email" name="identity" class="form-control" placeholder="<?php echo lang('login_identity_label') ?>" required="required" autofocus />
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-4 col-xs-offset-8">
          <button type="submit" class="btn btn-primary btn-block btn-flat" id="btnLoading"><?php echo lang('forgot_password_submit_btn') ?></button>
        </div><!-- /.col -->
      </div>
    <?php echo form_close(); ?>
  </div><!-- /.login-box-body -->
</div><!-- /.login-box -->