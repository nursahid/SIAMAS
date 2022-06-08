
<div class="module">
    <div class="module-head">
        <h3>Matapelajaran Kelas</h3>
    </div>
    <div class="module-body">
        <?=//$get_flashdata('mapel')|raw;?>
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading as-link" data-toggle="collapse" data-target="#form-filter">
                        <b><i class="icon-search"></i> Filter Kelas</b>
                    </div>
                    <div id="form-filter" class="collapse">
                        <div class="panel-body">
                            <form class="form-horizontal row" method="post" action="<?=//site_url('kelas/mapel_kelas/list');?>">
                                <table class="table table-form table-condensed">
                                    <tr>
                                        <td class="pull-right">Parent Kelas</td>
                                        <td>
                                            <select name="parent_kelas" id="kelas-mapel-kelas-parent-kelas">
                                                <option>--pilih--</option>
                                                <option value="" selected>-</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pull-right">Sub Kelas</td>
                                        <td>
                                            <select name="sub_kelas" id="kelas-mapel-kelas-sub-kelas">
                                                <option>--pilih--</option>
                                                    <option value="" selected>-</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <button class="btn btn-small btn-primary" type="submit">Submit</button>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                Atur matapelajaran yang ada pada masing - masing kelas
            </div>
        </div>

        <?=//$mapel_tingkat_hirarki;?>

    </div>
</div>
