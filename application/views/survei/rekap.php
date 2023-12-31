<?php $this->load->view('header') ?>
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
    <li><a href="<?php echo BASE_URL().'apps' ?>">Home</a></li>
    <li><a href="">Survei</a></li>
    <li class="active">Rekap</li>
</ol>
<div class="row">
    <div class="col-md-3">
        <div class="block-flat">
            <div class="header">
                <h4>
                   Rekap Survei
                 
                </h4>
            </div>
            <div class="content">
            	<div class="form-group">
            		<label>Periode</label>
            		<select name="periode" class="form-control" id="periode">
            			<?php for($i=2017; $i<=date("Y"); $i++){ ?>
            				<option value="<?php echo $i; ?>" <?php echo $i==date("Y")?"selected":""; ?>><?php echo $i; ?></option>
            			<?php } ?>
            		</select>
        		</div>
                <div class="form-group">
                    <label for="kelompok">Kategori</label>
                    <select class="form-control" name="kelompok" id="unsur">
                        <option value="">Pilih Kategori</option>
                        <?php foreach($unsurs as $unsur){ ?>
                            <option value="<?php echo $unsur->id_elemen; ?>"><?php echo $unsur->keterangan; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div >
                    <button type="button" class="btn btn-sm btn-danger" onclick="clear_marker()"><i class="fa fa-trash-o"></i> Clear Marker</button>
                </div>
            </div>
        </div>
    </div>
	<div class="col-md-9">
		<div class="tab-portlet tp-success">
			<div class="title">
				<h4>Hasil Survei</h4>
			</div>
			<div class="content" style="padding: 0px;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="map-tips"></div>
                        <div id="map" style="height: 500px;"></div>
                        <div class="row green">
                            <div class="col-md-3">
                                <i class="fa fa-square" style="color: #008cff;"></i> Cluster kelompok dengan jumlah sedikit
                            </div>
                            <div class="col-md-3">
                                <i class="fa fa-square" style="color: #ffbf00;"></i> Cluster kelompok dengan jumlah sedang
                            </div>
                            <div class="col-md-3">
                                <i class="fa fa-square" style="color: #ff0000;"></i> Cluster kelompok dengan jumlah banyak
                            </div>
                            <div class="col-md-3">
                                <i class="fa fa-square" style="color: #ff00f2;"></i> Cluster kelompok dengan jumlah sangat banyak
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</div>
	</div>
</div>
<div class="modal fade bs-example-modal-lg" id="dataModal" data-backdrop="static" role="dialog" aria-labelledby="myLargeModalLabel">
	<div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        	<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body" style="padding: 0px;">
            	<ul class="nav nav-tabs">
				  	<li class="active">
				    	<a data-toggle="tab" href="#tab-pertanian">Pertanian</a>
				  	</li>
				  	<li>
					    <a data-toggle="tab" href="#tab-perkebunan">Perkebunan</a>
				  	</li>
				  	<li>
				    	<a data-toggle="tab" href="#tab-peternakan">Peternakan</a>
				  	</li>
				  	<li>
					    <a data-toggle="tab" href="#tab-perikanan">Perikanan</a>
				  	</li>
				</ul>
            	<div class="tab-content" style="margin:0px; background:transparent; padding:0px;">
				  	<div class="tab-pane active" id="tab-pertanian" role="tabpanel">
				  		<div class="table-responsive">
				  			<table class="table" style="margin-bottom: 0px;"></table>
				  		</div>
				  	</div>
				  	<div class="tab-pane" id="tab-perkebunan" role="tabpanel">
				  		<div class="table-responsive">
				  			<table class="table" style="margin-bottom: 0px;"></table>
				  		</div>
				  	</div>
				  	<div class="tab-pane" id="tab-peternakan" role="tabpanel">
				  		<div class="table-responsive">
				  			<table class="table" style="margin-bottom: 0px;"></table>
				  		</div>
				  	</div>
				  	<div class="tab-pane" id="tab-perikanan" role="tabpanel">
				  		<div class="table-responsive">
				  			<table class="table" style="margin-bottom: 0px;"></table>
			  			</div>
				  	</div>
				</div>
            </div>
            <div class="modal-footer" style="margin-top: 0px;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
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