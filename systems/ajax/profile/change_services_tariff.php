<?php

$tariff_id = (int)$_POST['tariff_id'];
$time_now = time();
$sidebar = true;

$getTariff = $Profile->getTariff($tariff_id);

if(!$getTariff){ exit; }

$price_tariff = $getTariff['tariff']['services_tariffs_new_price'] ?: $getTariff['tariff']['services_tariffs_price'];

if($_SESSION["profile"]["id"]){

$getTariffOrder = findOne('uni_services_tariffs_orders', 'services_tariffs_orders_id_user=?', [$_SESSION["profile"]["id"]]);

if($getTariffOrder){

    if(strtotime($getTariffOrder['services_tariffs_orders_date_completion']) > $time_now){
        
        if($getTariff['tariff']['services_tariffs_id'] != $getTariffOrder['services_tariffs_orders_id_tariff']){

            if($price_tariff > $getTariffOrder['services_tariffs_orders_price']){

                $price_new = $Profile->calcPriceTariff($getTariff,$getTariffOrder);
                $total = $price_new;
                $button = $ULang->t('Доплатить').' '.$Main->price($total);

                exit(json_encode(["status"=>true, 'total' => $Main->price($total), "button" => $button, 'price_tariff' => $Main->price($price_tariff),'sidebar' => $sidebar]));
            }

        }else{

            exit(json_encode(["status" => true, 'total' => 0, "button" => "", 'price_tariff' => $ULang->t('Подключен'),'sidebar' => false]));

        }

    }

}

}

$total = $price_tariff;
$button = $total ? $ULang->t('Оплатить').' '.$Main->price($total) : $ULang->t('Подключить');

echo json_encode(["status"=>true, 'total' => $Main->price($total), "button" => $button, 'price_tariff' => $price_tariff ? $Main->price($price_tariff) : $ULang->t('Бесплатно'),'sidebar' => $sidebar]);

?>