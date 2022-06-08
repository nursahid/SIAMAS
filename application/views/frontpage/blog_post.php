<style type="text/css">
#blog-section{margin-top:40px;margin-bottom:80px;}
.content-title{padding:5px;background-color:#fff;}
.content-title h3 a{color:#34495E;text-decoration:none; transition: 0.5s;}
.content-title h3 a:hover{color:#F39C12; }
.content-footer{background-color:#16A085;padding:10px;position: relative;}
.content-footer span a {
    color: #fff;
    display: inline-block;
    padding: 6px 5px;
    text-decoration: none;
    transition: 0.5s;
}
.content-footer span a:hover{     
    color:#F39C12;   
}

/*recent-post-col////////////////////*/
.widget-sidebar {
    background-color: #fff;
    padding: 5px;
    margin-top: 25px;
}

.title-widget-sidebar {
    font-size: 14pt;
    border-bottom: 2px solid #e5ebef;
    margin-bottom: 15px;
    padding-bottom: 10px;    
    margin-top: 0px;
}

.title-widget-sidebar:after {
    border-bottom: 2px solid #f1c40f;
    width: 150px;
    display: block;
    position: absolute;
    content: '';
    padding-bottom: 10px;
}

.recent-post{width: 100%;height: 80px;list-style-type: none;}
.post-img img {
    width: 100px;
    height: 70px;
    float: left;
    margin-right: 15px;
    border: 5px solid #16A085;
    transition: 0.5s;
}

.recent-post a {text-decoration: none;color:#34495E;transition: 0.5s;}
.post-img, .recent-post a:hover{color:#F39C12;}
.post-img img:hover{border: 5px solid #F39C12;}

/*===============ARCHIVES////////////////////////////*/



button.accordion {
    background-color: #16A085;
    color: #fff;
    cursor: pointer;
    padding: 18px;
    width: 100%;
    border: none;
    text-align: left;
    outline: none;
    font-size: 15px;
    transition: 0.4s;
}

button.accordion.active, button.accordion:hover {
    background-color: #F39C12;color: #fff;
}

button.accordion:after {
    content: '\002B';
    color: #fff;
    font-weight: bold;
    float: right;
    margin-left: 5px;
}

button.accordion.active:after {
    content: "\2212";
}

.panel {
    padding: 0 18px;
    background-color: white;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.2s ease-out;
}


/*categories//////////////////////*/

.categories-btn{
    background-color: #F39C12;
    margin-top:30px;
    color: #fff;
    cursor: pointer;
    padding: 18px;
    width: 100%;
    border: none;
    text-align: left;
    outline: none;
    font-size: 15px;
    transition: 0.4s;
    
}
.categories-btn:after {
    content: '\25BA';
    color: #fff;
    font-weight: bold;
    float: right;
    margin-left: 5px;
}
.categories-btn:hover {
    background-color: #16A085;color: #fff;
}

.form-control{border-radius: 0px;}

.btn-warning {
    border-radius: 0px;
    background-color: #F39C12;
    margin-top: 15px;
}
.input-group-addon{border-radius: 0px;}

</style>
    <!-- Page Content -->
    <div class="container">
		<div class="row">
			<!-- Post Content Column -->
			<div class="col-lg-8" id="blog-section" class="">
					<ol class="breadcrumb post-header">
						<li><i class="fa fa-sign-out"></i> TULISAN TERBARU</li>
					</ol>
				<?php foreach ($datas as $key => $value): ?>
					<div class="col-md-12">
						<div class="row">
						  <div class="col-md-12">
							<h4><strong><a href="<?php echo site_url('berita/detail/' . $value->path) ?>" title="<?php echo $value->title ?>"><?php echo $value->title ?></a></strong></h4>
						  </div>
						</div>
						<div class="row">
						  <div class="col-md-4">
							<a href="#" class="thumbnail">
								<img src="<?php echo base_url('assets/uploads/thumbnail/'). $value->featured_image ?>" alt="" >
							</a>
						  </div>
						  <div class="col-md-8">      
								<p align="justify"><?php echo strlen($value->content) > 50 ? substr($value->content, 0, 200) . '...' : $value->content ?>
								<p>
								  <i class="icon-user"></i> oleh <a href="#"><?php echo $value->username ?></a> 
								  | <i class="icon-calendar"></i> <?=nama_hari(date('N', strtotime($value->created_at)))?>, <?=tgl_indo(date('Y-m-d', strtotime($value->created_at)))?>
								  <?php $total_komentar = $this->sistem_model->view_where('comments',array('id_blog' => $value->id))->num_rows();?>
								  | <i class="icon-comment"></i> <a href="#"><?=$total_komentar?> Komentar</a>
								  | <i class="icon-tags"></i> Tags : 
								  <?php 
									$cek_tags = $this->posttags->count_by('id_blog',$value->id);
									if ($cek_tags > 0) {
										$post_tags = $this->posttags->get_tags($value->id)->result();
										foreach ($post_tags as $tag) {
											echo '<a style="margin-right:3px;" href="'.base_url('tag/'.strtolower(trim($tag->tag))).'">';
											echo '<span class="label label-success">';
											echo '<i class="fa fa-tags"></i> '.ucwords(strtolower(trim($tag->tag)));
											echo '</span>';
											echo '</a>';
										}
									}
								  ?>
								</p>
								<a class="btn btn-xs btn-warning" href="<?php echo site_url('berita/detail/' . $value->path) ?>">Read more</a></p>
						  </div>
						</div>
						<div class="row">
						  <div class="col-md-12">
							<p></p>
							
						  </div>
						</div>
					</div>
					<hr/>
				<?php endforeach ?>
				<?php echo $pagination ?>

			</div>

			<!-- Sidebar Widgets Column -->
			<div class="col-md-4">
				<!--SEARCH-->
				<div class="widget-sidebar">
					<h2 class="title-widget-sidebar">PENCARIAN</h2>
					<p>Silakan gunakan form ini untuk mencari berita</p>  
					<form action="<?=site_url('pencarian')?>" method="POST">
					<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></span>
						<input type="text" name="keyword" id="keyword" class="form-control" placeholder="Masukkan Pencarian + Enter.....">
					</div>
					</form>
				</div>
				
				<!--RECENT POST-->
				<div class="widget-sidebar">
					<h2 class="title-widget-sidebar">RECENT POST</h2>
					<div class="content-widget-sidebar">
						<ul>
							<?php 
								$populer = $this->sistem_model->get_join_where('blog','users','id_user','id',array('blog.is_active' => 'Y'),'readtimes','DESC',0,5);
								
								foreach ($populer->result_array() as $data) {
									//var_dump($data['id']);
									$qry = $this->sistem_model->view_where('comments',array('id_blog' => $data['id']));
									$total_komentar = $qry->num_rows();
									$dcom = $qry->row();
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
				<!--KATEGORI-->
				<div class="widget-sidebar">
					<h2 class="title-widget-sidebar">KATEGORI</h2>
					<?php
					$kategoris = $this->db->get('category');
					if ($kategoris->num_rows() > 0) {
						foreach($kategoris->result() as $row) { ?>
							<a href="<?=site_url('kategori/'.$row->slug);?>" title="<?=$row->description;?>" class="list-group-item"><?=$row->category;?></a>
					<?php }
					} ?>
				</div>
				<!--ARSIP-->
				<div class="widget-sidebar">
					<h2 class="title-widget-sidebar">ARSIP</h2>
					<?php 
					$query = $this->blogpost->get_archive_year(); if ($query->num_rows() > 0) { 
						$no = 0; 
						foreach($query->result() as $row) { 
					?>
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" data-parent="#accordion" href="#archive_<?=$row->year?>" aria-expanded="true" aria-controls="archive_<?=$row->year?>">ARSIP <?=$row->year?></a>
							</h4>
							<div id="archive_<?=$row->year?>" class="panel-collapse collapse <?=$idx==0?'in':''?>" role="tabpanel" aria-labelledby="heading_<?=$row->year?>">
							<div class="list-group">
								<?php $archives = $this->blogpost->get_archives($row->year); if ($archives->num_rows() > 0) { ?>
									<?php foreach($archives->result() as $archive) { ?>
										<a href="<?=site_url('arsip/'.$row->year.'/'.$archive->code)?>" class="list-group-item"><?=bulan($archive->code)?> (<?=$archive->count?>)</a>
									<?php } ?>
								<?php } ?>
								</div>
							</div>
					<?php 
						$no++;
						}
					} ?>
				</div>
				<!--ARSIP-->
				<div class="widget-sidebar">
					<h2 class="title-widget-sidebar">TAGS</h2>
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

		</div><!-- /.row -->
    </div><!-- /.container -->
	
	