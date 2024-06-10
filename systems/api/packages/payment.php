<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$id = (int)$_POST['id'];
$type = $_POST['type'];
$cat_id = $_POST['cat_id'];
$errors = [];
$time_now = time();

if(!$cat_id){
	$errors[] = apiLangContent("Пожалуйста, выберите категорию");
}

if(!$id){
	$errors[] = apiLangContent("Пожалуйста, выберите пакет");
}

if(!$type){
	$errors[] = apiLangContent("Пожалуйста, выберите способ оплаты");
}

if(!$errors){

	$getPackage = findOne("uni_ads_packages", "id=?", [$id]);

	$getUser = findOne("uni_clients", "clients_id=?", [$idUser]);

	$date_completion = date("Y-m-d H:i:s", strtotime("+{$getPackage["period"]} days", $time_now));

	if($getPackage && $getUser){

		$orderId = generateOrderId();

		$price = $getPackage["price_ad"] * $getPackage["count_ad"];

		if($type == "balance"){

			if( $getUser["clients_balance"] >= $price ){

			  insert("INSERT INTO uni_ads_packages_orders(user_id,package_id,create_date,completion_date,order_id,status_pay,cat_id,count_ad,amount)VALUES(?,?,?,?,?,?,?,?,?)", [$idUser,$id,date('Y-m-d H:i:s',$time_now),$date_completion,$orderId,1,$cat_id,$getPackage["count_ad"],$price]);

			  $Main->addOrder( ["id_package"=>$id,"price"=>$price,"title"=>$static_msg["66"].' '.$getPackage["count_ad"] . ' ' . ending($getPackage["count_ad"], 'размещение', 'размещения', 'размещений'),"id_user"=>$idUser,"status_pay"=>1, "user_name" => $getUser["clients_name"], "id_hash_user" => $getUser["clients_id_hash"], "action_name" => "ad_package"] );
			  
			  $Profile->actionBalance(array("id_user"=>$idUser,"summa"=>$price,"title"=>$static_msg["66"].' '.$getPackage["count_ad"] . ' ' . ending($getPackage["count_ad"], 'размещение', 'размещения', 'размещений'),"id_order"=>$orderId),"-");

			  $Admin->notifications("ad_package");

			  echo json_encode( ["status"=>true, "type" => "balance"] );

			}else{
			  
			  echo json_encode( ["status"=>false, "balance"=> apiPrice($getUser["clients_balance"]) ] );

			}

		}else{

			$answer = $Profile->payMethod( $type, array( "amount" => $price, "name" => $getUser["clients_name"], "email" => $getUser["clients_email"], "phone" => $getUser["clients_phone"], "id_order" => $orderId, "id_user" => $idUser, "action" => "ad_package", "title" => $static_msg["66"].' '.$getPackage["count_ad"] . ' ' . ending($getPackage["count_ad"], 'размещение', 'размещения', 'размещений'), "package_id" => $id, "cat_id" => $cat_id ) );

			echo json_encode( array( "status" => true, "link" => $answer ) );

		}

	}

}else{
	echo json_encode( ["status"=>false, "answer"=> implode("\n", $errors)] );
}

?>