<?php $this->load->view('header_survey') ?>
<style>
    .select2-results__option.select2-results__option--highlighted small {
        color: #fff;
    }
</style>
<ol class="breadcrumb">
    <li><a href="<?php echo BASE_URL() . 'apps'; ?>">Home</a></li>
    <li class="active">Survei</li>
    <div class="control-breadcrumb">
        <li> <button class="btn btn-primary btn-sm" onclick="buat_survei()"><span class="fa fa-plus"></span> Buat Survei</button></li>
    </div>
</ol>

<div class="row" id="content_table_survei">
    <div class="col-md-3">
        <div class="block-flat">
            <div class="header">
                <h4>Filter</h4>
            </div>
            <div class="content">
                <div class="form-group">
                    <label>Unit Kecamatan</label>
                    <select id="id_unit_filter" name="id_unit" class="form-control"></select>
                </div>
                <div class="form-group">
                    <label>Unsur/Elemen</label>
                    <select id="id_elemen_filter" name="id_elemen" class="form-control"></select>
                </div>
                <div class="form-group">
                    <label>Keyword</label>
                    <input id="keyword_filter" type="text" name="keyword" class="form-control">
                </div>
                <button class="btn btn-twitter pull-right" type="button" onclick="exkspor()"><i class='fa fa-file-excel-o'></i> Ekspor</button>
                <button class="btn btn-default" type="button" onclick="search()"><i class='fa fa-search'></i> Cari</button>
            </div>
        </div>
    </div>
    <form id="ekspor_form" action="<?php echo BASE_URL() ?>survei/ekspor_survey" method="post" target="_blank" style="display: none;">
        <input name="id_unit" type="hidden">
        <input name="id_elemen" type="hidden">
        <input name="keyword" type="hidden">
    </form>
    <div class="col-md-9">
        <div class="tab-portlet tp-success">
            <div class="title">
                <h4>Daftar Survei</h4>
            </div>
            <div class="content">
                <table class="table table-striped" id="data_table">
                    <thead>
                        <th>Unit</th>
                        <th>Narasumber</th>
                        <th>Alamat</th>
                        <th>Catatan</th>
                        <th></th>
                    </thead>
                    <tbody></tbody>
                </table>
                <div class="row" id="info-content">
                    <div class="col-md-6">
                        <h6 class="jml_info"></h6>
                    </div>
                    <div class="col-md-6">
                        <h6 class="page_info" style="text-align: right;"></h6>
                    </div>
                </div>
                <h4 id="tabel_nothing" style="display:none; text-align: center;">Tidak ada survei</h4>
                <div id="page-content" class="pagination" style="text-align: center;margin: 0px; display: block;"></div>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default" id="content_form_survei" style="display: none;">
    <div class="panel-heading">
        <h3 class="panel-title">Form Survei</h3>
    </div>
    <div class="panel-body">
        <form class="" id="form_survei">
            <div class="form-group">
                <label>Unit Kecamatan</label>
                <select class="form-control" name="id_unit" id="id_unit"></select>
            </div>
            <div class="form-group">
                <label>Desa</label>
                <select class="form-control" name="id_desa" id="id_desa"></select>
            </div>
            <!-- <div class="form-group">
                <label>SKPD</label>
                <select class="form-control" name="id_skpd" id="id_skpd"></select>
            </div> -->
            <div class="form-group">
                <label>Elemen Survei</label>
                <select class="form-control" name="id_elemen" id="id_elemen"></select>
            </div>
            <div class="form-group">
                <label>Tgl Survei</label>
                <input type="text" class="form-control datepicker" name="tgl_survey" id="tgl_survey" required="" />
            </div>
            <div class="form-group">
                <label>Nama Surveyor</label>
                <input type="text" class="form-control" name="nama_surveyor" id="nama_surveyor" required="" />
            </div>
            <div class="form-group">
                <label>Nama PPL</label>
                <input type="text" class="form-control" name="nama_ppl" id="nama_ppl" required="" />
            </div>
            <div class="form-group">
                <label>Nama Narasumber</label>
                <input type="text" class="form-control" name="nama_narasumber" id="nama_narasumber" required="" />
            </div>
            <div class="form-group">
                <label>No Telp</label>
                <input type="text" class="form-control" name="telp" id="telp" />
            </div>

            <!-- <div class="form-group">
                <div class="row" style="margin-bottom: 10px;">

                </div>
            </div> -->

            <div id="fields"></div>

            <div class="form-group">
                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-sm-6">
                        <label>Latitude</label>
                        <input type="text" name="lat" class="form-control" required="" />
                    </div>
                    <div class="col-sm-6">
                        <label>Longtitude</label>
                        <input type="text" name="lon" class="form-control" required="" />
                    </div>
                </div>
                <a class="btn btn-success btn-sm" id="my_location">Lokasi Saat ini</a>
                <!-- <input id="pac-input" class="controls" type="text" placeholder="Search Box"> -->
                <div id="map" style="height: 300px;"></div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label><input type="checkbox" name="is_valid" id="is_valid">Survei Valid ?</label>
                    </div>
                    <div class="col-sm-6">
                        <label><input type="checkbox" name="is_potensial" id="is_valid">Survei Potensial ?</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Catatan</label>
                <textarea type="text" class="form-control" name="catatan" id="catatan" rows="2"></textarea>
            </div>
            <div>
                <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Simpan</button>
                <button class="btn btn-default" type="button" onclick="cancel_form()">Cancel</button>
            </div>
            <input type="hidden" id="keterangan_elemen" name="keterangan_elemen" value="" />
        </form>
    </div>
</div>
<div class="modal fade bs-example-modal-sm" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="confirmAcceptModal">Proses</button>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('footer') ?>