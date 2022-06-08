<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>CodeIgniter Basic BotDetect CAPTCHA Example</title>
	<link type="text/css" rel="Stylesheet" href="<?php echo CaptchaUrls::LayoutStylesheetUrl() ?>" />
	<link type="text/css" rel="Stylesheet" href="<?php echo base_url(); ?>css/style.css" />
</head>
<body>

<div id="container">
	<h1>CodeIgniter Basic BotDetect CAPTCHA Example</h1>

	<div id="body">
		<?php echo form_open('example'); ?>
    
		<label for="CaptchaCode">Please retype the characters from the image:</label>
		<?php echo $captchaHtml; ?>
		<input type="text" name="CaptchaCode" id="CaptchaCode" value="" size="35" style="height: 25px" />
    	<br><br>
		<div><input type="submit" value="Submit" /></div>
		<?php echo $captchaValidationMessage; ?>

		</form>
	</div>
</div>

<div id="exampleNavigation">
	<h1>BotDetect CAPTCHA CodeIgniter Examples</h1>
	<ul>
		<li><?php echo anchor('example', 'Basic CAPTCHA Example'); ?></li>
		<li><?php echo anchor('contact', 'Form Validation CAPTCHA Example'); ?></li>
	</ul>
</div>

</body>
</html>