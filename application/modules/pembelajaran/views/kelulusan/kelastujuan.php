<script>
$(function(){
	$("#self_loading").ajaxStart(function(){
		$(this).show();
	});
	$("#self_loading").ajaxStop(function(){
		$(this).hide();
	});
	
	$('#mp_id').change(function() {		
		var selectValues = $("#mp_id").val();
		
		//if (selectValues == 0){				
		//	alert('Silakan pilih kelas terlebih dahulu');
		//	return false;
		//}else{				
			$('#renderkelasakhir').load('<?=base_url()?>kelulusan/loadkelasakhir/' + $('#mp_id').val() + '/' + $('#tahunajaranakhir').val());
		//}		
	});	
});
</script>
<?php
if ($result) {
	echo form_dropdown("kelasakhir",$result,'',"class='form-control' id='mp_id'");
}
else {
	echo 'Siswa Untuk Angkatan sekarang belum dibuat.';
}