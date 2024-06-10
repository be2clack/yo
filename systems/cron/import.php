<?php

defined('unisitecms') or exit();

$Elastic = new Elastic();
$insert = [];

function csvCombine( $handle, $step, $param ){
  
  if(!$param["count_import"]) $param["count_import"] = 100;
  if(!$param["csv_char"]) $param["csv_char"] = ';';

  $data = [];
  $header = fgetcsv($handle, 0, $param["csv_char"]); 

  if($step) fseek( $handle, $step );

  while ($row = fgetcsv($handle, 0, $param["csv_char"])) { 

  	if(count($data) != $param["count_import"]){
  		if(count($header) == count($row)){
      	$data[] = array_combine($header, $row);
    	}
    }else{
      return $data;
      break;
    }

  }

  return $data;

}

$getImport = findOne("uni_ads_import", "ads_import_status=? order by ads_import_id asc", [1]);

if($getImport){
   
   $errors = [];

   if(file_exists($config["basePath"] . "/" . $config["folder_admin"] . "/include/modules/ads_import/errors/".$getImport["ads_import_uniq"].".txt")){
  	 $errors = unserialize(file_get_contents($config["basePath"] . "/" . $config["folder_admin"] . "/include/modules/ads_import/errors/".$getImport["ads_import_uniq"].".txt"));
   }

   $path = $config["basePath"] . "/" . $config["folder_admin"] . "/include/modules/ads_import/temp/" . $getImport["ads_import_file"];
   $params = json_decode($getImport["ads_import_params"], true);
   
   $handle = fopen($path, "rb");

   $data = csvCombine( $handle, $getImport["ads_import_step"], $params );

   if(count($data)){
   	  foreach ($data as $key => $nested) {

   	  	  $fields = [];
   	  	  $error = [];
   	  	  $category_name = "";
   	  	  $city_name = "";
   	  	  $map_lat = "";
   	  	  $map_lon = "";

		   	  foreach ($nested as $key => $value) {

		   	  	 if( $params["title"] == $key ){ $fields["title"] = clear($value); }
		   	  	 if( $params["price"] == $key ){ $fields["price"] = intval($value) ? round(intval(preg_replace('/\s/', '', $value)),2) : 0; }
		   	  	 if( $params["datetime_add"] == $key ){ $fields["datetime_add"] = $value ? date("Y-m-d H:i:s", strtotime($value)) : date("Y-m-d H:i:s"); }
		   	  	 if( $params["phone"] == $key ){ $fields["phone"] = formatPhone($value); }
		   	  	 if( $params["email"] == $key ){ $fields["email"] = clear($value); }
		   	  	 if( $params["electron_product_links"] == $key ){ $fields["electron_product_links"] = clear($value); }
		   	  	 if( $params["electron_product_text"] == $key ){ $fields["electron_product_text"] = clear($value); }
		   	  	 if( $params["name_user"] == $key ){ $fields["name_user"] = preg_replace("/[^а-яёa-z\s]/iu", '', clear($value)); }
						 if( $params["available_unlimitedly"] == $key ){ $fields["available_unlimitedly"] = intval($value); }
						 if( $params["delivery_status"] == $key ){ $fields["delivery_status"] = intval($value); }
						 if( $params["delivery_weight"] == $key ){ $fields["delivery_weight"] = intval($value); }

		   	  	 if( $params["mode_user"] == $key ){

		   	  	 	$fields["mode_user"] = strpos($value, "Частное") !== false ? "user" : "company"; 

		   	  	 	if($fields["mode_user"] == "company"){ $fields["name_company"] = $fields["name_user"]; }

		   	  	 }
		   	  	 if( $params["city"] == $key ){ 
	                
	                $city_name = trim($value);

	                if($city_name){

				   	  	 	$fields["geo"] = getOne("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id WHERE `uni_country`.country_status = '1' and `uni_city`.city_name = '".$city_name."'");

				   	  	 	}else{ $fields["geo"] = []; } 

		   	  	 }
		   	  	 if( $params["metro/area"] == $key ){ 
		   	  	 	if($value){
		   	  	 		$getArea = findOne("uni_city_area", "city_area_name=?", [$value]);
		   	  	 		if($getArea){
		   	  	 			$fields["area"] = $getArea["city_area_id"];
		   	  	 		}else{
		   	  	 			$getMetro = findOne("uni_metro", "name=?", [$value]);
		   	  	 			if($getMetro){
		   	  	 			$fields["metro"] = $getMetro["id"];	
		   	  	 			}
		   	  	 		}
		   	  	 	}
		   	  	 }
		   	  	 if( $params["address"] == $key ){ $fields["address"] = clear($value); }
		   	  	 if( $params["text"] == $key ){ $fields["text"] = clear($value); }
		   	  	 if( $params["category"] == $key ){ 

		   	  	 		$category_name = $value;
	                
	              if(!$params["change_category"]){
			   	  	 	  if($value){
			   	  	 	  $getCategory = findOne("uni_category_board", "category_board_name=?", [$value]);
			   	  	 	  if($getCategory) $fields["category"] = $getCategory["category_board_id"]; else $fields["category"] = 0;
			   	  	    }else{ $fields["category"] = 0; }
		   	  	    }else{
		   	  	    	$getCategory = findOne("uni_category_board", "category_board_id=?", [ intval($params["change_category"]) ]);
		   	  	    	$fields["category"] = $params["change_category"];
		   	  	    }

		   	  	 }
		   	  	 if( $params["latitude"] == $key ){ $fields["latitude"] = $value; }
		   	  	 if( $params["longitude"] == $key ){ $fields["longitude"] = $value; }
		   	  	 if( $params["images"] == $key ){ $fields["images"] = $value; }
		   	  	 if( $params["filters"] == $key ){ 
		   	  	 	$fields["filters"] = $value;
		   	  	 }

		   	  }

					if(!$fields["mode_user"]) $fields["mode_user"] = "user";

					if(!$fields["datetime_add"]) $fields["datetime_add"] = date("Y-m-d H:i:s");

					if(!$fields["available_unlimitedly"]){
					$fields["available_unlimitedly"] = 0;
					}
				
					if(!$fields["delivery_status"]){
					$fields["delivery_status"] = 0;
					}
				
					if(!$fields["delivery_weight"]){
					$fields["delivery_weight"] = 0;
					}

					if(!$fields["latitude"]){
						$fields["latitude"] = "";
					}

					if(!$fields["longitude"]){
						$fields["longitude"] = "";
					}

   	  	  if(!$fields["category"]){
   	  	 	$error[] = "Категория {$category_name} не определена или ее нет";
   	  	  }

   	  	  if($settings["main_type_products"] == 'physical'){
	   	  	  if(!$fields["geo"]){
	   	  	 		$error[] = "Город {$city_name} не определен или не указан";
	   	  	  }
   	  		}else{
	   	  	  if(!$fields["electron_product_links"]){
	   	  	 		$error[] = "Не указана ссылка на скачивание электронного продукта";
	   	  	  }   	  			
   	  		}

   	  	  if(!$fields["title"]){
   	  	 	$error[] = "Отсутствует заголовок";
   	  	  }

   	  	  if(!$fields["images"]){
   	  	 	if($params["always_image"]) $error[] = "Отсутствуют изображения";
   	  	  }
          
          if(!count($error)){
	          if(($fields["phone"] || $fields["email"]) && $fields["name_user"]){
	            
	         	$getUser = findOne("uni_clients", "clients_phone=? or clients_email=?", [$fields["phone"],$fields["email"]]);
	         	if($getUser){
	              $user_id = $getUser["clients_id"];
	         	}else{
	         	  $clients_id_hash = md5($fields["phone"] ? $fields["phone"] : $fields["email"]);
	         	  $notifications = '{"messages":"1","answer_comments":"1","services":"1"}';
	         	  $user_id = insert("INSERT INTO uni_clients(clients_email,clients_phone,clients_name,clients_id_hash,clients_datetime_add,clients_datetime_view,clients_notifications,clients_type_person,clients_name_company,clients_id_import,clients_secure,clients_ref_id,clients_verification_code)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)", array($fields["email"],$fields["phone"],$fields["name_user"],$clients_id_hash,date("Y-m-d H:i:s"),date("Y-m-d H:i:s"),$notifications,$fields["mode_user"],$fields["name_company"],$getImport["ads_import_uniq"],0,genRefId(),genVerificationCode()));
	         	}
	   	  	    
	   	  	  }else{
	   	  	  	 $error[] = "Нет номера телефона, e-mail адреса или имени пользователя";
	   	  	  }
   	  	  }

          if(count($error) == 0){
             
             if($fields["filters"]){
          	    $import_load_filters = import_load_filters($fields["filters"],$fields["category"]);
          	    $ads_filter_tags = $Filters->buildTags($import_load_filters);
          	 }

          	 if(!$fields["price"]){ $fields["price"] = 0; }

             if($fields["latitude"] && $fields["longitude"]){
                $map_lat = $fields["latitude"];
                $map_lon = $fields["longitude"];
             }elseif($fields["geo"]['city_lat'] && $fields["geo"]['city_lng']){
                $map_lat = $fields["geo"]['city_lat'];
                $map_lon = $fields["geo"]['city_lng'];
             }

					   $insert_id = smart_insert('uni_ads',[
						  	'ads_title' => $fields["title"],
						  	'ads_alias' => translite($fields["title"]),
						  	'ads_text' => $fields["text"],
						  	'ads_id_cat' => intval($fields["category"]),
						  	'ads_id_user' => intval($user_id),
						  	'ads_price' => $fields["price"],
						  	'ads_city_id' => intval($fields["geo"]["city_id"]),
						  	'ads_region_id' => intval($fields["geo"]["region_id"]),
						  	'ads_country_id' => intval($fields["geo"]["country_id"]),
						  	'ads_address' => $fields["address"],
						  	'ads_latitude' => $fields["latitude"],
						  	'ads_longitude' => $fields["longitude"],
						  	'ads_period_publication' => date("Y-m-d H:i:s", strtotime($fields["datetime_add"]) + ($settings["ads_time_publication_default"] * 86400)),
						  	'ads_status' => 1,
						  	'ads_metro_ids' => $fields["metro"],
						  	'ads_currency' => $settings["currency_main"]["code"],
						  	'ads_period_day' => $settings["ads_time_publication_default"],
						  	'ads_datetime_add' => $fields["datetime_add"],
						  	'ads_area_ids' => $fields["area"],
						  	'ads_id_import' => $getImport["ads_import_uniq"],
						  	'ads_import_images' => $fields["images"],
						  	'ads_auto_renewal' => intval($params["auto_renewal"]),
						  	'ads_filter_tags' => $ads_filter_tags,
						  	'ads_available' => intval($fields["available"]),
						  	'ads_electron_product_links' => $fields["electron_product_links"],
						  	'ads_electron_product_text' => $fields["electron_product_text"],
						  	'ads_map_lat' => $map_lat,
						  	'ads_map_lon' => $map_lon,
						  	'ads_available_unlimitedly' => $fields["available_unlimitedly"],
						  	'ads_delivery_status' => $fields["delivery_status"],
						  	'ads_delivery_weight' => $fields["delivery_weight"],
						  	'ads_search_tags' => $Ads->buildTagsSearch(["city_id"=>intval($fields["category"]), "cat_id"=>intval($fields["geo"]["city_id"])]),				  	
					   ]);

	           if($settings["main_type_products"] == 'physical'){
		         		if($fields["metro"]) $Ads->addMetroVariants([$fields["metro"]],$insert_id);
		         		if($fields["area"]) $Ads->addAreaVariants([$fields["area"]],$insert_id);
		         }

		         if($import_load_filters) $Filters->addVariants($import_load_filters,$insert_id);

		         $insert[] = $insert_id;

		         $getAd = $Ads->get("ads_id=".$insert_id);

		         $Elastic->index( [ "index" => "uni_ads", "type" => "ad", "id" => $insert_id, "body" => $Elastic->prepareFields( $getAd ) ] );

	   	  }else{
	   	  	 $errors[$fields["title"]] = $error;
	   	  }


   	  }
   }

   $step = ftell($handle);
   fclose($handle);


   if($insert){

     $count_loaded = (int)getOne("select count(*) as total from uni_ads where ads_id_import=?", [$getImport["ads_import_uniq"]])["total"];
     update("update uni_ads_import set ads_import_step=?,ads_import_count_loaded=?,ads_import_errors=? where ads_import_id=?", [ $step,$count_loaded,count($errors),$getImport["ads_import_id"] ]);

   }else{

   	 update("update uni_ads_import set ads_import_status=?,ads_import_errors=? where ads_import_id=?", [ 2,count($errors),$getImport["ads_import_id"] ]);

   }

   if(count($errors)){
   	  @unlink($config["basePath"] . "/" . $config["folder_admin"] . "/include/modules/ads_import/errors/".$getImport["ads_import_uniq"].".txt");
   	  file_put_contents($config["basePath"] . "/" . $config["folder_admin"] . "/include/modules/ads_import/errors/".$getImport["ads_import_uniq"].".txt", serialize($errors));
   }


}

?>