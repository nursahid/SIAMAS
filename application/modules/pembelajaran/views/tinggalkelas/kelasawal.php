<?php
if ($result) {
	echo form_multiselect('firstList[]', @$result, '', 'id="firstList" class="form-control" style="height:250px;"');
} else {
	echo form_multiselect('firstList[]', '', '', 'id="firstList" class="form-control" style="height:250px;"');
}