<?php

session_start();
define('unisitecms', true);

$config = require "./../../config.php";
include_once( $config["basePath"] . "/systems/unisite.php" );
$static_msg = require $config["basePath"] . "/static/msg.php";

if($_FILES['upload']['tmp_name']){
    
    $extensions = array('jpeg', 'jpg', 'png', 'gif');
    $ext = strtolower(pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION));
    $path = $config["basePath"] . "/" . $config["media"]["other"];

    if (in_array($ext, $extensions)){
    if($_FILES["upload"]["size"] <= 25*1024*1024){
    
        $name = "shop_" . uniqid() .".jpg";
    
        if (move_uploaded_file($_FILES['upload']['tmp_name'], $path."/".$name))
        {

        resize($path . "/" . $name, $path . "/" . $name, 1024, 0);

        echo json_encode( [ "uploaded" => true, "url" => $config["urlPath"].'/'.$config["media"]["other"].'/'.$name ] );

        }


    }else{
        echo json_encode( [ "uploaded" => false, "error" => [ "message" => $ULang->t("Размер изображения не должен превышать") . ' 25 mb!' ] ] );
    }

    }else{
    
        echo json_encode( [ "uploaded" => false, "error" => [ "message" => $ULang->t("Допустимые расширения для изображений") . '('.implode(',', $extensions).')' ] ] );

    }


}
?>