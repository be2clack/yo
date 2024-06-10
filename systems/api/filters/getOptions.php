<?php

$filters = $_GET["filters"] ? apiStructureFilters(json_decode($_GET["filters"], true)) : [];
$idCat = (int)$_GET['id_cat'];

$results = [];

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

// Options switch

if($getCategoryBoard["category_board_id"][$idCat]["category_board_secure"] && $settings["secure_status"]){
	$results['options']['secure'] = apiLangContent('Безопасная сделка');
}

if($getCategoryBoard["category_board_id"][$idCat]["category_board_online_view"]){
	$results['options']['online_view'] = apiLangContent('Онлайн-показ');
}

if($getCategoryBoard["category_board_id"][$idCat]["category_board_auction"]){
  $results['options']['auction'] = apiLangContent('Аукцион');
}

$results['options']['vip'] = apiLangContent('VIP объявления');

if($getCategoryBoard["category_board_id"][$idCat]["category_board_booking"]){

  if($getCategoryBoard["category_board_id"][$idCat]["category_board_booking_variant"] == 0){
    $results['options']['booking'] = apiLangContent('Онлайн-бронирование');
  }else{
    $results['options']['booking'] = apiLangContent('Онлайн-аренда');
  }

}

if($idCat){
    if($getCategoryBoard["category_board_id"][$idCat]["category_board_display_price"]){ 
       $results['price_name'] = apiNameInputPrice($getCategoryBoard["category_board_id"][$idCat]["category_board_variant_price_id"]);
    }else{
       $results['price_name'] = apiLangContent('Цена');
    }
}else{
   $results['price_name'] = apiLangContent('Цена');
}

// Filters

$filters_ids = $Filters->getCategory(["id_cat"=>$idCat]);

if($filters_ids){
  $query = "ads_filters_visible='1' and ads_filters_id IN(".implode(",", $filters_ids).")";
}else{
  $query = "ads_filters_visible='1' and ads_filters_id IN(0)";
}

$getFilters = $Filters->getFilters("where $query");
$getAllFilters = $Filters->getFilters("where ads_filters_visible='1'");

if($getFilters["id_parent"][0]){

  foreach ($getFilters["id_parent"][0] as $id_filter => $value) {
     
     $items = [];
     $ids = [];
     
     $getItems = getAll("select * from uni_ads_filters_items where ads_filters_items_id_filter=? order by ads_filters_items_sort asc", [$id_filter]);

     if($getItems){

        foreach ($getItems as $item) {          
          $items[] = ['name'=>$item['ads_filters_items_value'], 'id'=>$item['ads_filters_items_id'], 'podfilter'=>findOne('uni_ads_filters_items', 'ads_filters_items_id_item_parent=?', [$item['ads_filters_items_id']]) ? true : false];
        }

     }

     $ids_podfilter = $Filters->idsBuild($value["ads_filters_id"],$getAllFilters);
     if($ids_podfilter){
        foreach (explode(',', $ids_podfilter) as $id) {
          $ids[] = $id;
        }
     }

	  $results['filters'][] = [
  		'id' => $id_filter,
  		'view' => $value["ads_filters_type"],
  		'name' => $value["ads_filters_name"],
      'ids_podfilter'=> $ids ?: null,
  		'items' => $items,
      'required' => $value['ads_filters_required'] ? true : false,
      'podfilter' => $getAllFilters['id_parent'][$value['ads_filters_id']] ? true : false,
    ];
     
    if(isset($filters[$id_filter])){ 
      $parentFilter = apiPodfilters($id_filter, $filters, $getAllFilters);
      if($parentFilter){ 
        $results['filters'] = array_merge($results['filters'], $parentFilter);
      }
    }


  }

}

echo json_encode(['data'=>$results]); 

?>