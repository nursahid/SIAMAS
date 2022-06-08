<style type="text/css">
.modal {
	overflow: auto;
	color: #333;
}
</style>
<!-- The Bootstrap Image Gallery lightbox, should be a child element of the document body -->
<div id="blueimp-gallery" class="blueimp-gallery" data-use-bootstrap-modal="true">
    <!-- The container for the modal slides -->
    <div class="slides"></div>
    <!-- Controls for the borderless lightbox -->
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
    <!-- The modal dialog, which will be used to wrap the lightbox content -->
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body next"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left prev">
                        <i class="glyphicon glyphicon-chevron-left"></i> Previous
                    </button>
                    <button type="button" class="btn btn-primary next">
                        Next <i class="glyphicon glyphicon-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Galery here-->
<div id="links">
	<?php for ($i=0; $i<count($photos); $i++): ?>
	<div class="col-lg-3 col-md-4 col-xs-6 thumb">
		<a href="<?php echo base_url().$photos[$i]->file; ?>" class="thumbnail" title="Photo <?php echo $photos[$i]->name.'-'.$i+1; ?>" data-gallery>
			<img src="<?php echo base_url().$photos[$i]->file; ?>" alt="Photo <?php echo $i+1; ?>" width="180" height="180">
		</a>
	</div>	
	<?php endfor; ?>
</div>

<script>
document.getElementById('links').onclick = function (event) {
    event = event || window.event;
    var target = event.target || event.srcElement,
        link = target.src ? target.parentNode : target,
        options = {index: link, event: event},
        links = this.getElementsByTagName('a');
    blueimp.Gallery(links, options);
};
</script>