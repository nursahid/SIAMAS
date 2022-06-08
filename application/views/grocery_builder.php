<div class="row">
	<div class="col-lg-12">
		<div><?php echo $output; ?></div>
	</div>
</div>
<div class="modal fade" id="exportPHP">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><i class="fa fa-code"></i> Export</h4>
			</div>
			<div class="modal-body">
				<p class="text-right">
					<button type="button" data-clipboard-target="#PHPCode" class="copy btn btn-default btn-flat"><i class="fa fa-copy"></i> Copy</button> 
					<?php if ($this->uri->segment(2) != 'page_builder'): ?>
						<button type="button" id="create-module" data-id="" class="btn btn-success btn-flat"><i class="fa fa-file"></i> Create Module</button>
					<?php endif ?>
				</p>
				<pre><code class="html" id="PHPCode"></code></pre>
			</div>
		</div>
	</div>
</div>

