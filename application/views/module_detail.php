<div class="box box-default">
	<div class="box-header with-border">
		<i class="fa fa-th"></i>
		<h3 class="box-title"><?php echo $module['name'] ?></h3>
	</div><!-- /.box-header -->
	<div class="box-body">
		<div class="form-horizontal">
			<div class="form-group">
				<label for="input" class="col-sm-2 control-label">Name</label>
				<div class="col-sm-10">
					<p class="readonly_label"><?php echo $module['name'] ?></p>
				</div>
			</div>
			<div class="form-group">
				<label for="input" class="col-sm-2 control-label">Menu Link</label>
				<div class="col-sm-10">
					<div class="readonly_label">
					<?php foreach ($module['menu_link'] as $link => $link_desc): ?>
						<p>
						<a href="<?php echo site_url($link); ?>"><?php echo $link ?></a><br>
						<?php echo $link_desc ?>	
						</p>
					<?php endforeach ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="input" class="col-sm-2 control-label">Table</label>
				<div class="col-sm-10">
					<p class="readonly_label"><?php 
						if (is_array($module['table'] )) {
							foreach ($module['table']  as $table) {
								echo $table.'<br>';
							}
						} else{
							echo $module['table'];
						}
					?></p>
				</div>
			</div>
			<div class="form-group">
				<label for="input" class="col-sm-2 control-label">Description</label>
				<div class="col-sm-10">
					<p class="readonly_label"><?php echo $module['description'] ?></p>
				</div>
			</div>
		</div>
	</div><!-- /.box-body -->
</div>
<script>
	$(function(){
		$('#module').addClass('active');
	});
</script>