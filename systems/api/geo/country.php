<?php

$results = [];

$getCountry = getAll('select * from uni_country where country_status=? order by country_name desc', [1]);

if($getCountry){
	foreach ($getCountry as $value) {

		$latlngList = [];
		$citiesList = [];
		
		if(!$settings["region_id"]){

			$getCity = getAll("SELECT * FROM uni_city INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id WHERE `uni_city`.city_default = ? and `uni_city`.country_id=? order by city_count_view desc", [1,$value["country_id"]]);

			if($getCity){
				foreach ($getCity as $data) {
		         	$lat = $data["city_lat"] ?: 0;
		         	$lng = $data["city_lng"] ?: 0;			
					$citiesList[] = ['geo_name'=>$ULang->tApp($data["city_name"], [ "table" => "geo", "field" => "geo_name"]), 'city_name'=>$ULang->tApp($data["city_name"], [ "table" => "geo", "field" => "geo_name"]), 'region_name'=>$ULang->tApp($data["region_name"], [ "table" => "geo", "field" => "geo_name"]), 'country_name'=>$ULang->tApp($value["country_name"], [ "table" => "geo", "field" => "geo_name"]), 'country_id'=>$data["country_id"], 'city_id'=>$data["city_id"], 'region_id'=>$data["region_id"], 'lat'=>$lat,'lon'=>$lng, 'declination'=>$ULang->tApp($data["city_declination"], [ "table" => "geo", "field" => "geo_name"])];
				}
			}else{
				$getCity = getAll("SELECT * FROM uni_city INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id WHERE `uni_city`.country_id=? order by city_count_view desc limit 10", [$value["country_id"]]);
				foreach ($getCity as $data) {
		         	$lat = $data["city_lat"] ?: 0;
		         	$lng = $data["city_lng"] ?: 0;			
					$citiesList[] = ['geo_name'=>$ULang->tApp($data["city_name"], [ "table" => "geo", "field" => "geo_name"]), 'city_name'=>$ULang->tApp($data["city_name"], [ "table" => "geo", "field" => "geo_name"]), 'region_name'=>$ULang->tApp($data["region_name"], [ "table" => "geo", "field" => "geo_name"]), 'country_name'=>$ULang->tApp($value["country_name"], [ "table" => "geo", "field" => "geo_name"]), 'country_id'=>$data["country_id"], 'city_id'=>$data["city_id"], 'region_id'=>$data["region_id"], 'lat'=>$lat,'lon'=>$lng, 'declination'=>$ULang->tApp($data["city_declination"], [ "table" => "geo", "field" => "geo_name"])];
				}			
			}

			$getTopRightLatLng = getOne('select city_lat,city_lng from uni_city where country_id=? and city_lat is not null and city_lng is not null and city_lat !=0 and city_lng !=0 order by city_lat desc, city_lng desc limit 1', [$value["country_id"]]);
			$getBottomLeftLatLng = getOne('select city_lat,city_lng from uni_city where country_id=? and city_lat is not null and city_lng is not null and city_lat !=0 and city_lng !=0 order by city_lat asc, city_lng asc limit 1', [$value["country_id"]]);

		}else{

			$getCity = getAll("SELECT * FROM uni_city INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id WHERE `uni_city`.country_id=? and `uni_city`.region_id=? order by city_count_view desc", [$value["country_id"],$settings["region_id"]]);

			if($getCity){
				foreach ($getCity as $data) {
		         	$lat = $data["city_lat"] ?: 0;
		         	$lng = $data["city_lng"] ?: 0;			
					$citiesList[] = ['geo_name'=>$ULang->tApp($data["city_name"], [ "table" => "geo", "field" => "geo_name"]), 'city_name'=>$ULang->tApp($data["city_name"], [ "table" => "geo", "field" => "geo_name"]), 'region_name'=>$ULang->tApp($data["region_name"], [ "table" => "geo", "field" => "geo_name"]), 'country_name'=>$ULang->tApp($value["country_name"], [ "table" => "geo", "field" => "geo_name"]), 'country_id'=>$data["country_id"], 'city_id'=>$data["city_id"], 'region_id'=>$data["region_id"], 'lat'=>$lat,'lon'=>$lng, 'declination'=>$ULang->tApp($data["city_declination"], [ "table" => "geo", "field" => "geo_name"])];
				}
			}else{
				$getCity = getAll("SELECT * FROM uni_city INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id WHERE `uni_city`.country_id=? and `uni_city`.region_id=? order by city_count_view desc limit 10", [$value["country_id"],$settings["region_id"]]);
				foreach ($getCity as $data) {
		         	$lat = $data["city_lat"] ?: 0;
		         	$lng = $data["city_lng"] ?: 0;			
					$citiesList[] = ['geo_name'=>$ULang->tApp($data["city_name"], [ "table" => "geo", "field" => "geo_name"]), 'city_name'=>$ULang->tApp($data["city_name"], [ "table" => "geo", "field" => "geo_name"]), 'region_name'=>$ULang->tApp($data["region_name"], [ "table" => "geo", "field" => "geo_name"]), 'country_name'=>$ULang->tApp($value["country_name"], [ "table" => "geo", "field" => "geo_name"]), 'country_id'=>$data["country_id"], 'city_id'=>$data["city_id"], 'region_id'=>$data["region_id"], 'lat'=>$lat,'lon'=>$lng, 'declination'=>$ULang->tApp($data["city_declination"], [ "table" => "geo", "field" => "geo_name"])];
				}			
			}

			$getTopRightLatLng = getOne('select city_lat,city_lng from uni_city where region_id=? and country_id=? and city_lat is not null and city_lng is not null and city_lat !=0 and city_lng !=0 order by city_lat desc, city_lng desc limit 1', [$settings["region_id"],$value["country_id"]]);
			$getBottomLeftLatLng = getOne('select city_lat,city_lng from uni_city where region_id=? and country_id=? and city_lat is not null and city_lng is not null and city_lat !=0 and city_lng !=0 order by city_lat asc, city_lng asc limit 1', [$settings["region_id"],$value["country_id"]]);

		}

		if($getTopRightLatLng && $getBottomLeftLatLng){
			$latlngList = [['city_lat'=>$getTopRightLatLng['city_lat'],'city_lng'=>$getTopRightLatLng['city_lng']],['city_lat'=>$getBottomLeftLatLng['city_lat'],'city_lng'=>$getBottomLeftLatLng['city_lng']]];
		}

		$results['country'][] = ['id'=>$value['country_id'], 'name'=>$ULang->tApp($value['country_name'], [ "table" => "geo", "field" => "geo_name"]), 'default'=>$value['country_id'] == $settings["country_id"] ? true : false, 'cities'=>$citiesList, 'latlng'=>$latlngList ?: null, 'declination'=>$ULang->tApp($value["country_declination"], [ "table" => "geo", "field" => "geo_name"])];
	}
}

