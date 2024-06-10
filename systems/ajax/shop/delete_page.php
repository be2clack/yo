<?php

$id_page = (int)$_POST["id_page"];
$id_shop = (int)$_POST["id_shop"];

$getShop = findOne("uni_clients_shops", "clients_shops_id=? and clients_shops_id_user=?", [ $id_shop, $_SESSION["profile"]["id"] ]);

if(!$getShop || !$_SESSION['profile']['tariff']['services']['shop_page']){
   exit;
}

update("delete from uni_clients_shops_page where clients_shops_page_id=? and clients_shops_page_id_shop=?", [ $id_page, $id_shop ]);

echo $Shop->linkShop( $getShop->clients_shops_id_hash );

?>