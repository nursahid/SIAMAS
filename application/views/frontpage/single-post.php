<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

			<div class="col-xs-12 col-md-8">
				<div class="thumbnail no-border">
					<?php if (file_exists('./assets/uploads/thumbnail/'.$post->featured_image)) { ?>
					<img src="<?=base_url('assets/uploads/thumbnail/'.$post->featured_image)?>" style="width: 100%; display: block;">
					<?php } ?>
					<div class="caption">
						<h3><?=$post->title?></h3>
						<p class="by-author">
							<?=nama_hari(date('N', strtotime($post->created_at)))?>, 
							<?=tgl_indo(substr($post->created_at, 0, 10))?> 
							~ Oleh <?=$post->full_comment_name?> ~ Dilihat <?=$post->readtimes?> Kali
						</p>
						<?=$post->content?>
						<div id="share1"></div>

					<?php 
					$cek_tags = $this->posttags->count_by('id_blog',$post->id);
					if ($cek_tags > 0) {
						$post_tags = $this->posttags->get_tags($post->id)->result();
						foreach ($post_tags as $tag) {
							echo '<a style="margin-right:3px;" href="'.base_url('tag/'.strtolower(trim($tag->tag))).'">';
							echo '<span class="label label-success">';
							echo '<i class="fa fa-tags"></i> '.ucwords(strtolower(trim($tag->tag)));
							echo '</span>';
							echo '</a>';
						}
					}
					?>
					</div>
				</div>

				<?php if ($post_comments->num_rows() > 0) { ?>
			<div id="display_comment">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-comments-o"></i> KOMENTAR</h3>
					</div>
					<div class="panel-body">						
						<?php foreach($post_comments->result() as $row) { ?>
						<div class="panel panel-inverse" style="margin-bottom: 0px;">
							<div class="panel-heading" style="padding-bottom: 0px">
								<strong><?=$row->comment_name?></strong> - <span class="text-muted"><?=nama_hari(date('N', strtotime($row->created_at)))?>, <?=tgl_indo($row->created_at)?></span>
							</div>
							<div class="panel-body" style="padding-top: 0px">
								<p align="justify"><?=strip_tags($row->comments)?></p>
							</div>
						</div>
						<?php } ?>
					</div>
					<div class="panel-footer">
						<button class="btn btn-sm btn-block btn-inverse load-more" onclick="more_comments()">KOMENTAR LAINNYA</button>
					</div>
				</div>
			</div>
				<?php } ?>

				<?php if ($post->post_comment_status == 'open') { ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="fa fa-comments-o"></i> KOMENTARI TULISAN INI</h3>
						</div>
						<div class="panel-body">
							<form class="form-horizontal">
								<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
								<div class="form-group">
									<label for="comment_name" class="col-sm-3 control-label">Nama Lengkap <span style="color: red">*</span></label>
									<div class="col-sm-9">
										<input type="text" class="form-control input-sm" id="comment_name" name="comment_name">
									</div>
								</div>
								<div class="form-group">
									<label for="email" class="col-sm-3 control-label">Email <span style="color: red">*</span></label>
									<div class="col-sm-9">
										<input type="text" class="form-control input-sm" id="email" name="email">
									</div>
								</div>
								<div class="form-group">
									<label for="url" class="col-sm-3 control-label">URL</label>
									<div class="col-sm-9">
										<input type="text" class="form-control input-sm" id="url" name="url">
									</div>
								</div>
								<div class="form-group">
									<label for="comments" class="col-sm-3 control-label">Komentar <span style="color: red">*</span></label>
									<div class="col-sm-9">
										<textarea rows="5" class="form-control input-sm" id="comments" name="comments"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"></label>
									<div class="col-sm-9">
										<div class="g-recaptcha" data-sitekey="<?=$recaptcha_site_key?>"></div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-3 col-sm-9">
										<input type="hidden" name="id_blog" id="id_blog" value="<?=$id;?>">
										<!--<button type="button" onclick="post_comment(); return false;" class="btn btn-success"><i class="fa fa-send"></i> SUBMIT</button>-->
										<button type="submit" name="submitComment" id="submitComment" class="btn btn-success"><i class="fa fa-send"></i> &nbsp;&nbsp;Submit</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				<?php } ?>
				<?php 
				//if ($post->post_type == 'post') {
					//ambil data kategori
					$this->load->model('post_model','blogpost');
					$cats = $this->poskategori->get_by('id_blog',$post->id);
					$post_categories = $cats->id_category;
					
					//$this->db->select('id, title, content, LEFT(created_at, 10) AS created_at, featured_image, path, readtimes');
					$this->db->join('categories_blogs', 'blog.id=categories_blogs.id_blog', 'left');
					$this->db->where('blog.id !=', $id);
					$this->db->like('categories_blogs.id_category', $post_categories);
					$this->db->limit(10);
					$posts = $this->db->get('blog');
							
					//$posts = $this->blogpost->get_related_posts($post_categories, $post->id, 10); 
					if ($posts->num_rows() > 0) {
						$arr_posts = [];
						foreach ($posts->result() as $post) {
							array_push($arr_posts, $post);
						}
						?>
						<ol class="breadcrumb post-header">
							<li><i class="fa fa-sign-out"></i> TULISAN TERKAIT</li>
						</ol>
						<?php $idx = 2; $rows = $posts->num_rows(); foreach($posts->result() as $row) { ?>
							<?=($idx % 2 == 0) ? '<div class="row">':''?>
								<div class="col-md-6">
									<ul class="media-list main-list">
										<li class="media">
											<a class="pull-left" href="<?=site_url('berita/detail/'.$row->path)?>">
												<img class="media-object" src="<?=base_url('assets/uploads/thumbnail/'.$row->featured_image)?>" width="80px" height="80px">
											</a>
											<div class="media-body">
												<h4><a href="<?=site_url('berita/detail/'.$row->path)?>"><?=$row->title?></a></h4>
												<p class="by-author"><?=nama_hari(date('N', strtotime($row->created_at)))?>, <?=tgl_indo(date('Y-m-d', strtotime($row->created_at)))?></p>
											</div>
										</li>
									</ul>
								</div>
							<?=(($idx % 2 == 1) || ($rows+1 == $idx)) ? '</div>':''?>
						<?php $idx++; } ?>
					<?php }
					?>
				<?php //} ?>
			</div>
			
			<!-- Sidebar -->
			<div class="col-xs-12 col-md-4">
				<?=$this->load->view('template/sidebar');?>
			</div>
			
