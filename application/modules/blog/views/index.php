<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-lg-offset-2 col-md-offset-2">
			<?php foreach ($model as $key => $value): ?>
				<div class="box box-solid">
					<div class="box-header with-border">
						<h3><a href="<?php echo site_url('blog/read/' . $value->path . '/' . $value->id) ?>" title="<?php echo $value->title ?>"><?php echo $value->title ?></a></h3>
						<div style="font-size: 10pt" class="row">
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<p><i class="fa fa-analog"></i><?php echo $value->created_at ?></p>
							</div>
						</div>
					</div>
					<div class="box-body">
						<section>
							<?php echo strlen($value->content) > 50 ? substr($value->content, 0, 200) . '...' : $value->content ?>
						</section>
						<section>
							<p>Created by <strong><?php echo $value->username ?></strong></p>
						</section>
					</div>
				</div>
			<?php endforeach ?>
			<?php echo $pagination ?>
		</div>
	</div>
</div>