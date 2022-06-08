<?php foreach ($fields as $key => $value): ?>
	<tr>
		<!-- <td><i class="fa fa-bars fa-lg text-muted"></i></td> -->
		<td>
			<input type="hidden" class="fieldsNames" name="fieldsNames" value="<?php echo $value->name ?>" /> <label><?php echo $value->name ?></label><br>
			<?php if (is_array($Jfields)): ?>
				<input type="text" data-field="<?php echo $value->name ?>" name="table[<?php echo $value->name ?>][alias]" value="<?php echo isset($Jfields[$value->name]->alias) ? $Jfields[$value->name]->alias ? $Jfields[$value->name]->alias : '' : '' ?>"  class="form-control alias" placeholder="Alias">
			<?php else: ?>
				<input type="text" data-field="<?php echo $value->name ?>" name="table[<?php echo $value->name ?>][alias]" value=""  class="form-control alias" placeholder="Alias">
			<?php endif; ?>
		</td>
		<td class="text-center">
			<?php if (is_array($Jfields)): ?>
				<input class="check check-config" data-field="<?php echo $value->name ?>" data-action="column" type="checkbox" <?php echo ($Jfields[$value->name]->actions->column != 1) ? '' : 'checked="checked"' ?> name="table[<?php echo $value->name ?>][column]" value="1">
			<?php else: ?>
				<input class="check check-config" data-field="<?php echo $value->name ?>" data-action="column" type="checkbox" <?php echo ($value->primary_key) ? '' : 'checked="checked"' ?> name="table[<?php echo $value->name ?>][column]" value="1" />
			<?php endif; ?>
		</td>
		<td class="text-center">
			<?php if (is_array($Jfields)): ?>
				<input class="check check-config" data-field="<?php echo $value->name ?>" data-action="add" type="checkbox" <?php echo ($Jfields[$value->name]->actions->add != 1) ? '' : 'checked="checked"' ?> name="table[<?php echo $value->name ?>][add]" value="1">
			<?php else: ?>
				<input class="check check-config" data-field="<?php echo $value->name ?>" data-action="add" type="checkbox" <?php echo $value->primary_key ? '' : 'checked="checked"' ?> name="table[<?php echo $value->name ?>][add]" value="1">
			<?php endif; ?>
		</td>
		<td class="text-center">
			<?php if (is_array($Jfields)): ?>
				<input class="check check-config" data-field="<?php echo $value->name ?>" data-action="edit" type="checkbox" <?php echo ($Jfields[$value->name]->actions->edit != 1) ? '' : 'checked="checked"' ?> name="table[<?php echo $value->name ?>][edit]" value="1">
			<?php else: ?>
				<input class="check check-config" data-field="<?php echo $value->name ?>" data-action="edit" type="checkbox" <?php echo $value->primary_key ? '' : 'checked="checked"' ?> name="table[<?php echo $value->name ?>][edit]" value="1">
			<?php endif; ?>
		</td>
		<td class="text-center">
			<?php if (is_array($Jfields)): ?>
				<input class="check check-config" data-field="<?php echo $value->name ?>" data-action="details" type="checkbox" <?php echo ($Jfields[$value->name]->actions->details != 1) ? '' : 'checked="checked"' ?> name="table[<?php echo $value->name ?>][details]" value="1">
			<?php else: ?>
				<input class="check check-config" data-field="<?php echo $value->name ?>" data-action="details" type="checkbox" checked="checked" name="table[<?php echo $value->name ?>][details]" value="1">
			<?php endif; ?>
		</td>
		<td>
			<select class="chosen type" name="table[<?php echo $value->name ?>][type]" data-field="<?php echo $value->name ?>" data-placeholder="Select Type">
				<?php foreach ($fieldsType as $index => $val): ?>
					<?php  
					if ($value->type == $index OR ($value->type == 'timestamp' AND $index == 'datetime')) {
						$selected = 'selected';
					} elseif ($value->type == 'int' AND $index == 'number') {
						$selected = 'selected';
					} elseif ($value->type == 'text' AND $index == 'text') {
						$selected = 'selected';
					} elseif ($value->type == 'tinytext' AND $index == 'text') {
						$selected = 'selected';
					} elseif (($value->type == 'varchar' OR $value->type == 'tinyint') AND $index == 'string') {
						$selected = 'selected';
					} elseif ($value->type == 'decimal' AND $index == 'string') {
						$selected = 'selected';
					} else {
						$selected = '';
					}
					?>

					<?php // if was try to edit
					if (is_array($Jfields)) {
						$selected = '';
					}

					if (is_array($Jfields) && $Jfields[$value->name]->type == $index) {
						$selected = 'selected';
					}
					?>
					<option value="<?php echo $index ?>" <?php echo $selected ?>><?php echo $val ?></option>
				<?php endforeach ?>
			</select>
			<?php 
			if (is_array($Jfields) && ($Jfields[$value->name]->type == 'select' || $Jfields[$value->name]->type == 'select_multiple')): 
				$display = 'style="display: block"';
			else:
				$display = 'style="display: none"';
			endif;
			?>
			<div class="container-table-reff" <?php echo $display ?>>
				<label>Table Reff</label>
				<select class="chosen table-reff" id="relation-tableName-<?php echo $value->name ?>" data-field="<?php echo $value->name ?>" name="table[relation][tableName][<?php echo $value->name ?>]">
					<option value></option>
					<?php foreach ($table as $index => $val): ?>
						<?php if (is_array($Jfields)): ?>
							<option <?php echo ($Jfields[$value->name]->selectData->table == $val)? 'selected':'' ?> value="<?php echo $val ?>"><?php echo $val ?></option>
						<?php else: ?>
							<option value="<?php echo $val ?>"><?php echo $val ?></option>
						<?php endif ?>
					<?php endforeach ?>
					
				</select>
			</div>
			<?php 
			if (is_array($Jfields) && $Jfields[$value->name]->selectData->table != ''):
				$fieldsRef = $this->db->field_data($Jfields[$value->name]->selectData->table);
			endif; 
			?>
			<div class="container-field-reff" <?php echo $display ?>>
				<label>Value Field Reff</label>
				<select id="relation-field-<?php echo $value->name ?>" class="chosen field-reff" data-field="<?php echo $value->name ?>" name="table[relation][field][<?php echo $value->name ?>]">
					<option value></option>
					<?php if (is_array($fieldsRef) && isset($fieldsRef)): ?>
						<?php foreach ($fieldsRef as $key => $obj): ?>
							<?php 
							if ($Jfields[$value->name]->selectData->fieldReff == $obj->name):
								$selected = 'selected';
							else: 
								$selected = '';
							endif; ?>
							<option <?php echo $selected ?> value="<?php echo $obj->name ?>"><?php echo $obj->name ?></option>
						<?php endforeach ?>
					<?php endif ?>
				</select>
			</div>
			<div class="container-label-reff" <?php echo $display ?>>
				<label>Label Field Reff</label>
				<select id="relation-label-<?php echo $value->name ?>" class="chosen label-reff" data-field="<?php echo $value->name ?>" name="table[relation][label][<?php echo $value->name ?>]">
					<option value></option>
					<?php if (is_array($fieldsRef) && isset($fieldsRef)): ?>
						<?php foreach ($fieldsRef as $key => $obj): ?>
							<?php 
							if ($Jfields[$value->name]->selectData->labelReff == $obj->name):
								$selected = 'selected';
							else: 
								$selected = '';
							endif; ?>
							<option <?php echo $selected ?> value="<?php echo $obj->name ?>"><?php echo $obj->name ?></option>
						<?php endforeach ?>
					<?php endif ?>
				</select>
			</div>
			<?php 
			// if (is_array($Jfields) && $Jfields[$value->name]->type == 'select_multiple'): 
			// 	$display = 'style="display: block"';
			// else:
			$display = 'style="display: none"';
			// endif;			
			?>
			<!-- <div class="container-select-multiple" style="display: none;"> -->
			<div class="container-select-multiple text-primary" <?php echo $display; ?>>
				<div class="container-relation-n-n-labelName">
					<small class="select-multiple-data-label">Label Name </small>
					<?php if (is_array($Jfields) && is_object($Jfields[$value->name]->selectMultipleData)): ?>
						<input type="text" data-field="<?php echo $value->name ?>" name="table[relation_n_n][labelName][<?php echo $value->name ?>]" value="<?php echo $Jfields[$value->name]->selectMultipleData->labelName ?>" class="small-input form-control relation-n-n-labelName" />
					<?php else: ?>
						<input type="text" data-field="<?php echo $value->name ?>" name="table[relation_n_n][labelName][<?php echo $value->name ?>]" value="" class="small-input form-control relation-n-n-labelName" />
					<?php endif; ?>
				</div>
				<div class="container-relation-n-n-relationalTable">
					<small>Relational Table</small>
					<select class="chosen relation-n-n-relationalTable" data-field="<?php echo $value->name ?>" name="table[relation_n_n][relationalTable][<?php echo $value->name ?>]" data-placeholder='select table'>
						<option></option>
						<?php foreach ($table as $index => $val): ?>
							<?php if (is_array($Jfields)): ?>
								<option <?php echo ($Jfields[$value->name]->selectMultipleData->relationalTable == $val)? 'selected':'' ?> value="<?php echo $val ?>"><?php echo $val ?></option>
							<?php else: ?>
								<option value="<?php echo $val ?>"><?php echo $val ?></option>
							<?php endif ?>
						<?php endforeach ?>
					</select>
				</div>
				<?php 
				$display = 'style="display: none"';
				if (is_array($Jfields) && $Jfields[$value->name]->type == 'select_multiple' && $Jfields[$value->name]->selectMultipleData->relationalTable): 
					$display = 'style="display: block"';
				$relational_table_fields = $this->db->field_data($Jfields[$value->name]->selectMultipleData->relationalTable);
				else:
					$display = 'style="display: none"';
				endif;
				?>
				<div class="container-relation-n-n-primaryKeyAliasToThisTable" <?php echo $display; ?> >
					<small>Primary Key Alias To This Table </small>
					<select class="chosen relation-n-n-primaryKeyAliasToThisTable" data-field="<?php echo $value->name ?>" name="table[relation_n_n][primaryKeyAliasToThisTable][<?php echo $value->name ?>]" data-placeholder='select field name'>
						<?php if (is_array($relational_table_fields) && isset($relational_table_fields)): ?>
							<?php foreach ($relational_table_fields as $key => $obj): ?>
								<?php 
								if ($Jfields[$value->name]->selectMultipleData->primaryKeyAliasToThisTable == $obj->name):
									$selected = 'selected';
								else: 
									$selected = '';
								endif; ?>
								<option <?php echo $selected ?> value="<?php echo $obj->name ?>"><?php echo $obj->name ?></option>
							<?php endforeach ?>
						<?php endif ?>
					</select>
				</div>

				<?php 
				if (is_array($Jfields) && $Jfields[$value->name]->type == 'select_multiple' && $Jfields[$value->name]->selectMultipleData->selectionTable): 
					$display = 'style="display: block"';
				$selection_table_fields = $this->db->field_data($Jfields[$value->name]->selectMultipleData->selectionTable);
				else:
					$display = 'style="display: none"';
				endif;			
				?>
				<div class="container-relation-n-n-selectionTable" <?php echo $display ?>>
					<small>Selection Table </small>
					<select class="chosen relation-n-n-selectionTable" data-field="<?php echo $value->name ?>" name="table[relation_n_n][selectionTable][<?php echo $value->name ?>]" data-placeholder='select table'>
						<option></option>
						<?php foreach ($table as $index => $val): ?>
							<?php if (is_array($Jfields)): ?>
								<option <?php echo ($Jfields[$value->name]->selectMultipleData->selectionTable == $val)? 'selected':'' ?> value="<?php echo $val ?>"><?php echo $val ?></option>
							<?php else: ?>
								<option value="<?php echo $val ?>"><?php echo $val ?></option>
							<?php endif ?>
						<?php endforeach ?>
					</select>
				</div>
				<div class="container-relation-n-n-primaryKeyAliasToSelectionTable" <?php echo $display ?>>
					<small>Primary Key Alias To Selection Table </small>
					<select class="chosen relation-n-n-primaryKeyAliasToSelectionTable" data-field="<?php echo $value->name ?>" name="table[relation_n_n][primaryKeyAliasToSelectionTable][<?php echo $value->name ?>]" data-placeholder='select field name'>
						<?php if (is_array($selection_table_fields) && isset($selection_table_fields)): ?>
							<?php foreach ($selection_table_fields as $key => $obj): ?>
								<?php 
								if ($Jfields[$value->name]->selectMultipleData->primaryKeyAliasToSelectionTable == $obj->name):
									$selected = 'selected';
								else: 
									$selected = '';
								endif; ?>
								<option <?php echo $selected ?> value="<?php echo $obj->name ?>"><?php echo $obj->name ?></option>
							<?php endforeach ?>
						<?php endif ?>
					</select>
				</div>
				<div class="container-relation-n-n-titleFieldSelectionTable" <?php echo $display ?>>
					<small>Title Field Selection Table </small>
					<select class="chosen relation-n-n-titleFieldSelectionTable" data-field="<?php echo $value->name ?>" name="table[relation_n_n][titleFieldSelectionTable][<?php echo $value->name ?>]" data-placeholder='select field name'>
						<?php if (is_array($selection_table_fields) && isset($selection_table_fields)): ?>
							<?php foreach ($selection_table_fields as $key => $obj): ?>
								<?php 
								if ($Jfields[$value->name]->selectMultipleData->titleFieldSelectionTable == $obj->name):
									$selected = 'selected';
								else: 
									$selected = '';
								endif; ?>
								<option <?php echo $selected ?> value="<?php echo $obj->name ?>"><?php echo $obj->name ?></option>
							<?php endforeach ?>
						<?php endif ?>
					</select>
				</div>
			</div>
		</td>
		<td>
			<select class="chosen validation" name="table[<?php echo $value->name ?>][validation]" data-name='<?php echo $value->name ?>' data-placeholder="Add Rules">
				<option value="" class="address_map checkboxes custom_checkbox custom_option custom_select custom_select_multiple date datetime editor email file input number options password select select_multiple text time true_false year yes_no "></option>
				<option value="required" class="input  file  number  text  datetime  select  password  email  editor  date  yes_no  time  year  select_multiple  options  checkboxes  true_false  address_map  custom_option  custom_checkbox  custom_select_multiple  custom_select" title="no" data-placeholder="">Required</option>
				<option value="max_length" class="input  number  text  select  password  email  editor  yes_no  time  year  select_multiple  options  checkboxes  address_map" title="yes" data-placeholder="">Max Length</option>
				<option value="min_length" class="input  number  text  select  password  email  editor  time  year  select_multiple  address_map" title="yes" data-placeholder="">Min Length</option>
				<option value="valid_email" class="input  email" title="no" data-placeholder="">Valid Email</option>
				<option value="valid_emails" class="input  email" title="no" data-placeholder="">Valid Emails</option>
				<option value="regex" class="input  number  text  datetime  select  password  email  editor  date  yes_no  time  year  select_multiple  options  checkboxes" title="yes" data-placeholder="">Regex</option>
				<option value="decimal" class="input  number  text  select" title="no" data-placeholder="">Decimal</option>
				<option value="valid_url" class="input  text" title="no" data-placeholder="">Valid Url</option>
				<option value="alpha" class="input  text  select  password  editor  yes_no" title="no" data-placeholder="">Alpha</option>
				<option value="alpha_numeric" class="input  number  text  select  password  editor" title="no" data-placeholder="">Alpha Numeric</option>
				<option value="alpha_numeric_spaces" class="input  number  text select  password  editor" title="no" data-placeholder="">Alpha Numeric Spaces</option>
				<option value="valid_number" class="input  number  text  password  editor  true_false" title="no" data-placeholder="">Valid Number</option>
				<option value="valid_datetime" class="input  datetime  text" title="no" data-placeholder="">Valid Datetime</option>
				<option value="valid_date" class="input  datetime  date  text" title="no" data-placeholder="">Valid Date</option>
				<option value="valid_alpha_numeric_spaces_underscores" class="input  text select  password  editor" title="no" data-placeholder="">Valid Alpha Numeric Spaces Underscores</option>
				<option value="matches" class="input  number  text  password  email" title="yes" data-placeholder="any field">Matches</option>
				<option value="valid_json" class="input  text  editor" title="no" data-placeholder="">Valid Json</option>
				<option value="valid_url" class="input  text  editor" title="no" data-placeholder="">Valid Url</option>
				<option value="exact_length" class="input  text  number" title="yes" data-placeholder="0 - 99999*">Exact Length</option>
				<option value="alpha_dash" class="input  text" title="no" data-placeholder="">Alpha Dash</option>
				<option value="integer" class="input  text  number" title="no" data-placeholder="">Integer</option>
				<option value="differs" class="input  text  number  email  password  editor  options  select" title="yes" data-placeholder="any field">Differs</option>
				<option value="is_natural" class="input  text  number" title="no" data-placeholder="">Is Natural</option>
				<option value="is_natural_no_zero" class="input  text  number" title="no" data-placeholder="">Is Natural No Zero</option>
				<option value="less_than" class="input  text  number" title="yes" data-placeholder="">Less Than</option>
				<option value="less_than_equal_to" class="input  text  number" title="yes" data-placeholder="">Less Than Equal To</option>
				<option value="greater_than" class="input  text  number" title="yes" data-placeholder="">Greater Than</option>
				<option value="greater_than_equal_to" class="input  text  number" title="yes" data-placeholder="">Greater Than Equal To</option>
				<option value="in_list" class="input  text  number  select  options" title="yes" data-placeholder="">In List</option>
				<option value="valid_ip" class="input  text" title="no" data-placeholder="">Valid Ip</option>
				<option value="allowed_extensions" class="file required" title="no" data-placeholder="">Allowed Extensions</option>
				<option value="max_width" class="file required" title="no" data-placeholder="">Max Width</option>
				<option value="max_height" class="file required" title="no" data-placeholder="">Max Height</option>
				<option value="max_size" class="file required" title="no" data-placeholder="">Max Size</option>
			</select>
			<!-- validations elements -->
			<?php 

			if (is_array($Jfields) && is_array($Jfields[$value->name]->validation)):

				foreach ($Jfields[$value->name]->validation as $key => $rule) {
					$output = '<div class="box-validation">'; 
					if(is_object($rule)){
						$rule = (array) $rule;
						$rule_index = current(array_keys($rule));
						$rule_value = $rule[$rule_index];
						$output .= '<label><div class="validation-name">' . $rule_index . '</div></label>';
						$output .= '<input type="text" data-field="'.$value->name.'" data-name="'.$rule_index.'" value="'.$rule_value.'" class="small-input validationInputs"/>';
					}else{
						$output .= '<label><div class="validation-name">' . $rule . '</div></label>';
					}
					$output .= '<a class="delete" data-field="'.$value->name.'" data-name="'.$rule.'"><i class="fa fa-trash"></i></a>';
					$output .= '</div>';
					echo $output;
				}

				endif; 

				?>
			</td>
		</tr>
	<?php endforeach ?>
	<tr class="success hidden" id="RNNTemplate">
		<td colspan="7" style="position: relative;">
			<div class="col-sm-12">
				<h4><strong>Relation n-n</strong></h4>
			</div>
			<div class="col-sm-6">
				<p>Label Name : <b class="RNNVLabelName"></b></p>
				<p>Relation Table : <b class="RNNVRelationTable"></b></p>
				<p>PK Alias This : <b class="RNNVPriKeyAliasR"></b></p>
				<p>Priority Field : <b class="RNNVPriority"></b></p>
			</div>
			<div class="col-sm-6">
				<p>Selection Table : <b class="RNNVSelectionTable"></b></p>
				<p>PK Alias Selection : <b class="RNNVPriKeyAliasS"></b></p>
				<p>Title Field : <b class="RNNVTitle"></b></p>
			</div>
			<div class="col-sm-12">
				<p>Appears In: Column: <b class="RNNVInColumn"></b> | Add: <b class="RNNVInAdd"></b> | Edit: <b class="RNNVInEdit"></b> | Details: <b class="RNNVInDetails"></b></p>
			</div>
			<button type="button" class="btn btn-danger btn-flat removeTableRow" style="position: absolute; top: 0; right: 0"><i class="fa fa-trash"></i></button>
		</td>
	</tr>
	<?php if (count($Jfields['r_n_n']) > 0): ?>
		<?php foreach($Jfields['r_n_n'] as $row): ?>
			<tr class="success">
				<td colspan="7" style="position: relative;">
					<div class="col-sm-12">
						<h4><strong>Relation n-n</strong></h4>
					</div>
					<div class="col-sm-6">
						<p>Label Name : <b class="RNNVLabelName"><?php echo $row->RNNFieldName ?></b></p>
						<p>Relation Table : <b class="RNNVRelationTable"><?php echo $row->RNNRelationalTable ?></b></p>
						<p>PK Alias This : <b class="RNNVPriKeyAliasR"><?php echo $row->RNNPrimaryKeyAliasToThisTable ?></b></p>
						<p>Priority Field : <b class="RNNVPriority"><?php echo $row->RNNPriority ?></b></p>
					</div>
					<div class="col-sm-6">
						<p>Selection Table : <b class="RNNVSelectionTable"><?php echo $row->RNNSelectionTable ?></b></p>
						<p>PK Alias Selection : <b class="RNNVPriKeyAliasS"><?php echo $row->RNNPrimaryKeyAliasToSelectionTable ?></b></p>
						<p>Title Field : <b class="RNNVTitle"><?php echo $row->RNNTitleField ?></b></p>
					</div>
					<div class="col-sm-12">
						<p>Appears In : Column: <b class="RNNVInColumn"><?php echo ($row->RNNColumn == '1')?'yes':'no' ?></b> | Add: <b class="RNNVInAdd"><?php echo ($row->RNNAdd == '1')?'yes':'no' ?></b> | Edit: <b class="RNNVInEdit"><?php echo ($row->RNNEdit == '1')?'yes':'no' ?></b> | Details: <b class="RNNVInDetails"><?php echo ($row->RNNDetails == '1')?'yes':'no' ?></b></p>
					</div>
					<button type="button" class="btn btn-danger btn-flat removeTableRow" style="position: absolute; top: 0; right: 0"><i class="fa fa-trash"></i></button>
				</td>
			</tr>
		<?php endforeach ?>
	<?php endif ?>