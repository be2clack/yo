<?php

$page = (int)$_GET["page"];
$cat_id = (int)$_GET["cat_id"];

$output = 30;
$ads_images = [];

$query = "";

$totalCount = (int)getOne("select count(*) as total from uni_clients_shops INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_clients_shops`.clients_shops_id_user where (clients_shops_time_validity > now() or clients_shops_time_validity IS NULL) and clients_shops_status=1 and clients_status IN(0,1) {$query}")["total"];

$getShops = getAll("select * from uni_clients_shops INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_clients_shops`.clients_shops_id_user where (clients_shops_time_validity > now() or clients_shops_time_validity IS NULL) and clients_shops_status=1 and clients_status IN(0,1) {$query} order by clients_shops_id desc".navigation_offset(["count"=>$totalCount, "output"=>$output, "page"=>$page]));

echo json_encode(['data'=>apiArrayDataShops($getShops), 'count'=>$totalCount .' '.ending($totalCount, apiLangContent('магазин'), apiLangContent('магазина'), apiLangContent('магазинов')), 'pages'=>getCountPage($totalCount,$output)]);

?>