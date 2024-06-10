<?php
defined('unisitecms') or exit();

$getOrdersTariffs = getAll("select * from uni_services_tariffs_orders where date(services_tariffs_orders_date_completion) = date(now()) and services_tariffs_orders_price!=?",[0]);

if( count($getOrdersTariffs) ){
   foreach ($getOrdersTariffs as $key => $value) {

   	  $time_now = time();

   	  $getTariff = $Profile->getTariff($value['services_tariffs_orders_id_tariff']);

   	  $price_tariff = $getTariff['tariff']['services_tariffs_new_price'] ?: $getTariff['tariff']['services_tariffs_price'];

   	  $date_completion = date("Y-m-d H:i:s", strtotime("+{$getTariff['tariff']['services_tariffs_days']} days", $time_now));

   	  $getUser = findOne('uni_clients', 'clients_id=?', [$value['services_tariffs_orders_id_user']]);

   	  if($getUser['clients_tariff_autorenewal'] && $getUser && !$getTariff['tariff']['services_tariffs_onetime']){

          $data = array("{TARIFF_NAME}"=>$getTariff['tariff']['services_tariffs_name'],
                       "{USER_NAME}"=>$getUser["clients_name"],
                       "{TARIFF_PRICE}"=>$Main->price($price_tariff),
                       "{PROFILE_LINK}"=>_link("user/" . $getUser["clients_id_hash"]),
                       "{TARIFF_DAYS}"=>$getTariff['tariff']['services_tariffs_days'].' '.ending($getTariff['tariff']['services_tariffs_days'],'день','дня','дней'),
                       "{UNSUBCRIBE}"=>"",
                       "{EMAIL_TO}"=>$getUser["clients_email"]
                       );

          if($getUser["clients_balance"] >= $price_tariff){

                update('delete from uni_services_tariffs_orders where services_tariffs_orders_id=?', [$value['services_tariffs_orders_id']]);

                insert("INSERT INTO uni_services_tariffs_orders(services_tariffs_orders_id_tariff,services_tariffs_orders_days,services_tariffs_orders_date_activation,services_tariffs_orders_id_user,services_tariffs_orders_date_completion,services_tariffs_orders_price)VALUES(?,?,?,?,?,?)",[$value['services_tariffs_orders_id_tariff'],$getTariff['tariff']['services_tariffs_days'],date('Y-m-d H:i:s',$time_now),$value['services_tariffs_orders_id_user'],$date_completion,$price_tariff]);

	            $getShop = findOne("uni_clients_shops", "clients_shops_id_user=?", [$value['services_tariffs_orders_id_user']]);
	            if($getShop){
	                update("update uni_clients_shops set clients_shops_time_validity=?,clients_shops_status=? where clients_shops_id=?", [$date_completion,1, $getShop["clients_shops_id"]]);
	            }

                $title = "Подключение тарифа - {$getTariff['tariff']['services_tariffs_name']}";
                $Main->addOrder( ["price"=>$price_tariff,"title"=>$title,"id_user" => $value['services_tariffs_orders_id_user'],"status_pay"=>1, "user_name" => $getUser["clients_name"], "id_hash_user" => $getUser["clients_id_hash"], "action_name" => "services_tariff"] );
                $Profile->actionBalance(array("id_user"=>$value['services_tariffs_orders_id_user'],"summa"=>$price_tariff,"title"=>$title),"-");

                email_notification( array( "variable" => $data, "code" => "TARIFF_SUCCESS_EXTENDED" ) );

          }else{
          	    if(!$value['services_tariffs_orders_notification_pay']){
          	    	update('update uni_services_tariffs_orders set services_tariffs_orders_notification_pay=? where services_tariffs_orders_id=?', [1,$value['services_tariffs_orders_id']]);
					email_notification( array( "variable" => $data, "code" => "TARIFF_EXTENDED_NO_BALANCE" ) );
			    }
          }

   	  }

   }
}


if(time_of_day() == 'day' || time_of_day() == 'evening'){
	$getOrdersTariffs = getAll("select * from uni_services_tariffs_orders where date(services_tariffs_orders_date_completion) = ? and services_tariffs_orders_price!=? and services_tariffs_orders_notification=?",[date("Y-m-d", strtotime("+1 days", time())),0,0]);

	if( count($getOrdersTariffs) ){
	   foreach ($getOrdersTariffs as $key => $value) {

	   	  $getUser = findOne('uni_clients', 'clients_id=?', [$value['services_tariffs_orders_id_user']]);

	      $getTariff = $Profile->getTariff($value['services_tariffs_orders_id_tariff']);
	       
	      if($getTariff['tariff'] && $getUser && !$getTariff['tariff']['services_tariffs_onetime']){
	          
	          if($getUser){
	              $data = array("{TARIFF_NAME}"=>$getTariff['tariff']['services_tariffs_name'],
	                           "{USER_NAME}"=>$getUser["clients_name"],
	                           "{PROFILE_LINK}"=>_link("user/" . $getUser["clients_id_hash"]),
	                           "{UNSUBCRIBE}"=>"",
	                           "{EMAIL_TO}"=>$getUser["clients_email"]
	                           );
	        
	              email_notification( array( "variable" => $data, "code" => "TOMORROW_END_TARIFF" ) );
	          }

	      }

	      update('update uni_services_tariffs_orders set services_tariffs_orders_notification=? where services_tariffs_orders_id=?', [1,$value['services_tariffs_orders_id']]);

	   }
	}
}

?>