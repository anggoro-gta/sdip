<?php $this->load->view('landing/header'); ?>
    <section class="single-page">

        <section class="content content-white">
            <div class="container">
                <div style="padding-bottom: 10px">
                    <h4>
                        Rekap Nilai Tukar Petani (NTP)
                    </h4>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="block-flat">
                            <div class="content">
                                <div class="form-group">
                                    <label>Periode</label>
                                    <select name="periode" class="form-control" id="periode">
                                        <?php foreach ($ntp_years as $y){ ?>
                                            <option value="<?php echo $y->tahun; ?>" <?php echo $y->tahun==date("Y")?"selected":""; ?>>
                                                <?php echo $y->tahun; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="kelompok">Kategori</label>
                                    <select class="form-control" name="kelompok" id="unsur">
                                        <option value="">Pilih Kategori</option>
                                        <?php foreach($unsurs as $unsur){ ?>
                                            <option value="<?php echo $unsur->id_elemen; ?>">
                                                <?php echo $unsur->keterangan; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <button class="btn btn-primary btn-block" onclick="getRekapNTP()"><i class="fa fa-search"></i> Tampilkan Hasil Rekap</button>
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