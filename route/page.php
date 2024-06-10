<?php

$config = require "./config.php";

$route_name = "page";
$visible_footer = true;

$Main = new Main();
$settings = $Main->settings();

if(!$data["page"] || !$data["page"]["visible"]){
	$Main->response(404);
}

$Main = new Main();
$Seo = new Seo();
$Geo = new Geo();
$Profile = new Profile();
$CategoryBoard = new CategoryBoard();
$Banners = new Banners();
$ULang = new ULang();

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

$data["pages"] = getAll("select * from uni_pages where visible=1 order by id_position asc");

echo $Main->tpl("page.tpl", compact( 'Seo','Geo','Main','visible_footer','route_name','settings','config','data','Profile','CategoryBoard','Banners','getCategoryBoard', 'ULang' ) );

?>