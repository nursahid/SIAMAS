<div class="box box-default">
	<div class="box-header with-border">
		<i class="fa fa-th"></i>
		<h3 class="box-title">Tables for <b><?php echo $this->db->database ?></b> database</h3>
	</div><!-- /.box-header -->
	<div class="box-body">
		<?php //dump_exit($tables); ?>
		<p><a class="btn btn-primary btn-flat" data-toggle="modal" href='#formCreateTable'><i class="fa fa-plus-circle"></i> Add new table</a></p>
		<table class="table table-hover">
			<thead>
				<tr>
					<th style="width: 50px">#</th>
					<th><i class="fa fa-table"></i> Table</th>
					<th><i class="fa fa-columns"></i> Rows</th>
					<th class="text-right"><i class="fa fa-cogs"></i> Action</th>
				</tr>
			</thead>
			<tbody>
				<?php if (count($tables) > 2): ?>				
					<?php $i = 1; foreach ($tables as $table): ?>			
					<tr>
						<th><?php echo $i ?></th>
						<td>
							<strong><?php echo $table ?></strong>
						</td>
						<td> <span class="label label-info"><i class="fa fa-dot-circle-o"></i> <?php echo $this->db->count_all($table); ?> rows</span></td>
						<td class="text-right">
							<a class="migrateTable btn btn-flat btn-success btn-sm" data-name="<?php echo $table ?>" href="#" title="Generate Migration File">
								<i class="fa fa-magic"></i> Generate Migration 
							</a>
							<!-- <a class="edit_table btn btn-flat btn-info" data-name="<?php echo $table ?>" href="#" title="Edit Structure"><i class="fa fa-edit"></i> Structure</a>								 -->
							<a class="delete_table btn btn-flat btn-danger btn-sm" data-name="<?php echo $table ?>" href="#" title="Remove"><i class="fa fa-trash"></i> Remove</a>								
						</td>
					</tr>
					<?php $i++; endforeach ?>
				<?php else: ?>
					<td colspan="3">No tables founded</td>
				<?php endif ?>
			</tbody>
		</table>
	</div><!-- /.box-body -->
</div>

<div class="modal fade" id="formCreateTable">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><i class="fa fa-table"></i> Create new table</h4>
			</div>
			<div class="modal-body">
				<?php echo form_open('myigniter/create_table',['id' => 'creatTblForm']); ?>
				<div class="form-group">
					<input type="text" name="table_name" placeholder="Table name" class="form-control" id="table_name">
				</div>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Field Name</th>
							<th>Data Type</th>
							<th>Length / Set</th>
							<th class="text-center"><a href="#" title="Primary Key"><i class="fa fa-key"></i></a></th>
							<th>Unsigned</th>
							<th>NULL</th>
							<th>Zerofill</th>
							<th>Default</th>
							<th></th>
						</tr>
					</thead>
					<tbody id="list-fileds">
						<tr class="row_field" style="display: none">
							<td width="150px">
								<input type="text" name="field[name][]" placeholder="Field name" required="required" class="form-control">
							</td>
							<td width="170px">
								<select name="field[type][]" class="form-control chosen" style="width: 100%">
									<option value="">Select type</option>
									<?php foreach ($type as $key => $value): ?>
										<optgroup label="<?php echo $key ?>">
											<?php foreach ($value as $val): ?>
												<option <?php echo ($val == 'INT')?"selected":'' ?> value="<?php echo $val ?>"><?php echo $val ?></option>
											<?php endforeach ?>
										</optgroup>
									<?php endforeach ?>
								</select>
							</td>
							<td width="100px"><input type="text" name="field[length][]" placeholder="length/set"  value="" class="form-control"></td>
							<td class="text-center"><input class="check" type="checkbox"><input type="hidden" name="field[primary_key][]" value="0"></td>
							<td class="text-center"><input class="check" type="checkbox"><input type="hidden" name="field[unsigned][]" value="0"></td>
							<td class="text-center"><input class="check" type="checkbox"><input type="hidden" name="field[null][]" value="0"></td>
							<td class="text-center"><input class="check" type="checkbox"><input type="hidden" name="field[zerofill][]" value="0"></td>
							<td width="80px">
								<select name="field[value][]" class="form-control chosen" >
									<option value="">Default Value</option>
									<option value="">No Default Value</option>
									<option value="NULL">NULL</option>
									<option value="Auto Increment">Auto Increment</option>
								</select>
							</td>
							<td>
								<a href="#!" title="Delete" class="text-red delete-field"><i class="fa fa-close"></i></a>
							</td>
						</tr>
					</tbody>
				</table>
				<div class="form-group">
					<button type="button" class="btn btn-default btn-sm btn-block btn-flat" id="add-filed-row"><i class="fa fa-plus-circle"></i> Add Field</button>
				</div>
				<button type="submit" id="createTableBtn" class="btn btn-success btn-flat">Create</button>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>