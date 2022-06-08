<style type="text/css">
 /* Customize the label (the isinya) */
.isinya {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 20px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default checkbox */
.isinya input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}

/* Create a custom checkbox */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
}

/* On mouse-over, add a grey background color */
.isinya:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.isinya input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.isinya input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.isinya .checkmark:after {
  left: 9px;
  top: 5px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
} 
</style>
	<?php
	if ($absen) {
	echo '<div class="table-responsive">
	<table class="table table-condensed" width="100%">
	<tr>
		<th>No.</th>
		<th>NIS</th>
		<th>Nama</th>
		<th>Status</th>
		<th>Keterangan</th>
	</tr>';
		$i = 1;		
		foreach ($absen->result() as $q) {
			$arr = $this->db->get_where('absensi', array('nis' => $q->nis, 'tanggal' => $tgl));
			
			if ($arr->num_rows() > 0) {
				$r = $arr->row();
				$def = $r->absen;
			}
			else {
				$def = 'belum';
			}
			if($q->nis == '') {
				$nis = "<span class='badge btn-danger'>Belum ada NIS</span>";
			} else {
				$nis = $q->nis;
			}
			echo '<tr>
					<td>'.$i.'<input name="kelas[]" type="hidden" value="'.$q->idkelas.'" /></td>
					<td>'.$nis.'<input type="hidden" name="nis[]" value="'.$q->nis.'"></td>
					<td>'.$q->nama.'<input type="hidden" name="nama[]" value="'.$q->nama.'"></td>
					<td>'.form_dropdown('status[]', $status, $def).'</td>
					<td> <input name="nohp[]" type="hidden" value="'.$q->hp_ortu.'" />
						<textarea name="keterangan[]" id="" cols="30" rows="1" class="form-control">'.$q->hp_ortu.'</textarea>
					</td
				  </tr>';
			$i++;
		}
	echo '</table>
	</div>
	
	<table>
		<tr>
			<td>
				<label class="isinya"> Kirim SMS ?
				  <input type="checkbox" name="kirim_sms" value="1" checked="checked">
				  <span class="checkmark"></span>
				</label>
			</td>
			<td class="table-common-links">
				&nbsp;&nbsp;&nbsp;
				<a class="btn btn-success" href="javascript: void(0)" id="add"><i class="fa fa-save"></i> &nbsp;&nbsp;Simpan Absen</a>
			</td>
		</tr>
	</table>
	<br /><br />
	
	';
	}

else {
	echo "<div class='col-md-6'><span class='badge btn-danger'>Belum ada siswa di kelas ini</span></div> <br />";
}
?>	


<script type="text/javascript">
$(function() {
	$("#self_loading").ajaxStart(function(){
		$(this).show();
	});
	
	$("#self_loading").ajaxStop(function(){
		$(this).hide();
	});

	$('#add').click(function(){			
		var namasiswa = $('input[name="nama[]"]').val();
		if ($('input[name="nis[]"]').val() == "") { alertify.alert('Siswa '+namasiswa+' belum punya NIS'); return false; }
		$('#j_action').val('add_absen');
		$('#formKlien').submit();
	});
});
</script>