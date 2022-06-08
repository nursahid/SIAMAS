<div class="box box-default">
	<div class="box-header with-border">
		<i class="fa fa-th"></i>
		<h3 class="box-title">Modules</h3>
	</div><!-- /.box-header -->
	<div class="box-body">
		<p><a class="btn btn-primary btn-flat" data-toggle="modal" href='#formUploadModule'><i class="fa fa-upload"></i> Install</a></p>
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th style="width: 50px">#</th>
					<th>Module</th>
					<th class="text-right">Action</th>
				</tr>
			</thead>
			<tbody>
			<?php if (count($modules) > 2): ?>				
				<?php $i = 1; foreach ($modules as $key => $path): ?>
					<?php if ($key != 0 && $key != 1): ?>
						<tr>
							<th><?php echo $i ?></th>
							<td>
								<strong><a href="<?php echo site_url('myigniter/module_detail/'.$path); ?>" title="Detail"><?php echo $path ?></a></strong>
							</td>
							<td class="text-right">
								<a href="<?php echo site_url('myigniter/module_delete/'.$path); ?>" title="Remove"><i class="fa fa-trash"></i> Remove</a>								
							</td>
						</tr>
					<?php $i++; endif ?>
				<?php endforeach ?>
			<?php else: ?>
				<td colspan="3">No module installed</td>
			<?php endif ?>
			</tbody>
		</table>
	</div><!-- /.box-body -->
</div>

<div class="modal fade" id="formUploadModule">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><i class="fa fa-upload"></i> Install Module</h4>
			</div>
			<div class="modal-body">
				<?php echo form_open_multipart('myigniter/module_install'); ?>
					<div class="form-group">
                      <input type="file" name="module" id="exampleInputFile">
                      <p class="help-block">Chose zip file to install</p>
                    </div>
                    <button type="submit" class="btn btn-success btn-flat btn-block">Install</button>
                <?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>