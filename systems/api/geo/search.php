<?php

$query = clearSearch($_GET["query"]);
$onlyCity = (int)$_GET['only_city'];

$results = [];

if($query && mb_strlen($query, "UTF-8") >= 2 ){

	if(!$settings["region_id"]){

    	$get = getAll("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id WHERE `uni_country`.country_status = '1' and `uni_city`.city_name LIKE '%".$query."%' order by city_name asc");

	}else{

		$get = getAll("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id WHERE `uni_country`.country_status = '1' and `uni_city`.region_id = '".$settings["region_id"]."' and `uni_city`.city_name LIKE '%".$query."%' order by city_name asc");

	}

    if(!$onlyCity){

	    if(!$get){

	      if(!$settings["region_id"]){

	      	$get = getAll("SELECT * FROM uni_region INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_region`.country_id WHERE `uni_country`.country_status = '1' and `uni_region`.region_name LIKE '%".$query."%' order by region_name asc");

	  	  }else{

	  	  	$get = getAll("SELECT * FROM uni_region INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_region`.country_id WHERE `uni_country`.country_status = '1' and `uni_region`.region_id = '".$settings["region_id"]."' and `uni_region`.region_name LIKE '%".$query."%' order by region_name asc");

	  	  }

	      if(!$get){

	          $get = getAll("SELECT * FROM uni_country WHERE country_status = '1' and country_name LIKE '%".$query."%' order by country_name asc");

	      }

	    }

	    if(count($get)){

	    	foreach ($get as $data) {

	    		 $latlngList = [];

		         if($data["region_name"]){
					$latlngList = getAll('select city_lat,city_lng from uni_city where region_id=? and city_lat is not null and city_lng is not null and city_lat !=0 and city_lng !=0', [$data["region_id"]]);
		       	 	$list["region"][$data["region_name"]] = ['geo_name'=>$ULang->tApp($data["region_name"], [ "table" => "geo", "field" => "geo_name"]),'region_name'=>$ULang->tApp($data["region_name"], [ "table" => "geo", "field" => "geo_name"]),'country_name'=>$ULang->tApp($data["country_name"], [ "table" => "geo", "field" => "geo_name"]), 'country_id'=>0, 'city_id'=>0, 'region_id'=>$data["region_id"], 'latlng'=>$latlngList ?: null, 'declination'=>$ULang->tApp($data["region_declination"], [ "table" => "geo", "field" => "geo_name"])];
		       	 }
		         
		         if($data["city_name"]){
		         	$lat = $data["city_lat"] ?: 0;
		         	$lng = $data["city_lng"] ?: 0;
		         	$list["city"][] = ['geo_name'=>$ULang->tApp($data["city_name"], [ "table" => "geo", "field" => "geo_name"]),'city_name'=>$ULang->tApp($data["city_name"], [ "table" => "geo", "field" => "geo_name"]),'region_name'=>$ULang->tApp($data["region_name"], [ "table" => "geo", "field" => "geo_name"]),'country_name'=>$ULang->tApp($data["country_name"], [ "table" => "geo", "field" => "geo_name"]), 'country_id'=>0, 'city_id'=>$data["city_id"], 'region_id'=>0, 'lat'=>$lat,'lon'=>$lng, 'declination'=>$ULang->tApp($data["city_declination"], [ "table" => "geo", "field" => "geo_name"])];
		       	 }
		       	 
		       	 if($data["country_name"]){
					$getTopRightLatLng = getOne('select city_lat,city_lng from uni_city where country_id=? and city_lat is not null and city_lng is not null and city_lat !=0 and city_lng !=0 order by city_lat desc, city_lng desc limit 1', [$data["country_id"]]);
					$getBottomLeftLatLng = getOne('select city_lat,city_lng from uni_city where country_id=? and city_lat is not null and city_lng is not null and city_lat !=0 and city_lng !=0 order by city_lat asc, city_lng asc limit 1', [$data["country_id"]]);		       	 		
					if($getTopRightLatLng && $getBottomLeftLatLng){
						$latlngList = [['city_lat'=>$getTopRightLatLng['city_lat'],'city_lng'=>$getTopRightLatLng['city_lng']],['city_lat'=>$getBottomLeftLatLng['city_lat'],'city_lng'=>$getBottomLeftLatLng['city_lng']]];
					}		         	       	 	
		       	 	$list["country"][$data["country_name"]] = ['geo_name'=>$ULang->tApp($data["country_name"], [ "table" => "geo", "field" => "geo_name"]),'country_name'=>$ULang->tApp($data["country_name"], [ "table" => "geo", "field" => "geo_name"]), 'country_id'=>$data["country_id"], 'city_id'=>0, 'region_id'=>0, 'latlng'=>$latlngList ?: null, 'declination'=>$ULang->tApp($data["country_declination"], [ "table" => "geo", "field" => "geo_name"])];
		       	 }

	    	}

			foreach ($list as $key => $nested) {

				foreach ($nested as $value) {
					$results[] = $value;
				}

			}    

	    }

  	}else{

	    if(count($get)){

	    	foreach ($get as $data) {
		         
		         if($data["city_name"]){
		         	$lat = $data["city_lat"] ?: 0;
		         	$lng = $data["city_lng"] ?: 0;	         	
		         	$list["city"][] = ['geo_name'=>$ULang->tApp($data["city_name"], [ "table" => "geo", "field" => "geo_name"]),'city_name'=>$ULang->tApp($data["city_name"], [ "table" => "geo", "field" => "geo_name"]),'region_name'=>$ULang->tApp($data["region_name"], [ "table" => "geo", "field" => "geo_name"]),'country_name'=>$ULang->tApp($data["country_name"], [ "table" => "geo", "field" => "geo_name"]), 'country_id'=>$data["country_id"], 'city_id'=>$data["city_id"], 'region_id'=>$data["region_id"], 'lat'=>$lat,'lon'=>$lng, 'declination'=>$ULang->tApp($data["city_declination"], [ "table" => "geo", "field" => "geo_name"])];
		       	 }
		       	 
	    	}

			foreach ($list as $key => $nested) {

				foreach ($nested as $value) {
					$results[] = $value;
				}

			}    

	    }

  	}

    echo json_encode($results);	
	
}else{

	if(!$settings["region_id"]){
		$get = getAll("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id WHERE `uni_city`.city_default = '1' and `uni_country`.country_status = '1' order by city_count_view desc");
	}else{
		$get = getAll("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id WHERE `uni_city`.city_default = '1' and `uni_country`.country_status = '1' and `uni_city`.region_id='".$settings["region_id"]."' order by city_count_view desc");
	}

	if(count($get)){
		foreach ($get as $data) {
         	$lat = $data["city_lat"] ?: 0;
         	$lng = $data["city_lng"] ?: 0;			
			$results[] = ['geo_name'=>$ULang->tApp($data["city_name"], [ "table" => "geo", "field" => "geo_name"]), 'city_name'=>$ULang->tApp($data["city_name"], [ "table" => "geo", "field" => "geo_name"]), 'region_name'=>$ULang->tApp($data["region_name"], [ "table" => "geo", "field" => "geo_name"]), 'country_name'=>$ULang->tApp($data["country_name"], [ "table" => "geo", "field" => "geo_name"]), 'country_id'=>$data["country_id"], 'city_id'=>$data["city_id"], 'region_id'=>$data["region_id"], 'lat'=>$lat,'lon'=>$lng, 'declination'=>$ULang->tApp($data["city_declination"], [ "table" => "geo", "field" => "geo_name"])];
		}
	}

	echo json_encode($results);

}

?>