<?php

$id_page = (int)$_POST["id_page"];
$id_shop = (int)$_POST["id_shop"];
$text = $_POST["text"];

$getShop = findOne("uni_clients_shops", "clients_shops_id=? and clients_shops_id_user=?", [ $id_shop, $_SESSION["profile"]["id"] ]);

if(!$getShop || !$_SESSION['profile']['tariff']['services']['shop_page']){
   exit;
}

update("update uni_clients_shops_page set clients_shops_page_text=? where clients_shops_page_id=? and clients_shops_page_id_shop=?", [ $text, $id_page, $id_shop ]);

$Admin->notifications("shops_edit", ["shop_name"=>$getShop["clients_shops_title"], "shop_link"=>$Shop->linkShop($id_shop)]);

echo true;

?>