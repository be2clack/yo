<?php
$config = require "./config.php";
$static_msg = require "./static/msg.php";

$route_name = "ad_view";
$visible_footer = true;
$visible_cities = true;

$Main = new Main();
$settings = $Main->settings();

$CategoryBoard = new CategoryBoard(); 
$Seo = new Seo();
$Geo = new Geo();
$Profile = new Profile();
$Filters = new Filters();
$Banners = new Banners();
$ULang = new ULang();

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

if( $data["ad"]["ads_status"] != 8 && $data["ad"]["clients_status"] != 3 ){

if( !$_SESSION['cp_auth'][ $config["private_hash"] ] ){

      if( $data["ad"]["clients_status"] != 2 ){

          if( ($data["ad"]["ads_status"] == 1 || $data["ad"]["ads_status"] == 4 || $data["ad"]["ads_status"] == 5) && strtotime($data["ad"]["ads_period_publication"]) > time() ){

             $data["activity_ad"] = true;

          }else{

             if($_SESSION["profile"]["id"] == $data["ad"]["ads_id_user"]){
                $data["activity_ad"] = true;
             }      

          }

      }

    }else{
        $data["activity_ad"] = true;
    }

}

if($_SESSION['current_load']){
    $_SESSION['current_load']['ad'] = $data["ad"]["ads_id"];
}

$Ads->viewAds($data["ad"]["ads_id"],$data["ad"]["ads_id_user"]);
$Profile->addUserInterest($data["ad"]["ads_id_cat"], $_SESSION['profile']['id']);

if(isset($_SESSION["profile"])){
    $Ads->viewAdsUser($data["ad"]["ads_id"],$_SESSION["profile"]["id"]);
}

$data["tariff"] = $Profile->getOrderTariff($data["ad"]["ads_id_user"]);

$data["ad"]["ads_images"] = $Ads->getImages($data["ad"]["ads_images"]);

$data["order_secure"] = $Main->getSecureAdOrder('secure_ads_ad_id=? and secure_status NOT IN(3,5)', [$data["ad"]["ads_id"]]);

$data["reviews"] = getAll("select * from uni_clients_reviews INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_clients_reviews`.clients_reviews_from_id_user where clients_reviews_status=? and clients_reviews_id_ad=? order by clients_reviews_id desc", [1,$data["ad"]["ads_id"]]);

$data["properties"] = $Filters->outProductProp($data["ad"]["ads_id"], $data["ad"]["ads_id_cat"], $getCategoryBoard);
$data["properties_count"] = (int)getOne("select count(*) as total from uni_ads_filters_variants where ads_filters_variants_product_id=?", [$data["ad"]["ads_id"]])["total"];

$data["share"] = $Main->share( array( "title" => $Seo->out(["page" => "ad", "field" => "meta_title"], $data) , "image" => Exists($config["media"]["big_image_ads"],$data["ad"]["ads_images"][0],$config["media"]["no_image"]), "url" => $Ads->alias($data["ad"]) ) );

$data["breadcrumb"] = breadcrumb_count($CategoryBoard->breadcrumb($getCategoryBoard,$data["ad"]["ads_id_cat"],'
                  <li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><a itemprop="item" href="{LINK}"><span itemprop="name">{NAME}</span></a><meta itemprop="position" content="{INDEX}"></li>
                '));

$data["ratings"] = $Profile->outRating( $data["ad"]["clients_id"] );
$data["metro"] = $Ads->getMetro( $data["ad"]["ads_metro_ids"] );

if($data["ad"]["ads_area_ids"]){
    $getArea = getOne("select * from uni_city_area_variants INNER JOIN `uni_city_area` ON `uni_city_area`.city_area_id = `uni_city_area_variants`.city_area_variants_id_area where city_area_variants_id_area=? and city_area_variants_id_ad=?",[$data["ad"]["ads_area_ids"],$data["ad"]["ads_id"]]);
    $data["areas"] = $getArea["city_area_name"];
}

$data["locked"] = $Profile->getUserLocked( $data["ad"]["ads_id_user"], $_SESSION["profile"]["id"] );
$data["auction_users"] = getAll("select * from uni_ads_auction INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads_auction`.ads_auction_id_user where ads_auction_id_ad=? order by ads_auction_id desc", [ $data["ad"]["ads_id"] ]);
$data["auction_user_winner"] = $Ads->getAuctionWinner( $data["ad"]["ads_id"] );

if($data["ad"]["clients_view_phone"]){
  $_SESSION["ad-phone"][$data["ad"]["ads_id"]] = $data["ad"]["clients_phone"];
}

$data["services_order"] = getAll("select * from uni_services_order INNER JOIN `uni_services_ads` ON `uni_services_ads`.services_ads_uid = `uni_services_order`.services_order_id_service where services_order_id_ads=? and services_order_time_validity > now()", array($data["ad"]["ads_id"]));

$data["order_service_ids"] = $Ads->getOrderServiceIds( $data["ad"]["ads_id"] );

if(in_array(1, $data["order_service_ids"]) || in_array(2, $data["order_service_ids"])){
   $data["order_service_ids"][] = 3;
}elseif(in_array(3, $data["order_service_ids"])){
   $data["order_service_ids"][] = 1;
   $data["order_service_ids"][] = 2;
}

$data["order_service_ids"] = $data["order_service_ids"] ? "and services_ads_uid NOT IN(".implode(",", $data["order_service_ids"]).")" : "";

$data["services_ads"] = getAll("SELECT * FROM uni_services_ads WHERE services_ads_visible=1 {$data["order_service_ids"]} order by services_ads_recommended desc, services_ads_id_position asc");
if(count($data["services_ads"])){
  foreach ($data["services_ads"] as $key => $value) {
    
    ob_start();
    require $config["template_path"] . "/include/services_tariffs.php";
    $list_services .= ob_get_clean();

  }
}

$data["ad"]["ads_images"] = $data["ad"]["ads_images"] ?: [];

echo $Main->tpl("ad_view.tpl", compact( 'Seo','Geo','Main','visible_footer','Ads','route_name','list_services','data','Profile','languages_content','config','settings', 'CategoryBoard', 'getCategoryBoard', 'Filters', 'Banners', 'ULang', 'static_msg', 'visible_cities' ) );

?>