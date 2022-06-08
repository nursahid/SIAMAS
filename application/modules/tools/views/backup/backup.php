
	<div class="row">
	    <div class="col-md-6">
			<div class="box box-danger">
				<div class="box-header with-border">
				<h3 class="box-title">Backup Database</h3>
				</div>
				<div class="box-body">
					<div class="form-group">
						<p>Klik tombol "<strong>Backup Database</strong>" untuk membackup database sistem.</p>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<a class="btn btn-danger" href="<?php echo base_url('tools/backup/backup_db');?>"><i class="fa fa-database"></i> Backup Database</a>
				</div>			
			</div>
			<!-- /.box -->		
		</div>
	    <div class="col-md-6">
			<!-- box -->
			<div class="box box-success">
		    <form action="" method="post" name="FormImport" id="FormImport" enctype="multipart/form-data">
			<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
				<div class="box-header with-border">
					<h3 class="box-title">Restore Data</h3>
				</div>
				<div class="box-body">				
					<div class="form-group">
						<?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
						<label for="InputFile">File input</label>
						<input type="file" name="datafile" id="InputFile" value="<?php echo set_value('datafile'); ?>">
						<?php echo form_error('datafile'); ?>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<button type="submit" name="insertData" id="insertData" class="btn btn-primary"><i class="fa fa-save"></i> &nbsp;&nbsp;Restore Data</button>
				</div>
			</form>	
			</div><!-- /.box -->
		</div>
    </div>


<script type="text/javascript">
    $(document).ready(function(){
        $("#insertData").click(function(){
            var datafile = $("#InputFile").val();
            //pass object in post
            var dataString = {'datafile': datafile};
            if(datafile == '')
            {
                alertify.alert("<div class='text-red'><i class='fa fa-info'></i> Silakan ambil file data</div>");
            }
            else
            {
                // AJAX Code To Submit Form.
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('tools/backup/restore'); ?>",
                    data: dataString,
                    dataType: 'json',
                    cache: false,
                    success: function(result){
                        //alertify.alert(result.pesan);
						if(result.status == 1){
							alertify.alert(result.pesan);
						}
						if(result.status == 0){
							alertify.alert(result.pesan);
						}
                    }
                });
            }
            return false;
        });
    }); 
 </script>
