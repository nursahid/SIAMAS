	
    $(document).ready(function () {
		
		$('#nama_sutri_field_box').show();
		$('#pekerjaan_sutri_field_box').show();
		
        $('#field-status_kawin').change(function () {

			if(this.value == 'Belum Kawin' || this.value == 'Janda/Duda') {
				$('#nama_sutri_field_box').hide('fast');
				$('#pekerjaan_sutri_field_box').hide('fast');

			} else {
				$('#nama_sutri_field_box').show('fast');
				$('#pekerjaan_sutri_field_box').show('fast');
			
			}
        });

    });
jQuery(function($) {
    $('#field-file_name').slugify('#field-title'); // Type as you slug
});