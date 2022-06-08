<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
				<li class="<?php echo $this->uri->segment(3) == 'index' ? 'active':'';?>">
					<a href="<?php echo base_url('smsgateway/kirimsms/');?>">Orang Tua</a>
				</li>
				<li class="<?php echo $this->uri->segment(3) == 'groups' ? 'active':'';?>">
					<a href="<?php echo base_url('smsgateway/kirimsms/groups');?>">Kirim ke Group</a>
				</li>
				<li class="<?php echo $this->uri->segment(3) == 'nomor_baru' ? 'active':'';?>">
					<a href="<?php echo base_url('smsgateway/kirimsms/nomor_baru');?>" data-toggle="tab">Nomor Baru</a>
				</li>
            </ul>
            <div class="tab-content">
				<div class=" tab-pane" id="phonebook">
				
				</div>
				<!--By Groups SMS-->
				<div class="tab-pane" id="groups">
					
				</div>

				</div>
				<!--Custom Number SMS-->
				<div class="active tab-pane" id="number">
				
					<div class="box box-info">
						<div class="box-header">
							<i class="fa fa-envelope"></i><h3 class="box-title">Kirim SMS Nomor Baru</h3>
							<!-- tools box -->
							<div class="pull-right box-tools">
							</div>
							<!-- /. tools -->
						</div>
						<form action="<?php echo base_url('smsgateway/kirimsms/sending_nomorbaru');?>" method="post">
							<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
							
							<div class="box-body">
							
							<div id="message"><?php echo $this->session->userdata('message');?></div>
								<label>Tulis secara lengkap nomor HP tujuan</label>
								<hr/>
							
								<div class="form-group">
									<input type="text" name="no_hp" class="form-control multiple" placeholder="Tulis Nomor HP Tujuan" />			
									<?php echo form_error('no_hp');?>
								</div>
								<div>
									<textarea rows='6' class='form-control' name='pesan' id="pesan" placeholder='Tuliskan Pesan anda (Max 160 Karakter)...' onKeyDown="textCounter(this.form.pesan,this.form.countDisplay);" onKeyUp="textCounter(this.form.pesan,this.form.countDisplay);" required></textarea>
									<input type='number' name='countDisplay' size='3' maxlength='3' value='160' style='width:10%; text-align:center; border:1px solid #cecece; margin-top:4px' readonly> Sisa Karakter
									<?php echo form_error('pesan');?>
								</div>
							
							</div>
							<div class="box-footer clearfix">
								<input type="reset"  value="Reset" class='btn btn-default'>
								<button type="submit" class="pull-right btn btn-primary" id="sendSMS">Kirim SMS <i class="fa fa-arrow-circle-right"></i></button>
							</div>
						</form>
						
					</div>					
				</div>

			</div><!--end content-->
		</div>
	</div>	 
</div> 
 <script type="text/javascript">
 
      var maxAmount = 160;
      function textCounter(textField, showCountField) {
        if (textField.value.length > maxAmount) {
          textField.value = textField.value.substring(0, maxAmount);
        } else { 
          showCountField.value = maxAmount - textField.value.length;
        }
      }; 

		
 </script>