	<div class="col-lg-6 col-xs-12">
		<div class="box box-primary">
		  <div class="box-header with-border">
			<i class="fa fa-briefcase"></i>
			<h3 class="box-title">Pembayaran <small>Data Pembayaran</small></h3>
		  </div>
		  <div class="box-body">

			<table class="table table-condensed">
				<thead>
					<tr>
						<th>No.</th><th>Tgl. Transaksi</th><th>Nominal</th><th>Jns. Pembayaran</th>
					</tr>
				</thead>
				<tbody>
				<?php
					if ($pembayarans=="Nihil") {
						echo "<tr><td colspan='4'><span class='badge btn-danger'>Data kosong</span></td></tr>";
					} else {						
						$nomer = 0;
						foreach ($pembayarans as $uk) {
							$nomer++;
							echo "<tr><td>".$nomer."</td><td>".tgl_indo($uk->tgl_transaksi)."</td><td>Rp. ".rupiah($uk->nilai)."</td><td>".$uk->nama_jenispembayaran."</td></tr>";
						}
					}
				?>
				</tbody>
			</table>
			
			<center><strong>Total Pembayaran : <?php echo "Rp. ".rupiah($jml_pembayaran); ?></strong></center>

		  </div>
		</div>
	</div>