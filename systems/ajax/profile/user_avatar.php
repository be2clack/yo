<?php

$error = array();

$result = $Main->uploadedImage( ["files"=>$_FILES["image"], "path"=>$config["media"]["avatar"], "name"=>$_SESSION['profile']['id']] );

if($result["error"]){
    echo json_encode( ["error"=>implode("\n", $result["error"])] );
}else{
    update("UPDATE uni_clients SET clients_avatar=? WHERE clients_id=?", array($result["name"],$_SESSION['profile']['id'])); 
    echo json_encode(array("img"=>Exists($config["media"]["avatar"],$result["name"],$config["media"]["no_avatar"]).'?'.mt_rand(100, 1000)));            
}

?>