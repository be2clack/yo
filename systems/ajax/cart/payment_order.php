<?php

$errors = [];
$cart = $Cart->getCart();
$from_balance = (int)$_POST["from_balance"];
$data_order = [];

if(!$_SESSION['profile']['id']){
    echo json_encode(['status'=>false, 'auth'=>false]);
    exit;
}

if(count($cart)){

    if($settings["main_type_products"] == 'physical'){

        if(!$_POST['delivery_type']){
            $errors[] = $ULang->t("Выберите способ получения");
        }else{

            if($_POST['delivery_type'] != 'self'){

                if(!$_POST['delivery_id_point']){
                    $errors[] = $ULang->t("Выберите пункт выдачи");
                }

                if(!$_POST['delivery_surname']){
                    $errors[] = $ULang->t("Укажите фамилию");
                }

                if(!$_POST['delivery_name']){
                    $errors[] = $ULang->t("Укажите имя");
                }

                if(!$_POST['delivery_patronymic']){
                    $errors[] = $ULang->t("Укажите отчество");
                }

                if(!$_POST['delivery_phone']){
                    $errors[] = $ULang->t("Укажите номер телефона");
                }else{

                    $validatePhone = validatePhone($_POST['delivery_phone']);
                    if(!$validatePhone['status']){
                    $errors[] = $validatePhone['error'];
                    }

                }

            }

        }

    }

    if(!count($errors)){

        $delivery['type'] = $_POST['delivery_type'];
        $delivery['delivery_surname'] = $_POST['delivery_surname'];
        $delivery['delivery_name'] = $_POST['delivery_name'];
        $delivery['delivery_patronymic'] = $_POST['delivery_patronymic'];
        $delivery['delivery_phone'] = $_POST['delivery_phone'];
        $delivery['delivery_id_point'] = $_POST['delivery_id_point'];

        $orderId = generateOrderId();

        foreach ($cart as $id => $value) {

            if($value['ad']["ads_status"] != 1 || strtotime($value['ad']["ads_period_publication"]) < time()){
                unset($cart[$id]);
            }

        }

        if($from_balance){

            $price = $Cart->calcTotalPrice();

            if($_SESSION['profile']['data']['clients_balance'] >= $price){

               $Profile->actionBalance(array("id_user"=>$_SESSION['profile']['id'],"summa"=>$price,"title"=>$static_msg["11"]." №".$orderId,"id_order"=>$orderId),"-");

                foreach ($cart as $id => $value) {
                  $data_order[$value['ad']['ads_id_user']][] = $value;
                }

                foreach ($data_order as $id_user => $array) {

                     $total_price = 0;
                     $deliveryParams = [];
                     $order_id = generateOrderId();

                     foreach ($array as $value) {

                         $total_price += $value['ad']['ads_price'] * $value['count'];
                         
                         smart_insert('uni_secure_ads', ['secure_ads_ad_id'=>$value['ad']['ads_id'],'secure_ads_count'=>$value['count'],'secure_ads_total'=>$value['ad']['ads_price'] * $value['count'],'secure_ads_order_id'=>$order_id,'secure_ads_user_id'=>$value['ad']['ads_id_user']]);

                         if($settings["main_type_products"] == 'physical'){
                            if(!$value['ad']["ads_available_unlimitedly"]){
                                if($value['ad']["ads_available"]){
                                  update("update uni_ads set ads_available=ads_available-".$value['count']." where ads_id=?", [$value['ad']['ads_id']]);
                                  if(!($value['ad']["ads_available"]-$value['count'])){
                                    update("update uni_ads set ads_status=? where ads_id=?", [5,$value['ad']['ads_id']], true);
                                  }
                                }else{
                                  update("update uni_ads set ads_status=? where ads_id=?", [5,$value['ad']['ads_id']], true);
                                }
                            }
                         }

                         if($Ads->getStatusDelivery($value['ad'])){
                             $deliveryGoods[] = [
                                'id'=>$value['ad']['ads_id'],
                                'title'=>$value['ad']['ads_title'],
                                'cost'=>$value['ad']['ads_price'],
                             ];                                                                       
                         }

                         $Profile->sendChat( array("id_ad" => $value['ad']['ads_id'], "action" => 3, "user_from" => $_SESSION['profile']['id'], "user_to" => $value['ad']["ads_id_user"] ) );

                     }

                     if($deliveryGoods && $delivery["type"] != 'self' && $settings["main_type_products"] == 'physical'){

                         $deliveryResults = $Delivery->createOrder(["delivery"=>$delivery,"amount"=>$total_price, "id_user"=>$id_user, "goods"=>$deliveryGoods]);

                         smart_insert('uni_secure', ['secure_date'=>date("Y-m-d H:i:s"),'secure_id_user_buyer'=>$_SESSION['profile']['id'],'secure_id_user_seller'=>$id_user,'secure_id_order'=>$order_id,'secure_price'=>$total_price,'secure_status'=>$deliveryResults['invoice_number'] ? 2 : 1,'secure_delivery_type'=>$delivery["type"],'secure_delivery_invoice_number'=>$deliveryResults['invoice_number'],'secure_delivery_track_number'=>$deliveryResults['track_number'],'secure_delivery_errors'=>$deliveryResults['errors'],'secure_delivery_name'=>$delivery['delivery_name'],'secure_delivery_surname'=>$delivery['delivery_surname'],'secure_delivery_patronymic'=>$delivery['delivery_patronymic'],'secure_delivery_phone'=>$delivery['delivery_phone'],'secure_delivery_id_point'=>$delivery['delivery_id_point'],'secure_marketplace'=>1,'secure_balance_payment'=>1]);
                         
                     }else{
                        smart_insert('uni_secure', ['secure_date'=>date("Y-m-d H:i:s"),'secure_id_user_buyer'=>$_SESSION['profile']['id'],'secure_id_user_seller'=>$id_user,'secure_id_order'=>$order_id,'secure_price'=>$total_price,'secure_status'=>1,'secure_delivery_type'=>$delivery["type"],'secure_marketplace'=>1,'secure_balance_payment'=>1]);
                     }

                     smart_insert('uni_clients_orders', ['clients_orders_from_user_id'=>$_SESSION['profile']['id'],'clients_orders_uniq_id'=>$order_id,'clients_orders_date'=>date('Y-m-d H:i:s'),'clients_orders_to_user_id'=>$id_user,'clients_orders_secure'=>1,'clients_orders_status'=>1]);
                     
                     $Ads->addSecurePayments( ["id_order"=>$order_id, "amount"=>$total_price, "id_user"=>$_SESSION['profile']['id'], "status_pay"=>1, "status"=>0, "amount_percent"=>$total_price, "from_balance"=>1] );

                     $Admin->notifications("secure");

                }

                echo json_encode(array("status" => true, "redirect" => _link("cart/order?id={$orderId}")));               
               
                $Cache->update("uni_ads");

            }else{
               echo json_encode( array( "status" => false, "balance" => false, "balance_total" => $Main->price($_SESSION['profile']['data']['clients_balance']) ) );
            }

        }else{

            $html = $Profile->payMethod($settings["secure_payment_service_name"] , array("amount" => $Cart->calcTotalPrice(), "id_order" => $orderId, "id_user" => $_SESSION['profile']['id'], "action" => "marketplace", "title" => $static_msg["11"]." №".$orderId, 'cart' => $cart, 'delivery' => $delivery, 'link_success' => _link("cart/order?id={$orderId}")));

            echo json_encode(array("status" => true, "redirect" => $html));

        }

    }else{

        echo json_encode(['status'=>false, 'auth'=>true, 'errors'=>implode("\n", $errors)]);

    }

}else{
    echo json_encode(['status'=>false, 'auth'=>true, 'errors'=>$ULang->t("Корзина пуста")]);
}

?>