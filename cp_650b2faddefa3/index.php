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

if ( isset($_GET["logout"]) ){
  unset($_SESSION); session_destroy(); 
}

$_SESSION["entry_point"] = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

if ( !$_SESSION['cp_auth'][ $config["private_hash"] ] ){ header("Location: " . $config["urlPath"] . "/" . $config["folder_admin"] . "/login.php"); exit; }

$Admin->setMode();

$route = clear($_GET["route"]);

$id = (int)$_GET["id"];

$_SESSION["ByShow"] = 100;

$getWarning = $Admin->warningSystems();

if($_GET["route"] == "users" || $_GET["route"] == "user" || $_GET["route"] == "add_user"){
  if(!$_SESSION["cp_control_admin"]){
    header("Location: ?route=index");
  }
}elseif($_GET["route"] == "settings"){
  if(!$_SESSION["cp_control_settings"]){
    header("Location: ?route=index");
  }
}elseif($_GET["route"] == "tpl"){
  if(!$_SESSION["cp_control_tpl"]){
    header("Location: ?route=index");
  }
}elseif($_GET["route"] == "manager"){
  if(!$_SESSION["cp_control_manager"]){
    header("Location: ?route=index");
  }
}elseif($_GET["route"] == "ads_import" || $_GET["route"] == "category_board" || $_GET["route"] == "add_category_board" || $_GET["route"] == "edit_category_board" || $_GET["route"] == "filters" || $_GET["route"] == "services_ad" || $_GET["route"] == "complaints"){
  if(!$_SESSION["cp_control_board"]){
    header("Location: ?route=index");
  }
}elseif($_GET["route"] == "board"){
  if(!$_SESSION["cp_control_board"] && !$_SESSION["cp_processing_board"]){
    header("Location: ?route=index");
  }
}elseif($_GET["route"] == "seo"){
  if(!$_SESSION["cp_control_seo"]){
    header("Location: ?route=index");
  }
}elseif($_GET["route"] == "banners"){
  if(!$_SESSION["cp_control_banner"]){
    header("Location: ?route=index");
  }
}elseif($_GET["route"] == "pages" || $_GET["route"] == "add_page" || $_GET["route"] == "page"){
  if(!$_SESSION["cp_control_page"]){
    header("Location: ?route=index");
  }
}elseif($_GET["route"] == "clients"){
  if(!$_SESSION["cp_control_clients"]){
    header("Location: ?route=index");
  }
}elseif($_GET["route"] == "blog" || $_GET["route"] == "add_category_blog" || $_GET["route"] == "edit_category_blog" || $_GET["route"] == "add_article" || $_GET["route"] == "edit_article"){
  if(!$_SESSION["cp_control_blog"]){
    header("Location: ?route=index");
  }
}elseif($_GET["route"] == "orders"){
  if(!$_SESSION["cp_control_orders"]){
    header("Location: ?route=index");
  }
}elseif($_GET["route"] == "cities"){
  if(!$_SESSION["cp_control_city"]){
    header("Location: ?route=index");
  }
}elseif($_GET["route"] == "secure" || $_GET["route"] == "secure_view" || $_GET["route"] == "booking" || $_GET["route"] == "booking_view"){
  if(!$_SESSION["cp_control_transactions"]){
    header("Location: ?route=index");
  }
}elseif($_GET["route"] == "index" || !$_GET["route"]){
  if(!$_SESSION["cp_control_statistics"]){
    header("Location: ?route=card_user");
  }
}elseif($_GET["route"] == "chat"){
  if(!$_SESSION["cp_control_chat"]){
    header("Location: ?route=index");
  }
}elseif($_GET["route"] == "multilanguage"){
  if(!$_SESSION["cp_control_multilang"]){
    header("Location: ?route=index");
  }
}elseif($_GET["route"] == "shops"){
  if(!$_SESSION["cp_control_shops"]){
    header("Location: ?route=index");
  }
}elseif($_GET["route"] == "ad_packages"){
  if(!$_SESSION["cp_control_board"]){
    header("Location: ?route=index");
  }
}


