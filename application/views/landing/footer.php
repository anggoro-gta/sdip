  	</section>
  	<footer class="padding-top-xxl" id="footer">
  
  		<section class="footer-top margin-top-no">
  			<div class="container margin-top-no padding-top-no padding-bottom-no">
  				<div class="row">
  					<div class="col-md-8">
  						<div class="copyright text-center text-left-md">
  							<span class="muted">&copy; Updated 2021 FILKOM UB. All rights reserved. Please read our Terms of Use and Privacy Policy.</span> 
  						</div>
  					</div>
  					<div class="col-md-4">
  						<div class="text-center text-right-md">
  							<ul class="list-unstyled list-inline list-socmed">
  								<li>
  									<a href="#">
  										<img src="<?php echo BASE_URL() ?>assets/kediri/img/logo-datakediri-y.png" class="img img-responsive">
  									</a>
  								</li>
  								
  							</ul>
  						</div>
  					</div>
  				</div>
  			</div>
  		</section>
  	</footer>
  	
  	<script src="<?php echo BASE_URL() ?>assets/kediri/plugins/jquery/jquery-1.12.1.min.js"></script>
  	<script src="<?php echo BASE_URL() ?>assets/kediri/js/consulting_layout_bptik.min.js"></script>
	<script src="<?php echo BASE_URL() ?>assets/kediri/js/highcharts.js"></script>  	
  	<script src="<?php echo BASE_URL() ?>assets/kediri/js/themes.min.js"></script>
	<script src="<?php echo BASE_URL() ?>assets/smc/plugins/select2/select2.full.min.js"></script>
	
	<?php if(isset($scripts)){ ?>
		<?php if(is_array($scripts)){ ?>
			<?php foreach ($scripts as $key => $value) { ?>
			<script src="<?php echo BASE_URL().$value ?>"></script>
			<?php } ?>
		<?php } else { ?>
			<script src="<?php echo BASE_URL().$scripts ?>"></script>
		<?php } ?>
	<?php } ?>
	<?php if(isset($scripts_post)){ ?>
		<?php if(is_array($scripts_post)){ ?>
			<?php foreach ($scripts_post as $key => $value) { ?>
			<script src="<?php echo $value ?>"></script>
			<?php } ?>
		<?php } else { ?>
			<script src="<?php echo $scripts_post ?>"></script>
		<?php } ?>
	<?php }?>
		  <script src="<?php echo BASE_URL() ?>assets/kediri/plugins/cycle/jquery.cycle2.min.js"></script>
		 <script src="<?php echo BASE_URL() ?>assets/kediri/plugins/cycle/jquery.cycle2.tile.js"></script>
		
        <script>
        	if ($('#layerslider-front-inside-ptiik').length) {
			
				$('#layerslider-front-inside-ptiik').layerSlider({
				
					responsive: true,
					responsiveUnder: 1280,
					layersContainer: 1280,
					skin: 'v5',
					hoverPrevNext: false,
					autoPlayVideos: false,
					navStartStop: false,
					navButtons: false,
					skinsPath: base_apps + 'assets/kediri/plugins/layerslider/skins/'
				});
			}
        </script>
	<script>
			// var categories = ['0-4', '5-9', '10-14', '15-19',
			// 			'20-24', '25-29', '30-34', '35-39', '40-44',
			// 			'45-49', '50-54', '55-59', '60-64', '65-69',
			// 			'70-74', '75+'];
			// 	$(document).ready(function () {
			// 		Highcharts.chart('container', {
			// 			chart: {
			// 				type: 'bar'
			// 			},
			// 			title: {
			// 				text: 'Jumlah Penduduk Kabupaten Kediri'
			// 			},
			// 			subtitle: {
			// 				text: 'Berdasarkan Sensus Penduduk 2010'
			// 			},
			// 			xAxis: [{
			// 				categories: categories,
			// 				reversed: false,
			// 				labels: {
			// 					step: 1
			// 				}
			// 			}, { // mirror axis on right side
			// 				opposite: true,
			// 				reversed: false,
			// 				categories: categories,
			// 				linkedTo: 0,
			// 				labels: {
			// 					step: 1
			// 				}
			// 			}],
			// 			yAxis: {
			// 				title: {
			// 					text: null
			// 				},
			// 				labels: {
			// 					formatter: function () {
			// 						return Math.abs(this.value) ;
			// 					}
			// 				}
			// 			},
            //
			// 			plotOptions: {
			// 				series: {
			// 					stacking: 'normal'
			// 				}
			// 			},
            //
			// 			tooltip: {
			// 				formatter: function () {
			// 					return '<b>' + this.series.name + ', umur ' + this.point.category + '</b><br/>' +
			// 						'Jumlah:' + Highcharts.numberFormat(Math.abs(this.point.y), 0) + ' jiwa';
			// 				}
			// 			},
            //
			// 			series: [{
			// 				name: 'Laki-laki',
			// 				data: [-64603,-65757,-65406,-59065,-52047,-62675,-58611,-59127,-58423,-52101,-44368,-34979,-24353,-20598,-14245,-16974]
			// 			}, {
			// 				name: 'Perempuan',
			// 				data: [61468,62429,61087,54035,50356,60610,56666,58941,59604,54093,43539,32769,25640,22570,18441,24599]
			// 			}]
			// 		});
			// 	});
	</script>
  </body>
  </html>
