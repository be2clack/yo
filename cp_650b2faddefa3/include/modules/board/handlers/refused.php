<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_board']) && !(new Admin())->accessAdmin($_SESSION['cp_processing_board']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

$Ads = new Ads();
$Cache = new Cache();

$text = $_POST["comment"] ? $_POST["comment"] : $_POST["text"];
 
if($text){

   update("UPDATE uni_ads SET ads_status=?,ads_note=? WHERE ads_id=?", [7,clear($text),intval($_POST["id_ad"])], true);

   $Ads->changeStatus( intval($_POST["id_ad"]), 7, "", clear($text) );

   $getAd = $Ads->get("ads_id=?",[ intval($_POST["id_ad"]) ]);
   
   $data = array("{AD_TITLE}"=>$getAd["ads_title"],
                 "{AD_LINK}"=>$Ads->alias($getAd),
                 "{USER_NAME}"=>$getAd["clients_name"],                          
                 "{UNSUBSCRIBE}"=>"",                          
                 "{EMAIL_TO}"=>$getAd["clients_email"]
                 );

   email_notification( array( "variable" => $data, "code" => "AD_MODERATION_NOT_PUBLISHED" ) );

   $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";          
   echo true;

   $Cache->update( "uni_ads" );

}else{
   $_SESSION["CheckMessage"]["error"] = "Пожалуйста, укажите причину";
}


}

?>
