<?php 
$this->load->model('post_model','blogpost');
$this->load->model('pegawai/pegawai_model');
$setting = $this->settings->get();
$kepsek = $this->pegawai_model->get_by(array('id_user'=>3, 'jabatan'=>1));
//var_dump($kepsek);
?>
					<div class="thumbnail">
						<img src="<?=base_url('assets/uploads/image/').$kepsek->foto;?>" alt="<?=$setting['namakepsek'];?>" style="width: 50%">
						<div class="caption">
							<h3><?=$setting['namakepsek'];?></h3>
							<?=$setting['pengantar_kepsek'];?>
							<p>
								<a href="page/sambutan-kepala-sekolah" class="btn btn-success btn-sm" role="button">Selengkapnya <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
							</p>
						</div>
					</div>
						
					<div class="form-group has-feedback">
						<form action="<?=site_url('pencarian')?>" method="POST">
						<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
						<input type="text" name="keyword" id="keyword" class="form-control" placeholder="Cari artikel atau berita" autocomplete="off">
						<span class="fa fa-search form-control-feedback"></span>
						</form>
					</div>

					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">KATEGORI</h3>
						</div>
						<div class="list-group">
						<?php
						$kategoris = $this->db->get('category');
						if ($kategoris->num_rows() > 0) {
						?>
							<?php foreach($kategoris->result() as $row) { ?>
							<a href="<?=site_url('kategori/'.$row->slug);?>" title="<?=$row->description;?>" class="list-group-item"><?=$row->category;?></a>
							<?php } ?>
						<?php } ?>
						</div>
					</div>
					
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">ARTIKEL TERBARU</h3>
						</div>
						<div class="list-group">
						
							<ul>
								<?php 
									$populer = $this->sistem_model->get_join_where('blog','users','id_user','id',array('blog.is_active' => 'Y'),'readtimes','DESC',0,5);
									foreach ($populer->result_array() as $data) {
									$total_komentar = $this->sistem_model->view_where('comments',array('id_blog' => $data['id']))->num_rows();
									echo "<li class='recent-post'>
											<div class='post-img'>";
												if ($data['featured_image'] ==''){
													echo "<a href='".base_url('berita/detail/').$data['path']."' class='hover-effect'><img src='".base_url('assets/img/blog/small_no-image.jpg')."' alt='' class='img-responsive' /></a>";
												}else{
													echo "<a href='".base_url('berita/detail/').$data['path']."' class='hover-effect'><img src='".base_url('assets/uploads/thumbnail/').$data['featured_image']."' alt='' class='img-responsive' /></a>";
												}
											echo "</div>
											<div class='article-content'>
												<h5><a href='".base_url('berita/detail/').$data['path']."'>$data[title]</a><a href='".base_url('berita/detail/').$data['path']."/".$data['id']."' class='h-comment'> <br />[ <small>$total_komentar komentar</small> ]</a></h5>
												<p><small><i class='fa fa-calendar' data-original-title='' title=''></i> ".tgl_indo(date('Y-m-d', strtotime($data['created_at'])))."</small></p>
											</div>
										</li>
										<hr />";
									}
								?>
							</ul>
							
						</div>
					</div>	
					
					<?php 
					$query = $this->blogpost->get_archive_year(); if ($query->num_rows() > 0) { ?>
					<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
						<?php $idx = 0; foreach($query->result() as $row) { ?>
						<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="heading_<?=$row->year?>">
								<h4 class="panel-title">
								<a role="button" data-toggle="collapse" data-parent="#accordion" href="#archive_<?=$row->year?>" aria-expanded="true" aria-controls="archive_<?=$row->year?>">ARSIP <?=$row->year?></a>
								</h4>
							</div>
							<div id="archive_<?=$row->year?>" class="panel-collapse collapse <?=$idx==0?'in':''?>" role="tabpanel" aria-labelledby="heading_<?=$row->year?>">
								<div class="list-group">
								<?php $archives = $this->blogpost->get_archives($row->year); if ($archives->num_rows() > 0) { ?>
									<?php foreach($archives->result() as $archive) { ?>
										<a href="<?=site_url('arsip/'.$row->year.'/'.$archive->code)?>" class="list-group-item"><?=bulan($archive->code)?> (<?=$archive->count?>)</a>
									<?php } ?>
								<?php } ?>
								</div>
							</div>
						</div>
						<?php $idx++; } ?>
					</div>
					<?php } ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">TAGS</h3>
						</div>
						<div class="panel-body">
							<div class="tagcloud01">
								<ul>
								<?php 
								$query = $this->blogpost->get_tags();
								if ($query->num_rows() > 0) {
								foreach ($query->result() as $row) {
									echo '<li>'.anchor(site_url('tag/'.$row->slug), $row->tag).'</li>';
								}
								}
								?>
								</ul>
							</div>
						</div>
					</div>