<?php
defined('unisitecms') or exit();

$cities = [];

$getAds = $Ads->getAll( array("query"=>"ads_status='1' and clients_status IN(0,1) and ads_period_publication > now()", "sort"=>"group by ads_city_id", "navigation"=>false) );

if($getAds["all"]){
  foreach ($getAds["all"] as $key => $value) {
     
	 if( $value["ads_latitude"] && $value["ads_longitude"] ){
  	 $cities[ $value["ads_city_id"] ]["ads"] = $getAds["all"];
  	 $cities[ $value["ads_city_id"] ]["geo"] = [ "lat" => $value["ads_latitude"], "lon" => $value["ads_longitude"] ];
	 }

  }
}

if( count($cities) ){
	foreach ($cities as $id_city => $nested) {

			foreach ($nested["ads"] as $value) {

			     if( !findOne( "uni_city_distance", "(city_distance_id_city_from=? and city_distance_id_city_to=?) or (city_distance_id_city_to=? and city_distance_id_city_from=?)", [ $id_city, $value["ads_city_id"], $value["ads_city_id"], $id_city ] ) && $id_city != $value["ads_city_id"] && $value["ads_latitude"] && $value["ads_longitude"] ){
			          insert("INSERT INTO uni_city_distance(city_distance_id_city_from,city_distance_id_city_to,city_distance_km)VALUES(?,?,?)", [ $id_city, $value["ads_city_id"], $Main->distance($cities[ $id_city ]["geo"]["lat"],$cities[ $id_city ]["geo"]["lon"],$value["ads_latitude"],$value["ads_longitude"]) ]);
			     }

			}

	}
}

?>