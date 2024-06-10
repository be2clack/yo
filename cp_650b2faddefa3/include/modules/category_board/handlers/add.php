<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_board']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){
 
 $error = array();

 $Main = new Main();
 $Cache = new Cache();
 
 $_POST["name"] = addslashes($_POST["name"]);
 $_POST["title"] = addslashes($_POST["title"]);
 $_POST["h1"] = addslashes($_POST["h1"]);
 $_POST["desc"] = addslashes($_POST["desc"]);

 if($_POST["paid"]){
    if(!$_POST["price"]){
       $error[] = "Укажите стоимость размещения";
    }
 }
 
 if(!$_POST["name"]){$error[] = "Укажите название категории";}

 if(empty($_POST["alias"])){
     $alias = translite($_POST["name"]);         
 }else{
     $alias = translite($_POST["alias"]);
 }

if(!$_POST["title"]){
    $_POST["title"] = $_POST["name"];
}

if(!$_POST["h1"]){
    $_POST["h1"] = $_POST["title"];
}

if(!$_POST["display_price"]){
    $_POST["variant_price_id"] = 0;
    $_POST["booking"] = 0;
    $_POST["auction"] = 0;
    $_POST["secure"] = 0;
    $_POST["marketplace"] = 0;
}

if($_POST["marketplace"]){
    $_POST["variant_price_id"] = 0;
}

if($_POST["booking"]){
    if($_POST["auction"] || $_POST["secure"]){
        $error[] = "Бронирование/Аренда доступны без аукционов и безопасных сделок";
    }
}

if($_POST["measures_price"]){
    $measures_price = json_encode($_POST["measures_price"],JSON_UNESCAPED_UNICODE);
}

if($_POST["rules"]){
    $rules = json_encode($_POST["rules"],JSON_UNESCAPED_UNICODE);
}

if( !$_POST["image_delete"] ){

  if(count($error) == 0){
      $image = $Main->uploadedImage( ["files"=>$_FILES["image"], "path"=>$config["media"]["other"], "prefix_name"=>"category"] );
      if($image["error"]){
          $error = array_merge($error,$image["error"]);
      }    
  }

}

if (count($error) == 0) {

      smart_insert('uni_category_board',[
        'category_board_name' => $_POST["name"],
        'category_board_title' => $_POST["title"],
        'category_board_alias' => $alias,
        'category_board_description' => $_POST["desc"],
        'category_board_id_parent' => $_POST["id_cat"],
        'category_board_image' => $image["name"],
        'category_board_text' => urlencode($_POST["text"]),
        'category_board_visible' => intval($_POST["visible"]),
        'category_board_price' => $_POST["price"] ? round($_POST["price"],2) : 0,
        'category_board_count_free' => intval($_POST["count_free"]),
        'category_board_status_paid' => intval($_POST["paid"]),
        'category_board_display_price' => intval($_POST["display_price"]),
        'category_board_variant_price_id' => intval($_POST["variant_price_id"]),
        'category_board_measures_price' => $measures_price,
        'category_board_auction' => intval($_POST["auction"]),
        'category_board_secure' => intval($_POST["secure"]),
        'category_board_online_view' => intval($_POST["online_view"]),
        'category_board_h1' => clear($_POST["h1"]),
        'category_board_show_index' => intval($_POST["show_index"]),
        'category_board_marketplace' => intval($_POST["marketplace"]),
        'category_board_booking' => intval($_POST["booking"]),
        'category_board_booking_variant' => intval($_POST["booking_variant"]),
        'category_board_rules' => $rules,
      ]);
     
      $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
      echo true;  

      $Cache->update( "uni_category_board" );             
    
    } else {
              $_SESSION["CheckMessage"]["warning"] = implode("<br/>", $error);        
           }

}
?>