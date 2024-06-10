<?php

$query = urlencode(trim(clearSearch($_POST["query"])));
$city_id = (int)$_POST["city_id"];
$results = [];
$concat = [];

if($query){

	if($settings["map_vendor"] == "yandex"){

		$curl=curl_init('https://geocode-maps.yandex.ru/1.x/?apikey='.$settings["map_yandex_key"].'&format=json&geocode='.$query);

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
					  if($item["kind"] == "street"){
					  	$concat[] = $item["name"];
					  }
					  if($item["kind"] == "house"){
					  	$concat[] = $item["name"];
					  }							  
					  if($item["kind"] == "area"){
					  	$concat[] = $item["name"];
					  }	
					  if($item["kind"] == "province"){
					  	$concat[] = $item["name"];
					  }	
					  if($item["kind"] == "locality"){
					  	$concat[] = $item["name"];
					  }								  
					  if($item["kind"] == "other"){
					  	$concat[] = $item["name"];
					  }							  						  						  
					}

					$coordinates = explode(' ', $value['GeoObject']['Point']['pos']);

					if($concat && $coordinates) $results[implode(', ',$concat)] = '<div class="item-city" data-lat="'.$coordinates[1].'" data-lon="'.$coordinates[0].'" >'.implode(', ',$concat).'</div>';											
				}
			}

			if($results){
				echo implode('',$results);
			}
			
		}

	}else{

		$curl=curl_init('https://nominatim.openstreetmap.org/search?q='.$query.'&format=json&polygon=1&addressdetails=1&accept-language=ru');

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

			foreach ($results_decode as $value) { 
				if(isset($value["address"])){	
					if(isset($value["address"]["country_code"])) unset($value["address"]["country_code"]);
					if(isset($value["address"]["country"])) unset($value["address"]["country"]);
					if(isset($value["address"]["postcode"])) unset($value["address"]["postcode"]);
					if(isset($value["address"]["region"]))  unset($value["address"]["region"]);
					if(isset($value["address"]["state"])) unset($value["address"]["state"]);
					if(isset($value["address"]["city"])) unset($value["address"]["city"]);
					if(isset($value["address"]["region"])) unset($value["address"]["region"]);
					if(isset($value["address"]["ISO3166-2-lvl3"])) unset($value["address"]["ISO3166-2-lvl3"]);
					if(isset($value["address"]["ISO3166-2-lvl4"])) unset($value["address"]["ISO3166-2-lvl4"]);
					if($value["address"]) $results[implode(', ',$value["address"])] = '<div class="item-city" data-lat="'.$value['lat'].'" data-lon="'.$value['lon'].'" >'.implode(', ',$value["address"]).'</div>';											
				}
			}

			if($results){
				echo implode('',$results);
			}
			
		}

	}

}

?>