<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$time_now = time();

$getUser = findOne("uni_clients", "clients_id=?", [$idUser]);

$getTariff = apiGetTariff($getUser["clients_tariff_id"]);

if(!$getTariff){ exit; }

if($getTariff['tariff']['services_tariffs_days']){
    $date_completion = date("Y-m-d H:i:s", strtotime("+{$getTariff['tariff']['services_tariffs_days']} days", $time_now));
}else{
    $date_completion = null;
}

if(isset($getTariff['services']["shop"])){
    $getUserShop = findOne("uni_clients_shops", "clients_shops_id_user=?", [$idUser]);
    if(!$getUserShop){

        $insert_id = insert("INSERT INTO uni_clients_shops(clients_shops_id_user,clients_shops_id_hash,clients_shops_time_validity,clients_shops_title)VALUES(?,?,?,?)", [$idUser,md5($idUser),$date_completion, $Profile->name($getUser)]);

        $Admin->notifications("shops");

        echo json_encode( [ "status" => true, "id" => $insert_id ] );

    }else{
        
        echo json_encode( [ "status" => true, "id" => $getUserShop["clients_shops_id"] ] );

    }
}

?>