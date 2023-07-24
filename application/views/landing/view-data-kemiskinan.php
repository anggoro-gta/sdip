<?php  $this->load->view('landing/header'); ?>
<section id="wrapper">
<section class="single-page">
	<section class="content content-white">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
				<h4>Rekap Data Kemiskinan</h4>
					<div id="map" style="height: 500px;"></div>
					<div id="map_legends" class="row green" style="padding-top:20px"></div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="block-flat">
						<div class="content" style="padding-top:30px">	
							<div class="form-group">
								<label>Periode</label>
								<select name="periode" class="form-control" id="periode">
									<?php for($i=2017; $i<=date("Y"); $i++){ ?>
									<option value="<?php echo $i; ?>" <?php echo $i==date("Y")?"selected":""; ?>>
										<?php echo $i; ?>
									</option>
									<?php } ?>
								</select>
							</div>	
							<div class="form-group form_group_kecamatan">
								<label>Kecamatan</label>
								<select class="form-control" name="id_kecamatan" id="id_kecamatan" data-select="<?php echo ($this->session->userdata('smc_kategori_unit')=="desa" || $this->session->userdata('smc_kategori_unit')=="kecamatan"?"false":"true"); ?>">
								</select>								
							</div>
							<div class="form-group">
								<label>Parameter</label>
								<select class="form-control" id="id_parameter">
								</select>								
							</div>
							<div class="form-group">
								<label>Sub Parameter</label>
								<select class="form-control" id="id_sub_parameter">
								</select>								
							</div>						
							<div>
								<button class="btn btn-default btn-block" type="button" onclick="exportKemiskinanData()"><i class="fa fa-file-excel-o"></i> Ekspor</button>
								<button class="btn btn-primary btn-block" onclick="getKemiskinanData()"><i class="fa fa-search"></i> Tampilkan Hasil Rekap</button>	
							</div>
							<form id="ekspor_form" action="<?php echo base_url('d/survei/ekspor_survey_kemiskinan') ?>" method="post" target="_blank" style="display: none;">
								<input name="periode" type="hidden">
								<input name="id_kecamatan" type="hidden">
								<input name="id_parameter" type="hidden">
								<input name="id_sub_parameter" type="hidden">
							</form>
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<div class="tab-portlet tp-success">
						<div class="title">
							<h4>Rekap Survei Kemiskinan</h4>
						</div>
						<div class="content">
							<table class="table table-striped" id="data_table">
								<thead>
									<th>Unit</th>
									<th>Desil 1</th>
									<th>Desil 2</th>
									<th>Desil 3</th>
									<th>Desil 4</th>
									<th>Desil 1~4</th>
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
					<!-- <div class="tab-portlet tp-success">
						<div class="content">
							<table class="table table-stripped  table-responsive" id="data_table"></table>
							<h4 id="tabel_nothing" style="display:none; text-align: center;">Belum ada unit ditambahkan</h4>
							<div id="page-content" class="pagination" style="text-align: center;margin: 0px; display: block;"></div>
						</div>
					</div> -->
				</div>
			</div>
		</div>
	</section>
</section>
</section>

<?php  $this->load->view('landing/footer'); ?>