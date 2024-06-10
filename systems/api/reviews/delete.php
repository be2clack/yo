<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);
$idReview = (int)$_POST["id_review"];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$getReview = findOne("uni_clients_reviews", "clients_reviews_id=? and clients_reviews_from_id_user=?", [$idReview,$idUser]);

if($getReview["clients_reviews_files"]){
    $files = explode(",", $getReview["clients_reviews_files"]);
    if($files){
       foreach ($files as $name) {
           @unlink( $config["basePath"] . "/" . $config["media"]["user_attach"] . "/" . $name );
       }
    }
}

update('delete from uni_clients_reviews where clients_reviews_id=? and clients_reviews_from_id_user=?', [$idReview,$idUser]);

echo json_encode(['status'=>true]);

?>