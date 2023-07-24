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
    	height:100%;
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

<section id="wrapper">
<section class="single-page">
	<section class="content content-white">
		<div class="container">
			<div class="row">
				<div class="col-md-3">
					<div class="block-flat">
						<div class="content" style="padding-top:30px">							
							<div class="form-group">
								<label>Tingkat Unit</label>
								<select class="form-control" name="unit" id="unit">
									<option value="" disabled selected>Pilih Tingkat Unit</option>
									<option value="kabupaten">Kabupaten</option>
									<option value="kecamatan">Kecamatan</option>
								</select>
							</div>
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
							<div class="form-group">
									<label>Elemen</label>
									<select class="form-control" name="unsur" id="unsur"></select> 
							</div>
							<div>
								<button type="button" class="btn btn-sm btn-primary" onclick="show_lkpj_report()"><i class="fa fa-search"></i>
									Tampilkan</button>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<div class="tab-portlet tp-success">
						<div class="title">
							<h4>Laporan Keterangan Pertanggungjawaban (LKPJ)</h4>
						</div>
						<div class="content" style="padding: 0px;">
							<canvas id="bar-chart" width="400" height="250"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</section>
</section>

<?php  $this->load->view('landing/footer'); ?>