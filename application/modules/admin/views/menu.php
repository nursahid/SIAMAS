<div class="row">
  <div class="col-sm-4">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">Menu Type</h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="list-group">
          <?php foreach ($menu_type as $value): ?>
            <?php 
              $url = urldecode(str_replace(' ', '-', strtolower($value->type)));
              $active = '';
              if ($url == $this->uri->segment(3))
                $active = ' active ';
            ?>
            <?php if ($value->id_menu_type != 1): ?>
              <a href="<?php echo site_url('myigniter/menu_type/'.$url.'/delete/'.$value->id_menu_type) ?>" title="Delete menu type"><i class="fa fa-trash"></i> Delete</a>
            <?php endif ?>
            <a href="<?php echo site_url('myigniter/menu/'.$url) ?>" class="list-group-item <?php echo $active ?>"><?php echo $value->type ?></a>
            <br>
          <?php endforeach ?>
        </div>
        <div class="form-group">
          <a href="<?php echo site_url('myigniter/menu_type/'.$this->uri->segment(3).'/add') ?>" class="btn btn-primary btn-block btn-flat"><i class="fa fa-plus-circle"></i> Add Menu Type</a>
        </div> 
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">Admin</h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div><!-- /.box-tools -->
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php echo form_open('myigniter/update_menu') ?>
          <div class="form-group">
            <a href="<?php echo site_url('myigniter/crud_menu/'.$this->uri->segment(3).'/add') ?>" class="btn btn-primary btn-flat"><i class="fa fa-plus-circle"></i> Add Menu</a>
            <button type="submit" id="saveMenu" class="btn btn-success btn-flat"><i class="fa fa-save"></i> Save</button>
          </div>
          <div id="sideMenu" class="dd">
            <?php echo $admin_menu ?>
          </div>
          <input type="hidden" name="type" value="<?php echo $this->uri->segment(3) ?>">
          <textarea name="json_menu" hidden id="tampilJsonSideMenu"></textarea>
        <?php echo form_close() ?>
      </div><!-- /.box-body -->
    </div>
  </div>
</div>
<script>
  $(function(){
    $('#navMenu').addClass('active');
    $('#sideMenu').nestable();
    $('#tampilJsonSideMenu').html(window.JSON.stringify($('#sideMenu').nestable('serialize')));
    $('#sideMenu').on('change', function() {
      $('#tampilJsonSideMenu').val(window.JSON.stringify($('#sideMenu').nestable('serialize')));      
    });
  });
</script>