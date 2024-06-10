<?php

$lat = clear($_GET["lat"]);
$lng = clear($_GET["lng"]);

$results = [];
$address = [];

if($settings["map_vendor"] == "yandex"){

	$curl=curl_init('https://geocode-maps.yandex.ru/1.x/?apikey='.$settings["map_yandex_key"].'&format=json&results=1&geocode='.urlencode($lng.' '.$lat));

	curl_setopt_array($curl,array(
			CURLOPT_USERAGENT=>'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:60.0) Gecko/20100101 Firefox/60.0',
			CURLOPT_ENCODING=>'gzip, deflate',
			CURLOPT_RETURNTRANSFER=>1,
			CURLOPT_HTTPHEADER=>array(
					'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
					'Accept-Language: en-US,en;q=0.5',
					'Accept-Encoding: gzip, deflate',
					'Connection: keep-alive',
					'Upgrade-Insecure-Requests: 1',
			),
	));

	$results_decode = json_decode(curl_exec($curl), true);

	if($results_decode){

		foreach ($results_decode['response']['GeoObjectCollection']['featureMember'] as $value) { 

			$concat = [];
			$data = $value['GeoObject']['metaDataProperty']['GeocoderMetaData']['Address']['Components'];

			if(isset($data)){	

				foreach ($data as $item) {
				  if($item["kind"] == "locality"){
				  	$concat["locality"] = $item["name"];
				  }						
				  if($item["kind"] == "street"){
				  	$concat["street"] = $item["name"];
				  }
				  if($item["kind"] == "house"){
				  	$concat["house"] = $item["name"];
				  }	
				  if($item["kind"] == "district"){
				  	$concat["district"] = $item["name"];
				  }							  		  						  						  						  
				}

				if($concat["street"]){

					if($concat["street"]){
						$address[] = $concat["street"];
					}
					if($concat["house"]){
						$address[] = $concat["house"];
					}
					if($concat["locality"]){
						$address[] = $concat["locality"];
					}

				}elseif($concat["district"]){

					if($concat["district"]){
						$address[] = $concat["district"];
					}
					if($concat["house"]){
						$address[] = $concat["house"];
					}
					if($concat["locality"]){
						$address[] = $concat["locality"];
					}

				}elseif($concat["locality"]){

					if($concat["locality"]){
						$address[] = $concat["locality"];
					}
					if($concat["house"]){
						$address[] = $concat["house"];
					}

				}

				echo json_encode(["address"=>$address ? implode(", ", $address) : null]);

			}

			break;

		}
		
	}

}elseif($settings["map_vendor"] == "google"){

    $curl=curl_init('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$lng.'&sensor=false&key='.$settings["map_google_key"]);

    curl_setopt_array($curl,array(
            CURLOPT_USERAGENT=>'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:60.0) Gecko/20100101 Firefox/60.0',
            CURLOPT_ENCODING=>'gzip, deflate',
            CURLOPT_RETURNTRANSFER=>1,
            CURLOPT_HTTPHEADER=>array(
                    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language: en-US,en;q=0.5',
                    'Accept-Encoding: gzip, deflate',
                    'Connection: keep-alive',
                    'Upgrade-Insecure-Requests: 1',
            ),
    ));

    $results_decode = json_decode(curl_exec($curl), true);

    echo json_encode(["address"=>$results_decode['results'][0]['formatted_address'] ?: null]);

}else{

	$curl=curl_init("https://nominatim.openstreetmap.org/reverse?format=json&lat=".$lat."&lon=".$lng."&addressdetails=1&accept-language=ru");

	curl_setopt_array($curl,array(
			CURLOPT_USERAGENT=>'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:60.0) Gecko/20100101 Firefox/60.0',
			CURLOPT_ENCODING=>'gzip, deflate',
			CURLOPT_RETURNTRANSFER=>1,
			CURLOPT_HTTPHEADER=>array(
					'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
					'Accept-Language: en-US,en;q=0.5',
					'Accept-Encoding: gzip, deflate',
					'Connection: keep-alive',
					'Upgrade-Insecure-Requests: 1',
			),
	));

	$results_decode = json_decode(curl_exec($curl), true);

	if(isset($results_decode["address"]["road"])){
		$address[] = $results_decode["address"]["road"];
	}elseif(isset($results_decode["address"]["village"])){
	    $address[] = $results_decode["address"]["village"];
	}elseif(isset($results_decode["address"]["municipality"])){
	    $address[] = $results_decode["address"]["municipality"];
	}

	if(isset($results_decode["address"]["city"])){
	    $address[] = $results_decode["address"]["city"];
	}elseif(isset($results_decode["address"]["state"])){
	    $address[] = $results_decode["address"]["state"];
	}elseif(isset($results_decode["address"]["town"])){
	    $address[] = $results_decode["address"]["town"];
	}

	echo json_encode(["address"=>$address ? implode(", ", $address) : null]);

}

?>