<?php

$id_shop = (int)$_POST["id_shop"];
$name = custom_substr(clear($_POST["name"]), 50);
$error = [];

$getShop = findOne("uni_clients_shops", "clients_shops_id=? and clients_shops_id_user=?", [ $id_shop, $_SESSION["profile"]["id"] ]);

if(!$getShop || !$_SESSION['profile']['tariff']['services']['shop_page']){
   exit;
}

$getPages = getAll( "select * from uni_clients_shops_page where clients_shops_page_id_shop=?", [ $id_shop ] );

if( count($getPages) < $settings["user_shop_count_pages"] ){

    if( !$name ){ $error[] = $ULang->t("Пожалуйста, укажите название страницы"); }else{

        if( findOne( "uni_clients_shops_page", "clients_shops_page_id_shop=? and clients_shops_page_alias=?", [ $id_shop, translite($name) ] ) ){
            $error[] = $ULang->t("Страница с таким названием уже существует!");
        }

    }

}else{
    
    $error[] = $ULang->t("Исчерпан лимит добавления страниц!");

}

if( count( $error ) == 0 ){
    insert("INSERT INTO uni_clients_shops_page(clients_shops_page_id_shop,clients_shops_page_name,clients_shops_page_alias)VALUES(?,?,?)", [ $id_shop, $name, translite($name) ]);
    echo json_encode( ["status" => true, "link" => $Shop->aliasPage( $getShop["clients_shops_id_hash"], translite($name) )] );
}else{
    echo json_encode( ["status" => false, "answer" => implode( "\n", $error ) ] );
}

?>