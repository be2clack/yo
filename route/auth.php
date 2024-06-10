<?php
$config = require "./config.php";

$route_name = "auth";
$visible_footer = false;

$Main = new Main();
$settings = $Main->settings();

$Ads = new Ads();
$Main = new Main();
$Seo = new Seo();
$Geo = new Geo();
$Profile = new Profile();
$ULang = new ULang();

if( $_SESSION["profile"]["id"] ){
	$getUser = findOne("uni_clients","clients_id = ?", array( intval($_SESSION["profile"]["id"]) ));
	header( "Location: " . _link( "user/".$getUser["clients_id_hash"] ) );
}

if(isset($_SESSION['point-auth-location'])) unset($_SESSION['point-auth-location']);

echo $Main->tpl("auth.tpl", compact( 'Seo','Geo','Main','visible_footer','route_name','settings','config','Profile','ULang' ) );

?>