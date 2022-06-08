
<table class="table table-responsive" width="100%">
<tr>
	<th>No.</th>
	<th>id</th>
	<th>Nama</th>
	<th>Status</th>
	<th>Tanggal</th>
	<th>Nominal</th>	
</tr>

<?php
if ($datas) {	
	$i = 1;
	
	foreach ($datas->result() as $q) {
		$arr = $this->db->get_where('pembayaran', array('id_siswa' => $q->id, 'bulan' => $post['bulan'], 'tahun' => $post['tahun']));
		
		if ($arr->num_rows() > 0) {
			$f = $arr->row();
			$def = 'sudah';			
		}
		else {
			$def = 'belum';
		}
		
		echo '<tr>
				<td>'.$i.'</td>';
				
				if ($def == 'sudah') {
					echo '<td>'.$q->nis.'</td>
						  <td>'.$q->nama.'</td>';	
					echo '<td><strong>Sudah Bayar</strong></td>
						  <td><strong>'.$f->tgl_transaksi.'</strong></td>
						  <td><strong>'.$f->nilai.'</strong></td>';
			  
				}
				else {
					echo '<td>'.$q->nis.'
								<input type="hidden" name="idsiswa[]" value="'.$q->id.'">
						  </td>
						  <td>'.$q->nama.'</td>';
					echo '<td>'.form_dropdown('status[]', $status, $def, 'class="status" id="'.$q->id.'"').'</td>';
					echo '<td><input type="text" name="tglbayar[]" id="datepick_'.$q->id.'" style="display:none;"></td>
						<td><input type="text" name="nilai[]" id="nilai_'.$q->id.'" style="display:none;"></td>';
				}				
		$i++;
		echo '<input type="hidden" name="jenispembayaran" value="'.$this->uri->segment(3).'">
			  </tr>';
	}
}
else {
	
}
?>	
</table>
<table>
	<tr>
		<td class="table-common-links">
			<a class="btn btn-sm btn-danger" href="javascript: void(0)" id="add">Simpan</a>
		</td>
	</tr>
</table>

<script type="text/javascript">
$(function() {
	$("#self_loading").ajaxStart(function(){
		$(this).show();
	});
	
	$("#self_loading").ajaxStop(function(){
		$(this).hide();
	});

	$('#add').click(function(){			
		$('#j_action').val('add_absen');
		$('#formKlien').submit();
	});
	
	$('.status').change(function() {
		var id = $(this).attr('id');
		
		if ($('.status').val() == 'sudah') {			
			$('#datepick_'+id).datepick({dateFormat: 'yyyy-mm-dd'});
			$('#datepick_'+id).show();
			$('#nilai_'+id).show();
		}
		else {
			$('#datepick_'+id).hide();
			$('#nilai_'+id).hide();
		}
	});
	
	
});
</script>