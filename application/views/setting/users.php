<?php $this->load->view('header') ?>
<style>
    .select2-results__option.select2-results__option--highlighted small{
        color: #fff;
    }
</style>
<ol class="breadcrumb">
    <li><a href="<?php echo base_url('apps'); ?>">Home</a></li>
    <li><a href="">Setting</a></li>
    <li class="active"><a href="#">Setting Akun</a></li>
</ol>
<div class="row">
    <div class="col-md-6">
    	<h3>Daftar Akun</h3>
    </div>
    <div class="col-md-6" style="text-align: right;">
        <button class="btn btn-primary btn-sm" onclick="buat_user()">Tambah Akun</button>
    </div>
</div>
<div id="content_table_user">
    <div class="panel">
        <div class="panel-body">
            <table class="table table-striped" id="data_table">
                <thead>
                    <th>Nama</th>
                    <th>Unit Kerja</th>
                    <th>Keterangan</th>
                </thead>
                <tbody></tbody>
            </table>
            <h4 id="tabel_nothing" style="display:none; text-align: center;">Belum ada akun ditambahkan</h4>
            <div id="page-content" class="pagination" style="text-align: center;margin: 0px; display: block;"></div>
        </div>
    </div>
</div>
<div id="content_form_user" style="display:none;">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Form User</h4>
        </div>
        <div class="panel-body">
            <form class="" id="form_user">
                <div class="row">
                    <div class="col-md-3">
                        <img src="<?php echo base_url("assets/smc/dist/img/avatar.png"); ?>" class="img img-responsive" style="width:100%; cursor:pointer;">
                        <a href="#" class="btn btn-primary" id="foto" style="position:absolute;left: 15px;right: 15px;bottom: 0;margin: 0px;">ubah foto</a>
                        <input type="file" name="file_foto" accept="image/*">
                    </div>
                    <div class="col-md-9">
                        <div class="form-group">
                            <label>Username/Email</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="email" name="email" class="form-control" required="" autocomplete="false"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                <input type="password" name="password" class="form-control" required="" autocomplete="false"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><input type="checkbox" name="is_aktif"/> Aktifkan User ?</label>
                        </div>
                        <hr/>
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" required=""/>
                        </div>
                        <div class="form-group">
                            <label>Peran/Previlege</label>
                            <select name="id_role" class="form-control" require>
                                <option value="3" selected>Pengguna</option>
                                <option value="1">Administrator</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tingkat Unit Kerja</label>
                            <select name="kategori_unit" class="form-control" require>
                                <option value="" selected disabled>Pilih Tingkat Unit Kerja</option>
                                <option value="kabupaten">Kabupaten</option>
                                <option value="skpd">SKPD</option>
                                <option value="kecamatan">Kecamatan</option>
                                <option value="desa">Desa</option>
                            </select>
                        </div>
                        <div class="form-group" id="unit_kerja" style="display:none;">
                            <label></label>
                            <select name="id_unit_kerja" class="form-control" style="width: 100%" require></select>
                        </div>
                        <div class="form-group" id="unit_desa" style="display:none">
                            <label>Desa</label>
                            <select name="id_unit" class="form-control" style="width: 100%"></select>
                        </div>
                        <div class="form-group">
                            <label>No KTP</label>
                            <input type="text" name="no_ktp" class="form-control" required=""/>
                        </div>
                        <div class="form-group">
                            <label>No HP</label>
                            <input type="text" name="no_hp" class="form-control" required=""/>
                        </div>
                        <div class="form-group">
                            <label>No Telp</label>
                            <input type="text" name="no_telp" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div id="notif"></div>
                <div style="text-align:right">
                    <button class="btn btn-primary" type="submit">Submit</button>
                    <button class="btn btn-default" type="button" onclick="cancel_form()">Cancel</button>
                </div>
            </form>
        </div>
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