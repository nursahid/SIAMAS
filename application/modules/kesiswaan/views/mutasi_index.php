<div class="row">
	<div class="col-md-12">
		
		<!--panel-->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-list"></i> Data Mutasi Santri</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" id="mini-refresh"><i class="fa fa-refresh"></i></button>
				</div>
			</div>
			<div class="box-body">
			
				<table class="table table-responsive">
					<thead><tr><th>ID</th><th>Jenis Mutasi</th></tr></thead>
					<tbody>
						<tr>
							<td>1</td>
							<td><a href="<?=site_url('kesiswaan/mutasi/masuk/');?>" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="bottom" title="Masukkan Data Santri yang Pindah Masuk disini"><i class="fa fa-user"></i> Mutasi Masuk</a></td>
						</tr>
						<tr>
							<td>2</td>
							<td><a href="<?=site_url('kesiswaan/mutasi/keluar/');?>" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="bottom" title="Masukkan Data Santri yang Pindah Keluar disini"><i class="fa fa-user"></i> Mutasi Keluar</a></td>
						</tr>
					</tbody>
				</table>
		
			</div>
			<div class="panel-footer" align="center">
 
			</div>
		</div>
		
	</div>
</div>