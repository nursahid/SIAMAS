<?php
if ($result) {
	echo form_multiselect('kelasakhir', @$result, '', 'id="firstList" class="form-control" style="height:250px;"');
} else {
	echo form_multiselect('kelasakhir', '', '', 'id="firstList" class="form-control" style="height:250px;"');
}