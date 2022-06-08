					<div class="box box-info">
						<div class="box-header">
							<i class="fa fa-envelope"></i><h3 class="box-title">Kirim SMS Broadcast</h3>
							<!-- tools box -->
							<div class="pull-right box-tools">
							</div>
							<!-- /. tools -->
						</div>
						<form action="<?php echo base_url('smsgateway/kirimsms/broadcast');?>" method="post">
							<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">

							<div class="box-body">
							
								<div id="message3"><?php echo $this->session->userdata('message3');?></div>
								<div class="form-group">
								<label>Hilangkan centang untuk nomor yang tidak ingin dikirim</label>
								<hr>
								Total Phone Number : <?php //echo $datas->num_rows();?>
								<div style='width:100%;height:300px;overflow:auto;border:1px solid #CCC;padding:10px 0px 10px 10px ;'>
								
									<?php foreach($datas as $row){ ?>
										<?php //$group = $this->db->get_where('pbk_groups',array('ID' => $row->GroupID));?>
										<?php //$groups = $group->row();?>
										<label class="checkbox">
											<input type='checkbox' name='phone[]' value='<?php echo $row->hp_ortu;?>' checked> 
											<?php echo $row->nama;?> (<?php echo $row->hp_ortu;?>)
										</label>
										 
									<?php } ?>
								</div>
								<?php echo form_error('phone');?>
								</div>
								<div class="form-group">
									<textarea id='message' class='form-control' style='height:200px;' name='message' placeholder='message'><?php echo set_value('message');?></textarea>
									<?php echo form_error('message');?>
								</div>
							</div>
							<div class="box-footer clearfix">
								<input type="reset"  value="Reset" class='btn btn-default'>
								<button type="submit" class="pull-right btn btn-primary" id="sendSMS">Kirim SMS <i class="fa fa-arrow-circle-right"></i></button>
							</div>
						</form>
					</div>
					
 <script>
    var options = {
		'maxCharacterSize': 150,
		'originalStyle': 'originalDisplayInfo',
		'warningStyle': 'warningDisplayInfo',
		'warningNumber': 40,
		'displayFormat': '#input Karakter | Sisa #left Karakter | #words Kata'
	};
	$('#message').textareaCount(options);
		
 </script>