<?php
$config = require "./config.php";
$static_msg = require "./static/msg.php";

$route_name = "tariff";
$visible_footer = true;

$Main = new Main();
$settings = $Main->settings();

$CategoryBoard = new CategoryBoard(); 
$Ads = new Ads();
$Seo = new Seo();
$Geo = new Geo();
$Profile = new Profile();
$Banners = new Banners();
$ULang = new ULang();

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

if(!$_SESSION['profile']['id']){
	header("Location: "._link('auth'));
}

$data['tariffs'] = getAll('select * from uni_services_tariffs where services_tariffs_status=? order by services_tariffs_position asc', [1]);

echo $Main->tpl("tariff.tpl", compact( 'Seo','Geo','Main','visible_footer','Ads','route_name','Profile','settings','CategoryBoard','data','getCategoryBoard', 'Filters', 'Banners', 'ULang', 'visible_cities') );

?>