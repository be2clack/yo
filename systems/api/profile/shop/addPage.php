<?php

$id = (int)$_POST["id"];
$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$name = custom_substr(clear($_POST["name"]), 50);
$text = custom_substr(clear($_POST["text"]), 5000);

$errors = [];

$getUser = findOne("uni_clients", "clients_id=?", [$idUser]);

$getTariff = apiGetTariff($getUser["clients_tariff_id"]);

$getShop = findOne("uni_clients_shops", "clients_shops_id=? and clients_shops_id_user=?", [ $id, $idUser ]);

if(!$getShop){
   echo json_encode( ["status" => false, "answer" => apiLangContent("Магазин недоступен") ] );
}

if(!$getTariff['services']['shop_page']){
   echo json_encode( ["status" => false, "answer" => apiLangContent("Добавление страниц недоступно") ] );
}

$getPages = getAll( "select * from uni_clients_shops_page where clients_shops_page_id_shop=?", [ $id ] );

if( count($getPages) < $settings["user_shop_count_pages"] ){

    if( !$name ){ $errors[] = apiLangContent("Пожалуйста, укажите название"); }else{

        if( findOne( "uni_clients_shops_page", "clients_shops_page_id_shop=? and clients_shops_page_alias=?", [ $id, translite($name) ] ) ){
            $errors[] = apiLangContent("Страница с таким названием уже существует");
        }

    }

}else{
    
    $errors[] = apiLangContent("Исчерпан лимит добавления страниц");

}

if(!$text){
	$errors[] = apiLangContent("Пожалуйста, укажите описание");
}

if( !$errors ){
    insert("INSERT INTO uni_clients_shops_page(clients_shops_page_id_shop,clients_shops_page_name,clients_shops_page_alias,clients_shops_page_text)VALUES(?,?,?,?)", [ $id, $name, translite($name), $text ]);
    echo json_encode( ["status" => true] );
}else{
    echo json_encode( ["status" => false, "answer" => implode( "\n", $errors ) ] );
}

?>