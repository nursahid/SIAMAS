				<!-- Right Content -->
				<div class="col-xs-12 col-md-8">
				
					<!-- Image Slider -->
					<?php $query = $this->sliders->get_image_sliders(); if ($query->num_rows() > 0) { ?>
					<div class="row slider">
						<div class="col-xs-12 col-md-12">
							<div id="image-slider" class="carousel slide" data-ride="carousel">
								<div class="carousel-inner" role="listbox">
									<?php $idx = 0; foreach($query->result() as $row) { ?>
									<div class="item <?=$idx==0?'active':''?>">
										<img src="<?=base_url('assets/uploads/sliders/'.$row->image);?>" alt="...">
										<div class="carousel-caption">
											<?=$row->caption;?>
										</div>
									</div>
									<?php $idx++; } ?>
								</div>
								<a class="left carousel-control" href="#image-slider" role="button" data-slide="prev">
								<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
								<span class="sr-only">Previous</span>
							  </a>
							  <a class="right carousel-control" href="#image-slider" role="button" data-slide="next">
								<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
								<span class="sr-only">Next</span>
							  </a>
							</div>
						</div>
					</div>
					<?php } ?>
					<!-- End Image Slider -->
	
					<!-- Recent Posts -->
					<!-- Title -->
					<ol class="breadcrumb post-header">
						<li><i class="fa fa-sign-out"></i> TULISAN TERBARU</li>
					</ol>
					<div class="row">
						<div class="col-md-6">
							<?php 
							if (count(array_slice($posts, 0, 1)) > 0) { 
								foreach(array_slice($posts, 0, 1) as $row) {
							?>
							<div class="thumbnail no-border">
								<img src="<?=base_url('assets/uploads/thumbnail/'.$row->featured_image)?>" style="width: 100%; display: block;">
								<div class="caption">
									<h4><a href="<?=site_url('berita/detail/'.$row->path)?>"><?=$row->title?></a></h4>
									<p class="by-author"><?=nama_hari(date('N', strtotime($row->created_at)))?>, <?=tgl_indo(date('Y-m-d', strtotime($row->created_at)))?> | oleh <?=$row->full_name?></p>
									<p align="justify"><?=substr(strip_tags($row->content), 0, 165)?></p>
									<p>
										<a href="<?=site_url('berita/detail/'.$row->path)?>" class="btn btn-success btn-sm" role="button">Selengkapnya <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
									</p>
								</div>
							</div>
							<?php }
							} ?>
						</div>
						
						<div class="col-md-6">
						<?php if (count(array_slice($posts, 1)) > 0) { ?>
							<ul class="media-list main-list">
								<?php foreach(array_slice($posts, 1) as $row) { ?>
								<li class="media">
									<a class="pull-left" href="<?=site_url('berita/detail/'.$row->path)?>">
										<img class="media-object" src="<?=base_url('assets/uploads/thumbnail/'.$row->featured_image)?>" width="80px" height="80px">
									</a>
									<div class="media-body">
										<h4><a href="<?=site_url('berita/detail/'.$row->path)?>"><?=$row->title?></a></h4>
										<p class="by-author"><?=nama_hari(date('N', strtotime($row->created_at)))?>, <?=tgl_indo(date('Y-m-d', strtotime($row->created_at)))?></p>
									</div>
								</li>
								<?php } ?>
							</ul>
						<?php } ?>
						</div>
					</div>
					<!-- End Recent Posts -->
					<!-- Popular Posts -->
					<!-- Title -->
					<ol class="breadcrumb post-header">
						<li><i class="fa fa-sign-out"></i> TULISAN POPULER</li>
					</ol>
					<div class="row">
						<div class="col-md-6">
						<?php 
						$populer = $this->sistem_model->get_join_where('blog','users','id_user','id',array('blog.is_active' => 'Y'),'readtimes','DESC',0,1)->result();
						if (count(array_slice($populer, 0, 1)) > 0) { 
							foreach(array_slice($populer, 0, 1) as $row) {
						?>
							<div class="thumbnail no-border">
								<img src="<?=base_url('assets/uploads/thumbnail/'.$row->featured_image)?>" style="width: 100%; display: block;">
								<div class="caption">
									<h4><a href="<?=site_url('berita/detail/'.$row->path)?>"><?=$row->title?></a></h4>
									<p class="by-author"><?=nama_hari(date('N', strtotime($row->created_at)))?>, <?=tgl_indo(date('Y-m-d', strtotime($row->created_at)))?></p>
									<p align="justify"><?=substr(strip_tags($row->content), 0, 165)?></p>
									<p>
										<a href="<?=site_url('berita/detail/'.$row->path)?>" class="btn btn-success btn-sm" role="button">Selengkapnya <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
									</p>
								</div>
							</div>
						<?php }
						} ?>
						</div>
						<div class="col-md-6">
						<?php 
						$populer2 = $this->sistem_model->get_join_where('blog','users','id_user','id',array('blog.is_active' => 'Y'),'readtimes','DESC',0,5)->result();
						if (count(array_slice($populer2, 0, 5)) > 0) { 
						?>
							<ul class="media-list main-list">
								<?php foreach(array_slice($populer2, 0, 5) as $row) { ?>
								<li class="media">
									<a class="pull-left" href="<?=site_url('berita/detail/'.$row->path)?>">
										<img class="media-object" src="<?=base_url('assets/uploads/thumbnail/'.$row->featured_image)?>" width="80px" height="80px">
									</a>
									<div class="media-body">
										<h4><a href="<?=site_url('berita/detail/'.$row->path)?>"><?=$row->title?></a></h4>
										<p class="by-author"><?=nama_hari(date('N', strtotime($row->created_at)))?>, <?=tgl_indo(date('Y-m-d', strtotime($row->created_at)))?></p>
									</div>
								</li>
							<?php } ?>
							</ul>
						<?php } ?>
						</div>
					</div>
					<!-- End Popular Posts -->
					
				</div>
				<!-- Sidebar -->
				<div class="col-xs-12 col-md-4">
				<?=$this->load->view('template/sidebar');?>
				</div>