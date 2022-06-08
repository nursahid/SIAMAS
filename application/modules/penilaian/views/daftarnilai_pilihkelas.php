
		<table class="table table-responsive" width="100%">
		<tr>
			<th>No.</th>
			<th>Kelas</th>
			<th>Aksi</th>
		</tr>

		<?php
		if ($datakelas) {	
			$i = 1;
			
			foreach ($datakelas as $q) {
				echo '<tr>
						<td>'.$i.'</td>
						<td>'.$q->kelas.'</td>
						<td><a href="'.base_url('penilaian/daftarnilai/lihatdata/').$_POST['jenis_penilaian'].'/'.$q->id.'" class="btn btn-sm btn-info" id="lihat"><i class="fa fa-desktop"></i> Lihat Data Nilai Kelas</a></td>
					  </tr>';
				$i++;
			}
		}
		else {
			echo "<tr><td colspan='3'><span class='badge btn-danger'>Belum ada data kelas</span></td></tr>";
		}
		?>	
		</table>
