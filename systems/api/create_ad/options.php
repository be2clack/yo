<?php
$idUser = (int)$_GET["id_user"];
$idCat = (int)$_GET["id_cat"];

$results = [];
$measures = [];

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");
$getUser = findOne('uni_clients', 'clients_id=?', [$idUser]);

if(!$getUser){
	http_response_code(500); exit('User not found');
}

$results['auction'] = $getCategoryBoard["category_board_id"][$idCat]["category_board_auction"] ? true : false;

if($Ads->checkCategoryDelivery($idCat) && $settings["main_type_products"] == 'physical'){
  $results['delivery'] = true;
}else{
  $results['delivery'] = false;
}

$results['auto_title'] = $getCategoryBoard["category_board_id"][$idCat]["category_board_auto_title"] ? true : false;
$results['online_view'] = $getCategoryBoard["category_board_id"][$idCat]["category_board_online_view"] ? true : false;
$results['price_free'] = $getCategoryBoard["category_board_id"][$idCat]["category_board_rules"]["free_price"] ? true : false;
$results['booking'] = $getCategoryBoard["category_board_id"][$idCat]["category_board_booking"] ? true : false;
$results['available'] = $Cart->modeAvailableCart($getCategoryBoard,$idCat,$idUser);

$results['category_paid'] = $getCategoryBoard["category_board_id"][$idCat]["category_board_status_paid"] ? true : false;
$results['category_paid_price'] = apiPrice($getCategoryBoard["category_board_id"][$idCat]["category_board_price"]);
$results['category_paid_count_free'] = $getCategoryBoard["category_board_id"][$idCat]["category_board_count_free"] ?: 0;
$results['category_paid_available_count_user'] = $Ads->userCountAvailablePaidAddCategory($idCat, $idUser);

if($settings["board_type_ad_publication"] == "free"){
  $results['category_paid_show_modal'] = $getCategoryBoard["category_board_id"][$idCat]["category_board_status_paid"] ? true : false;
}else{
  $results['category_paid_show_modal'] = false;
}


if($settings["ad_create_phone"] && !$getUser['clients_phone']){
	$results['added_phone'] = true;
}else{
	$results['added_phone'] = false;
}

if($getCategoryBoard["category_board_id"][$idCat]["category_board_booking"]){

   if($getCategoryBoard["category_board_id"][$idCat]["category_board_booking_variant"] == 0){

       $results["booking_title"] = apiLangContent("Онлайн-бронирование");
       $results["booking_variant"] = $getCategoryBoard["category_board_id"][$idCat]["category_board_booking_variant"];
       
   }elseif($getCategoryBoard["category_board_id"][$idCat]["category_board_booking_variant"] == 1){

       $results["booking_title"] = apiLangContent("Онлайн-аренда");
       $results["booking_variant"] = $getCategoryBoard["category_board_id"][$idCat]["category_board_booking_variant"];

   }

}

if($getCategoryBoard["category_board_id"][$idCat]["category_board_display_price"]){

	if($getCategoryBoard["category_board_id"][$idCat]["category_board_measures_price"]){
	    $measuresList = json_decode($getCategoryBoard["category_board_id"][$idCat]["category_board_measures_price"], true);
        if($measuresList){
            foreach ($measuresList as $measure) {
               $measures[] = ['code'=>$measure, 'name'=>getNameMeasuresPrice($measure)];
            }
        }
	}

	$results['price'] = ['title'=>$Main->nameInputPrice($getCategoryBoard["category_board_id"][$idCat]["category_board_variant_price_id"]),'measures'=>$measures ?: null];
}

$filters_ids = $Filters->getCategory(["id_cat"=>$idCat]);

if($filters_ids){
  $query = "ads_filters_visible='1' and ads_filters_id IN(".implode(",", $filters_ids).")";
}else{
  $query = "ads_filters_visible='1' and ads_filters_id IN(0)";
}

$getFilters = $Filters->getFilters("where $query");

if($getFilters["id_parent"][0]){

  foreach ($getFilters["id_parent"][0] as $id_filter => $value) {
     
     $items = [];
     
     $getItems = getAll("select * from uni_ads_filters_items where ads_filters_items_id_filter=? order by ads_filters_items_sort asc", array($value["ads_filters_id"]));

     if($getItems){

	     	foreach ($getItems as $item) {
          $ids = [];
          $ids_podfilter = $Filters->idsBuild($value["ads_filters_id"],$getFilters);
          if($ids_podfilter){
              foreach (explode(',', $ids_podfilter) as $id) {
                  $ids[] = $id;
              }
          } 	     		
	     		$items[] = ['name'=>html_entity_decode($item['ads_filters_items_value']), 'id'=>$item['ads_filters_items_id'], 'podfilter'=>findOne('uni_ads_filters_items', 'ads_filters_items_id_item_parent=?', [$item['ads_filters_items_id']]) ? true : false, 'ids_podfilter'=>$ids ?: null];
	     	}

     }

		$results['filters'][] = [
		'id' => $id_filter,
		'view' => $value["ads_filters_type"],
		'name' => $value["ads_filters_name"],
		'items' => $items,
		'required' => $value['ads_filters_required'] ? true : false,
    ];
     

  }

}

echo json_encode($results);

?>