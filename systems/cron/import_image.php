<?php

defined('unisitecms') or exit();

$Elastic = new Elastic();

$getImport = findOne("uni_ads_import", "ads_import_status=? and ads_import_status_images=? order by ads_import_id asc", [2,0]);

if($getImport){

	$errors = [];
    
    if(file_exists($config["basePath"] . "/" . $config["folder_admin"] . "/include/modules/ads_import/errors/".$getImport["ads_import_uniq"].".txt")){
  	  $errors = unserialize(file_get_contents($config["basePath"] . "/" . $config["folder_admin"] . "/include/modules/ads_import/errors/".$getImport["ads_import_uniq"].".txt"));
    }

	$params = json_decode($getImport["ads_import_params"], true);
    
	if($params["count_load_image"] == 1){
		$limit = 150;
	}elseif($params["count_load_image"] == 2){
		$limit = 100;
	}elseif($params["count_load_image"] == 3){
		$limit = 100;
	}else{
		$limit = 50;
	}

	$getAds = getAll("select * from uni_ads where ads_id_import=? and ads_import_images!=? order by ads_id desc limit $limit", [$getImport["ads_import_uniq"],""]);

	if(count($getAds)){
		foreach ($getAds as $key => $value) {
			$images = import_load_image($value["ads_import_images"], $params["count_load_image"], $params);
   	  	  	if(!count($images)){
   	  	  		if($params["always_image"]){
   	  	  			$errors[$value["ads_title"]][] = "Изображение отсеяно или не скачено";
   	  	  			update("delete from uni_ads where ads_id=?", array($value["ads_id"]));
   	  	  			$Elastic->delete( [ "index" => "uni_ads", "type" => "ad", "id" => $value["ads_id"] ] );
   	  	  		}
   	  	  	}
   	  	  	update("update uni_ads set ads_import_images=?,ads_images=? where ads_id=?", array("",json_encode($images),$value["ads_id"]), true);			
		}
	}else{
		update("update uni_ads_import set ads_import_status_images=? where ads_import_id=?", [ 1,$getImport["ads_import_id"] ]);
	}

	$count_loaded = (int)getOne("select count(*) as total from uni_ads where ads_id_import=?", [$getImport["ads_import_uniq"]])["total"];

	update("update uni_ads_import set ads_import_count_loaded=?,ads_import_errors=? where ads_import_id=?", [ $count_loaded,count($errors),$getImport["ads_import_id"] ]);

    if(count($errors)){
      @unlink($config["basePath"] . "/" . $config["folder_admin"] . "/include/modules/ads_import/errors/".$getImport["ads_import_uniq"].".txt");
   	  file_put_contents($config["basePath"] . "/" . $config["folder_admin"] . "/include/modules/ads_import/errors/".$getImport["ads_import_uniq"].".txt", serialize($errors));
    }

}

?>