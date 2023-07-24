<?php echo $this->head() ?>
<style>
	.select2-results__option.select2-results__option--highlighted small{
        color: #fff;
    }
    .map-tips{
    	position: absolute;
	    background: #e57373;
	    left: 125px;
	    top : 8px;
	    z-index: 1;
	    min-width: 200px;
	    box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
    }
</style>
<ol class="breadcrumb">
    <li><a href="<?php echo $this->location('apps'); ?>">Home</a></li>
    <li><a href="<?php echo $this->location('module/smc/rekap'); ?>">Rekap</a></li>
</ol>
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header">
				<h4 class="box-title">Filter Profil</h4>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Unsur</label>
							<select class="form-control" name="elemen-1" id="elemen-1">
								<?php foreach ($unsur as $key => $value) { ?>
									<option value="<?php echo $value->id_elemen; ?>"><?php echo $value->keterangan; ?></option>
								<?php } ?>
							</select>					
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Elemen Detail</label>
							<select class="form-control" name="elemen-2" id="elemen-2"></select>					
						</div>
					</div>
					<div class="col-md-12">
						<div class="map-tips"></div>
						<div id="map" style="height: 400px;"></div>
					</div>
				</div>
				<!-- <div class="form-group">
                    <label>Kecamatan</label>
                    <select class="form-control" name="id_unit" id="id_unit"></select>
                </div>
				<button class="btn btn-default" onclick="search()">Cari</button> -->
			</div>
		</div>
	</div>
	<!-- <div class="col-md-12">
		<div class="panel">
			<div class="panel-heading">
				<h4 class="panel-title">Data Profil</h4>
			</div>
			<div class="panel-body">
				<table class="table table-stripped" id="data_table"></table>
				<h4 id="tabel_nothing" style="display:none; text-align: center;">Belum ada unit ditambahkan</h4>
            	<div id="page-content" class="pagination" style="text-align: center;margin: 0px; display: block;"></div>
			</div>
		</div>
	</div> -->
</div>
<div class="modal fade bs-example-modal-lg" id="dataModal" data-backdrop="static" role="dialog" aria-labelledby="myLargeModalLabel">
	<div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        	<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Data Profil</h4>
            </div>
            <div class="modal-body">
            	<table class="table table-stripped" id="data_table"></table>
				<h4 id="tabel_nothing" style="display:none; text-align: center;">Belum ada unit ditambahkan</h4>
            	<div id="page-content" class="pagination" style="text-align: center;margin: 0px; display: block;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
    	</div>
	</div>
</div>
<div class="modal fade bs-example-modal-lg" id="formModal" data-backdrop="static" role="dialog" aria-labelledby="myLargeModalLabel">
	<div class="modal-dialog modal-lg" role="document">
        <form id="form_rekap">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
        		<input type="hidden" name="periode" id="periode" required=""/>
        		<input type="hidden" name="id_elemen" id="id_elemen" required=""/>
        		<input type="hidden" name="id_unit" required=""/>
        		<div class="form-group">
                    <label>SKPD</label>
                    <select class="form-control" name="id_skpd" id="id_skpd"></select>
                </div>
                <div class="form-group">
        			<label>Jumlah/Nilai</label>
        			<input type="number" class="form-control" name="jumlah" id="jumlah" required=""/>
        		</div>
        		<div class="form-group">
        			<label>Satuan</label>
        			<input type="text" class="form-control" name="satuan" id="satuan" required=""/>
        		</div>
        		<div class="form-group">
        			<label>Jenis Data</label>
        			<select class="form-control" name="jenis_data" id="jenis_data">
        				<option value="tetap">Tetap</option>
        				<option value="sementara">Sementara</option>
        				<option value="sangat sementara">Sangat Sementara</option>
        				<option value="tidak ada">Tidak Ada</option>
        			</select>
        		</div>
        		<div class="form-group">
        			<label><input type="checkbox" name="is_valid" id="is_valid"> Validasi ?</label>
        		</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="submit" ><i class="fa fa-check"></i> Simpan</button>
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
<?php echo $this->foot() ?>