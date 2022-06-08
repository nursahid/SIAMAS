<!--panel-->
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><i class="fa fa-list"></i> Alumnikan Santri</h3>
		<div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			<button class="btn btn-box-tool" id="mini-refresh"><i class='fa fa-refresh'></i></button>
		</div>
	</div>
    <div class="box-body">

		<form id="formKlien" name="formKlien" method="post" action="">
		<input type="hidden" id="j_action" name="j_action" value="add_alumni">
		<table width="100%" class="table table-responsive">	
			<tr>
				<td>Nama Siswa </td>
				<td><input type="text" name="id_siswa" value="<?=$id_siswa?>" /></td>
			</tr>
		<tr>
				<td>Tahun Kelulusan </td>
				<td><?=htmlYearSelector('angkatan')?></td>
			</tr>					
			<tr>
				<td colspan="2"><input type="submit" name="btn_simpan" id="btn_simpan" class="btn btn-sm btn-danger" value="Simpan Data"  /> </td>		
			</tr>
		</table>
		<br/><br/>
		
		</form>
		<div id="message"></div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#btn_simpan').click(function(e){	
		//e.preventDefault();
		var form_Data	= {
							angkatan : $("#angkatan").val(),
							id_siswa : $("#id_siswa").val(),
							j_action : "add_alumni"
						};
		$.ajax({
			type	: 'POST',
			url		: '<?=base_url()?>siswa/addalumni',
			dataType: 'json',
			data	: form_Data,
			//data	: $("#formKlien").serialize(),
			success	: function(pesan) {
				$("#message").html(pesan);
				$("div").addClass("alert alert-success");
				alert('Data sukses disimpan');
			},
			error: function(error) {
				alert(error);
			}
		});
		return false;
	});	
});
</script>