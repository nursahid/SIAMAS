<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="col-xs-12 col-sm-12 col-md-12">
	<div class="panel panel-default">
	  	<div class="panel-heading"><i class="fa fa-sign-out"></i> <?=strtoupper('data alumni')?></div>
	  	<div class="table-responsive">
	  		<table class="table table-hover table-striped table-condensed">
				<thead>
					<tr>
						<th width="20px">No.</th>
						<th>Foto</th>
						<th>NIS</th>
						<th>Nama</th>
						<th>Kelamin</th>
						<th>Tempat Lahir</th>
						<th>Tanggal Lahir</th>
						<th>Thn. Masuk</th>
						<th>Thn. Lulus</th>
					</tr>
				</thead>
				<tbody>
					<?php $no = 1; foreach($query->result() as $row) { ?>
					<tr>
						<td class="text-center number"><?=$no?>.</td>
						<td>
							<?php
							$photo = 'default.png';
							if ($row->photo && file_exists($_SERVER['DOCUMENT_ROOT'] . '/assets/uploads/image/'.$row->foto)) {
								$photo = $row->foto;
							}
							echo '<img width="80px" src="' . base_url('assets/uploads/image/'.$photo).'" class="img-responsive img-thumbnail" alt="Responsive image">';
							?>
						</td>
						<td><?=$row->nis;?></td>
						<td><?=$row->nama;?></td>
						<td><?=$row->kelamin;?></td>
						<td><?=$row->tempat_lahir;?></td>
						<td><?=tgl_indo($row->tgl_lahir);?></td>
						<td><?=$row->tgl_daftar;?></td>
						<td><?=$row->angkatan;?></td>
					</tr>
					<?php $no++; } ?>
				</tbody>
			</table>
	  	</div>
		<div class="panel-footer">
			<button class="btn btn-block btn-sm btn-primary load-more" onclick="load_more_alumni()">TAMPILKAN LEBIH BANYAK</button>
		</div>
	</div>
</div>

<script type="text/javascript">
	var page = 1;
	var total_pages = "<?=$total_pages;?>";
	$(document).ready(function() {
		if (parseInt(total_pages) == page || parseInt(total_pages) == 0) {
			$('.panel-footer').remove();
		}
	});
	function load_more_alumni() {
		page++;
		var data = {
			page_number: page
		};
		if ( page <= parseInt(total_pages) ) {
			$('body').addClass('loading');
			$.post( _BASE_URL + 'alumni/more_alumni', data, function( response ) {
				var res = typeof response !== 'object' ? $.parseJSON( response ) : response;
				var rows = res.rows;
				var html = '';
				var no = parseInt($('.number:last').text()) + 1;
				for (var z in rows) {
					var result = rows[ z ];
					html += '<tr>';
					html += '<td class="text-center number">' + no + '</td>';
					html += '<td><img width="80px" src="' + result.foto + '" class="img-responsive img-thumbnail" alt="Responsive image"></td>';
					html += '<td>' + result.nis + '</td>';
					html += '<td>' + result.nama + '</td>';
					html += '<td>' + result.kelamin + '</td>';
					html += '<td>' + result.tempat_lahir + '</td>';
					html += '<td>' + result.tgl_lahir + '</td>';
					html += '<td>' + result.tgl_daftar + '</td>';
					html += '<td>' + result.angkatan + '</td>';
					html += '</tr>';
					no++;
				}
				var el = $("tbody > tr:last");
				$( html ).insertAfter(el);
				if ( page == parseInt(total_pages) ) {
					$('.panel-footer').remove();
				}
				$('body').removeClass('loading');
			});
		}
	}
</script>