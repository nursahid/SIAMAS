<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
				<li class="active"><a href="#phonebook" data-toggle="tab">Orang Tua</a></li>
				<li class="<?php echo $this->uri->segment(3) == 'groups' ? 'active':'';?>">
					<a href="<?php echo base_url('smsgateway/kirimsms/groups');?>">Kirim ke Group</a>
				</li>
				<li class="<?php echo $this->uri->segment(3) == 'nomor_baru' ? 'active':'';?>">
					<a href="<?php echo base_url('smsgateway/kirimsms/nomor_baru');?>">Nomor Baru</a>
				</li>
			</ul>
            <div class="tab-content">
				<div class="active tab-pane" id="phonebook">

					<div class="box box-info">
						<form action="<?php echo base_url('smsgateway/kirimsms/sending_phonebook');?>" method="post">
						<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">

						<div class="box-header">
							<i class="fa fa-envelope"></i><h3 class="box-title">Kirim SMS ke Orang Tua</h3>
						</div>
						<div class="box-body">
							<div id="message"><?php echo $this->session->userdata('message');?></div>
							<div class="form-group">
								<label>Hilangkan centang untuk nomor yang tidak ingin dikirim</label>
								<hr/>
								Total No. Telepon : <?php echo $totalphones;?>
								<div style='width:100%;height:300px;overflow:auto;border:1px solid #CCC;padding:10px 0px 10px 10px ;'>
								
									<?php foreach($datas as $row){ ?>
										<?php //$group = $this->db->get_where('pbk_groups',array('ID' => $row->GroupID));?>
										<?php //$groups = $group->row();?>
										<label class="checkbox">
											<input type='checkbox' name='no_hp[]' value='<?php echo $row->hp_ortu;?>' checked> 
											<?php echo $row->nama;?> (<?php echo $row->hp_ortu;?>)
										</label>
										 
									<?php } ?>
								</div>
								<?php echo form_error('no_hp');?>
							</div>
							<div class="form-group">
								<textarea id='pesan' class='form-control' style='height:200px;' name='pesan' placeholder='Tuliskan Pesan anda (Max 160 Karakter)...'><?php echo set_value('message');?></textarea>
								<?php echo form_error('pesan');?>
							</div>
						</div>
						<div class="box-footer">
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
    var options = {
		'maxCharacterSize': 150,
		'originalStyle': 'originalDisplayInfo',
		'warningStyle': 'warningDisplayInfo',
		'warningNumber': 40,
		'displayFormat': '#input Karakter | Sisa #left Karakter | #words Kata'
	};
	$('#pesan').textareaCount(options);
		
 </script>