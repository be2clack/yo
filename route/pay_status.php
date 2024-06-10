<?php

$config = require "./config.php";

$route_name = "pay_status";
$visible_footer = true;

$Main = new Main();
$settings = $Main->settings();

if( intval($_GET["order"]) ){

	$getOrderParam = findOne("uni_orders_parameters","orders_parameters_id_uniq=?", [intval($_GET["order"])]);

	if(!$getOrderParam) $Main->response(404);

	if( $getOrderParam->orders_parameters_status == 1 ){
		$status = "success";
	}else{
		$status = "fail";
	}

}else{

	if( $status != "success" && $status != "fail" ){
		$Main->response(404);
	}

}

$Main = new Main();
$Seo = new Seo();
$Geo = new Geo();
$Profile = new Profile();
$CategoryBoard = new CategoryBoard();
$Banners = new Banners();
$ULang = new ULang();

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

echo $Main->tpl("pay_status.tpl", compact( 'Seo','Geo','Main','visible_footer','route_name','settings','config','data','Profile','CategoryBoard','Banners','status','getCategoryBoard','ULang') );

?>