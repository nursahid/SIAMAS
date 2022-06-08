<div class="row">
  <div class="col-sm-4">
    <div class="box box-default">
      <div class="box-body">
        <fieldset>
          <?php 
            $attributes = array('class' => 'form-horizontal', 'id' => '');
            echo form_open_multipart('', $attributes); 
          ?>
          <div class="control-group">
            <label for="file" class="control-label" title="Allowed file types: jpg|jpeg|png|gif|pdf|doc|docs|zip Max upload limit: 5MB">Choose a file to upload: <span style="color:red">*</span></label>
            <div class='controls'>
              <input id="file" type="file" name="file" />
              <?php echo form_error('file'); ?> 
            </div>
          </div>
          <div class="control-group">
            <label for="user_name" class="control-label">Your name:</label>
            <div class='controls'>
              <input id="user_name" class="form-control" type="text" name="user_name" maxlength="255" value="<?php echo set_value('user_name'); ?>"  />
              <?php echo form_error('user_name'); ?> 
            </div>
          </div>
          <div class="control-group">
            <label></label>
            <div class='controls'> 
              <button type="submit" class="btn btn-block btn-success"><i class="fa fa-upload"></i> Upload</button>
            </div>
          </div>
          <?php echo form_close(); ?>
        </fieldset>
      </div>
    </div>
    <?php if ($this->session->flashdata('msg') != "") { ?>
      <div class="alert alert-success"> <?php echo $this->session->flashdata('msg'); ?></div>
    <?php } ?>
  </div>
  <div class="col-sm-8">
    <div class="box box-default">
      <div class="box-header with-border">
        <i class="fa fa-file"></i>
        <h3 class="box-title">List Files</h3>
      </div>
      <div class="box-body">
        <table  class="table table-hover">
          <caption>
            <strong>Last 10 user uploaded files</strong>
          </caption>
          <?php $i = 1; foreach($files_result->result() as $file) { ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo anchor(s3_site_url("uploads/".$file->file), "Download", "Title = 'File S3 Bucket URL: ".s3_site_url("user_photos/".$file->file)."'"); ?></td>
            <td><?php echo anchor(site_url("aws3/delete_file/".$file->id), "Delete", "Title = 'Click here to delete the file from S3 Bucket' onClick='return confirm(\"Are you sure you want to delete this file?\")'"); ?></td>
            <td><?php echo "Uploaded by: ".$file->user_name;?></td>
          </tr>
          <?php } if($i == 1) { ?>
          <tr>
            <td colspan="2"> You didn't uploaded any files yet</td>
          </tr>
          <?php } ?>
        </table>
      </div>
    </div>
  </div>
</div>