
<section class="content">
	<div class="row">
		<div class="col-md-6">
			<div class="box box-danger">
				<div class="box-header with-border">
				<h3 class="box-title">Unduh format Excel</h3>
				</div>
				<div class="box-body">
					<div class="form-group">				  
						<p>Silakan unduh format Excel, sebelum menggunakan form Upload disamping. 
					      Klik tombol di bawah ini.</p>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<a class="btn btn-primary" href="<?php echo base_url('assets/templates/siswa.xls');?>"><i class="fa fa-database"></i> Unduh Format Excel</a>
				</div>			
			</div>
			<!-- /.box -->
		</div>
		<div class="col-md-6">		
			<!-- box -->
			<div class="box box-success">
		    <!--<form action="<?php //echo base_url('personil/csvuploadsiswa');?>" method="post" enctype="multipart/form-data">-->
			<form method="post" id="import_csv" enctype="multipart/form-data">
				<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
				<div class="box-header with-border">
					<h3 class="box-title">Unggah File</h3>
				</div>
				<div class="box-body">				
					<div class="form-group">
						<?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
						<label for="exampleInputFile">File input</label>
						<!--<input type="file" name="userfile" id="exampleInputFile">-->
						<input type="file" name="csv_file" id="csv_file" required accept=".csv" />
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<!--<button type="submit" class="btn btn-primary"><i class="fa fa-file-excel-o"></i> Unggah File Excel</button>-->
					<button type="submit" name="import_csv" class="btn btn-info" id="import_csv_btn">Import CSV</button>
				</div>
			</form>	
			</div>
			<!-- /.box -->
			<?php
				if(isset($_POST['submit'])){
					echo '<div id="progress" style="width:500px;border:1px solid #ccc;"></div>
					<div id="info"></div>';
				}
			?>			
		</div>
	</div>
	<br />
	<div id="imported_csv_data"></div>
</section>

<script type="text/javascript">
$(document).ready(function(){

 //load_data();

 function load_data()
 {
  $.ajax({
   url:"<?php echo base_url(); ?>kesiswaan/import/load_data",
   method:"POST",
   success:function(data)
   {
    $('#imported_csv_data').html(data);
   }
  })
 }

 $('#import_csv').on('submit', function(event){
  //event.preventDefault();
  $.ajax({
   url:"<?php echo base_url(); ?>kesiswaan/import/csvupload",
   method:"POST",
   data:new FormData(this),
   contentType:false,
   cache:false,
   processData:false,
   beforeSend:function(){
    $('#import_csv_btn').html('Importing Data ...');
   },
   success:function(data)
   {
    $('#import_csv')[0].reset();
    $('#import_csv_btn').attr('disabled', false);
    $('#import_csv_btn').html('Import SELESAI');
    load_data();
   }
  })
 });
 
});

</script>
