	<div class="col-lg-6 col-xs-12">
		<div class="box box-primary">
		  <div class="box-header with-border">
			<i class="fa fa-briefcase"></i>
			<h3 class="box-title">Absensi <small>Data Absensi</small></h3>
		  </div>
		  <div class="box-body">
			<strong>A. ABSENSI HARIAN</strong> <hr/>
			<table class="table table-condensed">
				<thead>
					<tr>
						<th>No.</th><th>Tgl. Absensi</th><th>Absensi</th>
					</tr>
				</thead>
				<tbody>
				<?php
					if ($absensi=="Nihil") {
						echo "<tr><td colspan='4'><span class='badge btn-danger'>Data kosong</span></td></tr>";
					} else {						
						$nomer = 0;
						foreach ($absensi as $uk) {
							if($uk->absen=='hadir') {$absen ="<span class='badge btn-success'>Hadir</span>";}
							elseif($uk->absen=='alfa') {$absen ="<span class='badge btn-danger'>Alfa</span>";}
							$nomer++;
							echo "<tr><td>".$nomer."</td><td>".tgl_indo($uk->tanggal)."</td><td>".$absen."</td></tr>";
						}
					}
				?>
				</tbody>
			</table>

			<hr/><strong>B. ABSENSI MATA PELAJARAN</strong> <hr/>
			<table class="table table-condensed">
				<thead>
					<tr>
						<th>No.</th><th>Mata Pelajaran</th><th>Tgl. Absensi</th><th>Absensi</th>
					</tr>
				</thead>
				<tbody>
				<?php
					if ($absensi_mapel=="Nihil") {
						echo "<tr><td colspan='4'><span class='badge btn-danger'>Data kosong</span></td></tr>";
					} else {						
						$nomer2 = 0;
						foreach ($absensi_mapel as $am) {
							$nomer2++;
							if($am->absen=='hadir') {$absens ="<span class='badge btn-success'>Hadir</span>";}
							elseif($am->absen=='alfa') {$absens ="<span class='badge btn-danger'>Alfa</span>";}
							echo "<tr><td>".$nomer2."</td><td>".$this->members_model->get_nama_mapel($am->id_mapel)."</td><td>".tgl_indo($am->tanggal)."</td><td>".$absens."</td></tr>";
						}
					}
				?>
				</tbody>
			</table>
			
		  </div>
		</div>
	</div>