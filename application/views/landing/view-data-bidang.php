<?php $this->load->view('landing/header'); ?>
<section class="single-page">

    <section class="content content-white">
        <div class="container">
            <div style="padding-bottom: 10px">
                <h4>
                    Rekap Bidang UMKM
                </h4>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="block-flat">
                        <div class="content">
                            <form method="post" action="bidang_print" target="_blank">
                                <div class="form-group">
                                    <label>Periode</label>
                                    <select name="periode" class="form-control" id="periode">

                                        <?php for ($i = 0; $i < $count_years; $i++) { ?>
                                            <option value="<?php echo $survey_years[$i]['tahun']; ?>" <?php echo $survey_years[$i]['tahun'] == date("Y") ? "selected" : ""; ?>>
                                                <?php echo $survey_years[$i]['tahun']; ?>
                                            </option>
                                        <?php } ?>

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="kelompok">Bidang</label>
                                    <select class="form-control" name="kelompok" id="unsur">
                                        <option value="All" selected>All</option>
                                        <option value="Kuliner">Kuliner</option>
                                        <option value="Fashion">Fashion</option>
                                        <option value="Kerajinan">Kerajinan</option>
                                        <option value="Agribisnis">Agribisnis</option>
                                        <option value="Perdagangan Besar">Perdagangan Besar</option>
                                        <option value="Jasa">Jasa</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div class="btn btn-primary btn-block" onclick="getrekapbidang()"><i class="fa fa-search"></i> Tampilkan Hasil Rekap</div>
                                <div class="btn btn-warning btn-block" onclick="getswot()"><i class="fa fa-search"></i> Roadmap SWOT</div>
                                <div class="btn btn-warning btn-block" onclick="getswotpermen()"><i class="fa fa-search"></i> Roadmap SWOT Permendagri 90</div>
                                <button type="submit" class="btn btn-success btn-block"><i class="fa fa-file"></i> Cetak Rekap Excel</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-portlet tp-success">
                        <div class="content">
                            <table class="table table-striped" id="data_table">

                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>
</section>

<?php $this->load->view('landing/footer'); ?>