<!DOCTYPE html>
<html lang="en">
	<head itemscope itemtype="http://schema.org/WebPage">
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<title itemprop="name">Data Kediri</title>
	<meta property="og:title" content="Data Kediri">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Official Site - Data Kediri">
	<meta name="keywords" content="">
    <meta name="author" content="tdp,rzk,dws">
	<meta name="robots" content="index, follow" />
    <meta name="HandheldFriendly" content="true">
    <meta http-equiv="cleartype" content="on">
	<meta name="rating" content="general">
    <meta name="spiders" content="all">
    <meta name="revisit-after" content="7">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <meta property="og:site_name" content="Data Kediri">
    <meta property="og:description" content="Official Site - Data Kediri">
    <meta http-equiv="cache-control" content="no-cache">
	<meta name="rating" content="Mature" />

	 <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Poppins:400,300,500,600,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Ubuntu:400,300,500,700' rel='stylesheet' type='text/css'>

	 <!-- Owl Carousel -->
    <link rel="stylesheet" href="<?php echo BASE_URL() ?>assets/kediri/plugins/owlcarousel/owl.carousel.min.css">
    <!-- Bootstrap -->
    <link href="<?php echo BASE_URL() ?>assets/kediri/css/consulting_layout_kediri.css" rel="stylesheet">
	<link href="<?php echo BASE_URL() ?>assets/kediri/plugins/layerslider/css/layerslider.css" rel="stylesheet">
 	 <?php if(isset($styles)){ ?>
		<?php if(is_array($styles)){ ?>
			<?php foreach ($styles as $key => $value) { ?>
			<link href="<?php echo BASE_URL().$value ?>" rel="stylesheet" />
			<?php } ?>
		<?php } else { ?>
			<link href="<?php echo BASE_URL().$styles ?>" rel="stylesheet" />
		<?php } ?>
	<?php } ?>
    <script>
    	base_url = "<?php echo BASE_URL(); ?>";
    	file_url = "<?php echo $this->config->item("file_url"); ?>";
    </script>
	<style>
		audio, canvas, progress, video { display: inline-block;  vertical-align: baseline;}
	</style>
  </head>
 <body>

 	<header id="header">
		<section id="header-top">
  			<div class="container">
  				<div class="row">
  					<div class="col-sm-12">
  						<ul class="list-unstyled list-inline margin-top-no margin-bottom-no list-left">
  							<li class="dropdown active">
  								<a href="#"  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
									<img src="<?php echo BASE_URL() ?>assets/kediri/img/logo-pemkab-kediri.png" class="img" style="height:38px">
  								</a>
  							</li>
  						</ul>

  						<ul class="list-unstyled list-inline margin-top-no margin-bottom-no text-center-md list-right">
  							<li>
  								<span>
  									<span class="fa fa-map-marker icon-header"></span>
  									<span class="text-header">Jl. Soekarno Hatta No.01, Kediri</span>
  								</span>
  							</li>
  							<li>
  								<span>
  									<span class="fa fa-clock-o icon-header"></span>
  									<span class="text-header">Senin - Jumat 8.00 - 15.00.</span>
  								</span>
  							</li>
  							<li>
  								<span>
  									<span class="fa fa-phone icon-header"></span>
  									<span class="text-header">(0354) 689995</span>
  								</span>
  							</li>
							<li>
  								<span>
  									<span class="fa fa-envelope icon-header"></span>
  									<span class="text-header">bappeda [at] kedirikab.go.id</span>
  								</span>
  							</li>
  						</ul>
  					</div>
  				</div>
  			</div>
  		</section>
  		<nav class="navbar navbar-default navbar-static-top <?php	if(!isset($view_slide)) echo 'header-content'; ?>" id="header-navigation">
		  <div class="container">
			<div class="navbar-header">
			  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header-navigation-menu" aria-expanded="false">
			    <span class="sr-only">Toggle navigation</span>
			    <span class="icon-bar"></span>
			    <span class="icon-bar"></span>
			    <span class="icon-bar"></span>
			  </button>


			   <a class="navbar-brand text-bold" href="<?php echo BASE_URL() ?>">
			  	<img src="<?php echo BASE_URL() ?>assets/kediri/img/logo-datakediri-y.png" class="img img-responsive">
			  </a>

			</div>

			<div class="collapse navbar-collapse text-bold" id="header-navigation-menu">
			  <ul class="nav navbar-nav navbar-right">
			  	<li><a href="<?php echo BASE_URL() ?>page/kontak"><span class="fa fa-phone"></span></a></li>
			  </ul>
			  <ul class="nav navbar-nav navbar-right">
				<li  <?php if(isset($smenu) &&$smenu=='home') echo 'class="active"'; ?>><a href="<?php echo BASE_URL() ?>">Home</a></li>
				<li  <?php if(isset($smenu) &&$smenu=='profil') echo 'class="active"'; ?>><a href="<?php echo BASE_URL() ?>page/profil">Profil</a></li>				
		    	<!-- <li <?php if(isset($smenu) &&$smenu=='statistik') echo 'class="active"'; ?>><a href="<?php echo BASE_URL() ?>page/statistik">Data Statistik</a></li> -->
				<li <?php if(isset($smenu) &&$smenu=='ntp') echo 'class="active"'; ?>><a href="<?php echo BASE_URL() ?>page/ntp">NTP</a></li>
				<li <?php if(isset($smenu) &&$smenu=='survey') echo 'class="active"'; ?>><a href="<?php echo BASE_URL() ?>page/survei">Data Spasial</a></li>
				<!-- <li <?php if(isset($smenu) &&$smenu=='kemiskinan') echo 'class="active"'; ?>><a href="<?php echo BASE_URL() ?>page/kemiskinan">Data Kemiskinan</a></li> -->
				<li  <?php if(isset($smenu) &&$smenu=='galeri') echo 'class="active"'; ?>><a href="<?php echo BASE_URL() ?>page/galeri">Galeri</a></li>
				<li><a href="<?php echo BASE_URL() ?>auth">Login</a></li>
			  </ul>
			</div>
		  </div>
		</nav>

		<?php	if(isset($view_slide)): ?>
  		<section class="slider-main slider-large">
			<div id="slideshow" class="cycle-slideshow" data-cycle-tile-count="20" data-cycle-pause-on-hover="false" data-cycle-slides="> .slide" data-cycle-timeout="8000" data-cycle-speed="500" >
				<div class="cycle-prev cycle-arrow"><i class="fa fa-angle-left"></i></div>
				<div class="cycle-next cycle-arrow"><i class="fa fa-angle-right"></i></div>
				<div class="slide" data-cycle-fx="tileBlind" style="background-image: url('<?php echo BASE_URL() ?>assets/smc/dist-landing/img/slider/background3.jpg')">
					<div class="container">
						<div class="caption">
						<div class="slider-caption-title text-uppercase font-ubuntu">Selamat Datang</div>
						<div class="padding-top-md" style="padding:10px; background: rgba(255,255,255,0.8);margin-bottom: 10px;">Data Kediri merupakan sebuah komitmen bersama dari setiap elemen pemerintah di Kab. Kediri untuk dapat menyediakan data yang terpusat, update dan akurat.</div>
						<div class="slider-caption-action padding-top-sm">
							<!-- <a href="<?php echo BASE_URL() ?>page/statistik" class="btn btn-blue btn-icon-inverse">Data Statistik<span class="fa fa-chevron-right"></span></a> -->
							<a href="<?php echo BASE_URL() ?>page/kontak" class="btn btn-yellow">Hubungi Kami <span class="fa fa-chevron-right"></span></a>
						</div>
					</div>
					</div>
				</div>
				<div class="slide" data-cycle-fx="tileBlind" style="background-image: url('<?php echo BASE_URL() ?>assets/smc/dist-landing/img/slider/background4.jpg')">
					<div class="container">
						<div class="caption">
							<div class="slider-caption-title font-ubuntu">
								Bupati Kediri
							</div>
							<div class="padding-top-md" style="padding:10px; background: rgba(255,255,255,0.6);margin-bottom: 10px;">Kami berkomitmen untuk membangun Kabupaten Kediri menjadi lebih baik</div>
						</div>
					</div>
				</div>
				<div class="slide" data-cycle-fx="tileBlind" style="background-image: url('<?php echo BASE_URL() ?>assets/smc/dist-landing/img/slider/background5.jpg')">
					<div class="container">
						<div class="caption">
							<div class="slider-caption-title font-ubuntu">
								Kediri dalam angka
							</div>
							<div class="padding-top-md" style="padding:10px; background: rgba(255,255,255,0.6);margin-bottom: 10px;">Tanpa data yang baik, pembangunan dapat salah sasaran. Perencanaan pembangunan yang objektif adalah perencanaan yang berbasiskan pada sebuah data</div>
						</div>
					</div>
				</div>
				<div class="slide" data-cycle-fx="tileBlind" style="background-image: url('<?php echo BASE_URL() ?>assets/smc/dist-landing/img/slider/background6.jpg')">
					<div class="container">
						<div class="caption">
							<div class="slider-caption-title font-ubuntu">
								Bupati Kediri
							</div>
							<div class="padding-top-md" style="padding:10px; background: rgba(255,255,255,0.6);margin-bottom: 10px;">Pembangunan Kabupaten Kediri berbasiskan pada data agar lebih tepat sasaran dan bermanfaat bagi masyarakat</div>
						</div>
					</div>
				</div>
				<div class="slide" data-cycle-fx="tileBlind" style="background-image: url('<?php echo BASE_URL() ?>assets/smc/dist-landing/img/slider/background1.jpg')">
					<div class="container">
						<div class="caption">
							<div class="slider-caption-title font-ubuntu">
								Data Kediri
							</div>
							<div class="padding-top-md" style="padding:10px; background: rgba(255,255,255,0.6); margin-bottom: 10px;">Data Kediri menyajikan informasi secara spasial dari setiap elemen pemerintah di Kab. Kediri</div>
							<!-- <div class="slider-caption-action padding-top-sm">
								<a href="<?php echo BASE_URL() ?>page/umum" class="btn btn-blue btn-icon-inverse">Data Umum <span class="fa fa-chevron-right"></span></a>
							</div> -->
						</div>
					</div>
				</div>
				<div class="slide" data-cycle-fx="tileBlind" style="background-image: url('<?php echo BASE_URL() ?>assets/smc/dist-landing/img/slider/background2.jpg')">
					<div class="container">
						<div class="caption">
							<div class="slider-caption-title font-ubuntu">
								Bupati Kediri
							</div>
							<div class="padding-top-md" style="padding:10px; background: rgba(255,255,255,0.6);margin-bottom: 10px;">Masyarakat dapat memperoleh informasi tentang pembangunan Kabupaten Kediri melalui Data Kediri Lagi</div>
						</div>
					</div>
				</div>
			</div>


    	</section>
		<?php endif; ?>
  	</header>
  	<section id="wrapper">
