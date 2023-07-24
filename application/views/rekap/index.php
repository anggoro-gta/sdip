<?php $this->load->view('header') ?>
<style>
    .select2-results__option.select2-results__option--highlighted small{
        color: #fff;
    }
    .ajaxLoading {
        background: #FFF url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDQiIGhlaWdodD0iNDQiIHZpZXdCb3g9IjAgMCA0NCA0NCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBzdHJva2U9IiM5OTkiPiAgICA8ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiIHN0cm9rZS13aWR0aD0iMiI+ICAgICAgICA8Y2lyY2xlIGN4PSIyMiIgY3k9IjIyIiByPSIxIj4gICAgICAgICAgICA8YW5pbWF0ZSBhdHRyaWJ1dGVOYW1lPSJyIiAgICAgICAgICAgICAgICBiZWdpbj0iMHMiIGR1cj0iMS40cyIgICAgICAgICAgICAgICAgdmFsdWVzPSIxOyAyMCIgICAgICAgICAgICAgICAgY2FsY01vZGU9InNwbGluZSIgICAgICAgICAgICAgICAga2V5VGltZXM9IjA7IDEiICAgICAgICAgICAgICAgIGtleVNwbGluZXM9IjAuMTY1LCAwLjg0LCAwLjQ0LCAxIiAgICAgICAgICAgICAgICByZXBlYXRDb3VudD0iaW5kZWZpbml0ZSIgLz4gICAgICAgICAgICA8YW5pbWF0ZSBhdHRyaWJ1dGVOYW1lPSJzdHJva2Utb3BhY2l0eSIgICAgICAgICAgICAgICAgYmVnaW49IjBzIiBkdXI9IjEuNHMiICAgICAgICAgICAgICAgIHZhbHVlcz0iMTsgMCIgICAgICAgICAgICAgICAgY2FsY01vZGU9InNwbGluZSIgICAgICAgICAgICAgICAga2V5VGltZXM9IjA7IDEiICAgICAgICAgICAgICAgIGtleVNwbGluZXM9IjAuMywgMC42MSwgMC4zNTUsIDEiICAgICAgICAgICAgICAgIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIiAvPiAgICAgICAgPC9jaXJjbGU+ICAgICAgICA8Y2lyY2xlIGN4PSIyMiIgY3k9IjIyIiByPSIxIj4gICAgICAgICAgICA8YW5pbWF0ZSBhdHRyaWJ1dGVOYW1lPSJyIiAgICAgICAgICAgICAgICBiZWdpbj0iLTAuOXMiIGR1cj0iMS40cyIgICAgICAgICAgICAgICAgdmFsdWVzPSIxOyAyMCIgICAgICAgICAgICAgICAgY2FsY01vZGU9InNwbGluZSIgICAgICAgICAgICAgICAga2V5VGltZXM9IjA7IDEiICAgICAgICAgICAgICAgIGtleVNwbGluZXM9IjAuMTY1LCAwLjg0LCAwLjQ0LCAxIiAgICAgICAgICAgICAgICByZXBlYXRDb3VudD0iaW5kZWZpbml0ZSIgLz4gICAgICAgICAgICA8YW5pbWF0ZSBhdHRyaWJ1dGVOYW1lPSJzdHJva2Utb3BhY2l0eSIgICAgICAgICAgICAgICAgYmVnaW49Ii0wLjlzIiBkdXI9IjEuNHMiICAgICAgICAgICAgICAgIHZhbHVlcz0iMTsgMCIgICAgICAgICAgICAgICAgY2FsY01vZGU9InNwbGluZSIgICAgICAgICAgICAgICAga2V5VGltZXM9IjA7IDEiICAgICAgICAgICAgICAgIGtleVNwbGluZXM9IjAuMywgMC42MSwgMC4zNTUsIDEiICAgICAgICAgICAgICAgIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIiAvPiAgICAgICAgPC9jaXJjbGU+ICAgIDwvZz48L3N2Zz4=) no-repeat 50% 50%;
        height: 200px;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        z-index: 9;
    }
    .ajaxLoading h4{
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        text-align: center;
        color: #c1c1c1;
    }
    #data_table tr{
        transition: background-color .5s linear; 
        -moz-transition: background-color .5s linear;  
        -webkit-transition: background-color .5s linear; 
        -ms-transition: background-color .5s linear; 
        background-color : #FFFFFF;
    }
    #data_table tr.edited{
        background-color : #B3E5FC;
    }
</style>
<ol class="breadcrumb">
   <li><a href="<?php echo BASE_URL().'apps' ?>">Home</a></li>
    <li class="active">Data Umum</li>
