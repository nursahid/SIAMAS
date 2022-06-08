<?php
if($q->foto=='' || is_null($q->foto)) {
	if($q->kelamin == 'L'){
		$foto = "/assets/img/default.png";
	}elseif($q->kelamin == 'P'){
		$foto = "/assets/img/default_female.png";
	}	
} else {
	$foto = "/assets/uploads/image/".$q->foto;
}
?>
<div class="row">
	
		<div class="panel-body">
			<h4>Data Rinci Pegawai an. <strong><?=$q->nama?></strong></h4>
			<!--Tabs-->
			<div class="row">
				<div class="col-md-12">
					  <!-- Custom Tabs -->
					  <div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
						  <li class="active"><a href="#tab_1" data-toggle="tab">Data Pribadi</a></li>
						  <li><a href="#tab_2" data-toggle="tab">Data Mata Pelajaran</a></li>
						</ul>
						<div class="tab-content">
						  <div class="tab-pane active" id="tab_1">
							<b>Data Pribadi Pegawai</b>
							<hr />
								<div class="row">
									<div class="col-md-6">
										<table class="table">
											<tr>
												<td>Nama Lengkap</td><td>:</td><td><?=$q->nama?></td>
											</tr>
											<tr>
												<td>Tempat, Tgl. Lahir</td><td>:</td><td><?=$q->tempat_lahir.', '.tgl_indo($q->tgl_lahir);?></td>
											</tr>
											<tr>
												<td>NIP</td><td>:</td><td><?=$q->nip?></td>
											</tr>
											<tr>
												<td>NIK</td><td>:</td><td><?=$q->nik?></td>
											</tr>
											<tr>
												<td>NPWP</td><td>:</td><td><?=$q->npwp?></td>
											</tr>
											<tr>
												<td>Jabatan</td><td>:</td><td><?=$this->sistem_model->get_nama_jabatan($q->jabatan)?></td>
											</tr>
											<tr>
												<td>Status Menikah</td><td>:</td><td><?=$q->status_kawin?></td>
											</tr>
											<tr>
												<td>Alamat</td><td>:</td><td><?=$q->alamat?></td>
											</tr>
										</table>
									</div>
									<div class="col-md-6">
										<div class="col-md-3 col-lg-3 " align="center">
											<img alt="Foto <?=$q->nama?>" src="<?=base_url($foto);?>" class="img-circle img-responsive">
										</div>
									</div>									
								</div>
							</div><!-- /.tab-pane -->
						  <div class="tab-pane" id="tab_2">
							
							<!--Data Mapel Diampu-->
							<b>Mata Pelajaran Diampu</b>
							<hr />
							<?php
							//cek dari tabel Mapel yang diampu
							$mapels = $this->ampumapel_model->get_mapel_by_guru($q->id,$tapel);
							if($mapels) {
								echo '<div class="row">
										<div class="col-md-12">
										<table class="table table-responsive">
											<thead>
												<tr>
													<th>No.</th><th>Mata Pelajaran</th>
												</tr>
											</thead>
											<tbody>';
											$no = 1;
											foreach($mapels as $mp) {
												echo '
												<tr>
													<td width="5%">'.$no++.'</td><td>'.$mp->mapel.'</td>
												</tr>
												';
											}
											
								echo '		</tbody>
										</table>
										</div>
									</div>';
							} else {
								echo '<span class="badge btn-danger">Belum mengampu Mata Pelajaran</span>';
							}
							?>
							<!--End mapel-->
							
						  </div><!-- /.tab-pane -->

						</div><!-- /.tab-content -->
					  </div><!-- nav-tabs-custom -->				
				</div>
			</div>
		</div>
	
</div>