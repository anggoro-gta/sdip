<?php $this->load->view('header') ?>
<?php
$elemen=$survei->singkat_keterangan_elemen;
?>

<ol class="breadcrumb">
    <li><a href="<?php echo BASE_URL().'apps' ?>">Home</a></li>
    <li><a href="<?php echo BASE_URL().'survei' ?>">Survei</a></li>
    <li class="active">Detail</li>
</ol>
<div class="portlet portlet-green">
    <h3>Data Survei</h3>
    <div class="row">
        <div class="col-md-2">Elemen</div>
        <div class="col-md-10" id="keterangan_unsur"><?php echo $survei->keterangan_elemen; ?></div>
    </div>
    <?php if($survei->id_skpd){ ?>
    <div class="row">
        <div class="col-md-2">SKPD</div>
        <div class="col-md-10"><?php echo $survei->nama_skpd; ?></div>
    </div>
    <?php } ?>
    <div class="row">
        <div class="col-md-2">Kecamatan</div>
        <div class="col-md-10"><?php echo $survei->nama_unit; ?></div>
    </div>		
    <div class="row">
        <div class="col-md-2">PPL</div>
        <div class="col-md-10"><?php echo $survei->nama_ppl; ?></div>
    </div>   
</div>
<div class="panel" id="content_table_detail_survei">	
	<div class="panel-body">
    <?php 
        $id_bdt = null;
        foreach($survei->field_values as $v){
            if(strpos($v->key_field,"id_bdt")!== false)
            {
                $id_bdt=$v->value;
            }
        }
        ?>
        <?php foreach($survei->field_values as $v){?>
		<div class="row" style="margin:5px 0;">
			<div class="col-md-3"><?php echo $v->nama_field; ?></div>
			<div class="col-md-9">: 
                <?php if(strpos($v->key_field,"images")!== false) { 
                    $images_list = json_decode($v->value);
                    foreach ($images_list as $image) {
                        if ($id_bdt != null) echo '<img width="150px" src="'.base_url('assets/survey/'.$id_bdt.'/'.$image).'" /> ';
                    }
                ?>
                <?php } else {echo $v->value === NULL?'-':$v->value;} ?>
				<?php if (strpos($v->key_field,"alamat") !== false){ ?>
                    <a href="http://maps.google.com/maps?&z=11&q=<?php echo $survei->lat; ?>+<?php echo $survei->lon; ?>&ll=<?php echo $survei->lat; ?>+<?php echo $survei->lon; ?>" target="_blank"><i class="fa fa-map-marker"></i></a>
                <?php } ?>
			</div>
		</div>
        <?php }?>
	</div>
