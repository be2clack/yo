<?php

$config = require "./config.php";

$route_name = "subscribe";
$visible_footer = true;

$Main = new Main();
$settings = $Main->settings();

$Main = new Main();
$Seo = new Seo();
$Geo = new Geo();
$Profile = new Profile();
$CategoryBoard = new CategoryBoard();
$Banners = new Banners();
$Subscription = new Subscription();
$ULang = new ULang();

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

if($_GET["hash"]){
	$get = findOne("uni_subscription","subscription_email=?", array(clear($_GET['email'])));
	if(!$get){
         if ($_GET["hash"] == hash('sha256', $_GET['email'].$config["private_hash"])) {
         	$Subscription->add(array("email"=>$_GET['email'],"status" => 1));
         }else{
         	$Main->response(404);
         }
	}
}else{
	$Main->response(404);
}

echo $Main->tpl("subscribe.tpl", compact( 'Seo','Geo','Main','visible_footer','route_name','settings','config','data','Profile','CategoryBoard','Banners','getCategoryBoard','ULang') );

?>