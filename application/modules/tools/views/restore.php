	
	<div class="row">
	    <div class="col-md-6">
			<!-- box -->
			<div class="box box-primary">
				<form method="post" id="import_csv" enctype="multipart/form-data">
				<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
				<div class="box-header with-border">
					<h3 class="box-title">Restore dari file CSV</h3>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label>Pilih File</label>
						<input type="file" name="csv_file" id="csv_file" required accept=".csv" />
					</div>
					<div class="form-group">
						<button type="submit" name="import_csv" id="import_csv_btn" class="btn btn-info"><i class="fa fa-save"></i> &nbsp;&nbsp;Restore Data</button>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<div id="imported_csv_data"></div>
				</div>
				</form>
				
			</div><!-- /.box -->
		</div>
	</div>

<script type="text/javascript">

$(document).ready(function(){

 load_data();

 function load_data()
 {
  $.ajax({
   url:"<?php echo base_url(); ?>tools/restore/load_data",
   method:"POST",
   success:function(data)
   {
    $('#imported_csv_data').html(data);
   }
  })
 }

 $('#import_csv').on('submit', function(event){
  event.preventDefault();
  $.ajax({
   url:"<?php echo base_url(); ?>tools/restore/import",
   method:"POST",
   data:new FormData(this),
   contentType:false,
   cache:false,
   processData:false,
   beforeSend:function(){
    $('#import_csv_btn').html('Importing...');
   },
   success:function(data)
   {
    $('#import_csv')[0].reset();
    $('#import_csv_btn').attr('disabled', false);
    $('#import_csv_btn').html('Impor Selesai...');
    load_data();
   }
  })
 });
 
});
</script>