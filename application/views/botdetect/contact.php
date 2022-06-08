<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>CodeIgniter Form Validation BotDetect CAPTCHA Example</title>
	<link type="text/css" rel="Stylesheet" href="<?php echo CaptchaUrls::LayoutStylesheetUrl() ?>" />
	<link type="text/css" rel="Stylesheet" href="<?php echo base_url(); ?>css/style.css" />
</head>
<body>

<div id="container">
	<h1>CodeIgniter Form Validation BotDetect CAPTCHA Example</h1>

	<div id="body">

    <?php if ($emailSent) { ?>
    
		<h2>Thank you!</h2>
		<p>Your message was sent successfully.</p>
		<p><?php echo anchor('contact', 'Try it again!'); ?></p>
      
    <?php } else { ?>
    
		<?php echo form_open('contact'); ?>
      
		<h5>Your email</h5>
		<?php echo form_input(
			array(
				'name'        => 'Email',
				'id'          => 'Email',
				'value'       => set_value('Email'),
				'maxlength'   => '100',
				'size'        => '35',
				'style'		  => 'height: 25px'
			)
		);?>
		<?php echo form_error('Email'); ?>
      
		<h5>Message</h5>
		<div>
		<?php echo form_textarea(
			array(
				'name'        => 'Message',
				'id'          => 'Message',
				'value'       => set_value('Message'),
				'maxlength'   => '100',
				'size'        => '35',
				'style'		  => 'width: 271px'
			)
		);?>
		</div>
		<?php echo form_error('Message'); ?>
      
		<?php // Captcha is only shown if not already solved
		if (!$captchaSolved) { ?>
			<h5>Captcha</h5>
			<div>
			<?php echo $captchaHtml; ?>
			<?php echo form_input(
				array(
					'name'        => 'CaptchaCode',
					'id'          => 'CaptchaCode',
					'value'       => '',
					'maxlength'   => '100',
					'size'        => '35',
					'style'		  => 'height: 25px'
				)
			);?>
			</div>
			<?php echo form_error('CaptchaCode'); ?>
		<?php }; // end if(!$captchaSolved) ?>
      		<br>
			<div><?php echo form_submit('submit', 'Submit'); ?></div>
      
		</form>
	<?php }; // end if($emailSent) ?>
	</div>
</div>

<div id="exampleNavigation">
	<h1>BotDetect Captcha CodeIgniter Examples</h1>
	<ul>
		<li><?php echo anchor('example', 'Basic CAPTCHA Example'); ?></li>
		<li><?php echo anchor('contact', 'Form Validation CAPTCHA Example'); ?></li>
	</ul>
</div>

</body>
</html>