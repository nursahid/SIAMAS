
	<?php
	if ($datasiswa) {
		echo '<table class="table table-responsive" width="100%">
				<tr>
					<th>No.</th>
					<th>NIS</th>
					<th>Nama</th>
					<th>Nilai</th>
				</tr>';
			$i = 1;
			
			foreach ($datasiswa->result() as $q) {
				//ambil data guru by mapel
				$peg = $this->nilai->get_pegawai_by_mapel($_POST['mapel']);
				echo '<tr>
						<td>'.$i.'</td>
						<td>'.$q->nis.'<input type="hidden" name="idsiswa[]" value="'.$q->idsiswa.'"></td>
						<td>'.$q->nama.'</td>
						<td> <input type="text" name="nilai[]" class="input-sm required" />  </td>
					  </tr>';
				$i++;
			}
		echo '</table>
		<table>
			<tr>
				<td class="table-common-links">
					<a class="tbn btn-sm btn-danger" href="javascript: void(0)" id="add"><i class="fa fa-save"></i> Simpan</a>
				</td>
			</tr>
		</table>
		<br /><br />';
	}
	else {
		echo "<div class='col-md-6'><span class='badge btn-danger'>Belum ada siswa di kelas ini</span></div> <br />";
	}
	?>	


<script type="text/javascript">
$(function() {
	$("#self_loading").ajaxStart(function(){
		$(this).show();
	});
	
	$("#self_loading").ajaxStop(function(){
		$(this).hide();
	});

	$('#add').click(function(){			
		$('#j_action').val('add_nilai');
		$('#formKlien').submit();
	});
});
</script>