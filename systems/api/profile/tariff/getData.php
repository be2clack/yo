<?php

$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

$results = [];
$tariffServices = [];
$services = [];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$getUser = $Profile->oneUser("where clients_id=?", [$idUser]);

if(!$getUser){
	http_response_code(500); exit('User not found'); 
}

if($getUser['clients_tariff_id']){
	$getTariffs = getAll("select * from uni_services_tariffs where services_tariffs_status=? and services_tariffs_id!=? order by services_tariffs_position asc", [1,$getUser['clients_tariff_id']]);
}else{
	$getTariffs = getAll("select * from uni_services_tariffs where services_tariffs_status=? order by services_tariffs_position asc", [1]);
}

if($getTariffs){

	if($getUser['clients_tariff_id']){
		$getUserTariff = findOne("uni_services_tariffs", "services_tariffs_id=?", [$getUser['clients_tariff_id']]);
		if($getUserTariff){

		  	$getUserTariff['services_tariffs_services'] = json_decode($getUserTariff['services_tariffs_services'], true);
		    
		    if($getUserTariff['services_tariffs_bonus']){
		   	  $services[] = ["name"=>apiPrice($getUserTariff['services_tariffs_bonus']).' '.apiLangContent("на бонусный счет"), "text"=>null];
		    }

		    if($getUserTariff['services_tariffs_services']){
		       foreach ($getUserTariff['services_tariffs_services'] as $service_id) {
		           $checklist = findOne('uni_services_tariffs_checklist', 'services_tariffs_checklist_id=?', [$service_id]);
		           if($checklist) $services[] = ["name"=>$ULang->tApp($checklist['services_tariffs_checklist_name'], [ "table"=>"uni_services_tariffs_checklist", "field"=>"services_tariffs_checklist_name" ]), "text"=>$ULang->tApp($checklist['services_tariffs_checklist_desc'], [ "table"=>"uni_services_tariffs_checklist", "field"=>"services_tariffs_checklist_desc" ]) ?: null];
		       }
		    }

				$results[] = [
					"id" => $getUserTariff['services_tariffs_id'],
					"name" => $ULang->tApp( $getUserTariff["services_tariffs_name"], [ "table"=>"uni_services_tariffs", "field"=>"services_tariffs_name" ] ),
					"price" => $getUserTariff["services_tariffs_new_price"] ? ['now'=>$getUserTariff["services_tariffs_new_price"], 'old'=>$getUserTariff['services_tariffs_price']] : ['now'=>$getUserTariff["services_tariffs_price"], 'old'=>null],
					"text" => $ULang->tApp( $getUserTariff['services_tariffs_desc'], [ "table"=>"uni_services_tariffs", "field"=>"services_tariffs_desc" ] ),
					"days" => $getUserTariff['services_tariffs_days'] . ' ' . ending($getUserTariff['services_tariffs_days'],apiLangContent('день'),apiLangContent('дня'),apiLangContent('дней')),
					"free" => $getUserTariff["services_tariffs_price"] ? false : true,
					"services" => $services ?: null,
				];
			
		}
	}

  foreach ($getTariffs as $value) {

  	$services = [];

  	$tariffServices = json_decode($value['services_tariffs_services'], true);
    
    if($value['services_tariffs_bonus']){
   	  $services[] = ["name"=>apiPrice($value['services_tariffs_bonus']).' '.apiLangContent("на бонусный счет"), "text"=>null];
    }

    if($tariffServices){
       foreach ($tariffServices as $service_id) {
           $checklist = findOne('uni_services_tariffs_checklist', 'services_tariffs_checklist_id=?', [$service_id]);
           if($checklist) $services[] = ["name"=>$ULang->tApp($checklist['services_tariffs_checklist_name'], [ "table"=>"uni_services_tariffs_checklist", "field"=>"services_tariffs_checklist_name" ]), "text"=>$ULang->tApp($checklist['services_tariffs_checklist_desc'], [ "table"=>"uni_services_tariffs_checklist", "field"=>"services_tariffs_checklist_desc" ]) ?: null];
       }
    }

		$results[] = [
			"id" => $value['services_tariffs_id'],
			"name" => $ULang->tApp( $value["services_tariffs_name"], [ "table"=>"uni_services_tariffs", "field"=>"services_tariffs_name" ] ),
			"price" => $value["services_tariffs_new_price"] ? ['now'=>$value["services_tariffs_new_price"], 'old'=>$value['services_tariffs_price']] : ['now'=>$value["services_tariffs_price"], 'old'=>null],
			"text" => $ULang->tApp( $value['services_tariffs_desc'], [ "table"=>"uni_services_tariffs", "field"=>"services_tariffs_desc" ] ),
			"days" => $value['services_tariffs_days'] . ' ' . ending($value['services_tariffs_days'],apiLangContent('день'),apiLangContent('дня'),apiLangContent('дней')),
			"free" => $value["services_tariffs_price"] ? false : true,
			"services" => $services ?: null,
		];  	

  }
}

echo json_encode($results);

?>