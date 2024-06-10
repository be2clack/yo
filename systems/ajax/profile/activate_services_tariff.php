<?php

$tariff_id = (int)$_POST['tariff_id'];
$time_now = time();
$price_tariff_current = 0;
$add_tariff = true;

$getTariff = $Profile->getTariff($tariff_id);

if(!$getTariff){ exit; }

if($getTariff['tariff']['services_tariffs_days']){
    $date_completion = date("Y-m-d H:i:s", strtotime("+{$getTariff['tariff']['services_tariffs_days']} days", $time_now));
}else{
    $date_completion = null;
}

$getTariffOrder = findOne('uni_services_tariffs_orders', 'services_tariffs_orders_id_user=?', [$_SESSION["profile"]["id"]]);

$price_tariff = $getTariff['tariff']['services_tariffs_new_price'] ?: $getTariff['tariff']['services_tariffs_price'];

if(strtotime($getTariffOrder['services_tariffs_orders_date_completion']) > $time_now && $getTariffOrder){

    if($getTariff['tariff']['services_tariffs_id'] != $getTariffOrder['services_tariffs_orders_id_tariff']){
    
        if($price_tariff > $getTariffOrder['services_tariffs_orders_price']){
            $price_tariff_current = $Profile->calcPriceTariff($getTariff,$getTariffOrder);
        }else{
            exit(json_encode(["status"=>false, "answer" => $ULang->t("Перейти на тариф ниже можно только по истечению существующего тарифа!")]));
        }

    }else{
        $add_tariff = false;
    }

}else{

    if($getTariff['tariff']['services_tariffs_onetime']){
        $getOnetime = findOne('uni_services_tariffs_onetime', 'services_tariffs_onetime_user_id=? and services_tariffs_onetime_tariff_id=?', [$_SESSION["profile"]["id"],$tariff_id]);
        if($getOnetime){
            exit(json_encode(["status"=>false, "answer" => $ULang->t("Данный тариф можно подключить только 1 раз!")]));
        }        
    }

    $price_tariff_current = $price_tariff;

}

$total = $price_tariff_current;

if($total){
    if($_SESSION["profile"]["data"]["clients_balance"] >= $total){

    if($price_tariff_current){
        $title = "Подключение тарифа - {$getTariff['tariff']['services_tariffs_name']}";
        $Main->addOrder( ["price"=>$price_tariff_current,"title"=>$title,"id_user"=>$_SESSION["profile"]["id"],"status_pay"=>1, "user_name" => $_SESSION["profile"]["data"]["clients_name"], "id_hash_user" => $_SESSION["profile"]["data"]["clients_id_hash"], "action_name" => "services_tariff"] );
        $Profile->actionBalance(array("id_user"=>$_SESSION['profile']['id'],"summa"=>$price_tariff_current,"title"=>$title),"-");
    }

    if($getTariff['tariff']['services_tariffs_bonus']){

        $getBonus = findOne('uni_services_tariffs_bonus', 'services_tariffs_bonus_user_id=? and services_tariffs_bonus_tariff_id=?', [$_SESSION["profile"]["id"],$tariff_id]);
        if(!$getBonus){
            $title = "Бонус за подключение тарифа - {$getTariff['tariff']['services_tariffs_name']}";
            $Main->addOrder( ["price"=>$getTariff['tariff']['services_tariffs_bonus'],"title"=>$title,"id_user"=>$_SESSION["profile"]["id"],"status_pay"=>1, "user_name" => $_SESSION["profile"]["data"]["clients_name"], "id_hash_user" => $_SESSION["profile"]["data"]["clients_id_hash"], "action_name" => "services_tariff_bonus"] );
            $Profile->actionBalance(array("id_user"=>$_SESSION['profile']['id'],"summa"=>$getTariff['tariff']['services_tariffs_bonus'],"title"=>$title),"+");
            insert("INSERT INTO uni_services_tariffs_bonus(services_tariffs_bonus_user_id,services_tariffs_bonus_tariff_id)VALUES(?,?)",[$_SESSION["profile"]["id"],$tariff_id]);
        }

    }

    }else{
    
    exit(json_encode(["status"=>false, "balance" => $Main->price($_SESSION["profile"]["data"]["clients_balance"])]));

    }
}

if($add_tariff){
    if($getTariffOrder['services_tariffs_orders_id']) update('delete from uni_services_tariffs_orders where services_tariffs_orders_id=?', [$getTariffOrder['services_tariffs_orders_id']]);

    insert("INSERT INTO uni_services_tariffs_orders(services_tariffs_orders_id_tariff,services_tariffs_orders_days,services_tariffs_orders_date_activation,services_tariffs_orders_id_user,services_tariffs_orders_date_completion,services_tariffs_orders_price)VALUES(?,?,?,?,?,?)",[$tariff_id,$getTariff['tariff']['services_tariffs_days'],date('Y-m-d H:i:s',$time_now),$_SESSION["profile"]["id"],$date_completion,$price_tariff]);

    if($getTariff['services']['shop']){
        if(!$_SESSION["profile"]['shop']){
            insert("INSERT INTO uni_clients_shops(clients_shops_id_user,clients_shops_id_hash,clients_shops_time_validity,clients_shops_title)VALUES(?,?,?,?)", [$_SESSION["profile"]["id"],md5($_SESSION["profile"]["id"]),$date_completion, $Profile->name($_SESSION["profile"]["data"])]);
            $Admin->notifications("shops");
        }else{
            update("update uni_clients_shops set clients_shops_time_validity=?,clients_shops_status=? where clients_shops_id=?", [$date_completion,1, $_SESSION["profile"]['shop']["clients_shops_id"]]);
        }
    }else{
        if($_SESSION["profile"]['shop']) update("update uni_clients_shops set clients_shops_status=? where clients_shops_id=?", [0, $_SESSION["profile"]['shop']["clients_shops_id"]]);
    }

    if($getTariff['tariff']['services_tariffs_onetime']){
        insert("INSERT INTO uni_services_tariffs_onetime(services_tariffs_onetime_user_id,services_tariffs_onetime_tariff_id)VALUES(?,?)",[$_SESSION["profile"]["id"],$tariff_id]);
    }
    update('update uni_clients set clients_tariff_id=? where clients_id=?', [$tariff_id,$_SESSION["profile"]["id"]]);
}

echo json_encode(["status"=>true, 'redirect' =>_link("user/" . $_SESSION["profile"]["data"]["clients_id_hash"] . "/tariff")]);

?>