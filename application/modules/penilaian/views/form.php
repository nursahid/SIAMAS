<!--panel-->
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><i class="fa fa-list"></i> Daftar Nilai <?=$siswa->nama?></h3>
		<div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			<button class="btn btn-box-tool" id="mini-refresh"><i class='fa fa-refresh'></i></button>
		</div>
	</div>
    <div class="box-body">

		<form id="formKlien" name="formKlien" method="post" action="" enctype="multipart/form-data">
			<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
			<input type="hidden" id="j_action" name="j_action" value="">
			<input type="hidden" name="tahunajaran" value="<?=@$tahunajaran->id?>">
			<input type="hidden" name="id_siswa" value="<?=@$this->uri->segment(3)?>">
			<table class="table table-collapse" width="100%">
				<tr>
					<th>No.</th>
					<th>NIP</th>
					<th>Guru</th>		
					<th>Kode Mapel</th>
					<th>Mata Pelajaran </th>		
					<th>Nilai</th>
				</tr>
				<?php
			if ($mapel) {
				$i = 1;$k = 0;
				foreach ($mapel as $key) :
				
					if ($nilaisiswa) {
						$j = 0;
						foreach ($nilaisiswa as $v) {		
							
							if ($v->id_mapel == $key->id) {
								$mNilai[$j] = $v->nilai;
							}
							else
								$mNilai[$j] = '';
							$j++;
						}			
					}
				?>
				<tr>
					<td><?=$i?></td>		
					<td><?=$key->nipeg?></td>
					<td><?=$key->nama?></td>
					<td><?=$key->kode?></td>
					<td><?=$key->mapel?></td>				
					<td class="table-common-links" align="center">
						<input type="hidden" name="db_idmp[]" value="<?=@$key->idmapel?>" />
						<input type="hidden" name="db_nip[]" value="<?=@$key->nipeg?>" />
						<input type="text" name="db_nilai[]" size="5" value="<?=@$mNilai[$k]?>" />
					</td>
				</tr>
				<?php			
				$i++; $k++;
				endforeach;
				
			}
			?>
				<tr>
					<td class="table-common-links" colspan="6" style="align: right !important;">
						<a class="btn btn-sm btn-default" href="<?=base_url('penilaian/');?>" >Kembali</a>&nbsp;&nbsp;
						<a class="btn btn-lg btn-danger" href="javascript: return void(0)" id="add">Simpan</a>&nbsp;&nbsp;
						<a class="btn btn-sm btn-primary" href="<?=base_url()?>nilai/kirimsms/<?=$this->uri->segment(3)?>/<?=$this->uri->segment(4)?>" id="kirimsms">Kirim Nilai</a>
					</td>
				</tr>
			</table>
			</form>

	</div>
</div>
<script>
$(function(){		
	$('#add').click(function(){	
		$('#j_action').val('add_nilai');
		$('#formKlien').submit();
	});	
});

function resetForm(){
	$('form :input').val("");		
}
</script>