<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

$step = (int)$_POST["step"];
$catId = (int)$_POST["cat_id"];
$title = clear($_POST["title"]);
$text = clear($_POST["text"]);
$period = (int)$_POST["period"];
$currency = clear($_POST["currency"]);
$price = $_POST["price"] ? round($_POST["price"], 2) : 0;
$images = isset($_POST["images"]) ? json_decode($_POST["images"], true) : [];
$measure = clear($_POST["measure"]);
$city_id = (int)$_POST["city_id"];
$var_price = clear($_POST["var_price"]);
$video = clear($_POST["video"]);
$online_view = (int)$_POST["online_view"];
$phone = formatPhone(clear($_POST["phone"]));

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$results = [];
$errors = [];

$getTariff = $Profile->getOrderTariff($idUser);

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");
$filters = apiStructureAdVariantsFilters(json_decode($_POST["filters"], true));

if($step == 1){

	if(!$getCategoryBoard["category_board_id"][$catId]["category_board_auto_title"]){
	    if(empty($title)){ $errors[] = apiLangContent("Пожалуйста, укажите заголовок объявления"); }
	}

	if(!$text){
	    $errors[] = apiLangContent("Пожалуйста, укажите описание объявления");
	}

	if($settings["main_type_products"] == 'electron'){
	    if(empty($_POST["electron_product_links"])){ $errors[] = apiLangContent("Пожалуйста, укажите ссылку на электронный товар"); }
	    if(!$price){
	        $errors[] = apiLangContent("Пожалуйста, укажите цену");
	    }
	}

  if($settings["ad_create_always_image"]){
      if(!$images){
        $errors[] = apiLangContent("Загрузите хотя бы одну фотографию");
      }
  }

  if($settings["main_type_products"] == "physical"){
	  if(!$settings["city_id"]){
	    if(empty($city_id)){ $errors[] = apiLangContent("Пожалуйста, укажите город"); }else{
	      $getCity = findOne("uni_city","city_id=?", array($city_id));
	      if(count($getCity) == 0){
	        $errors[] = apiLangContent("Пожалуйста, укажите город");
	      }
	    }
	  }
	}

}elseif($step == 2){

  // if($getCategoryBoard["category_board_id"][$catId]["category_board_measures_price"]){
  //     if($getCategoryBoard["category_board_id"][$catId]["category_board_rules"]["measure_booking"]){
  //         if($booking){
  //             if(empty($measure)){ $errors[] = "Пожалуйста, выберите вариант измерения"; }
  //         }
  //     }else{
  //         if(empty($measure)){ $errors[] = "Пожалуйста, выберите вариант измерения"; }
  //     }
  // }

  if($settings["ad_create_period"]){
      if(!$period){
        $errors[] = apiLangContent("Выберите срок публикации");
      }
  }

	$filters_ids = $Filters->getCategory(["id_cat"=>$catId]);

	if($filters_ids){
	  $getFilters = getAll('select * from uni_ads_filters where ads_filters_visible=1 and ads_filters_required=1 and ads_filters_id IN('.implode(",", $filters_ids).')');
	  foreach ($getFilters as $key => $value) {

	      if($value['ads_filters_id_parent']){

	         if(isset($filters[$value['ads_filters_id_parent']])){ 

	              $getParentFilterItem = findOne('uni_ads_filters_items', 'ads_filters_items_id_filter=? and ads_filters_items_id_item_parent=?', [$value['ads_filters_id'], $filters[$value['ads_filters_id_parent']][0]]);

	              if($getParentFilterItem){

	                  if(empty($filters[$value['ads_filters_id']])){
	                      if($value['ads_filters_type'] == 'input'){
	                         $getItems = getAll("select * from uni_ads_filters_items where ads_filters_items_id_filter=? order by ads_filters_items_value asc", [$value["ads_filters_id"]]);

	                         if($filters[$value['ads_filters_id']][0] < $getItems[0]['ads_filters_items_value'] || $filters[$value['ads_filters_id']][0] > $getItems[1]['ads_filters_items_value']){
	                              $errors[] = apiLangContent("Укажите фильтр:")." ".mb_strtolower($value['ads_filters_name'], 'utf-8')." ".apiLangContent("от")." ".$getItems[0]['ads_filters_items_value']." ".apiLangContent("до")." ".$getItems[1]['ads_filters_items_value'];
	                         }
	                      }else{
	                         $errors[] = apiLangContent("Выберите фильтр:")." ".mb_strtolower($value['ads_filters_name'], 'utf-8');
	                      }                  
	                  }else{
	                      if($value['ads_filters_type'] == 'input'){
	                         $getItems = getAll("select * from uni_ads_filters_items where ads_filters_items_id_filter=? order by ads_filters_items_value asc", [$value["ads_filters_id"]]);

	                         if($filters[$value['ads_filters_id']][0] < $getItems[0]['ads_filters_items_value'] || $filters[$value['ads_filters_id']][0] > $getItems[1]['ads_filters_items_value']){
	                              $errors[] = apiLangContent("Укажите фильтр:")." ".mb_strtolower($value['ads_filters_name'], 'utf-8')." ".apiLangContent("от")." ".$getItems[0]['ads_filters_items_value']." ".apiLangContent("до")." ".$getItems[1]['ads_filters_items_value'];
	                         }
	                      }                    
	                  }

	              }

	         }

	      }else{

	         if(empty($filters[$value['ads_filters_id']])){ 
	              $errors[] = "Выберите фильтр: ".mb_strtolower($value['ads_filters_name'], 'utf-8');
	         }else{
	              if($value['ads_filters_type'] == 'input'){
	                 $getItems = getAll("select * from uni_ads_filters_items where ads_filters_items_id_filter=? order by ads_filters_items_value asc", [$value["ads_filters_id"]]);
	                 if($filters[$value['ads_filters_id']][0] < $getItems[0]['ads_filters_items_value'] || $filters[$value['ads_filters_id']][0] > $getItems[1]['ads_filters_items_value']){
	                      $errors[] = apiLangContent("Укажите фильтр:")." ".mb_strtolower($value['ads_filters_name'], 'utf-8')." ".apiLangContent("от")." ".$getItems[0]['ads_filters_items_value']." ".apiLangContent("до")." ".$getItems[1]['ads_filters_items_value'];
	                 }
	              }
	         }

	      }

	  }
	}

	
}


if(!count($errors)){
	echo json_encode(['status'=>true]);
}else{
	echo json_encode(['status'=>false, 'answer'=>implode("\n", $errors)]);
}
?>