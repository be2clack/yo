<?php
$idAd = (int)$_GET['id'];
$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);
$idCat = (int)$_GET["id_cat"];

$results = [];
$measures = [];
$link_images = [];
$params = [];
$filters = [];

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$getAd = $Ads->get('ads_id=? and ads_id_user=?', [$idAd,$idUser]);

if(!$getAd){
	http_response_code(500); exit('Ad not found');
}

if(!$idCat){
	$idCat = $getAd['ads_id_cat'];
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

if($getCategoryBoard["category_board_id"][$idCat]["category_board_booking"]){

   if($getCategoryBoard["category_board_id"][$idCat]["category_board_booking_variant"] == 0){

       $results["booking_title"] = apiLangContent("Онлайн-бронирование");
       $results["booking_variant"] = $getCategoryBoard["category_board_id"][$idCat]["category_board_booking_variant"];
       
   }elseif($getCategoryBoard["category_board_id"][$idCat]["category_board_booking_variant"] == 1){

       $results["booking_title"] = apiLangContent("Онлайн-аренда");
       $results["booking_variant"] = $getCategoryBoard["category_board_id"][$idCat]["category_board_booking_variant"];

   }

}

$filters_ids = $Filters->getCategory(["id_cat"=>$idCat]);

if($filters_ids){
  $query = "ads_filters_visible='1' and ads_filters_id IN(".implode(",", $filters_ids).")";
}else{
  $query = "ads_filters_visible='1' and ads_filters_id IN(0)";
}

$getFiltersVariants = getAll('select * from uni_ads_filters_variants where ads_filters_variants_product_id=?', [$idAd]);
if($getFiltersVariants){
	foreach ($getFiltersVariants as $value) {
		$getFilter = findOne('uni_ads_filters', 'ads_filters_id=?', [$value['ads_filters_variants_id_filter']]);
		if($getFilter['ads_filters_type'] == 'input' || $getFilter['ads_filters_type'] == 'input_text'){
			$params[] = ['filterId'=>$value['ads_filters_variants_id_filter'], 'item'=>$value['ads_filters_variants_val'], 'field'=>'text'];
		}else{
			$getFilterItem = findOne('uni_ads_filters_items', 'ads_filters_items_id=?', [$value['ads_filters_variants_val']]);
			$params[] = ['filterId'=>$value['ads_filters_variants_id_filter'], 'item'=>$value['ads_filters_variants_val'], 'name'=>html_entity_decode($getFilterItem['ads_filters_items_value'])];
		}
	}
	$filters = apiStructureFilters($params);
}

$getFilters = $Filters->getFilters("where $query");
$getAllFilters = $Filters->getFilters("where ads_filters_visible='1'");

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
     
    if(isset($filters[$id_filter])){ 
      $parentFilter = apiPodfilters($id_filter, $filters, $getAllFilters);
      if($parentFilter){ 
        $results['filters'] = array_merge($results['filters'], $parentFilter);
      }
    }


  }

}

$images = $Ads->getImages($getAd["ads_images"]);
$getShop = $Shop->getUserShop($getAd["ads_id_user"]);

if($images){
	foreach ($images as $img) {
		$link_images[] = ['name'=>$img, 'link'=>Exists($config["media"]["small_image_ads"],$img,$config["media"]["no_image"])];
	}
}

$results['data'] = [
	"status" => $getAd['ads_status'],
	"id" => $getAd['ads_id'],
	"title" => $getAd['ads_title'],
	"id_cat" => $getAd['ads_id_cat'],
	"price_free" => $getAd['ads_price_free'] ? true : false,
	"category_name" => $getCategoryBoard["category_board_id"][$getAd['ads_id_cat']]["category_board_name"],
	"category_breadcrumb" => breadcrumbCategories($getCategoryBoard,$idCat),
	"video_link" => $getAd['ads_video'] ?: null,
	"price" => $getAd['ads_price'],
	"currency_code" => $getAd['ads_currency'],
	"currency_sign" => $settings["currency_data"][$getAd['ads_currency']]['sign'],
	"price_from" => $getAd['ads_price_from'] ? true : false,
	"city_name" => $getAd['city_name'] ?: '',
	"city_id" => $getAd['city_id'] ?: '',
	"latitude" => $getAd['ads_latitude'] ?: '',
	"longitude" => $getAd['ads_longitude'] ?: '',
	"text" => $getAd['ads_text'],
	"address" => $getAd['ads_address'] ?: null,
	"images" => $link_images ?: null,
	"online_view" => $getAd['ads_online_view'] ? true : false,
	"period_day" => $getAd['ads_period_day'],
	"period_day_name" => $getAd['ads_period_day'].' '.ending($getAd['ads_period_day'], 'день', 'дня', 'дней'),
	"price_measure" => $getAd['ads_price_measure'] ?: '',
	"price_measure_name" => $getAd['ads_price_measure'] ? getNameMeasuresPrice($getAd['ads_price_measure']) : '',
	"params" => $params ?: null,
	"electron_product_links" => $getAd['ads_electron_product_links'] ?: null,
	"electron_product_text" => $getAd['ads_electron_product_text'] ?: null,
	"delivery_status" => $getAd['ads_delivery_status'] ? true : false,
	"delivery_weight" => $getAd['ads_delivery_weight'],
	"auction" => $getAd['ads_auction'] ? true : false,
	"auction_duration" => $getAd['ads_auction_duration'],
	"auction_price_sell" => $getAd['ads_auction_price_sell'],
	"auction_day" => $getAd['ads_auction_day'],
	'booking' => $getAd['ads_booking'] ? true : false,
  "booking_additional_services" => $getAd['ads_booking_additional_services'] ? json_decode($getAd['ads_booking_additional_services'],true) : null,
  "booking_prepayment_percent" => $getAd['ads_booking_prepayment_percent'],
  "booking_max_guests" => $getAd['ads_booking_max_guests'],
  "booking_min_days" => $getAd['ads_booking_min_days'],
  "booking_max_days" => $getAd['ads_booking_max_days'],
  "booking_available" => $getAd['ads_booking_available'],
  "booking_available_unlimitedly" => $getAd['ads_booking_available_unlimitedly'] ? true : false,
  "available" => $getAd['ads_available'],
  "available_unlimitedly" => $getAd['ads_available_unlimitedly'] ? true : false,
  "renewal" => $getAd['ads_auto_renewal'] ? true : false,
];


echo json_encode($results);

?>