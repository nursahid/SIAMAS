(function() {
	var tinymce_path = default_texteditor_path+'/tinymce/';
	var tinymce_options = {
		//lokasi TinyMCE
		script_url : tinymce_path +"tinymce.min.js",
		// General options
		selector: 'textarea',
		height: 500,
		theme: 'modern',
		skin: 'lightgray',
		language:'id',
		branding: false,
		plugins: 'print preview fullpage powerpaste searchreplace autolink directionality advcode visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount tinymcespellchecker a11ychecker imagetools mediaembed  linkchecker contextmenu colorpicker textpattern help',
		toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
		theme_advanced_buttons2 : "ardate,hijri,arnumber,arkeyboard,enterms,arstandard",
		image_advtab: true,
		//templates: [
		//	{ title: 'Test template 1', content: 'Test 1' },
		//	{ title: 'Test template 2', content: 'Test 2' }
		//],
		content_css: [
			'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
			'//www.tinymce.com/css/codepen.min.css'
		]		
	};
	tinymce.init(tinymce_options);
});