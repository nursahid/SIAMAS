<script type="text/javascript">
$(function(){
	$("#self_loading").ajaxStart(function(){
		$(this).show();
	});
	$("#self_loading").ajaxStop(function(){
		$(this).hide();
	});
	
	$('#mp_id').change(function() {		
		var selectValues = $("#mp_id").val();
		
		if (selectValues == 0){				
			alert('Silakan pilih kelas terlebih dahulu');
			return false;
		}else{				
			$('#renderkelasakhir').load('<?=base_url()?>seting/pmb/loadmapelakhir/' + $('#mp_id').val() + '/' + $('#tahunajaranakhir').val());
		}		
	});	
});
</script>

<?php
if ($result) {
	echo form_dropdown("kelasakhir",$result,'',"id='mp_id'");
}
else {
	echo 'Pegawai Untuk TA sekarang belum dibuat.';
}