</div>
	        <?php if(strpos(strtolower($survei->keterangan_elemen), 'peternakan') !== false || 
                strpos(strtolower($survei->keterangan_elemen), 'perikanan') !== false || 
                strpos(strtolower($survei->keterangan_elemen), 'pertanian') !== false || 
                strpos(strtolower($survei->keterangan_elemen), 'perkebunan') !== false ){ ?>
<div class="panel" id="content_table_detail_survei">
	<div class="panel-heading">
		<button class="btn btn-primary btn-sm pull-right" onclick="open_tambah_detail_form()">Tambah Detail</button>
		<button class="btn btn-twitter btn-sm pull-right" style="margin-right:5px;" onclick="ekspor()">Ekspor Excel</button>
		<h3 class="panel-title">Detail Survei</h3>
		<form id="ekspor_form" action="<?php echo BASE_URL() ?>survei/ekspor_survey_detail" method="post" target="_blank" style="display: none;">
            <input name="id_survey" type="hidden" value="<?php echo $survei->id_survey; ?>">
        </form>
	</div>
	<div class="panel-body">
		<table class="table table-condensed table-striped" id="data_table" >
			
		</table>
		<h4 id="tabel_nothing" style="display:none; text-align: center;">Tidak ada detail</h4>
        <div id="page-content" class="pagination" style="text-align: center;margin: 0px; display: block;"></div>
	</div>
</div> <?php } ?>
<div class="panel" id="content_form_detail_survei" style="display: none;">
	<form class="" id="form_detail_survei">
		<div class="panel-heading">
	        <h4 class="panel-title">Form Detail Survei</h4>
	    </div>
	    <div class="panel-body">
        	<input type="hidden" name="id_survey" id="id_survey" value="<?php echo $survei->id_survey; ?>"/>
            <input type="hidden" name="keterangan_elemen" id="keterangan_elemen" value="<?php echo $survei->keterangan_elemen; ?>"/>
        	<div class="form-group">
                <label>Nama</label>
                <input type="text" class="form-control" name="nama" id="nama"/>
            </div>
            <div class="form-group">
                <label>Jumlah Anggota</label>
                <input type="number" class="form-control" name="jumlah_anggota" id="jumlah_anggota"/>
            </div>
            <div class="form-group">
                <label>Status Anggota</label>
                <select class="form-control" name="status_anggota" id="status_anggota">
                	<option value="1">Aktif</option>
                	<option value="0">Tidak Aktif</option>
            	</select>
            </div>
        </div>        
        <?php if(strpos(strtolower($survei->keterangan_elemen), 'pertanian') !== false || 
                strpos(strtolower($survei->keterangan_elemen), 'perkebunan') !== false ){ ?>
        <div>
        <?php }else{ ?>
        <div style="display: none">
        <?php } ?>
            <div class="panel-heading" style="padding: 10px 20px; border-top: 1px solid #ddd; border-radius: 0px;">
                <h4 class="panel-title"><b>Kelompok Pertanian/perkebunan</b></h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label>Luas Lahan <code>(*Ha)</code></label>
                    <input type="number" class="form-control" name="luas_lahan" id="luas_lahan"/>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Pola Tanam 1</label>
                            <input type="text" class="form-control" name="pola_tanam_1" id="pola_tanam_1"/>
                        </div>
                        <div class="col-md-4">
                            <label>Pola Tanam 2</label>
                            <input type="text" class="form-control" name="pola_tanam_2" id="pola_tanam_2"/>
                        </div>
                        <div class="col-md-4">
                            <label>Pola Tanam 3</label>
                            <input type="text" class="form-control" name="pola_tanam_3" id="pola_tanam_3"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Jumlah Produksi <code>(*Ton)</code></label>
                    <input type="number" class="form-control" name="jumlah_produksi" id="jumlah_produksi"/>
                </div>
            </div>
        </div>
        <?php if(strpos(strtolower($survei->keterangan_elemen), 'peternakan') !== false || 
                strpos(strtolower($survei->keterangan_elemen), 'perikanan') !== false ){ ?>
        <div>
        <?php }else{ ?>
        <div style="display: none">
        <?php } ?>
            <div class="panel-heading" style="padding: 10px 20px; border-top: 1px solid #ddd; border-radius: 0px;">
                <h4 class="panel-title"><b>Kelompok Peternakan/Perikanan</b></h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label>Jenis Ternak/Ikan</label>
                    <select type="text" class="form-control" name="jenis_ternak" id="jenis_ternak">
                        <option value="" selected data-kategori="">Tidak ada Ternak</option>
                        <?php foreach ($ternak as $key => $value) { ?>
                            <option value="<?php echo $value->nama ?>" data-kategori="<?php echo $value->kategori; ?>"><?php echo $value->nama ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Kategori Produk</label>
                    <select class="form-control" name="kategori_produk" id="kategori_produk">
                        <option value="">None</option>
                        <option value="Benih">Benih/Bibit</option>
                        <option value="Konsumsi">Konsumsi</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="number" class="form-control" name="jumlah_ternak" id="jumlah_ternak"/>
                </div>
            </div>
        </div>
        <div class="panel-body" style="border-top: 1px solid #ddd!important; border-radius: 0px;">
            <div class="form-group">
                <label>Potensi Utama</label>
                <input type="text" class="form-control" name="potensi_utama" id="potensi_utama"/>
            </div>
            <div class="form-group">
                <label>Produk Olahan</label>
                <input type="text" class="form-control" name="produk_olahan" id="produk_olahan"/>
            </div>
            <div>
	            <button class="btn btn-primary" type="submit" ><i class="fa fa-check"></i> Simpan</button>
	            <button class="btn btn-default" type="button" onclick="cancel_form()">Cancel</button>
	        </div>
		</div>
	</form>
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