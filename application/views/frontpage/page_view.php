			<div class="col-xs-12 col-md-8">
				<div class="thumbnail no-border">
				<?php
				if($page->featured_image == NULL) {
					$tampil = '';
				} else {
					$tampil = '<img src="'.base_url('assets/uploads/thumbnail/'.$page->featured_image.'').'" style="width: 100%; display: block;">';
				}
				echo $tampil;
				?>
					<div class="caption">
						<h3><?=$page->title?></h3>
						<?=html_entity_decode($page->content);?>
						<div id="share1"></div>
					</div>
				</div>
			</div>

			<!-- Sidebar -->
			<div class="col-xs-12 col-md-4">
				<?=$this->load->view('template/sidebar');?>
			</div>
			
			<script type="text/javascript">
			$("#share1").jsSocials({
				shares: ["email", "twitter", "facebook", "googleplus", "linkedin", "pinterest", "stumbleupon", "whatsapp"]
			});
			</script>