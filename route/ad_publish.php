<?php
$config = require "./config.php";

$route_name = "ad_publish";
$visible_footer = true;

$Main = new Main();
$settings = $Main->settings();

$CategoryBoard = new CategoryBoard(); 
$Seo = new Seo();
$Geo = new Geo();
$Profile = new Profile();
$Ads = new Ads();
$Banners = new Banners();
$ULang = new ULang();

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

$data = $Ads->get("ads_id=? and ads_id_user=? and ads_status=?", [$id_ad,intval($_SESSION["profile"]["id"]),6]);

if(!$data){
   $Main->response("404");
}

echo $Main->tpl("ad_publish.tpl", compact( 'Seo','Geo','Main','visible_footer','Ads','route_name','list_services','data','Profile','languages_content','config','settings', 'CategoryBoard', 'getCategoryBoard', 'Banners', 'ULang' ) );

?>