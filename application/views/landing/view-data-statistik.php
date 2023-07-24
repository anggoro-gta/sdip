<?php  $this->load->view('landing/header'); ?>
<style>
    .iziModal{
        margin-left:auto;
        margin-right:auto;
    }
    .card{
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        transition: all 0.3s cubic-bezier(.25,.8,.25,1);
    }
    .card-map-right{
        position: relative;
        background: #f5f5f5;
    }
    .single-page{
        padding-top:0px!important;
        padding-bottom:0px!important;
    }
    .rotation {
    	display: flex;
    }
    .rotation .rotate {
    	margin : 1px;
    }
    .rotation .rotate:first-child {
    	margin-left:0px
    }
    .rotation .rotate:last-child {
    	margin-right:0px
    }
    #profil{
    	height: calc(100% - 65px);
		overflow-y: scroll;
    }
    @media (min-width: 768px){
        .iziModal{
            margin-left:40%;
        }
        .card-map-right{
	        position: absolute;
	        background: rgba(220, 220, 220, 0.8);
	        right: 0;
	        left: 60%;
	        bottom: 0;
	        top: 0;
	        z-index: 2;
	    }
	    .single-page{
	        padding-top:100px!important;
	        padding-bottom:0px!important;
	    }
	    .nav-card-map-right{
	    	position: absolute;
		    left: calc(60% - 35px);
		    top: 0px;
	        width: 30px;
		    bottom: 0;
		    z-index: 1;
	    }
	    .rotation {
	    	display: flex;
		    transform: rotate(90deg);
		    /*transform-origin: bottom left;*/
		}
		.rotation .rotate {		    
		    display: inline-block;
		    transform: rotate(180deg);
		}
		#profil{
	    	max-height:100%;
	    }
	    .filter-container{
	    	max-width:100%;
	    	max-height: 100%;
	    }
    }
    .content{
        position:relative;
    }
    .ajaxLoading {
        background: #FFF url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDQiIGhlaWdodD0iNDQiIHZpZXdCb3g9IjAgMCA0NCA0NCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBzdHJva2U9IiM5OTkiPiAgICA8ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiIHN0cm9rZS13aWR0aD0iMiI+ICAgICAgICA8Y2lyY2xlIGN4PSIyMiIgY3k9IjIyIiByPSIxIj4gICAgICAgICAgICA8YW5pbWF0ZSBhdHRyaWJ1dGVOYW1lPSJyIiAgICAgICAgICAgICAgICBiZWdpbj0iMHMiIGR1cj0iMS40cyIgICAgICAgICAgICAgICAgdmFsdWVzPSIxOyAyMCIgICAgICAgICAgICAgICAgY2FsY01vZGU9InNwbGluZSIgICAgICAgICAgICAgICAga2V5VGltZXM9IjA7IDEiICAgICAgICAgICAgICAgIGtleVNwbGluZXM9IjAuMTY1LCAwLjg0LCAwLjQ0LCAxIiAgICAgICAgICAgICAgICByZXBlYXRDb3VudD0iaW5kZWZpbml0ZSIgLz4gICAgICAgICAgICA8YW5pbWF0ZSBhdHRyaWJ1dGVOYW1lPSJzdHJva2Utb3BhY2l0eSIgICAgICAgICAgICAgICAgYmVnaW49IjBzIiBkdXI9IjEuNHMiICAgICAgICAgICAgICAgIHZhbHVlcz0iMTsgMCIgICAgICAgICAgICAgICAgY2FsY01vZGU9InNwbGluZSIgICAgICAgICAgICAgICAga2V5VGltZXM9IjA7IDEiICAgICAgICAgICAgICAgIGtleVNwbGluZXM9IjAuMywgMC42MSwgMC4zNTUsIDEiICAgICAgICAgICAgICAgIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIiAvPiAgICAgICAgPC9jaXJjbGU+ICAgICAgICA8Y2lyY2xlIGN4PSIyMiIgY3k9IjIyIiByPSIxIj4gICAgICAgICAgICA8YW5pbWF0ZSBhdHRyaWJ1dGVOYW1lPSJyIiAgICAgICAgICAgICAgICBiZWdpbj0iLTAuOXMiIGR1cj0iMS40cyIgICAgICAgICAgICAgICAgdmFsdWVzPSIxOyAyMCIgICAgICAgICAgICAgICAgY2FsY01vZGU9InNwbGluZSIgICAgICAgICAgICAgICAga2V5VGltZXM9IjA7IDEiICAgICAgICAgICAgICAgIGtleVNwbGluZXM9IjAuMTY1LCAwLjg0LCAwLjQ0LCAxIiAgICAgICAgICAgICAgICByZXBlYXRDb3VudD0iaW5kZWZpbml0ZSIgLz4gICAgICAgICAgICA8YW5pbWF0ZSBhdHRyaWJ1dGVOYW1lPSJzdHJva2Utb3BhY2l0eSIgICAgICAgICAgICAgICAgYmVnaW49Ii0wLjlzIiBkdXI9IjEuNHMiICAgICAgICAgICAgICAgIHZhbHVlcz0iMTsgMCIgICAgICAgICAgICAgICAgY2FsY01vZGU9InNwbGluZSIgICAgICAgICAgICAgICAga2V5VGltZXM9IjA7IDEiICAgICAgICAgICAgICAgIGtleVNwbGluZXM9IjAuMywgMC42MSwgMC4zNTUsIDEiICAgICAgICAgICAgICAgIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIiAvPiAgICAgICAgPC9jaXJjbGU+ICAgIDwvZz48L3N2Zz4=) no-repeat 50% 50%;
        position: absolute;
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
    .mix{
	    display: inline-block;
	    vertical-align: top;
	    background: #fff;
	   /* border-top: .0rem solid #002E5C;*/
	    box-shadow: 0 0 0 0 #fff inset, 0 1px 2px 0 #B3B3B3;
	    border-radius: 2px;
	    height : 100px;
	    margin-bottom: 1rem;
	    position: relative;
	}
	.mix:before {
	    content: '';
	    display: inline-block;
	}
	
	.mix {
	    width: calc(100%/2 - (((2 - 1) * 1rem) / 2));
	}
</style>
<div id="modal" class="iziModal">
    <div class="form-group" style="margin: 10px 10px;">
        <label>Elemen Data</label>
        <select class="form-control" name="unsur" id="unsur">
            <option value="" disabled selected>Pilih Elemen Data</option>
            <?php foreach ($unsur as $value) { ?>
                <option value="<?php echo $value->id_elemen ?>"><?php echo $value->keterangan ?></option>
            <?php } ?>
        </select>
    </div>
    <div id="chart-elemen" style="display: none;">
        <button type="button" class="btn btn-sm btn-default" onclick="show_tree()"><i class="fa fa-chevron-left"></i> Kembali</button>
        <canvas id="chart" width="500" height="400"></canvas>
    </div>
    <div class="table-responsive" id="tree-elemen"></div>
</div>
<section class="single-page">
	<input type="hidden" id="tahun" value="<?php echo date("Y"); ?>" />
	<input type="hidden" id="currentTahun" value="<?php echo date("Y"); ?>" />
	<input type="hidden" value="<?php echo $unit->id_unit; ?>" id="id_unit_home" data-nama="<?php echo $unit->nama; ?>">
	<section class="content content-white">
	<a class='btn btn-default' id="zoom_out_map" role='button' href='#' style="visibility:hidden;position: absolute;left: 0px;top: 22px;z-index:9999;background-color:#fc0000;color:#fff;">
	        		back
        		</a>
		<div id="map" class=" embed-responsive-large-map" style="height:100%;"></div>
        <div class="card card-map-right">
    		<div class='dropdown'>
	        	<a class='dropdown-toggle btn btn-default' id="triggerTahun" role='button' data-toggle='dropdown' href='#' style="transform: rotate(270deg);position: absolute;right: 0px;top: 22px;z-index:9999;background-color:#fc0000;color:#fff;">
	        		<b style="font-size:16px"><?php echo date("Y"); ?></b> <span class='fa fa-angle-up'></span>
        		</a>
	        	<ul class='dropdown-menu pull-right' role='menu' id="menuTahun" aria-labelledby='drop4' style="margin-right: 28px;"></ul>
	        </div> 
			<h2 class="h2 title-content margin-top-lg margin-left-sm text-bold font-montserrat" id="area_title">Kabupaten Kediri</h2>
        	<div id="profil" style="padding:10px; height: 60px;"></div>
        	<div id="profil-loading" style="display: none;">
        		<div class="ajaxLoading">
	                <h4>Mohon tunggu sebentar . . .</h4>
	            </div>
        	</div>
        </div>
        <div class="nav-card-map-right">
        	<div class="filter-container">
		        <div class="rotation filter" id="filter">
		    		<button class="rotate btn btn-primary btn-sm filter-btn" type="button" data-filter="all">Semua</button>
		    		<?php foreach ($unsur_profil as $value) { ?>
		                <button class="rotate btn btn-sm filter-btn <?php echo strtolower(trim(explode(' ',$value->singkat_keterangan)[0])); ?>" style="background-color:<?php echo $value->warna ?>" type="button" data-filter=".<?php echo strtolower(trim(explode(' ',$value->singkat_keterangan)[0])); ?>"><?php echo $value->singkat_keterangan ?></option>
		            <?php } ?>
		        </div>
	        </div>
       </div>
	</section>
</section>

<?php  $this->load->view('landing/footer'); ?>