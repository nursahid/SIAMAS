		<a href="<?=base_url('soal/tambah/').$_POST['mapel'].'/add';?>" title="Tambah Data" class="add-anchor add_button btn btn-primary btn-flat">
			<i class="fa fa-plus-circle"></i> 
			<span class="add">Tambah Soal <?=$this->sistem_model->get_nama_mapel($_POST['mapel'])?></span>
		</a>
		
		<table class="table table-responsive" width="100%">
		<tr>
			<th>No.</th>
			<th>Soal</th>
			<th>Aksi</th>
		</tr>

		<?php
		if ($datakuis) {	
			$i = 1;
			
			foreach ($datakuis as $q) {
				echo '<tr>
						<td>'.$i.'</td>
						<td>'.$q->name.'</td>
						<td><a href="'.base_url('soal/itemsoal/').$q->id_mapel.'/'.$q->id.'" class="btn btn-sm btn-danger" id="tambah"><i class="fa fa-list"></i> Kelola Item Soal</a></td>
					  </tr>';
				$i++;
			}
		}
		else {
			echo "<tr><td colspan='3'>
					<span class='badge btn-danger'>Belum ada data KUIS klik tombol di atas untuk menambahkan data </span>
				 </td></tr>";
		}
		?>	
		</table>
