<?php

$config = require "./config.php";

$route_name = "shop";
$visible_footer = false;

$Main = new Main();
$settings = $Main->settings();

$Main = new Main();
$Seo = new Seo();
$Geo = new Geo();
$Profile = new Profile();
$CategoryBoard = new CategoryBoard();
$Banners = new Banners();
$ULang = new ULang();
$Shop = new Shop();
$Ads = new Ads();
$Elastic = new Elastic();
$Filters = new Filters();

cleanFiltersVars();

if(!$settings["user_shop_status"]){
    $Main->response(404);
}

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

if(!$id_shop){

$params = explode( "/", trim( $params, "/" ) );

$id_shop = $params[0];
unset( $params[0] );

}

$data["shop"] = findOne("uni_clients_shops", "clients_shops_id_hash=?", [ clear($id_shop) ]);
$data["user"] = findOne("uni_clients", "clients_id=?", [ $data["shop"]["clients_shops_id_user"] ]);

if(!$data["shop"] || !$data["user"]){
  $Main->response(404);
}

if($data["shop"]["clients_shops_status"] != 1){

    if(intval($_SESSION["profile"]["id"]) != $data["shop"]["clients_shops_id_user"]){
        if( !$_SESSION['cp_auth'][ $config["private_hash"] ] ){
            $Main->response(404);
        }
    }

}

$data["tariff"] = $Profile->getOrderTariff($data["shop"]["clients_shops_id_user"]);

if($alias_page){
    $data["current_page"] = findOne( "uni_clients_shops_page", "clients_shops_page_alias=? and clients_shops_page_status=? and clients_shops_page_id_shop=?", [ clear($alias_page), 1, $data["shop"]["clients_shops_id"] ] );

    if( !$data["current_page"] ) $Main->response(404);
}else{

    if($params){
        $alias = clear( implode( "/", $params ) );
        $data["current_category"] = $getCategoryBoard["category_board_chain"][$alias];
        if( !$data["current_category"] ) $Main->response(404);
        $Profile->addUserInterest($data["current_category"]["category_board_id"], $_SESSION['profile']['id']);
    }

}

if($data["current_category"]["category_board_id"]) $data["breadcrumb"] = breadcrumb_count($CategoryBoard->breadcrumbShop($getCategoryBoard,$data["current_category"]["category_board_id"],'
                  <li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><a itemprop="item" href="{LINK}"><span itemprop="name">{NAME}</span></a><meta itemprop="position" content="{INDEX}"></li>
                ',$data["shop"]["clients_shops_id_hash"]),3);

if( $data["current_page"] ){

    $data["meta_title"] = $data["current_page"]["clients_shops_page_name"] . ' - ' . $data["shop"]["clients_shops_title"];

}else{

    if( $data["current_category"] ){
        $data["h1"] = $ULang->t( $data["current_category"]["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] );
        $data["meta_title"] = $ULang->t( $data["current_category"]["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ) . ' - ' . $data["shop"]["clients_shops_title"];
    }else{
        $data["h1"] = $ULang->t('Объявления');
        $data["meta_title"] = $data["shop"]["clients_shops_title"];
    }

}

$data["shop_count_subscriptions"] = (int)getOne("select count(*) as total from uni_clients_subscriptions where clients_subscriptions_id_user_to=?", [ intval($data["shop"]["clients_shops_id_user"]) ])["total"];

$data["shop_count_ads"] = $Ads->getCount( "ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_id_user='{$data["shop"]["clients_shops_id_user"]}'" );
$data["shop_sliders"] = getAll("select * from uni_clients_shops_slider where clients_shops_slider_id_shop=?", [ $data["shop"]["clients_shops_id"] ]);

$data["shop_subscribers"] = getAll("select * from uni_clients_subscriptions where clients_subscriptions_id_shop=?", [ $data["shop"]["clients_shops_id"] ]);
$data["user_status_subscribe"] = findOne("uni_clients_subscriptions", "clients_subscriptions_id_user_from=? and clients_subscriptions_id_user_to=?", [ intval($_SESSION["profile"]["id"]) ,$data["shop"]["clients_shops_id_user"]]);

$data["user_reviews"] = (int)getOne("select count(*) as total from uni_clients_reviews where clients_reviews_id_user=?", [ $data["shop"]["clients_shops_id_user"] ])["total"];

$data["ads"] = $Shop->getAdsUser( [ "id_user" => $data["shop"]["clients_shops_id_user"], "navigation" => true ] );

$getShopCategories = $Shop->adCategories($data["shop"]["clients_shops_id_user"]);

$data["category"] = $getShopCategories;
$data["category_list"] = $Shop->outCategories($getShopCategories, 0, $data["shop"]["clients_shops_id_hash"],$data["current_category"]["category_board_id"]);

$data["pages"] = getAll("select * from uni_clients_shops_page where clients_shops_page_id_shop=? and clients_shops_page_status=?", [ $data["shop"]["clients_shops_id"], 1 ] );

$Shop->viewShop( $data["shop"]["clients_shops_id"] );
$Ads->clickKeyword(intval($_GET['s_id']));

if( $data["user"]["clients_status"] != 3 ){

    if( !$_SESSION['cp_auth'][ $config["private_hash"] ] ){

      if( $data["user"]["clients_status"] != 2 ){

          if( (strtotime($data["shop"]["clients_shops_time_validity"]) > time() || !$data["shop"]["clients_shops_time_validity"] || $data["shop"]["clients_shops_time_validity"] == null) && $data["shop"]["clients_shops_status"] ){

             $data["activity_shop"] = true;

          }

      }

    }else{
        $data["activity_shop"] = true;
    }

}

$data["param_filter"] = $_GET;

$data["count_filters"] = $Filters->countFilters($data["current_category"]["category_board_id"]);
$data["count_get_filters"] = $Filters->countGetFilters( $data["param_filter"] );

$data["share"] = $Main->share( array( "title" => $data["shop"]["clients_shops_title"], "image" => Exists($config["media"]["other"], $data["shop"]["clients_shops_logo"], $config["media"]["no_image"]), "url" => $Shop->linkShop( $data["shop"]["clients_shops_id_hash"] ), "description" => $data["shop"]["clients_shops_desc"] ) );

$data["locked"] = findOne( "uni_clients_blacklist", "clients_blacklist_user_id=? and clients_blacklist_user_id_locked=?", array(intval($_SESSION['profile']['id']),$data["user"]["clients_id"]) );

echo $Main->tpl("shop.tpl", compact( 'Seo','Geo','Main','visible_footer','route_name','settings','config','data','Profile','CategoryBoard','Banners','getCategoryBoard','ULang','Shop','Ads','Filters') );


?>