<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_clients']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

$Ads = new Ads();

$id = (int)$_POST["id"];

$Ads->delete(["id_user"=>$id]);  

update("delete from uni_clients where clients_id=?", array($id));

$getSecure = findOne("uni_secure", "secure_id_user_buyer=? or secure_id_user_seller=?", array($id,$id));

if($getSecure){
update("delete from uni_secure where secure_id=?", array($getSecure["secure_id"]));
update("delete from uni_secure_disputes where secure_disputes_id_secure=?", array($getSecure["secure_id"]));
update("delete from uni_secure_payments where secure_payments_id_order=?", array($getSecure["secure_id_order"]));
}

update("delete from uni_clients_reviews where clients_reviews_id_user=?", array($id));
update("delete from uni_clients_reviews where clients_reviews_from_id_user=?", array($id));
update("delete from uni_clients_subscriptions where clients_subscriptions_id_user_from=?", array($id));
update("delete from uni_clients_subscriptions where clients_subscriptions_id_user_to=?", array($id));
update("delete from uni_chat_users where chat_users_id_user=?", array($id));
update("delete from uni_chat_users where chat_users_id_interlocutor=?", array($id));

$getShops = getAll( "select * from uni_clients_shops where clients_shops_id_user=?", [ $id ] );

if( count($getShops) ){

    foreach ($getShops as $value) {

    	@unlink( $config["basePath"] . "/" . $config["media"]["other"] . "/" .  $value["clients_shops_logo"] );
    	
    	update("delete from uni_clients_shops where clients_shops_id_user=?", array( $value["clients_shops_id"] ));
    	update("delete from uni_clients_shops_page where clients_shops_page_id_shop=?", array( $value["clients_shops_id"] ));

    	  $getSliders = getAll( "select * from uni_clients_shops_slider where clients_shops_slider_id_shop=?", [ $value["clients_shops_id"] ] );

	      if( count($getSliders) ){
	      	 foreach ($getSliders as $slide) {
	           @unlink( $config["basePath"] . "/" . $config["media"]["user_attach"] . "/" . $slide["clients_shops_slider_image"] );
               update("delete from uni_clients_shops_slider where clients_shops_slider_id=?", array( $slide["clients_shops_slider_id"] ));
             }
	      }

    }

}

$_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";          
          
echo true;
    
}
?>