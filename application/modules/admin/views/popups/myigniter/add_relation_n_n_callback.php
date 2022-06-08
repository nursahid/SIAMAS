<div class="modal fade" id="relationNNForm">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><i class="fa fa-plus-circle"></i> Add new relation_n_n field <a class="badge" href="https://www.grocerycrud.com/examples/set_a_relation_n_n" target="_blank"> <i class="fa fa-external-link"></i> Learn how to use this feature </a></h4>
			</div>
			<div class="modal-body" id="rNNForm">
				<div class="form-group">
					<label for="RNNFieldName" class="col-sm-2 control-label">Label name</label>
					<div class="col-sm-10">
						<label class="control-label hidden" for="RNNFieldName"></label>
						<input type="text" class="form-control" name='RNNFieldName' id="RNNFieldName" placeholder="Label name">
					</div>
				</div>
				<div class="form-group">
					<label for="relationTable" class="col-sm-2 control-label">Relation Table</label>
					<div class="col-sm-4">
						<label class="control-label hidden" for="RNNRelationalTable"></label>
						<select class="chosen-select form-control" id='RNNRelationalTable' data-placeholder="Select table">
							<option value></option>
							<?php foreach ($tables as $index => $val): ?>
								<option value="<?php echo $val ?>"><?php echo $val ?></option>
							<?php endforeach ?>
						</select>
					</div>
					<label for="relationTable" class="col-sm-2 control-label">Selection Table</label>
					<div class="col-sm-4">
						<label class="control-label hidden" for="RNNSelectionTable"></label>
						<select class="chosen-select form-control" id='RNNSelectionTable' data-placeholder="Select table">
							<option value></option>
							<?php foreach ($tables as $index => $val): ?>
								<option value="<?php echo $val ?>"><?php echo $val ?></option>
							<?php endforeach ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="relationTable" class="col-sm-2 control-label">PK alias this</label>
					<div class="col-sm-4">
						<label class="control-label hidden" for="RNNPrimaryKeyAliasToThisTable"></label>
						<select class="chosen-select form-control" id='RNNPrimaryKeyAliasToThisTable' data-placeholder="primary_key_alias_to_this_table">
							<option value></option>
						</select>
					</div>
					<label for="relationTable" class="col-sm-2 control-label">PK alias selection</label>
					<div class="col-sm-4">
						<label class="control-label hidden" for="RNNPrimaryKeyAliasToSelectionTable"></label>
						<select class="chosen-select form-control" id='RNNPrimaryKeyAliasToSelectionTable' data-placeholder="primary_key_alias_to_selection_table">
							<option value></option>
						</select>
					</div>
				</div>
				<div class="form-group" >
<!-- 					<label for="RNNPriority" class="col-sm-2 control-label">Priority field <small><i>(optional)</i></small></label>
					<div class="col-sm-4">
						<select class="chosen-select form-control " id="RNNPriority" data-placeholder="Title Field at selection table">
							<option ></option>
						</select>
					</div> -->
					<label for="RNNTitleField" class="col-sm-2 control-label">Title field</label>
					<div class="col-sm-4">
						<label class="control-label hidden" for="RNNTitleField"></label>
						<select class="chosen-select form-control " id="RNNTitleField" data-placeholder="Title Field at selection table">
							<option value></option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">View In</label>
					<div class="col-sm-10">
						<div class="checkbox">
							<label>
								<input id='RNNColumn' class="check" type="checkbox" value="1" checked="checked">
								Column
							</label>
							<label>
								<input id='RNNAdd' class="check" id='RNNAdd' type="checkbox" value="1" checked="checked">
								Add
							</label>
							<label>
								<input id='RNNEdit' class="check" id='RNNEdit' type="checkbox" value="1" checked="checked">
								Edit
							</label>
							<label>
								<input id='RNNDetails' class="check" id='RNNDetails' type="checkbox" value="1" checked="checked">
								Detail
							</label>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-warning" id='addRNNBtn'>Save changes</button>
			</div>
		</div>
	</div>
</div>