<div class="row">
	<div class="col-md-12">
		
		<!--panel-->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-list"></i> CETAK DATA</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" id="mini-refresh"><i class="fa fa-refresh"></i></button>
				</div>
			</div>
			<div class="box-body">
				<?php if($this->ion_auth->in_group(array('superadmin', 'admin'))) {?>
					<a href="<?=base_url('cetak/siswa');?>" class='btn btn-app'><i class='fa fa-user'></i> Data Siswa</a>
					<a href="<?=base_url('cetak/mutasi');?>" class='btn btn-app'><i class='fa fa-building'></i> Mutasi Siswa</a>
					<a href="<?=base_url('cetak/pegawai');?>" class='btn btn-app'><i class='fa fa-user-plus'></i> Data Pegawai</a>
				<?php } elseif($this->ion_auth->in_group(array('superadmin', 'guru'))) { ?>
					<a href="<?=base_url('cetak/soal');?>" class='btn btn-app'><i class='fa fa-question'></i> Data Soal</a>
					<a href="<?=base_url('cetak/nilai');?>" class='btn btn-app'><i class='fa fa-pencil'></i> Data Nilai</a>
					<a href="<?=base_url('cetak/absensi');?>" class='btn btn-app'><i class='fa fa-edit'></i> Data Absensi</a>
				<?php } elseif($this->ion_auth->in_group(array('superadmin', 'bendahara'))) { ?>
					<a href="<?=base_url('cetak/pembayaran');?>" class='btn btn-app'><i class='fa fa-money'></i> Data Pembayaran</a>
				<?php } ?>
			</div>
			<div class="panel-footer" align="center">
 
			</div>
		</div>
		
	</div>
</div>