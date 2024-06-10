<?php 

$alias = clear($_GET["alias"]);

if($alias){

	$data = getOne("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id WHERE `uni_country`.country_status = '1' and `uni_city`.city_alias='".translite($alias)."'");

	if($data){

	   echo json_encode(["data"=>["name"=>$data["city_name"], "id"=>$data["city_id"], "table"=>"city", "declination"=>$data["city_declination"]?:null]]);

	}else{

	   $data = getOne("SELECT * FROM uni_region INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_region`.country_id WHERE `uni_country`.country_status = '1' and `uni_region`.region_alias='".translite($alias)."'");

		if($data){

		   echo json_encode(["data"=>["name"=>$data["region_name"], "id"=>$data["region_id"], "table"=>"region", "declination"=>$data["region_declination"]?:null]]);

		}else{

		 	$data = getOne("SELECT * FROM uni_country WHERE country_status = '1' and country_alias='".translite($alias)."'"); 

		 	if($data){
		 		echo json_encode(["data"=>["name"=>$data["country_name"], "id"=>$data["country_id"], "table"=>"country", "declination"=>$data["country_declination"]?:null]]);
		 	}else{
		 		echo json_encode(["data"=>null]);
		 	}

		 
		}

	}

}else{

	echo json_encode(["data"=>null]);

}

?>