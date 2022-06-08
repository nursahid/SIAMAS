<div class="box box-default">
	<div class="box-header with-border">
		<i class="fa fa-image"></i>
		<h3 class="box-title">List Image</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-xs-6">
				<div class="media-lib-action">
					<button type="button" id="show-upload" class="btn btn-success btn-sm btn-flat"><i class="fa fa-plus-circle"></i> Add Media</button>
					<button type="button" id="bulk-select" class="btn btn-default btn-sm btn-flat">Bulk Select</button>
				</div>
				<div class="media-lib-select" style="display: none;">
					<button type="button" id="cancel-select" class="btn btn-default btn-sm btn-flat">Cancel Selection</button>
					<button type="button" id="delete-select" class="btn btn-danger btn-sm btn-flat"><i class="fa fa-trash"></i> Delete Selected</button>
					<button type="button" id="all-select" class="btn btn-success btn-sm btn-flat">Select all</button>
				</div>
			</div>
		</div>
		<div class="media-lib-drop" id="upload-container" style="display: none;">
			<span class="media-lib-close" id="upload-close">
				<i class="fa fa-close"></i>
			</span>
			<div class="media-upload-text">
				<p>Drop files here to upload.</p>
				<p>Maximum 2MB.</p>
				<div class="progress" style="display: none">
					<div class="progress-bar progress-bar-aqua" id="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100">
					</div>
				</div>
			</div>
			<?php echo form_open('media/upload', ['id' => 'upload-zone', 'class' => 'dz']); ?>
				<div class="fallback">
				    <input name="file" type="file" multiple />
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>
<div class="media-lib-container">
	<?php if ($media): ?>
		<?php foreach ($media as $key => $value): ?>
			<a data-toggle="modal" href='#modal-id' data-id="<?php echo $value->id ?>" data-file="<?php echo $value->file ?>" data-name="<?php echo $value->name ?>" data-date="<?php echo $value->uploaded_at ?>" data-user="<?php echo $value->username ?>" data-ext="<?php echo $value->ext ?>" class="media-lib-grid normal">
				<img src="<?php echo base_url('assets/uploads/image/' . $value->name . '-thumb' . $value->ext) ?>" alt="Image">
				<div class="selected-check">
					<i class="fa fa-check-circle"></i>
				</div>
			</a>
		<?php endforeach ?>
	<?php else: ?>
		<h2 class="title-none text-center">There is no files uploaded.</h2>
		<a data-toggle="modal" href='#modal-id' data-id="" data-file="" data-name="" data-date="" data-user="" class="media-lib-grid normal" style="display: none;">
			<img src="" alt="Image">
			<div class="selected-check">
				<i class="fa fa-check-circle"></i>
			</div>
		</a>
	<?php endif ?>
	<button type="button" class="btn btn-default btn-flat btn-block" id="load-more" style="<?php echo $count ? 'display:none' : 'display:block' ?>">Load more</button>
</div>
<!-- Modal -->
<div class="modal fade" id="modal-id">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Image Detil</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-8 col-lg-8">
						<img src="<?php echo base_url('assets/uploads/image/myIgniter-wide.jpg') ?>" class="img-responsive media-lib-image" alt="Image">
					</div>
					<div class="col-md-4 col-lg-4">
						<strong>File name : </strong><span class="media-lib-name"></span><br>
						<strong>Uploaded on : </strong><span class="media-lib-date"></span><br>
						<strong>Uploaded by : </strong><span class="media-lib-user"></span><br>
						<hr>
						<div class="form-group">
							<label for="input" class="control-label">Url :</label>
							<input type="text" class="form-control input-sm media-lib-url" value="<?php echo base_url() ?>" readonly="readonly">
						</div>
						<?php echo form_open('media/delete', ['id' => 'form-delete']); ?>
							<input type="hidden" name="id" id="media-input-id" value="">
							<input type="hidden" name="name" id="media-input-name" value="">
							<input type="hidden" name="ext" id="media-input-ext" value="">
							<button type="submit" class="btn btn-danger btn-flat"><i class="fa fa-trash"></i> Delete</button>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>