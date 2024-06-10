<?php

$config = require "./config.php";

$route_name = "unsubscribe";
$visible_footer = true;

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

if($_GET["hash"]){
    
    if( $_GET["id"] ){

         if ($_GET["hash"] == hash('sha256', $_GET["id"].$config["private_hash"])) {
         	update("delete from uni_ads_subscriptions where ads_subscriptions_id=?", [ intval($_GET["id"]) ]);
         }else{
         	$Main->response(404);
         }

    }else{

		$get = findOne("uni_subscription","subscription_email=?", array(clear($_GET['email'])));
		if($get){
	         if ($_GET["hash"] == hash('sha256', $_GET['email'].$config["private_hash"])) {
	         	update("delete from uni_subscription where subscription_id=?", [$get->subscription_id]);
	         }else{
	         	$Main->response(404);
	         }
		}else{
			$Main->response(404);
		}

    }

}else{
	$Main->response(404);
}

echo $Main->tpl("unsubscribe.tpl", compact( 'Seo','Geo','Main','visible_footer','route_name','settings','config','data','Profile','CategoryBoard','Banners','getCategoryBoard','ULang') );

?>