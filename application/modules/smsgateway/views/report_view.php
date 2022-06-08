	<div class="row">
	  <div class="col-md-3 col-sm-6 col-xs-12">
		 <div class="info-box">
			<a href="<?=site_url('smsgateway/report/inbox');?>">
				<span class="info-box-icon bg-aqua"><i class="fa fa-envelope-o"></i></span>
			</a>
			<div class="info-box-content">
			  <span class="info-box-text">SMS Masuk</span>
			  <span class="info-box-number"><?=$sms_masuk;?></span>
			</div>
		 </div>
	  </div>
	  <div class="col-md-3 col-sm-6 col-xs-12">
		 <div class="info-box">
		 	<a href="<?=site_url('smsgateway/report/outbox');?>">
				<span class="info-box-icon bg-green"><i class="fa fa-comments-o"></i></span>
			</a>
			<div class="info-box-content">
			  <span class="info-box-text">SMS Terkirim</span>
			  <span class="info-box-number"><?=$pesan_keluar;?></span>
			</div>
		 </div>
	  </div>
	  <div class="col-md-3 col-sm-6 col-xs-12">
		 <div class="info-box">
		 	<a href="<?=site_url('smsgateway/report/grafik');?>">
				<span class="info-box-icon bg-yellow"><i class="fa fa-edit"></i></span>
			</a>
			<div class="info-box-content">
			  <span class="info-box-text">Grafik</span>
			  <span class="info-box-number"></span>
			</div>
		 </div>
	  </div>
	  <div class="col-md-3 col-sm-6 col-xs-12">
		 <div class="info-box">
		 	<a href="<?=site_url('smsgateway/report/cetak');?>">
				<span class="info-box-icon bg-red"><i class="fa fa-file-o"></i></span>
			</a>
			<div class="info-box-content">
			  <span class="info-box-text">Cetak</span>
			  <span class="info-box-number"></span>
			</div>
		 </div>
	  </div>
	</div>