?>
<!DOCTYPE HTML>
<html>
<head>

    <meta http-equiv="content-type" content="text/html; charset=UTF8" />

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">

    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $settings["favicon"]; ?>">

    <link href='https://fonts.googleapis.com/css?family=PT+Sans+Narrow' rel='stylesheet' type='text/css'/>
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400italic,400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>

    <link href="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" /> 
    <link href="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/timepicker/jquery-ui-1.10.4.custom.css" rel="stylesheet" type="text/css" /> 
    <link href="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/codemirror-5.3/lib/codemirror.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/FancyBox/jquery.fancybox.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/morris/morris.css" rel="stylesheet" type="text/css" /> 
    <link href="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" /> 
    <link href="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/owl-carousel/owl.carousel.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/css/bootstrap-editable.css" rel="stylesheet" type="text/css" /> 
    <link href="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/css/styles.css?122" rel="stylesheet" type="text/css" /> 
    <link href="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/css/animate.min.css" rel="stylesheet" type="text/css" />   
    <link href="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/apexcharts/apexcharts.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/codemirror-5.3/theme/lucario.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/minicolors/jquery.minicolors.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/slick/slick.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/slick/slick-theme.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/jquery-1.11.1.min.js"></script>        
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/core.min.js"></script>
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/bootstrap-select.min.js"></script>  
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/raphael.js"></script>
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/morris/morris.min.js"></script>
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/jquery-ui.min.js"></script>          
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/FancyBox/jquery.fancybox.js"></script>
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/ckeditor/ckeditor.js"></script> 
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/codemirror-5.3/lib/codemirror.js"></script>
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/codemirror-5.3/mode/javascript/javascript.js"></script>
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/codemirror-5.3/addon/selection/active-line.js"></script>
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/codemirror-5.3/addon/edit/matchbrackets.js"></script>
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/moment-with-locales.min.js"></script>
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/chartjs/Chart.min.js"></script>
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/knob/jquery.knob.js"></script>
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/radialIndicator.min.js"></script>
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/owl-carousel/owl.carousel.min.js"></script>
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/bootstrap-editable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.24.4/dist/sweetalert2.all.min.js"></script>
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/noty.min.js"></script>
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/notifications.min.js"></script>
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/nicescroll.min.js"></script>
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/main.js"></script>  
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/apexcharts/apexcharts.min.js"></script>  
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/minicolors/jquery.minicolors.min.js"></script>  
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/slick/slick.min.js"></script>
    <script type="text/javascript" src="<?php echo  $config["urlPath"] . "/" . $config["folder_admin"]; ?>/files/js/tippy.all.min.js"></script>
       
    <title><?php echo $_SERVER["SERVER_NAME"]?> - Панель управления</title>
</head>
<body id="page-top" data-currency="<?php echo $settings["currency_main"]["sign"]; ?>" data-prefix="<?php echo $config["urlPrefix"]; ?>" data-media-other="<?php echo $config["urlPath"] . "/" . $config["media"]["other"]; ?>" >

      <div id="preloader">
         <div class="canvas">
            <div class="spinner"></div>
         </div>
      </div>

        <div class="proccess_load" >
             <div class="canvas">
                <div class="spinner"></div>
             </div>        
        </div>
  
        <div class="page">

        <?php
          include( $config["basePath"] . "/" . $config["folder_admin"] . "/include/html/header.php" );
        ?>

        <div class="page-content d-flex align-items-stretch">
             <?php

             include( $config["basePath"] . "/" . $config["folder_admin"] . "/include/html/sidebar.php" );

             $array_include_pages = $Admin->checkPages($route); 

             ?>
             <div class="content-inner">
               <div class="container-fluid"  >
             <?php
                  if(file_exists( $config["basePath"] . "/" . $config["folder_admin"] . "/include/modules{$array_include_pages}.php" )){                   
                       include( $config["basePath"] . "/" . $config["folder_admin"] . "/include/modules{$array_include_pages}.php" );
                     }else{
                       include( $config["basePath"] . "/" . $config["folder_admin"] . "/include/modules/index/index.php" );
                     }                     
             ?>
                </div>
                <?php
                include( $config["basePath"] . "/" . $config["folder_admin"] . "/include/html/footer.php" );
                ?>  

             </div>

        </div>
        </div>
  
</body>
</html>