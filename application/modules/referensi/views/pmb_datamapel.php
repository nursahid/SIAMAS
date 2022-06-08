
	<div class="panel panel-primary">
		<div class="panel-heading">
			<div id="jmlmapel"><strong><?=$namatingkat?> [ <?=$namajurusan?> ]</strong> ( <?=$jumlahmapel?> Mapel )</div>
			<a href="#" class="btn btn-default pull-right" style="margin-top:-26.5px;"  onclick="add_data()"><i class="fa fa-wrench"></i> Atur Matapelajaran</a>
			
		</div>
		<div class="panel-body">
			<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>No.</th>
						<th>Mata Pelajaran</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
				

				</tbody>
			</table>
		
		</div>
	</div>
	
<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Mata Pelajaran Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Tingkat</label>
                            <div class="col-md-9">
                                <select name="id_tingkat" class="form-control" readonly>
                                    <option value="<?=$id_tingkat?>"><?=$this->sistem_model->get_nama_tingkat($id_tingkat)?></option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Jurusan</label>
                            <div class="col-md-9">
                                <select name="id_jurusan" class="form-control" readonly>
                                    <option value="<?=$id_jurusan?>"><?=$this->sistem_model->get_nama_jurusan($id_jurusan)?></option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Mata Pelajaran</label>
                            <div class="col-md-9">
                                <?php
									echo form_dropdown('id_mapel',$dropdown_mapel, set_value('id_mapel'),'class="form-control input-sm required" id="id_mapel"');
								?>
                                <span class="help-block"></span>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<script src="<?php echo base_url('assets/plugins/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables/js/dataTables.bootstrap.js')?>"></script>

<script type="text/javascript">
 
var save_method; //for save method string
var table;
 
$(document).ready(function() {
 
    //datatables
    table = $('#table').DataTable({ 
 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
 
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('referensi/mapeltingkat/mapeltingkat_ajaxlist/'.$id_tingkat.'/'.$id_jurusan)?>",
            "type": "POST"
        },
 
        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],
 
    });
 
    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,  
    });
 
});
 
 
 
function add_data()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Mata Pelajaran'); // Set Title to Bootstrap modal title
}
 
function edit_data(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
 
    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('referensi/mapeltingkat/mapeltingkat_ajaxedit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
 
            $('[name="id"]').val(data.id);
            $('[name="id_jurusan"]').val(data.id_jurusan);
            $('[name="id_tingkat"]').val(data.id_tingkat);
            $('[name="id_mapel"]').val(data.id_mapel);
			
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Data Mata Pelajaran'); // Set title to Bootstrap modal title
 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alertify.alert('Error get data from ajax');
        }
    });
}
 
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}
 
function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;
 
    if(save_method == 'add') {
        url = "<?php echo site_url('referensi/mapeltingkat/mapeltingkat_ajaxadd')?>";
    } else {
        url = "<?php echo site_url('referensi/mapeltingkat/mapeltingkat_ajaxupdate')?>";
    }
 
    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {
 
            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
				$('#jmlmapel').load('<?php echo "<strong>".$namatingkat." [ ".$namajurusan." ]</strong> ( ".$jumlahmapel." mapel )"?>');
                reload_table();
            }
 
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 
 
 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alertify.alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 
 
        }
    });
}
 
function delete_data(id)
{
    if(alertify.confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('referensi/mapeltingkat/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alertify.alert('Error deleting data');
            }
        });
 
    }
}
 
</script>