<?php


define('unisitecms', true);
session_start();

$config = require "../../../../config.php";

if( $_GET["key"] != $config["feed_ads_key"] ){ exit; }

include_once( $config["basePath"] . "/systems/unisite.php");

$Ads = new Ads();
$CategoryBoard = new CategoryBoard();
$Filters = new Filters();
$Shop = new Shop();
$query = [];

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

function titleBuild($id = 0){

  $get = getOne("SELECT * FROM uni_category_board where category_board_id=?", array($id));
  
    if($get['category_board_id_parent']!=0){ 
        $out .= titleBuild($get['category_board_id_parent'])."/";            
    }
    $out .= $get['category_board_name'];

    return $out; 
           
}

$_GET["page"] = $_GET["page"] ? intval($_GET["page"]) : 1;
$_GET["output"] = $_GET["output"] ? intval($_GET["output"]) : 50;


if($_GET["status"] != "all"){
  if($_GET["status"] == 1){
  	 $query[] = "clients_status IN(0,1) and ads_period_publication > now() and ads_status='1'";
  }else{
  	 $query[] = "ads_status='".intval($_GET["status"])."'";
  }
}


if($_GET["display_date"]){
	if($_GET["display_date"] == "now"){
		$query[] = "date(ads_datetime_add) = '".date("Y-m-d")."'";
	}elseif($_GET["display_date"] == "hour"){
		$query[] = "(ads_datetime_add BETWEEN NOW() - INTERVAL 1 HOUR AND NOW())";
	}
}

if($query){
	$where = implode(" and ", $query);
	$total_count_ads = (int)getOne("SELECT count(*) as total FROM `uni_ads` 
        INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user where $where", $param)["total"];
}else{
	$total_count_ads = (int)getOne("SELECT count(*) as total FROM `uni_ads` 
        INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user", $param)["total"];
}


$get = $Ads->getAll( array("navigation"=>true,"page"=>$_GET["page"],"output"=>$_GET["output"],"query"=>$where, "sort"=>"ORDER By ads_datetime_add DESC") ); 

$json["param"] = [ "page" => $_GET["page"], "count_page" => getCountPage($total_count_ads,$_GET["output"]) , "output" => $_GET["output"], "total_count_ads" => $total_count_ads ];

if($get["count"]){

    foreach ($get["all"] as $key => $value) {

      $value = $Ads->getDataAd($value);

    	$link_images = [];

    	$images = $Ads->getImages($value["ads_images"]);

    	if($images){
    		foreach ($images as $img) {
    			$link_images[] = Exists($config["media"]["big_image_ads"],$img,$config["media"]["no_image"]);
    		}
    	}

        $getShop = $Shop->getUserShop( $value["ads_id_user"] );

        if(!$getShop) $value["ads_price_old"] = 0;

    	$properties = $Filters->outProductPropArray($value["ads_id"], $value["ads_id_cat"], $getCategoryBoard);

    	$json["ads"][$value["ads_id"]] = [
        "id" => $value["ads_id"],
        "title" => $value["ads_title"],
        "link" => $Ads->alias($value),
        "alias" => $value["ads_alias"],
        "text" => $value["ads_text"],
        "link_video" => $value["ads_video"],
        "price" => $value["ads_price"],
        "old_price" => $value["ads_price_old"],
        "currency" => $value["ads_currency"],
        "address" => $value["ads_address"],
        "city_name" => $value["city_name"],
        "region_name" => $value["region_name"],
        "country_name" => $value["country_name"],
        "status" => $value["ads_status"],
        "vip" => $value["ads_vip"],
        "price_free" => $value["ads_price_free"],
        "online_view" => $value["ads_online_view"],
        "images" => $link_images,
        "date_add" => $value["ads_datetime_add"],
        "date_active" => $value["ads_period_publication"],
        "current_category" => $value["category_board_name"],
        "breadcrumb_category" => titleBuild($value["category_board_id"]),
        "filters" => $properties,
        "user" => [ "name" => $value["clients_name"], "surname" => $value["clients_surname"], "avatar" => Exists($config["media"]["avatar"],$value["clients_avatar"],$config["media"]["no_avatar"]), "email" => $value["clients_email"], "phone" => $value["clients_phone"], "link" => _link("user/".$value["clients_id_hash"]), "shop" => $getShop ? 1 : 0, "link_shop" => $Shop->linkShop($getShop["clients_shops_id_hash"]) ],
    	];

    }

}

echo json_encode($json);

?>
