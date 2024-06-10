<?php

class Delivery{
    
    function createOrder($deliveryParams=[]){

       global $settings;

       $params = [];

       if($deliveryParams["delivery"]["type"] == "boxberry"){

          $params['goods'] = $deliveryParams["goods"];

          $params["public_price"] = $deliveryParams["amount"];

          $getUser = findOne('uni_clients', 'clients_id=?', [$deliveryParams["id_user"]]);

          $params['sender'] = [
             'client_name'=>$getUser['clients_name'].' '.$getUser['clients_surname'].' '.$getUser["clients_patronymic"],
             'phone'=>$getUser['clients_phone'],
             'city'=>$getUser['clients_delivery_id_city'],
          ];

          $getDeliveryPoint = findOne("uni_boxberry_points","boxberry_points_code=?", [$deliveryParams["delivery"]["delivery_id_point"]]);

          $params['receiver'] = [
             'client_name'=>$deliveryParams["delivery"]["delivery_surname"].' '.$deliveryParams["delivery"]["delivery_name"].' '.$deliveryParams["delivery"]["delivery_patronymic"],
             'phone'=>$deliveryParams["delivery"]["delivery_phone"],
             'city'=>$getDeliveryPoint["boxberry_points_city_code"],
             'point_code'=>$deliveryParams["delivery"]["delivery_id_point"],
          ];


          $params['api_token'] = decrypt($settings['delivery_api_key']);
          $params['method'] = 'NewOrder';
          $params['delivery_type'] = '2';
          $params['payer_type'] = '2';

          $getInvoice = json_decode(file_get_contents('https://lk.boxberry.ru/lap.json/?'.http_build_query($params)), true);
          if(!$getInvoice['err']){
             return ['invoice_number'=>$getInvoice['data']['tracking'], 'track_number'=>$getInvoice['data']['tracking']];
          }else{
             return ['errors'=>$getInvoice['err']];
          }

       }

    }
     
}


?>