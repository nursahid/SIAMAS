<div class="row">
	
		<div class="panel-body">
			<h4>Data Rinci Santri an. <strong><?=$q->nama?></strong></h4>
			<!--Tabs-->
			<div class="row">
				<div class="col-md-12">
					  <!-- Custom Tabs -->
					  <div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
						  <li class="active"><a href="#tab_1" data-toggle="tab">Data Pribadi</a></li>
						  <li><a href="#tab_2" data-toggle="tab">Data Orang Tua</a></li>
						  <li><a href="#tab_3" data-toggle="tab">Prestasi</a></li>
						</ul>
						<div class="tab-content">
						  <div class="tab-pane active" id="tab_1">
							<b>Data Pribadi Santri</b>
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
												<td>NIS</td><td>:</td><td><?=$q->nis?></td>
											</tr>
											<tr>
												<td>NISN</td><td>:</td><td><?=$q->nisn?></td>
											</tr>
											<tr>
												<td>NIK</td><td>:</td><td><?=$q->nik?></td>
											</tr>
											<tr>
												<td>Kelas Saat ini</td><td>:</td><td><?=$kelas_sekarang?></td>
											</tr>
											<tr>
												<td>Asal Sekolah</td><td>:</td><td><?=$q->namasekolah?></td>
											</tr>
											<tr>
												<td>Alamat</td><td>:</td><td><?=$q->alamat?></td>
											</tr>
										</table>
									</div>
									<div class="col-md-6">
										<div class="col-md-3 col-lg-3 " align="center"> 
										<?php 
										if ($q->foto ==''){
												$foto = "default.png";
											}else{
												$foto = $q->foto;
											}
										?>
											<img alt="Foto <?=$q->nama?>" src="<?php echo base_url('assets/uploads/siswa/'.$foto); ?>" width="150" class="thumbnail ">
										</div>
									</div>									
								</div>
							</div><!-- /.tab-pane -->
						  <div class="tab-pane" id="tab_2">							
							<!--Data ortu-->
							<b>Data Orang Tua Siswa</b>
							<hr />
							<?php
							//cek dari tabel orangtua
							$cek = $this->orangtua_model->get_by('id_siswa',$q->id);
							if($cek) {
							?>
								<div class="row">
									<div class="col-md-6">
										<table class="table">
											<tr>
												<td>Nama Ayah</td><td>:</td><td><?=$cek->nama_ayah?></td>
											</tr>
											<tr>
												<td>Tempat, Tgl. Lahir</td><td>:</td><td><?=$cek->tmpt_lahir_ayah.', '.tgl_indo($cek->tgl_lahir_ayah)?></td>
											</tr>
											<tr>
												<td>Pekerjaan Ayah</td><td>:</td><td><?=$cek->pekerjaan_ayah?></td>
											</tr>
											<tr>
												<td>Tentang Orangtua</td><td>:</td><td><?=$cek->tentang?></td>
											</tr>
											<tr>
												<td>Alamat</td><td>:</td><td><?=$cek->alamat?></td>
											</tr>
										</table>
									</div>
									<div class="col-md-6">
										<table class="table">
											<tr>
												<td>Nama Ibu</td><td>:</td><td><?=$cek->nama_ibu?></td>
											</tr>
											<tr>
												<td>Tempat, Tgl. Lahir</td><td>:</td><td><?=$cek->tmpt_lahir_ibu.', '.tgl_indo($cek->tgl_lahir_ibu)?></td>
											</tr>
											<tr>
												<td>Pekerjaan Ibu</td><td>:</td><td><?=$cek->pekerjaan_ibu?></td>
											</tr>
										</table>									
									</div>
								</div>
								<?php
								} else {
									echo "<a href=".base_url('kesiswaan/orangtua/index/'.$q->id)." class='btn btn-danger'>Input Data Orang Tua</a>" ;
								}
								?>
							<!--End ortu-->
							
						  </div><!-- /.tab-pane -->
						  <div class="tab-pane" id="tab_3">
						  <b>Data Prestasi Santri</b>
							<hr />
							<?php
							//cek dari tabel Prestasi
							$qpres = $this->prestasi_model->get_many_by('id_siswa',$q->id);
							if($qpres) {
								//var_dump($qpres);
								echo '<div class="row">
										<div class="col-md-12">
										<table class="table table-responsive">
											<thead>
												<tr>
													<th>No.</th><th>Tanggal</th><th>Prestasi</th>
												</tr>
											</thead>
											<tbody>';
											$no = 1;
											foreach($qpres as $p) {
												echo '
												<tr>
													<td>'.$no++.'</td><td>'.tgl_indo($p->tgl_perolehan).'</td><td>'.$p->prestasi.'</td>
												</tr>
												';
											}
											
								echo '		</tbody>
										</table>
										</div>
									</div>';
							} else {
								echo '<span class="badge btn-danger">Belum Memiliki Data Prestasi</span>';
							}
							?>
						  </div><!-- /.tab-pane -->
						</div><!-- /.tab-content -->
					  </div><!-- nav-tabs-custom -->				
				</div>
			</div>
		</div>
	
</div>