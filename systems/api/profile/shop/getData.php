<?php

$id = (int)$_GET["id"];
$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$results = [];
$categories = [];
$sliders = [];
$pages = [];

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

$getShop = findOne('uni_clients_shops', 'clients_shops_id=? and clients_shops_id_user=?', [$id,$idUser]);

if(!$getShop){
	http_response_code(500); exit('Shop not found');
}

if($getCategoryBoard["category_board_id_parent"][0]){
   foreach ($getCategoryBoard["category_board_id_parent"][0] as $value) {
   	  $categories[] = ["id"=>$value["category_board_id"], "name"=>$value["category_board_name"]];
   }
}

$getSliders = getAll("select * from uni_clients_shops_slider where clients_shops_slider_id_shop=?", [$getShop["clients_shops_id"]]);

if($getSliders){
  foreach ($getSliders as $key => $value) {
  	 $sliders[] = ['name'=>$value["clients_shops_slider_image"], 'link'=>Exists($config["media"]["user_attach"],$value["clients_shops_slider_image"],$config["media"]["no_image"])];
  }
}

$getPages = getAll("select * from uni_clients_shops_page where clients_shops_page_id_shop=? and clients_shops_page_status=?", [$getShop["clients_shops_id"],1]);

if($getPages){
  foreach ($getPages as $key => $value) {
  	 $pages[] = ["id"=>$value["clients_shops_page_id"], "name"=>$value["clients_shops_page_name"], "text"=>html_entity_decode(strip_tags($value["clients_shops_page_text"]))];
  }
}

$results = [
	"title" => $getShop['clients_shops_title'],
	"text" => $getShop['clients_shops_desc'],
	"logo" => Exists($config["media"]["other"],$getShop["clients_shops_logo"],$config["media"]["no_image"]),
	"id_theme_category" => $getShop['clients_shops_id_theme_category'],
	"name_theme_category" => $getCategoryBoard["category_board_id"][$getShop['clients_shops_id_theme_category']]["category_board_name"],
	"status" => $getShop['clients_shops_status'],
	"status_note" => $getShop['clients_shops_status_note'],
	"id_hash" => $getShop['clients_shops_id_hash'],
	"categories" => $categories ? $categories : null,
	"id_user" => $getShop['clients_shops_id_user'],
	"sliders" => $sliders ? $sliders : null,
	"pages" => $pages ? $pages : null,
];

echo json_encode(['status'=>true, 'data'=>$results]);

?>