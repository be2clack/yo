<?php

$config = require "./config.php";

$route_name = "promo";
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

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");


$data["elements"] = [
    "header_1" => "Шапка с изображением и заголовком",
    "header_2" => "Шапка с заголовком и текстом",
    "info_block_1" => "Информационный блок 1",
    "info_block_2" => "Информационный блок 2",
    "info_block_3" => "Информационный блок 3",
    "button_1" => "Кнопка",
];


if( $_SESSION['cp_auth'][ $config["private_hash"] ] && $_SESSION["cp_control_page"] ){
    $data["page"] = findOne("uni_promo_pages","promo_pages_alias=?", [$name] );
}else{
	$data["page"] = findOne("uni_promo_pages","promo_pages_alias=? and promo_pages_visible=?", [$name,1] );
}

if(!$data["page"]){
	$Main->response(404);
}

$Main->viewPromoPage($data["page"]["promo_pages_id"]);

echo $Main->tpl("promo.tpl", compact( 'Seo','Geo','Main','visible_footer','route_name','settings','config','data','Profile','CategoryBoard','Banners','getCategoryBoard','ULang' ) );


?>