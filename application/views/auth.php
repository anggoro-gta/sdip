<!DOCTYPE html>
<html lang="en">
<head>
  <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Auth - Data Kediri</title>
  <!--Main Style-->
  <link rel="stylesheet" href="<?php echo BASE_URL() ?>assets/smc/dist-landing/css/smartcity-style-avocado.min.css">
  <!--End Main Style-->
  <!--Custom Style-->
  <link rel="stylesheet" href="<?php echo BASE_URL() ?>assets/smc/dist-landing/css/smartcity-style-avocado-auth.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL() ?>assets/smc/dist-landing/vendor/animatecss/animate.min.css">
  <!--End Custom Style-->
  <!--Font-->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
  <!--End Font-->
</head>
<body class="auth__body" style="background-image:url('<?php echo BASE_URL() ?>assets/smc/dist-landing/img/background/kedirilagi.png'); background-repeat: no-repeat;background-position: bottom left;">
<main class="wrapper auth__main">
  <div class="container-fluid auth__container">
    <div class="row no-gutter auth__row">
      <div class="col-sm-5 col-sm-push-7 hidden-xs" style="padding: 0px;">
        <div class="grid grid--blue animation--fade animation--once animation--time-xl fadeInRight" style="background-image:url('<?php echo BASE_URL() ?>assets/smc/dist-landing/img/background/bg-login.png'); background-repeat: no-repeat;background-position: bottom;">
        
          <img
                      class="auth__image auth__image--background animation--fade animation--once animation--time-xxl fadeInUpBig"
                      src="<?php echo BASE_URL() ?>assets/smc/dist-landing/img/background/gumu-awan-3.png">
                    <img
                      class="auth__image auth__image--background animation--fade animation--once animation--time-xl fadeInUpBig"
                      src="<?php echo BASE_URL() ?>assets/smc/dist-landing/img/background/gumu-awan-2.png">
                    <img
                      class="auth__image auth__image--background animation--fade animation--once animation--time-lg fadeInUpBig"
                      src="<?php echo BASE_URL() ?>assets/smc/dist-landing/img/background/gumu-awan-1.png">
					 
          
          <img
            class="auth__image auth__image--background animation--fade animation--once animation--time-xxl fadeInRight"
            src="<?php echo BASE_URL() ?>assets/smc/dist-landing/img/background/gumul.png" >
			
       
          <div class="row middle-xs auth__row">
            <div class="col-sm-8 col-lg-6">
              <div class="auth__description">
                <!--<img class="auth__image auth__image--logo animation--fade animation--once animation--time-lg fadeInDown"
                     src="<?php echo BASE_URL() ?>assets/smc/dist-landing/img/background/slider-text.png">-->
					 
                <p class="auth__paragraph" style="line-height:25px;color:#fff;">
				<span style="font-size:30px;">Kediri dalam angka</span><br><br>
				Perencanaan Pembangunan adalah upaya mewujudkan sebuah mimpi. Perencanaan yang baik adalah ketika mimpi tersebut bisa diwujudkan.<br><br>
                <!--aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum-->
                <!--dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui-->
                <!--officia deserunt mollit anim id est laborum-->
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-sm-pull-4 col-lg-3 col-lg-pull-1" >
        <div class="grid">
          <div class="row middle-xs auth__row">
            <div class="col-xs-12">

              <div
                class="text-center-sm margin-bottom-xxl margin-on-sm-top-xxl padding-on-sm-right-no padding-right-xl animation--fade animation--once animation--time-md fadeInDown">
                <a href="<?php echo BASE_URL().'/page'; ?>"><img class="auth__image auth__image--form" src="<?php echo BASE_URL() ?>assets/smc/dist-landing/img/logo/logo-datakediri.png"></a>
              </div>
			  
			 

              <form  class="form form--login padding-on-sm-right-no padding-right-xxl animation--fade animation--once animation--time-md fadeInDown" action="<?php echo BASE_URL().'auth/logon'; ?>" method="post">
				 <?php
				if(isset($msglogin)) echo $msglogin;
				?>
			   
                <div class="form-group">
                  <label class="sr-only" for="authInputEmail">Email address</label>
                  <input type="email" class="form-control form-control--no-radius" id="authInputEmail" placeholder="Email" name="username">
                </div>
                <!--Use this to handle when error-->
                <!--<div class="form-group has-error">-->
                <div class="form-group">
                  <label class="sr-only" for="authInputPassword">Password</label>
                  <input type="password" class="form-control form-control--no-radius" id="authInputPassword"  placeholder="Password" name="password">
                </div>
                <div class="row">
                  <div class="col-sm-8">
                  
                    <a href="#" class="form__link--forgot-password" style="color:#002e5b">Lupa Password</a>
                  </div>
                  <div class="col-sm-4 text-right">
                    <button type="submit" class="btn btn-blue btn--no-radius btn-block btn-block--on-sm" name="b_submit">Login
                    </button>
                  </div>
                </div>
              </form>

              <form
                class="form form--reset padding-on-sm-right-no padding-right-xxl animation--fade animation--once animation--time-md fadeInDown"
                style="display: none">
                <div class="form-group has-error">
                  <label class="sr-only" for="authInputEmailForgot">Email address</label>
                  <input type="email" class="form-control form-control--no-radius" id="authInputEmailForgot"
                         placeholder="Email">
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <a href="#" class="text-danger form__link--login">Login</a>
                  </div>
                  <div class="col-sm-6 text-right">
                    <button type="submit" class="btn btn-danger btn--no-radius btn-block btn-block--on-sm">
                      Reset Password
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<!--Script-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo BASE_URL() ?>assets/smc/dist-landing/js/smartcity-style-avocado-modernizr.min.js"></script>
<script src="<?php echo BASE_URL() ?>assets/smc/dist-landing/js/smartcity-style-avocado-global.min.js"></script>
<script src="<?php echo BASE_URL() ?>assets/smc/dist-landing/js/smartcity-style-avocado-auth.min.js"></script>
<script>
document.onkeypress = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
           //alert('No F-12');
            return false;
        }
    }
    document.onmousedown = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            //alert('No F-keys');
            return false;
        }
    }
    document.onkeydown = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            //alert('No F-keys');
            return false;
        }
    }
	var message="";
	function clickIE() {if (document.all) {(message);return false;}} function clickNS(e) {if (document.layers||(document.getElementById&&!document.all)) { if (e.which==2||e.which==3) {(message);return false;}}} if (document.layers) {document.captureEvents(Event.MOUSEDOWN);document.onmousedown=clickNS;} else{document.onmouseup=clickNS;document.oncontextmenu=clickIE;} document.oncontextmenu=new Function("return false") 
</script>
</body>
</html>
