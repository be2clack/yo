<?php

$config = require "./config.php";

$route_name = "shops";
$visible_footer = true;
$visible_cities = true;

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

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

$data["current_category"] = $getCategoryBoard["category_board_chain"][$alias_category];

if(!$settings["user_shop_status"]){
    $Main->response(404);
}

if( $alias_category ){
    if( !$data["current_category"] ){
         $Main->response(404);
    }
    $data["meta_title"] = $Seo->out(["page" => "shops_category", "field" => "meta_title"], $data);
    $data["h1"] = $Seo->out(["page" => "shops_category", "field" => "h1"], $data);
    $data["meta_desc"] = $Seo->out(["page" => "shops_category", "field" => "meta_desc"], $data);
    $data["seo_text"] = $Seo->out(["page" => "shops_category", "field" => "text"], $data);
}else{
    $data["meta_title"] = $Seo->out(["page" => "shops", "field" => "meta_title"], $data);
    $data["h1"] = $Seo->out(["page" => "shops", "field" => "h1"], $data);
    $data["meta_desc"] = $Seo->out(["page" => "shops", "field" => "meta_desc"], $data);
    $data["seo_text"] = $Seo->out(["page" => "shops", "field" => "text"], $data);
}

echo $Main->tpl("shops.tpl", compact( 'Seo','Geo','Main','visible_footer','route_name','settings','config','data','Profile','CategoryBoard','Banners','getCategoryBoard','ULang','Shop','Ads','Elastic', 'visible_cities') );


?>