	<div class="col-lg-6 col-xs-12">
		<div class="box box-primary">
		  <div class="box-header with-border">
			<i class="fa fa-briefcase"></i>
			<h3 class="box-title">Riwayat <small>Penilaian</small></h3>
		  </div>
		  <div class="box-body">

			<table class="table table-condensed">
				<thead>
					<tr>
						<th>No.</th><th>Jenis Penilaian</th><th>Tgl. Penilaian</th><th>Mapel</th><th>Nilai</th><th>Huruf</th>
					</tr>
				</thead>
				<tbody>
				<?php
					if (is_null($nilai)) {
						echo "<tr><td colspan='6'><span class='badge btn-danger'>Data kosong</span></td></tr>";
					} else {						
						$nomer = 0;
						foreach ($nilai as $ht) {
							$nomer++;
							echo "<tr><td>".$nomer."</td><td>".$this->members_model->get_jenis_penilaian($ht->id_siswa)."</td><td>".tgl_indo($ht->tgl_penilaian)."</td><td>".$ht->mapel."</td><td>".$ht->nilai."</td><td>".$ht->huruf."</td></tr>";
						}
					}
				?>
				</tbody>
			</table>

		  </div>
		</div>
	</div>