<?php
/**
 * UniSite CMS
 *
 * @link https://unisite.org
 *
 */

define('unisitecms', true);

session_start();

$config = require "../config.php";

include_once("../systems/unisite.php");

if( $config["https"] ){
  if( strpos( $_SERVER['HTTP_CF_VISITOR'] , "https") === false ){
     
      if ( (!isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off')) {
          $redirect_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
          header("Location: $redirect_url");
          exit();
      }

  }
}

$Admin = new Admin();
?>

<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />

    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $settings["favicon"]; ?>">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">

    <link href="<?php echo $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/css/styles.css?123" rel="stylesheet" type="text/css" />
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400italic,400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    
    <script type="text/javascript" src="<?php echo $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="<?php echo $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/core.min.js"></script>
    <script type="text/javascript" src="<?php echo $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/nicescroll.min.js"></script>
    <script type="text/javascript" src="<?php echo $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/main.js"></script>

    <script type="text/javascript">
      $(document).ready(function () {

        var url_path = $("body").data("prefix");

        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        
        $(".form-auth").on("submit", function (e) {
            $(".alert-danger").hide();          
                $.ajax({
                    type: "POST",url: url_path + "systems/ajax/controller.php",data: $( this ).serialize()+"&action=admin/auth",dataType: "json",cache: false,
                    success: function (data) {

                        if(data["status"] == true){ location.href = data["location"]; }else{ 

                            if(data["answer"]){
                               $(".form-auth .alert-danger").html(data["answer"]).show();  
                            }else{
                               location.reload();
                            }

                        }                                        
                    }
                }); 
            e.preventDefault();                            
       });
        
       $(".form-remind").on("submit", function (e) { 
            $(".alert-danger").hide();      
            $(".alert-success").hide();
                $.ajax({
                    type: "POST",url: url_path + "systems/ajax/controller.php",data: $( this ).serialize()+"&action=admin/remind",dataType: "html",cache: false,
                    success: function (data) {

                        if(data == true){ $(".alert-success").html("Новый пароль успешно сгенерирован и выслан на Ваш E-mail.").show(); $(".form-remind input").val(""); }else{ 
                              
                              if(data){
                                 $(".form-remind .alert-danger").html(data).show();
                              }else{
                                 location.reload();
                              }

                         }
                                         
                    }
                });
           e.preventDefault();              
      });
     

      $(".forget-password").click(function () {
          $(".tab-auth").fadeOut("100",function(){
             $(".tab-remind").fadeIn("100");
          });
      });

      $(".click-auth").click(function () {
          $(".tab-remind").fadeOut("100",function(){
             $(".tab-auth").fadeIn("100");
          });
      });



    });
    </script>	
	
	<style type="text/css">
    body {
        font-family: 'Open Sans', Arial, Helvetica, 'Helvetica Neue', sans-serif; 
        background-color: #f5f5f5;
        overflow: hidden;   
    }    
	</style>
    
 <title><?php echo $_SERVER["SERVER_NAME"]; ?> - Панель управления</title>
    
</head>

<body class="bg-white"  data-prefix="<?php echo $config["urlPrefix"]; ?>" >
   <div id="preloader">
      <div class="canvas">
         <div class="spinner"></div>
      </div>
   </div>
   <div class="container-fluid no-padding h-100">
      <div class="row flex-row h-100 bg-white">
         <div class="col-xl-8 col-lg-6 col-md-5 d-none d-md-none d-lg-block"  >

            <div class="elisyam-bg background-01">

               <div class="elisyam-overlay overlay-01" ></div>
               <div class="authentication-col-content mx-auto">
                  <h1 class="gradient-text-01">
                     Добро пожаловать, <?php echo $settings["site_name"]; ?>!
                  </h1>
                  <span class="description">
                     <?php echo $Admin->randQuotes(); ?> <span class="emoji_3" ></span> <span class="emoji_2" ></span> <span class="emoji_1" ></span>
                  </span>
               </div>
            </div>

         </div>
         <div class="col-xl-4 col-lg-6 col-md-12 my-auto no-padding">
            
            <div class="authentication-form tab-auth mx-auto" >
               <form class="form-auth" >
               <h3 style="text-align: center;" ><strong>Вход в панель управления</strong></h3>
               
                <div class="alert alert-danger mb25" style="display: none;" role="alert"></div>

                  <div class="group material-input">
                     <input type="email" name="email" value="<?php echo $demo_login; ?>" required>
                     <span class="highlight"></span>
                     <span class="bar"></span>
                     <label>Email</label>
                  </div>
                  <div class="group material-input">
                     <input type="password" name="pass" value="<?php echo $demo_pass; ?>" autocomplete="new-password" required>
                     <span class="highlight"></span>
                     <span class="bar"></span>
                     <label>Пароль</label>
                  </div>
               
               <div class="row">
                  <div class="col text-right">
                     <a href="#" class="forget-password" >Забыли пароль?</a>
                  </div>
               </div>
               <div class="sign-btn text-center">
                  <button  class="btn btn-lg btn-gradient-01">
                    Войти
                  </button>
               </div>
               </form>
            </div>

            <div class="authentication-form tab-remind mx-auto">
            <form class="form-remind" >
               <h3 style="text-align: center;" ><strong>Восстановление пароля</strong></h3>
               
                <div class="alert alert-danger mb25" style="display: none;" role="alert"></div>
                <div class="alert alert-success mb25" style="display: none;" role="alert"></div>

                  <div class="group material-input">
                     <input type="text" name="email" required>
                     <span class="highlight"></span>
                     <span class="bar"></span>
                     <label>Email</label>
                  </div>
               
               <div class="row">
                  <div class="col text-right">
                     <a href="#" class="click-auth" >Вход</a>
                  </div>
               </div>
               <div class="sign-btn text-center">
                  <button  class="btn btn-lg btn-gradient-01 click-auth-remind">
                    Восстановить пароль
                  </button>
               </div>
            </form>
            </div>



         </div>
      </div>
   </div>




</body>
</html>