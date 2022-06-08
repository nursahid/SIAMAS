
		<table class="table table-responsive" width="100%">
		<tr>
			<th>No.</th>
			<th>NIS Siswa</th>
			<th>Nama</th>
			<th>Nilai</th>
		</tr>

		<?php
		if ($datasiswa) {	
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
		}
		else {
			echo "<tr><td colspan='3'><span class='badge btn-danger'>Belum ada siswa di kelas ini</span></td></tr>";
		}
		?>	
		</table>
		<table>
			<tr>
				<td class="table-common-links">
					<a class="tbn btn-sm btn-danger" href="javascript: void(0)" id="add">Simpan</a>
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
		$('#j_action').val('add_nilai');
		$('#formKlien').submit();
	});
});
</script>