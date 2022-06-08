
<section class="content">
	<div class="row">
		<div class="col-md-6">
			<div class="box box-danger">
				<div class="box-header with-border">
				<h3 class="box-title">Unduh format Excel</h3>
				</div>
				<div class="box-body">
					<div class="form-group">				  
						<p>Silakan unduh format Excel terlebih dahulu dengan cara klik tombol <strong>Unduh Format Excel</strong> di bawah ini.</p>
						<p>Selanjutnya silakan masukkan data pada file excel sesuai kolom yang telah disediakan</p>
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
		    <form action="<?php echo base_url('kesiswaan/import/uploadexcel');?>" method="post" enctype="multipart/form-data">
			<!--<form method="post" id="import_form" enctype="multipart/form-data">-->
				<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
				<div class="box-header with-border">
					<h3 class="box-title">Import File Excel</h3>
				</div>
				<div class="box-body">				
					<div class="form-group">
						<p>Silakan klik tombol <strong>Browse (Telusuri)</strong>, lalu klik tombol <strong>Import Data</strong></p>
						<span class="text-red"><?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?></span>
						<br />
						<input type="file" name="userfile" id="file" required accept=".xls, .xlsx" />
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<input type="submit" name="import" value="Import" class="btn btn-info" />
				</div>
			</form>	
			</div><!-- /.box -->			
		</div>
	</div>
	<br />
	<div class="table-responsive" id="customer_data"> </div>
</section>

<script type="text/javascript">
$(document).ready(function(){

	load_data();

	function load_data() {
		$.ajax({
			url:"<?php echo base_url('kesiswaan/import/fetch'); ?>",
			method:"POST",
			success:function(data){
				$('#customer_data').html(data);
			}
		})
	}

	$('#import_form').on('submit', function(event){
		event.preventDefault();
		$.ajax({
			url:"<?php echo base_url('kesiswaan/import/import'); ?>",
			method:"POST",
			data:new FormData(this),
			contentType:false,
			cache:false,
			processData:false,
			success:function(data){
				$('#file').val('');
				load_data();
				alert(data);
			}
		})
	});

});
</script>
