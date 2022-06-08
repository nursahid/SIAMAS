<div class="container">
		<div id="message" class="alert alert-<?=$type;?>"><?php echo $message;?></div>
        <?php
        echo form_open($this->uri->uri_string(), array('id' => 'add_tags_form'));
        ?>
        <p>
            <label>Tags (ketik dan tekan 'Enter')</label><br/>
            <textarea name="blog_tags" id="blog_tags" rows="5" cols="50" class="textarea"></textarea>
        </p>
        <p>
            <input type="submit" name="add_tags" id="add_tags" class="btn btn-sm btn-primary" value="Simpan tags"/>
        </p>
        <?php
        echo form_close();
        ?>

</div>

        <script type="text/javascript">
            $('#blog_tags')
                    .textext({
                        plugins: 'tags',
                        tagsItems: [<?php
									if (isset($blog_tags)) {
										$i = 1;
										foreach ($blog_tags as $tag) {
											echo "'" . $tag . "'";
											if (count($blog_tags) == $i) {
												echo '';
											} else {
												echo ',';
											}
											$i++;
										}
									}
									?>]
                    })
                    .bind('tagClick', function (e, tag, value, callback) {
                        var newValue = window.prompt('Enter New value', value);
                        if (newValue)
                            callback(newValue);
                    });
        </script>

