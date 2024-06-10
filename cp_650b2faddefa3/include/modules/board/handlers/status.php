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
 $Elastic = new Elastic();
 $Cache = new Cache();
 $Profile = new Profile();
 
 if( is_array($_POST["id"]) ){

     $ids = iteratingArray($_POST["id"], "int");

     $getAds = $Ads->getAll(["query"=>"ads_id IN(".implode(",", $ids).")"]);

     if($getAds['count']){
         foreach ($getAds['all'] as $value) {

              $time = date("Y-m-d H:i:s");

              $period = $Ads->adPeriodPub( $value["ads_period_day"] );

              if(strtotime($value["ads_period_publication"]) <= time()){
                 $ads_period_day = $period["days"];
                 $ads_period_publication = $period["date"];
              }else{
                 $ads_period_day = $value["ads_period_day"];
                 $ads_period_publication = $value["ads_period_publication"];                
              }

              update("UPDATE uni_ads SET ads_status='".intval($_POST["status"])."',ads_period_publication='".$ads_period_publication."',ads_datetime_add='".$time."',ads_period_day='".$ads_period_day."' WHERE ads_id='".$value["ads_id"]."'");

              $fieldsUpdate["ads_status"] = intval($_POST["status"]);
              $fieldsUpdate["ads_period_publication"] = $ads_period_publication;
              $fieldsUpdate["ads_period_day"] = $ads_period_day;
              $fieldsUpdate["ads_datetime_add"] = $time;

              $Elastic->update( [ "index" => "uni_ads", "type" => "ad", "id" => $value["ads_id"], "body" => [ "doc" => $fieldsUpdate ] ] );
              
              if($_POST["status"] == 1){

                $getUser = findOne( "uni_clients", "clients_id=?", [ $value["ads_id_user"] ] );
                 
                $data = array("{AD_TITLE}"=>$value["ads_title"],
                               "{AD_LINK}"=>$Ads->alias($value),
                               "{USER_NAME}"=>$getUser["clients_name"],                          
                               "{UNSUBSCRIBE}"=>"",                          
                               "{EMAIL_TO}"=>$getUser["clients_email"]
                               );

                email_notification( array( "variable" => $data, "code" => "AD_MODERATION_PUBLISHED" ) );
                firebase_notification($value,"moderation_ad");

                if(!$getUser['clients_first_ad_publication']){

                      if($settings["bonus_program"]["ad_publication"]["status"] && $settings["bonus_program"]["ad_publication"]["price"]){
                           $Profile->actionBalance(array("id_user"=>$value["ads_id_user"],"summa"=>$settings["bonus_program"]["ad_publication"]["price"],"title"=>$settings["bonus_program"]["ad_publication"]["name"],"id_order"=>$config["key_rand"],"email" => $getUser["clients_email"],"name" => $getUser["clients_name"], "note" => $settings["bonus_program"]["ad_publication"]["name"]),"+");    
                           update('update uni_clients set clients_first_ad_publication=? where clients_id=?', [1, $value["ads_id_user"]]);           
                      }                   

                }

              }

              $Ads->changeStatus( $value["ads_id"], intval($_POST["status"]) );

         }
     }

     if($_POST["status"] == 1) $Ads->serviceActivation( ["id_ad"=>implode(",", $ids),"status"=>$_POST["status"]] );

 }else{
     
     $time = date("Y-m-d H:i:s");

     $getAd = $Ads->get("ads_id=?",[intval($_POST["id"])]);

     $period = $Ads->adPeriodPub( $getAd["ads_period_day"] );

     if(strtotime($getAd["ads_period_publication"]) <= time()){
        $ads_period_day = $period["days"];
        $ads_period_publication = $period["date"];
     }else{
        $ads_period_day = $getAd["ads_period_day"];
        $ads_period_publication = $getAd["ads_period_publication"];                
     }

     update("UPDATE uni_ads SET ads_status='".intval($_POST["status"])."',ads_period_publication='".$ads_period_publication."',ads_datetime_add='".$time."',ads_period_day='".$ads_period_day."' WHERE ads_id='".intval($_POST["id"])."'");

     $fieldsUpdate["ads_status"] = intval($_POST["status"]);
     $fieldsUpdate["ads_period_publication"] = $ads_period_publication;
     $fieldsUpdate["ads_period_day"] = $ads_period_day;
     $fieldsUpdate["ads_datetime_add"] = $time;

     $Elastic->update( [ "index" => "uni_ads", "type" => "ad", "id" => intval($_POST["id"]), "body" => [ "doc" => $fieldsUpdate ] ] );

     if($_POST["status"] == 1){

         $Ads->serviceActivation( ["id_ad"=>intval($_POST["id"]),"status"=>$_POST["status"]] );

         $data = array("{AD_TITLE}"=>$getAd["ads_title"],
                       "{AD_LINK}"=>$Ads->alias($getAd),
                       "{USER_NAME}"=>$getAd["clients_name"],                          
                       "{UNSUBSCRIBE}"=>"",                          
                       "{EMAIL_TO}"=>$getAd["clients_email"]
                       );

         email_notification( array( "variable" => $data, "code" => "AD_MODERATION_PUBLISHED" ) );
         firebase_notification($getAd,"moderation_ad");

         if(!$getAd['clients_first_ad_publication']){

              if($settings["bonus_program"]["ad_publication"]["status"] && $settings["bonus_program"]["ad_publication"]["price"]){
                   $Profile->actionBalance(array("id_user"=>$getAd["ads_id_user"],"summa"=>$settings["bonus_program"]["ad_publication"]["price"],"title"=>$settings["bonus_program"]["ad_publication"]["name"],"id_order"=>$config["key_rand"],"email" => $getAd["clients_email"],"name" => $getAd["clients_name"], "note" => $settings["bonus_program"]["ad_publication"]["name"]),"+");   
                   update('update uni_clients set clients_first_ad_publication=? where clients_id=?', [1, $getAd["ads_id_user"]]);            
              }                   

         }

     }

     $Ads->changeStatus( intval($_POST["id"]), intval($_POST["status"]) );

 }


 $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";          
 echo true;

 $Cache->update( "uni_ads" );

}

?>