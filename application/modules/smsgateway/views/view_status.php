<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.fusioncharts.js'); ?>"></script>
<div class="container">
	<!-- grafik-->
	<div style="overflow:auto; width:575px; height:370px; padding:5px; border:1px solid #eee">
		<center><h4>Grafik Inbox dan Sent Item</h4></center>
		<?php
		echo "<center>$last sampai $now</center>";
		?>
		<table style="display: none;" id="myHTMLTable" border="1" align="center">
			<?php echo $tgl; ?>
			<?php echo $inbox; ?>
			<?php echo $sentitem; ?>
		</table>
		<script type="text/javascript">
			$('#myHTMLTable').convertToFusionCharts({
			swfPath: "<?php echo base_url(); ?>charts/",
			type: "MSColumn3D",
			data: "#myHTMLTable",
			dataFormat: "HTMLTable",
			width : "550"
			});
		</script>
		<table border="0">
			<tr><td>Total Inbox</td><td>:</td><td><?php echo $jumlahinbox; ?></td></tr>
			<tr><td>Total Sent Item</td><td>:</td><td><?php echo $jumlahsitem ?></td></tr>
		</table>
	</div>
	<!-- end grafik-->
</div>