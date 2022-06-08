// Added by CI Bootstrap 3
$(function(){
	// Reference: http://alex-d.github.io/Trumbowyg/documentation.html
	var options = {
		resetCss: true,
		btnsDef: {
			// Customizables dropdowns
			image: {
				dropdown: ['insertImage', 'upload', 'base64', 'noEmbed'],
				ico: 'insertImage'
			}
		},
		btns: [
			['viewHTML'],
			['formatting'],
			'btnGrp-design',
			['superscript', 'subscript'],
			['link'],
			['image'],
			'btnGrp-justify',
			'btnGrp-lists',
			['removeformat'],
			['fullscreen']
		],
		plugins: {
		}
	};

	/* Default btns:
		btns: [
			['viewHTML'],
			['formatting'],
			'btnGrp-semantic',
			['superscript', 'subscript'],
			['link'],
			['insertImage'],
			'btnGrp-justify',
			'btnGrp-lists',
			['horizontalRule'],
			['removeformat'],
			['fullscreen']
		],
	*/

	//$('textarea.texteditor').trumbowyg(options);
	//$('textarea.mini-texteditor').trumbowyg(options);
	
	$('textarea.texteditor').trumbowyg({
    btns: [
        ['viewHTML'],
        ['undo', 'redo'], // Only supported in Blink browsers
        ['formatting'],
        ['strong', 'em', 'del'],
        ['superscript', 'subscript'],
        ['link'],
        ['insertImage'],
        ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
        ['unorderedList', 'orderedList'],
        ['horizontalRule'],
        ['removeformat'],
        ['fullscreen']
    ]
});

});