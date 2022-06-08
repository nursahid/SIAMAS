<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
			<div class="col-xs-12 col-md-8">
				<ol class="breadcrumb post-header">
					<li><?=$title?></li>
				</ol>
				<?php if($posts || $pages) { ?>
				<div class="thumbnail no-border">
					<div class="caption">
						<?php if($posts) { ?>
							<?php if ($posts->num_rows() > 0) { ?>
								<h3>Tulisan</h3>
								<?php foreach($posts->result() as $row) { ?>
									<h4><a href="<?=site_url('berita/detail/'.$row->path)?>"><?=$row->title?></a></h4>
									<p style="text-align: justify;"><?=word_limiter(strip_tags($row->content), 30)?></p>
								<?php } ?>
							<?php } ?>
						<?php } ?>
						
						<?php if($pages) { ?>
							<?php if ($pages && $pages->num_rows() > 0) { ?>
								<hr>
								<h3>Halaman</h3>
								<?php foreach($pages->result() as $row) { ?>
									<h4><a href="<?=site_url('page/'.$row->slug)?>"><?=$row->title?></a></h4>
									<p style="text-align: justify;"><?=word_limiter(strip_tags($row->content), 30)?></p>
								<?php } ?>
							<?php } ?>
						<?php } ?>
						<?php if ($pages && $pages->num_rows() == 0 && $posts && $posts->num_rows() == 0) { ?>
							<p class="text-red" style="font-family:'Georgia,Times';font-size:28px;"><blink>Hasil pencarian tidak ditemukan.</blink></p>
						<?php } ?>	
					</div>
				</div>
				<?php } ?>
			</div>
			<!-- Sidebar -->
			<div class="col-xs-12 col-md-4">
				<?=$this->load->view('template/sidebar');?>
			</div>