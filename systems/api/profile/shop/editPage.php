<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$id_shop = (int)$_POST["id_shop"];
$id_page = (int)$_POST["id_page"];
$name = custom_substr(clear($_POST["name"]), 50);
$text = custom_substr(clear($_POST["text"]), 5000);

$errors = [];

$getShop = findOne("uni_clients_shops", "clients_shops_id=? and clients_shops_id_user=?", [$id_shop,$idUser]);

if(!$getShop){
    exit;
}

if( !$name ){ $errors[] = apiLangContent("Пожалуйста, укажите название"); }else{

    if( findOne( "uni_clients_shops_page", "clients_shops_page_id_shop=? and clients_shops_page_alias=? and clients_shops_page_id!=?", [ $id_shop, translite($name), $id_page ] ) ){
        $errors[] = apiLangContent("Страница с таким названием уже существует");
    }

}

if(!$text){
    $errors[] = apiLangContent("Пожалуйста, укажите описание");
}

if( !$errors ){
    update("update uni_clients_shops_page set clients_shops_page_name=?,clients_shops_page_alias=?,clients_shops_page_text=? where clients_shops_page_id=?", [$name, translite($name), $text, $id_page]);
    echo json_encode( ["status" => true] );
}else{
    echo json_encode( ["status" => false, "answer" => implode( "\n", $errors ) ] );
}


?>