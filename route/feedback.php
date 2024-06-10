<?php

$config = require "./config.php";

$route_name = "feedback";
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

$data["pages"] = getAll("select * from uni_pages where visible=1 order by id_position asc");

$secure_category = getAll("select category_board_name from uni_category_board where category_board_secure=? and category_board_visible=?", [1,1]);

if(count($secure_category)){
 foreach ($secure_category as $key => $value) {
 	$data["secure_category"][] = '«'.$value["category_board_name"].'»';
 }
}else{
 $data["secure_category"] = [];
}

if( $settings["secure_payment_service_name"] ){
    $data['payment'] = findOne("uni_payments","code=?", array( $settings["secure_payment_service_name"] ));
}

echo $Main->tpl("feedback.tpl", compact( 'Seo','Geo','Main','visible_footer','route_name','settings','config','data','Profile','CategoryBoard','Banners','getCategoryBoard', 'ULang' ) );

?>