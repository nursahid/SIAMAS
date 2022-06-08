  <div class="container">
    <?php echo $this->session->flashdata('message'); ?>
    <h2>Form Kirim SMS</h2>
    <form class="form-horizontal" action="<?php echo site_url('sms/kirim_sms') ?>" method="POST">
      <div class="form-group">
        <label class="control-label col-sm-2">No Handphone 1</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" placeholder="Nomor HP 1" name="no_hp[]">
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-2">No Handphone 2</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" placeholder="Nomor HP 2" name="no_hp[]">
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-2">No Handphone 3</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" placeholder="Nomor HP 3" name="no_hp[]">
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-2">No Handphone 4</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" placeholder="Nomor HP 4" name="no_hp[]">
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-2">No Handphone 5</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" placeholder="Nomor HP 5" name="no_hp[]">
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-2">Isi Pesan</label>
        <div class="col-sm-6">
          <textarea name="isi_pesan" class="form-control" placeholder="Masukan pesan disini.."></textarea>
        </div>
      </div>
      <div class="form-group">        
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-default">Kirim</button>
        </div>
      </div>
    </form>
  </div>