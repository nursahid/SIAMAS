
<!--panel-->
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><i class="fa fa-list"></i> Hapus Absensi</h3>
		<div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			<button class="btn btn-box-tool" id="mini-refresh"><i class='fa fa-refresh'></i></button>
		</div>
	</div>
    <div class="box-body">

		<form id="formSubmit" method="post" action="">
		<table width="100%" class="table table responsive">
			<tr>
				<th colspan="3"><h3>APAKAH ANDA INGIN MENGHAPUS DATA INI ?</h3></th>
			</tr>
			<tr>
				<th width="23%">NIS</th>
				<th width="7%">:</th>
				<td><?=@$row->nis?></td>
			</tr>	
			<tr>
				<th width="23%">Tanggal</th>
				<th width="7%">:</th>
				<td><?=@$row->tanggal?></td>
			</tr>
			<tr>
				<th>Alasan Absen</th>
				<th>:</th>
				<td><?=@$row->absen?></td>
			</tr>			
			<tr>
				<td colspan="2" align="left"></td>
				<td class="table-common-links">
					<input type="hidden" id="j_action" name="j_action" value=""><input type="hidden" name="id" value="<?=@$row->id?>"><a class="btn btn-sm btn-danger" href="javascript: void(0)" id="btn_delete">Hapus</a><a href="<?=base_url();?>voucher">Kembali</a>
				</td>
			</tr>
		</table>
		</form>

	</div>
</div>
<script type="text/javascript">
$(function() {
	$('#btn_delete').click(function() {	
		$('#j_action').val('delete_absensi');;
		$('#formSubmit').submit();
	});	
});
</script>