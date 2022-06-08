			<!-- box -->
			<div class="box box-success">
		    <form action="<?php echo base_url('backup/restore_db');?>" method="post" enctype="multipart/form-data">
				<div class="box-header with-border">
					<h3 class="box-title">Restore File Backup</h3>
				</div>
				<div class="box-body">				
					<div class="form-group">
						<?php echo $message; ?>
						<label for="exampleInputFile">File input</label>
						<input type="file" name="datafile" id="exampleInputFile" value="<?php echo set_value('datafile'); ?>">
						<?php echo form_error('datafile'); ?>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<button type="submit" class="btn btn-primary"><i class="fa fa-file-pdf-o"></i> Restore File Backup</button>
				</div>
			</form>	
			</div>
			<!-- /.box -->