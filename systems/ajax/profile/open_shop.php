<?php

if(isset($_SESSION["profile"]["tariff"])){

    $getTariff = $Profile->getTariff($_SESSION["profile"]["tariff"]["services_tariffs_id"]);

    if($getTariff['tariff']['services_tariffs_days']){
        $date_completion = date("Y-m-d H:i:s", strtotime("+{$getTariff['tariff']['services_tariffs_days']} days", time()));
    }else{
        $date_completion = null;
    }

    if(isset($_SESSION["profile"]["tariff"]["services"]["shop"])){
        $getUserShop = findOne("uni_clients_shops", "clients_shops_id_user=?", [$_SESSION["profile"]["id"]]);
        if(!$getUserShop){

            insert("INSERT INTO uni_clients_shops(clients_shops_id_user,clients_shops_id_hash,clients_shops_time_validity,clients_shops_title)VALUES(?,?,?,?)", [$_SESSION["profile"]["id"],md5($_SESSION["profile"]["id"]),$date_completion, $Profile->name($_SESSION["profile"]["data"])]);

            $Admin->notifications("shops");

        }
    }

}

echo json_encode(["status"=>true]);

?>