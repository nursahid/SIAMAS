
	<div class="row">
	    <div class="col-md-12">
			<div class="box box-danger">
				<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-edit text-green"></i> Tambah Tulisan</h3>
				</div>
				<div class="box-body">
					<form>
						<div class="row">
							<div class="col-lg-8">
								<div class="panel panel-default">
									<div class="panel-body">
										<div class="form-group" style="margin-bottom: 10px;">
											<input id="post_title" name="post_title" value="" autofocus required="true" type="text" class="form-control input-lg" placeholder="Enter title here..." style="font-size: 16px">
										</div>
										<div class="form-group">
											<textarea id="post_content" name="content" rows="25" class="form-control ckeditor"></textarea>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="box box-default">
									<div class="box-header with-border">
										<h3 class="box-title"><i class="fa fa-tasks"></i> Kategori</h3>
									</div>
									<div class="box-body">
										<div class="form-group">
											<div class="checkbox"><label><input type="checkbox" class="checkbox" name="category[]" value="1">Uncategorized</label></div><div class="checkbox"><label><input type="checkbox" class="checkbox" name="category[]" value="2">Berita Sekolah</label></div>						</div>
									</div>
								</div>
								<div class="box box-default">
									<div class="box-header with-border">
										<h3 class="box-title"><i class="fa fa-send-o"></i> Publikasi</h3>
									</div>
									<div class="box-body">
										<div class="form-group">
											<label class="control-label">Gambar</label>
											<div class="input-group">
												<input type="file" name="post_image" class="form-control" id="post_image">
												<img  id="preview" width="293px" style="margin-top: 50px; display:none;" class="img-responsive" title="Double klik untuk menghapus gambar">
											</div>
										</div>
									</div>
									<div class="box-footer">
										<div class="btn-group pull-right">
											<button type="reset" class="btn btn-info btn-sm"><i class="fa fa-retweet"></i> ATUR ULANG</button>
											<button type="submit" id="submit" class="btn btn-primary btn-sm"><i class="fa fa-send-o"></i> SIMPAN</button>	
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
				<!-- /.box-body -->
				<div class="box-footer">

				</div>			
			</div>
		</div>
	</div>
	

<script type="text/javascript">
	/** @namespace tinymce */
	tinymce.init({
      selector: "#post_content",
      theme: 'modern',
      paste_data_images:true,
      relative_urls: false,
      remove_script_host: false,
      toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
      toolbar2: "print preview forecolor backcolor emoticons",
      image_advtab: true,
      plugins: [
         "advlist autolink lists link image charmap print preview hr anchor pagebreak",
         "searchreplace wordcount visualblocks visualchars code fullscreen",
         "insertdatetime nonbreaking save table contextmenu directionality",
         "emoticons template paste textcolor colorpicker textpattern"
      ],
      automatic_uploads: true,
      images_upload_url: _BASE_URL + 'posts/tinymce_upload',
      file_picker_types: 'image', 
      file_picker_callback: function(cb, value, meta) {
         var input = document.createElement('input');
         input.setAttribute('type', 'file');
         input.setAttribute('accept', 'image/*');
         input.onchange = function() {
            var file = this.files[0];
            var reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function () {
               var id = 'member-post-image-' + (new Date()).getTime();
               var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
               var blobInfo = blobCache.create(id, file, reader.result);
               blobCache.add(blobInfo);
               cb(blobInfo.blobUri(), { title: file.name });
            };
         };
         input.click();
      }
   });

	/** @namespace posts */
	$( document ).ready( function() {
		// Image Preview 
		$('#post_image').on('change', function() {
			$('#preview').show();
			H.preview(this);
		});

		// Image Preview 
		$('#preview').on('dblclick', function() {
			$('#preview').hide().removeAttr('src');
		});

		/* Submit Posts */
		$( '#submit' ).on('click', function(event) {
			event.preventDefault();
			var categories = $("input.checkbox:checked");
			var post_categories = [];
			categories.each( function() {
			  post_categories.push($(this).val());
			});

			var fill_data = new FormData();
			fill_data.append('post_title', $('#post_title').val());
			fill_data.append('post_content', tinyMCE.get('post_content').getContent());
			fill_data.append('post_categories', post_categories.join(','));
			fill_data.append('post_image', $('input[type=file]')[ 0 ].files[ 0 ]);
			// send data
			$.ajax({
				url: 'http://localhost/sekolah/cms_sekolahku/posts/save/',
				type: 'POST',
				data: fill_data,
				contentType: false,
				processData: false,
				success : function( response ) {
					var res = typeof response !== 'object' ? $.parseJSON( response ) : response;
					H.growl(res.type, H.message(res.message));
					if (res.action == 'save')  {
						$('input[type="text"], input[type="file"]').val('');
						tinyMCE.get('post_content').setContent('');
						$("input.checkbox").prop('checked', false);
						$('#post_title').focus();
						$('#preview').removeAttr('src').hide();
					}
				}
			});   
		});  
	});
</script>
