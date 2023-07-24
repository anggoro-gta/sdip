<?php $this->load->view('header') ?>
 <style>
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
    @media (min-width: 768px){
    	.nav-card-right{
	    	position: absolute;
		    left: 0px;;
		    top: 0px;
		    width:30px;
		    bottom: 0;
		    z-index: 1;
	    }
    	.rotation {
	    	display: flex;
		    transform: rotate(90deg);
		    
		}
		.rotation .rotate {		    
		    display: inline-block;
		    transform: rotate(180deg);
		}
	    .filter-container{
	    	max-width: 100%;
	    	max-height: 100%;
	    }
    }
    .mix{
	    display: inline-block;
	    vertical-align: top;
	    background: #fff;
	    border-top: .0rem solid #002E5C;
	    box-shadow: 0 0.5px 0 0 #ffffff inset, 0 1px 2px 0 #B3B3B3;
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
<ol class="breadcrumb">
  <li><a href="<?php echo BASE_URL().'apps'; ?>">Home</a></li>
  <li class="active"><a href="#">Dashboard</a></li>
</ol>
<div class="row">
    <div class="col-md-7">
        <div class="box box-primary">
            <div id="map" style="height: 500px;"></div>
        </div>
    </div>
    <div class="col-md-5">
    	 <div class="nav-card-right" style="height: 500px;">
        	 <div class="filter-container">
		         <div class="rotation filter" id="filter">
		    		<button class="rotate btn btn-primary  btn-sm filter-btn" type="button" data-filter="all">Semua</button>
		    		<?php foreach ($unsur_profil as $value) { ?>
		                 <button class="rotate btn btn-sm filter-btn <?php echo strtolower(trim(explode(' ',$value->singkat_keterangan)[0])); ?>" style="background : <?php echo $value->warna; ?>" type="button" data-filter=".<?php echo strtolower(trim(explode(' ',$value->singkat_keterangan)[0])); ?>"><?php echo $value->singkat_keterangan ?></option>
		            <?php } ?>
		         </div>
	         </div>
        </div>
        <div id="profil" style="margin-left: 20px;">
            <a href="#" onclick="get_profil('<?php echo $unit->id_unit; ?>','<?php echo $unit->nama; ?>')">
                <img class="pull-left" src="<?php echo BASE_URL() ?>assets/smc/dist-landing/img/background/slider-text.png" style="width:35px; margin-right: 10px;">
                <input type="hidden" value="<?php echo date("Y"); ?>" id="tahun">
                <input type="hidden" value="<?php echo date("Y"); ?>" id="currentTahun">
                <input type="hidden" value="<?php echo $unit->id_unit; ?>" id="id_unit_home" data-nama="<?php echo $unit->nama; ?>">
            </a>
            <h4 id="profil-title" style="margin: 0px;">
                <?php echo $unit->nama; ?><br/>
                <small>Tahun <?php echo date("Y"); ?></small>
            </h4>
            <div style="height: 435px; overflow-y: auto;">
            <div id="profil-content"></div>
            </div>
        </div>
        <div id="profil-loading" style="display: none;">
            <div class="ajaxLoading">
                <h4>Mohon tunggu sebentar . . .</h4>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('footer') ?>