<script type="text/javascript">
	var page = 1;
	var total_pages = "<?=$total_comment_pages;?>";
	$(document).ready(function() {
		if (parseInt(total_pages) == page || parseInt(total_pages) == 0) {
			$('.panel-footer').remove();
		}
	});
	function more_comments() {
		page++;
		var data = {
			page_number: page,
			id_blog: '<?=$id;?>'
		};		
		if ( page <= parseInt(total_pages) ) {
			$('body').addClass('loading');
			$.post( _BASE_URL + 'berita/more_comments', data, function( response ) {
				var res = typeof response !== 'object' ? $.parseJSON( response ) : response;
				var comments = res.comments;
				var html = '';
				for (var z in comments) {
					var comment = comments[ z ];
					html += '<div class="panel panel-inverse" style="margin-bottom: 0px;">';
					html += '<div class="panel-heading" style="padding-bottom: 0px">';
					html += '<strong>' + comment.comment_name + '</strong> - <span class="text-muted">' + comment.comment_at + '</span>';
					html += '</div>';
					html += '<div class="panel-body" style="padding-top: 0px">';
					html += '<p align="justify">' + comment.comments + '</p>';
					html += '</div>';
					html += '</div>';
				}
				var el = $(".panel-inverse:last"); 
				$( html ).insertAfter(el);
				if ( page == parseInt(total_pages) ) {
					$('.panel-footer').remove();
				}
				$('body').removeClass('loading');
			});
		}
	}
	
	//post komentar
	//----------------
	//function post_comment() {
		$("#submitComment").click(function() {
			var comment_name = $("#comment_name").val();
			var email 		 = $("#email").val();
			var url 		 = $("#url").val();
			var comments 	 = $("#comments").val();
			var id_blog 	 = '<?=$id;?>'; 
			var dataString 	 = {'comment_name': comment_name , 'email': email , 'url': url , 'comments': comments , 'id_blog': id_blog };
			
			if(comment_name=='' || email=='' || comments=='') {
				//alert('Please Give Valid Details');
				$.notify("Silakan masukkan data", { position:"right" });
			}
			else {
				$("#display_comment").show();
				$("#display_comment").fadeIn(100).html('<img src="<?php echo base_url('assets/img/ajax_loader_blue.gif');?>" />Loading Comment...');
				$.ajax({
					type: "POST",
					url: "<?php echo base_url('berita/submit_comment/');?>",
                    data: dataString,
                    dataType: 'json',
                    cache: false,
					success: function(data){
						if(data.status === 1 ) { 
							$("#display_comment").html(data);
							$("#display_comment").fadeIn(slow);
							more_comments();
							$.notify(data.pesan, "success", { position:"right" });
						}
						if(data.status == 0){
							$.notify(data.pesan, { position:"right" });
						}
					}
				});
			}
			return false;
		});	
	//};
	
	$("#share1").jsSocials({
		shares: ["email", "twitter", "facebook", "googleplus", "linkedin", "pinterest", "stumbleupon", "whatsapp"]
	});	
</script>