<div class="container">
	<div class="row">
		<div class="col-xs-12 col-md-8 col-md-offset-1">			
			<div class="box">
				<div class="box-header">
					<h1><?php echo $content->title ?></h1>
				</div><!-- /.box-header -->
				<div class="box-body">
					<?php echo htmlspecialchars_decode($content->content) ?>
				</div><!-- /.box-body -->
			</div>
		</div>	
			<!-- Sidebar -->
			<div class="col-xs-12 col-md-4">
				<?=$this->load->view('template/sidebar');?>
			</div>
	</div>
</div>
