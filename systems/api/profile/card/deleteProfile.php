<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$getUser = $Profile->oneUser("where clients_id=?", [$idUser]);

if(!$getUser){
	http_response_code(500); exit('User not found'); 
}

$Ads->delete(["id_user"=>$idUser]);

$getShops = getAll( "select * from uni_clients_shops where clients_shops_id_user=?", [ $idUser ] );

if( count($getShops) ){

    foreach ($getShops as $value) {

      @unlink( $config["basePath"] . "/" . $config["media"]["other"] . "/" .  $value["clients_shops_logo"] );
      
      update("delete from uni_clients_shops where clients_shops_id_user=?", array( $value["clients_shops_id"] ));
      update("delete from uni_clients_shops_page where clients_shops_page_id_shop=?", array( $value["clients_shops_id"] ));

        $getSliders = getAll( "select * from uni_clients_shops_slider where clients_shops_slider_id_shop=?", [ $value["clients_shops_id"] ] );

        if( count($getSliders) ){
           foreach ($getSliders as $slide) {
             @unlink( $config["basePath"] . "/" . $config["media"]["user_attach"] . "/" . $slide["clients_shops_slider_image"] );
               update("delete from uni_clients_shops_slider where clients_shops_slider_id=?", array( $slide["clients_shops_slider_id"] ));
             }
        }

    }

}

update("delete from uni_clients where clients_id=?", array($idUser));

echo json_encode( ["status"=>true] );

?>