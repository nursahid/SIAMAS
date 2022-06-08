<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-lg-offset-2 col-md-offset-2">
			<div class="box box-solid">
				<div class="box-header with-border">
					<h1><?php echo $model->title ?></h1>
					<div style="font-size: 10pt" class="row">
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<p><?php foreach ($categories as $key => $value): echo $key != 0 ? ', ' : ''; echo $value->category; endforeach ?></p>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<p class="text-right"><i class="fa fa-analog"></i><?php echo $model->created_at ?></p>
						</div>
					</div>
				</div>
				<div class="box-body">
					<section>
						<?php echo $model->content ?>
					</section>
					<section>
						<p>Created by <strong><?php echo $model->username ?></strong></p>
					</section>
				</div>
			</div>
		</div>
	</div>
</div>