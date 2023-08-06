<!DOCTYPE html> <html lang="en"> <head> <meta charset="utf-8"> <title>Data Kediri</title> 
<meta name="viewport" content="width=device-width, initial-scale=1.0"> <meta name="description" content=""> 
<meta name="author" content=""> <meta http-equiv="cache-control" content="no-cache">  
 <link rel="stylesheet" href="<?php echo BASE_URL() ?>assets/smc/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo BASE_URL() ?>assets/smc/dist/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo BASE_URL() ?>assets/smc/dist/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo BASE_URL() ?>assets/smc/dist/css/smc.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL() ?>assets/smc/dist/css/style.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL() ?>assets/smc/plugins/datepicker/datepicker3.css">

<?php if(isset($styles)){ ?>
		<?php if(is_array($styles)){ ?>
			<?php foreach ($styles as $key => $value) { ?>
			<link href="<?php echo BASE_URL().$value ?>" rel="stylesheet" />
			<?php } ?>
		<?php } else { ?>
			<link href="<?php echo BASE_URL().$styles ?>" rel="stylesheet" />
		<?php } ?>
	<?php } ?>
<style>
input.money-bank{
    text-align: right;
}
</style>
<!-- Le base URL helper for javascript --> <script type="text/javascript"> var base_url = '<?php echo BASE_URL(); ?>'; 
var web_base_url = '<?php echo BASE_URL(); ?>'; var base_path = '<?php echo addslashes(getcwd()); ?>'; 
var web_url = '<?php echo $_SERVER['SERVER_NAME'] ?>'; //alert (web_base_url); </script>  
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" style="border-bottom: 1px solid #002e5b!important;">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <div class="logo hidden-xs" style="background-color:#002e5b; padding:10px; text-align:center;">
                    <img src="<?php echo BASE_URL() ?>assets/kediri/img/logo-datakediri-2.png" style="height:100%;">
                </div>
                <ul class="nav navbar-right top-nav">
                <li class="visible-xs hidden-lg hidden-sm hidden-md">
                    <b><h3>Admin</h3></b>
                </li>
              
                <li class="dropdown profile">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span><img src="<?php echo BASE_URL().($this->session->userdata('smc_avatar') ? "assets/kediri/upload/".$this->session->userdata('smc_avatar') : "assets/smc/dist/img/avatar.png");  ?>" height="27" width="27"></span> <span class="fa fa-angle-down"></span></a>
                    <div class="dropdown-menu block-profile">
                        <div class="img-cover" style="background-color:#002e5b; height:100px; text-align:center;">
                             <img src="<?php //echo $this->asset("smc/dist/img/cover.png"); ?>" alt=""> 
                        </div>
                        <div class="img-profile"><img src="<?php echo BASE_URL().($this->session->userdata('smc_avatar') ? "assets/kediri/upload/".$this->session->userdata('smc_avatar') : "assets/smc/dist/img/avatar.png"); ?>" alt="" class="img img-circle img-md"></div>
                        <div class="content" style="min-height:auto;">
                            <div class="nama"><center><h4><?php echo $this->session->userdata('smc_nama');?><small><br><?php echo ucwords($this->session->userdata('smc_role'));?></small></h4></center></div>
                            <hr>
                            <ul class="block-profile-control">
                                <li><a href="<?php echo BASE_URL().'apps/profil'; ?>"><center><span class="fa fa-cog"></span> Setting</center></a></li>
                                <li><a href="<?php echo BASE_URL().'auth/logoff'; ?>"><center><span class="fa fa-power-off"></span> Log Out</center></a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <!-- Top Menu Items -->
			<?php $this->load->view('navbar') ?>