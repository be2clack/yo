<?php

$data = [];

$coorTopLeft = clear($_POST["coorTopLeft"]);
$coorTopRight = clear($_POST["coorTopRight"]);
$coorBottomLeft = clear($_POST["coorBottomLeft"]);
$coorBottomRight = clear($_POST["coorBottomRight"]);

if($_POST['search']){

	$query = clearSearchBack($_POST["search"]);

	if($coorTopLeft && $coorTopRight && $coorBottomLeft && $coorBottomRight){
		$geoQuery = "((ads_map_lat < '$coorTopLeft' and ads_map_lon < '$coorTopRight') and (ads_map_lat > '$coorBottomLeft' and ads_map_lon > '$coorBottomRight'))";
	}else{
	    $geoQuery = $Ads->queryGeo();
	    $geoQuery = $geoQuery ? ' and ' . $geoQuery : '';
	}

    $result = $Ads->getAll(array("query"=>"ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() $geoQuery and " . $Filters->explodeSearch($query), "navigation"=>true, "page"=>$_POST['page'], "output"=>2000));

}else{

	$result = $Filters->queryFilter($_POST, ["navigation"=>true, "page"=>$_POST['page'], "output"=>2000]);

}

if($result["count"]){
	foreach ($result["all"] as $key => $value) {

		$latitude = $value["ads_latitude"] ? $value["ads_latitude"] : $value["ads_map_lat"];
		$longitude = $value["ads_longitude"] ? $value["ads_longitude"] : $value["ads_map_lon"];

	    if($settings["map_vendor"] == "yandex"){

			$data["type"] = "FeatureCollection";
			$data["features"][] = [
				"id"=>$value["ads_id"],
				"type"=>"Feature",
				"geometry"=>[
					"type"=>"Point",
					"coordinates"=>[$latitude,$longitude],
					"properties"=>[
						"id"=>$value["ads_id"],
					],
					"options"=>["preset"=>"islands#redDotIcon"]
				]
			];

	    }elseif($settings["map_vendor"] == "google"){

	    	$data["type"] = "FeatureCollection";
			$data["features"][] = [
				"id"=>$value["ads_id"],
				"type"=>"Feature",
				"geometry"=>[
					"type"=>"Point",
					"coordinates"=>[$latitude,$longitude],
					"properties"=>[
						"id"=>$value["ads_id"],
					],
				]
			];

	    }elseif($settings["map_vendor"] == "openstreetmap"){

			$data["type"] = "FeatureCollection";
			$data["features"][] = [
				"id"=>$value["ads_id"],
				"type"=>"Feature",
				"geometry"=>[
					"type"=>"Point",
					"coordinates"=>[$longitude,$latitude],
					"properties"=>[
						"id"=>$value["ads_id"],
					],
				]
			];

	    }


	}
}

$data["total"] = $result["count"];
$data["pages"] = getCountPage($result["count"],2000);

echo json_encode($data);

?>