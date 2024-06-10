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

$CategoryBoard = new CategoryBoard();
$Main = new Main();
$Cache = new Cache();

$id = (int)$_POST["id"];
$error = [];
$params = [];

$getCategory = findOne("uni_category_board", "category_board_id =?", [$id]);

$_POST["name"] = addslashes($_POST["name"]);
$_POST["title"] = addslashes($_POST["title"]);
$_POST["h1"] = addslashes($_POST["h1"]);
$_POST["desc"] = addslashes($_POST["desc"]);

if(!$_POST["paid"]){
    $_POST["price"] = 0;
    $_POST["count_free"] = 0;
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

if($_POST["paid"]){
  if(!$_POST["price"]){
     $error[] = "Укажите стоимость размещения";
  }
}

if(!$_POST["name"]){$error[] = "Укажите название категории";}
 if($_POST["auto_title"]){
    if(!$_POST["auto_title_template"]){
        $error[] = "Укажите шаблон заголовка";
    }
 }

if(!$_POST["title"]){
    $_POST["title"] = $_POST["name"];
}

if(!$_POST["h1"]){
    $_POST["h1"] = $_POST["title"];
}

if($_POST["measures_price"]){
    $measures_price = json_encode($_POST["measures_price"],JSON_UNESCAPED_UNICODE);
}

if($_POST["rules"]){
    $rules = json_encode($_POST["rules"],JSON_UNESCAPED_UNICODE);
}

if(empty($_POST["alias"])){
   $alias = translite($_POST["name"]);         
}else{
   $alias = translite($_POST["alias"]);
}

if($_POST["id_cat"] != $id){
  $idsBuild = idsBuildJoin($CategoryBoard->idsBuild($id, $CategoryBoard->getCategories()));
  if(!$idsBuild){
     $params['category_board_id_parent'] = intval($_POST["id_cat"]);
  }else{
     $explode = explode(",",$idsBuild);
     if(!in_array($_POST["id_cat"], $explode)){
        $params['category_board_id_parent'] = intval($_POST["id_cat"]);
     }
  }
}     

if( !$_POST["image_delete"] ){

  if(count($error) == 0){
      $image = $Main->uploadedImage( ["files"=>$_FILES["image"], "path"=>$config["media"]["other"], "prefix_name"=>"category"] );
      if($image["error"]){
          $error = array_merge($error,$image["error"]);
      }else{
          if($image["name"]) $getCategory["category_board_image"] = $image["name"];
      }    
  }

}else{

  $getCategory["category_board_image"] = "";

}

if (!count($error)) {

  $params['category_board_name'] = $_POST["name"];
  $params['category_board_title'] = $_POST["title"];
  $params['category_board_alias'] = $alias;
  $params['category_board_text'] = urlencode($_POST["text"]);
  $params['category_board_description'] = $_POST["desc"];
  $params['category_board_visible'] = intval($_POST["visible"]);
  $params['category_board_price'] = $_POST["price"] ? round($_POST["price"],2) : 0;
  $params['category_board_count_free'] = intval($_POST["count_free"]);
  $params['category_board_status_paid'] = intval($_POST["paid"]);
  $params['category_board_display_price'] = intval($_POST["display_price"]);
  $params['category_board_variant_price_id'] = intval($_POST["variant_price_id"]);
  $params['category_board_measures_price'] = $measures_price;
  $params['category_board_auction'] = intval($_POST["auction"]);
  $params['category_board_secure'] = intval($_POST["secure"]);
  $params['category_board_image'] = $getCategory["category_board_image"];
  $params['category_board_online_view'] = intval($_POST["online_view"]);
  $params['category_board_h1'] = clear($_POST["h1"]);
  $params['category_board_auto_title'] = intval($_POST["auto_title"]);
  $params['category_board_auto_title_template'] = clear($_POST["auto_title_template"]); 
  $params['category_board_show_index'] = intval($_POST["show_index"]); 
  $params['category_board_marketplace'] = intval($_POST["marketplace"]);
  $params['category_board_booking'] = intval($_POST["booking"]);
  $params['category_board_booking_variant'] = intval($_POST["booking_variant"]);
  $params['category_board_rules'] = $rules;

  smart_update('uni_category_board', $params, 'category_board_id='.$id);
    
  if($_POST["subcategories"]){ 

     $nested_cat_ids = $CategoryBoard->idsBuild($id, $CategoryBoard->getCategories() );

     if($nested_cat_ids){

        smart_update('uni_category_board', [
            'category_board_price' => $_POST["price"] ? round($_POST["price"],2) : 0,
            'category_board_count_free' => intval($_POST["count_free"]),
            'category_board_status_paid' => intval($_POST["paid"]),
            'category_board_display_price' => intval($_POST["display_price"]),
            'category_board_variant_price_id' => intval($_POST["variant_price_id"]),
            'category_board_measures_price' => $measures_price,
            'category_board_auction' => intval($_POST["auction"]),
            'category_board_secure' => intval($_POST["secure"]),
            'category_board_online_view' => intval($_POST["online_view"]),
            'category_board_marketplace' => intval($_POST["marketplace"]),
            'category_board_booking' => intval($_POST["booking"]),
            'category_board_booking_variant' => intval($_POST["booking_variant"]),
            'category_board_rules' => $rules,
        ], 'category_board_id IN('.$nested_cat_ids.')');


     }

  }
                 
  $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
  echo true;

  $Cache->update( "uni_category_board" );
            
                
  } else {
           $_SESSION["CheckMessage"]["warning"] = implode("<br/>", $error);        
         }

}
?>