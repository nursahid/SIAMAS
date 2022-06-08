    $(document).ready(function () {
		$('#answer_essay_field_box').hide();
		
        $('#field-test_type').change(function() {
			
			if(this.value == 'essay') {
				$('#answer_essay_field_box').show('fast');
				$('#answer1_field_box').hide('fast');
				$('#answer2_field_box').hide('fast');
				$('#answer3_field_box').hide('fast');
				$('#answer4_field_box').hide('fast');
				$('#answer5_field_box').hide('fast');
				$('#answer_field_box').hide('fast');
			} else {
				$('#answer_essay_field_box').hide('fast');
				$('#answer1_field_box').show('fast');
				$('#answer2_field_box').show('fast');
				$('#answer3_field_box').show('fast');
				$('#answer4_field_box').show('fast');
				$('#answer5_field_box').show('fast');
				$('#answer_field_box').show('fast');
			}			

        });
		
		
    });