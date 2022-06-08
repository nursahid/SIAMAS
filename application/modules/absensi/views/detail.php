<!--panel-->
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><i class="fa fa-list"></i> Data Siswa</h3>
		<div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			<button class="btn btn-box-tool" id="mini-refresh"><i class='fa fa-refresh'></i></button>
		</div>
	</div>
    <div class="box-body">
		<h4>Detail Data <?=@$row->nama?></h4>
		<br/>
		<table width="100%" class="table-form">	
			<tr>
				<th width="23%">NIS</th>
				<th width="7%">:</th>
				<td><?=@$row->nis?></td>
			</tr>	
			<tr>
				<th width="23%">Nama</th>
				<th width="7%">:</th>
				<td><?=@$row->nama?></td>
			</tr>
			<tr>
				<th>Tahun Masuk</th>
				<th>:</th>
				<td><?=@$row->tgl_daftar?></td>
			</tr>
			<tr>
				<th>Status</th>
				<th>:</th>
				<td><?=(@$row->status == 'Aktif') ? 'Aktif' : 'Tidak Aktif';?></td>
			</tr>
			<tr>
				<th>Alamat</th>
				<th>:</th>
				<td><?=@$row->alamat?></td>
			</tr>	
			<tr>
				<th>Tempat Lahir</th>
				<th>:</th>
				<td><?=@$row->tempat_lahir?></td>
			</tr>	
			<tr>
				<th>Tanggal Lahir</th>
				<th>:</th>
				<td><?=@$row->tgl_lahir?></td>
			</tr>
			<tr>
				<th>Agama</th>
				<th>:</th>
				<td><?=@$row->agama?></td>
			</tr>
			<tr>
				<th>Jenis Kelamin</th>
				<th>:</th>
				<td><?=@$row->kelamin?></td>
			</tr>
			<tr>
				<th>Nama Ayah</th>
				<th>:</th>
				<td><?=@$row->nama_ayah?></td>
			</tr>
			<tr>
				<th>Pekerjaan Ayah</th>
				<th>:</th>
				<td><?=@$row->pekerjaan_ayah?></td>
			</tr>
			<tr>
				<th>Nama Ibu</th>
				<th>:</th>
				<td><?=@$row->nama_ibu?></td>
			</tr>
			<tr>
				<th>Pekerjaan Ibu</th>
				<th>:</th>
				<td><?=@$row->pekerjaan_ibu?></td>
			</tr>
			<tr>
				<th>Alamat Orang Tua</th>
				<th>:</th>
				<td><?=@$row->alamat?></td>
			</tr>

			<tr>
				<th>No. Hp Orang Tua</th>
				<th>:</th>
				<td><?=@$row->hp_ortu?></td>
			</tr>
			<tr>
				<th>NIS</th>
				<th>:</th>
				<td><?=@$row->nis?></td>
			</tr>
			<tr>
				<th>Foto</th>
				<th>:</th>
				<td><img src="<?=base_url()?>assets/uploads/siswa/<?=trim(@$row->Photo)?>" /></td>
			</tr>
			<tr>
				<td colspan="2" align="left"></td>
				<td class="table-common-links">
					<a class="btn btn-sm btn-danger" href="<?=base_url();?>siswa">Kembali</a>
				</td>
			</tr>
		</table>
	</div>
</div>