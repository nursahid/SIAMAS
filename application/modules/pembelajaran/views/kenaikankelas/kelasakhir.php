<?php
if ($result) {
	echo form_multiselect('secondList[]', @$result, '', 'id="secondList" class="form-control" style="height:250px;"');
}