</ol>
<div class="row">
    <div class="col-md-3">
          <div class="block-flat">
            <div class="header">
                <h4>Filter Elemen</h4>
            </div>
            <div class="content">
                <div class="form-group">
                    <label>Elemen</label>
                    <select class="form-control" name="unsur" id="unsur"></select>                  
                </div>
                <div class="form-group">
                    <label>Tingkat Unit</label>
                    <select class="form-control" name="unit" id="unit">
                        <?php if ($this->session->userdata('smc_kategori_unit')=="kabupaten" || strtolower($this->session->userdata('smc_role'))=="administrator") { ?>
                            <option value="" disabled selected>Pilih Tingkat Unit</option>
                            <option value="kabupaten">Kabupaten</option>
                            <option value="kecamatan">Kecamatan</option>
                        <?php } else if($this->session->userdata('smc_kategori_unit')=="kecamatan" || $this->session->userdata('smc_kategori_unit')=="desa") { ?>
                            <option value="kecamatan" selected>Kecamatan</option>
                        <?php } else { ?>
                            <option value="kabupaten" selected>Kabupaten</option>
                        <?php } ?>
                    </select>
                </div>
                <?php if ($this->session->userdata('smc_kategori_unit')=="skpd" || $this->session->userdata('smc_kategori_unit')=="kabupaten" || strtolower($this->session->userdata('smc_role'))=="administrator") { ?>
                    <div class="form-group form_group_skpd" style="display:none;">
                        <label>SKPD</label>
                        <select class="form-control" name="id_skpd" id="id_skpd" data-select="<?php echo ($this->session->userdata('smc_kategori_unit')=="skpd"?"false":"true"); ?>">
                        	<?php if($this->session->userdata('smc_kategori_unit')=="skpd"){ ?>
                        		<option value="<?php echo $this->session->userdata('smc_id_unit_kerja'); ?>" selected><?php echo $this->session->userdata('smc_nama_unit_kerja'); ?></option>
                    		<?php } ?>
                        </select>
                    </div>
                <?php } ?>
                <?php if ($this->session->userdata('smc_kategori_unit')=="kecamatan" || 
                            $this->session->userdata('smc_kategori_unit')=="desa" || 
                            $this->session->userdata('smc_kategori_unit')=="kabupaten" || 
                            strtolower($this->session->userdata('smc_role'))=="administrator") { ?>
                    <div class="form-group form_group_kecamatan" <?php echo ($this->session->userdata('smc_kategori_unit')=="desa" || $this->session->userdata('smc_kategori_unit')=="kecamatan"?"false":"style='display:none;'"); ?>>
                        <label>Kecamatan</label>
                        <select class="form-control" name="id_kecamatan" id="id_kecamatan" data-select="<?php echo ($this->session->userdata('smc_kategori_unit')=="desa" || $this->session->userdata('smc_kategori_unit')=="kecamatan"?"false":"true"); ?>">
                            <?php if ($this->session->userdata('smc_kategori_unit')=="kecamatan") { ?>
                                <option value="<?php echo $this->session->userdata('smc_id_unit_kerja'); ?>"><?php echo $this->session->userdata('smc_nama_unit_kerja'); ?></option>
                            <?php } else if ($this->session->userdata('smc_kategori_unit')=="desa") { ?>
                                <option value="<?php echo $this->session->userdata('smc_id_parent_unit'); ?>"><?php echo $this->session->userdata('smc_nama_parent_unit'); ?></option>
                            <?php } ?>
                        </select>
                        <?php if ($this->session->userdata('smc_kategori_unit')=="desa") { ?>
                            <input type="hidden" id="id_desa" value="<?php echo $this->session->userdata('smc_id_unit_kerja'); ?>"/>
                        <?php } ?>
                    </div>
                <?php } ?>
                <button class="btn btn-default btn-block" onclick="search()">Tampilkan Hasil Rekap</button>
            </div>
        </div>
    </div>
    <div class="col-md-9" id="data-loading" style="display: none;">
        <div class="ajaxLoading">
            <h4>Mohon tunggu sebentar . . .</h4>
        </div>
    </div>
    <div class="col-md-9" id="main-box" style="display: none;">
        <div class="tab-portlet tp-success">
			<div class="title">
                <h4>Rekap Data Umum</h4>
            </div>
            <div class="content">
                <table class="table table-stripped  table-responsive" id="data_table"></table>
                <h4 id="tabel_nothing" style="display:none; text-align: center;">Belum ada unit ditambahkan</h4>
                <div id="page-content" class="pagination" style="text-align: center;margin: 0px; display: block;"></div>
            </div>
        </div>
    </div>
    <div class="col-md-9" id="form-panel" style="display: none;">
        <form id="form_rekap">
            <div class="panel">
                <div class="panel-heading">
                    <h4 class="panel-title">Data Umum</h4>
                </div>
                <div class="panel-body">
                    <input type="hidden" name="periode" id="periode" required=""/>
                    <input type="hidden" name="id_elemen" id="id_elemen" required=""/>
                    <input type="hidden" name="id_unit" required=""/>
                    <input type="hidden" name="satuan" id="satuan" required=""/>
                    <input type="hidden" name="is_valid" id="is_valid" value="1" required=""/>
                    <div class="form-group">
                        <label>SKPD</label>
                        <select class="form-control" name="id_skpd"></select>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Jumlah/Nilai</label>
                        <input type="number" class="form-control" name="jumlah" id="jumlah" required=""/>
                    </div>
                    <div class="form-group">
                        <label>Jenis Data</label>
                        <select class="form-control" name="jenis_data" id="jenis_data" required>
                            <option value="tetap">Tetap</option>
                            <option value="sementara">Sementara</option>
                            <option value="sangat sementara">Sangat Sementara</option>
                            <option value="tidak ada">Tidak Ada</option>
                        </select>
                    </div>
                </div>
                <div class="panel-footer">
                    <button class="btn btn-primary" type="submit" ><i class="fa fa-check"></i> Simpan</button>
                    <button type="button" class="btn btn-default" onclick="cancel_form()">Close</button>
                </div>
            </div>
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
<div class="modal fade" id="chartModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Grafik</h4>
            </div>
            <div class="modal-body">
                <canvas id="chart" width="400" height="400"></canvas>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('footer') ?>