if(!$settings["region_id"]){
	$getCityDefaultCountry = getAll("SELECT * FROM uni_city INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id WHERE `uni_city`.city_default = ? and `uni_country`.country_status = ? and `uni_country`.country_alias=? order by city_count_view desc", [1,1,$settings["country_default"]]);
}else{
	$getCityDefaultCountry = getAll("SELECT * FROM uni_city INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id WHERE `uni_country`.country_status = ? and `uni_country`.country_alias=? and `uni_region`.region_id = ? order by city_count_view desc", [1,$settings["country_default"],$settings["region_id"]]);
}

if($getCityDefaultCountry){
	foreach ($getCityDefaultCountry as $data) {
	 	$lat = $data["city_lat"] ?: 0;
	 	$lng = $data["city_lng"] ?: 0;	 
	 	$results['cities_default'][] = ['geo_name'=>$ULang->tApp($data["city_name"], [ "table" => "geo", "field" => "geo_name"]), 'city_name'=>$ULang->tApp($data["city_name"], [ "table" => "geo", "field" => "geo_name"]), 'region_name'=>$ULang->tApp($data["region_name"], [ "table" => "geo", "field" => "geo_name"]), 'country_name'=>$ULang->tApp($data["country_name"], [ "table" => "geo", "field" => "geo_name"]), 'country_id'=>$data["country_id"], 'city_id'=>$data["city_id"], 'region_id'=>$data["region_id"], 'lat'=>$lat,'lon'=>$lng, 'declination'=>$ULang->tApp($data["city_declination"], [ "table" => "geo", "field" => "geo_name"])];		
	}
}else{
	if(!$settings["region_id"]){
		$getCityCountry = getAll("SELECT * FROM uni_city INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id WHERE `uni_country`.country_status = ? and `uni_country`.country_alias=? order by city_count_view desc limit 30", [1,$settings["country_default"]]);
	}else{
		$getCityCountry = getAll("SELECT * FROM uni_city INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id WHERE `uni_country`.country_status = ? and `uni_country`.country_alias=? and `uni_region`.region_id = ? order by city_count_view desc limit 30", [1,$settings["country_default"],$settings["region_id"]]);
	}
	foreach ($getCityCountry as $data) {
	 	$lat = $data["city_lat"] ?: 0;
	 	$lng = $data["city_lng"] ?: 0;	
	 	$results['cities_default'][] = ['geo_name'=>$ULang->tApp($data["city_name"], [ "table" => "geo", "field" => "geo_name"]), 'city_name'=>$ULang->tApp($data["city_name"], [ "table" => "geo", "field" => "geo_name"]), 'region_name'=>$ULang->tApp($data["region_name"], [ "table" => "geo", "field" => "geo_name"]), 'country_name'=>$ULang->tApp($data["country_name"], [ "table" => "geo", "field" => "geo_name"]), 'country_id'=>$data["country_id"], 'city_id'=>$data["city_id"], 'region_id'=>$data["region_id"], 'lat'=>$lat,'lon'=>$lng, 'declination'=>$ULang->tApp($data["city_declination"], [ "table" => "geo", "field" => "geo_name"])];		
	}	
}

echo json_encode(['country'=>$results['country'] ?: [], 'cities_default'=>$results['cities_default'] ?: null]);
?>