<?php
$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

$filters = $_GET["filters"] ? apiStructureFiltersCatalog(json_decode($_GET["filters"], true)) : [];
$search = clearSearchBack($_GET["search"]);

$page = (int)$_GET["page"];
$sorting = clear($_GET["sorting"]);

$cat_id = (int)$_GET["cat_id"];
$city_id = (int)$_GET["city_id"];
$region_id = (int)$_GET["region_id"];
$country_id = (int)$_GET["country_id"];

$booking_date_start = $_GET["booking_date_start"];
$booking_date_end = $_GET["booking_date_end"];

$secure = $_GET["secure"] === 'true'? true: false;
$vip = $_GET["vip"] === 'true'? true: false;
$online_view = $_GET["online_view"] === 'true'? true: false;
$auction = $_GET["auction"] === 'true'? true: false;
$booking = $_GET["booking"] === 'true'? true: false;

$price_start = round($_GET["price_start"],2);
$price_end = round($_GET["price_end"],2);

$topLat = clear($_GET["top_lat"]);
$topLon = clear($_GET["top_lon"]);

$bottomLat = clear($_GET["bottom_lat"]);
$bottomLon = clear($_GET["bottom_lon"]);

$query = [];
$latlon = [];
$flCount = 0;    
$ids = [];
$only_ids = [];
$forming = [];
$forming_filters = [];
$ids_not = [];

$output = 30;

if(isset($_GET["ids"])){
	$only_ids = iteratingArray(json_decode($_GET["ids"], true), "int");
}

$Profile->addUserInterest($cat_id,$idUser);

if($sorting == 'default'){

    if($settings["ads_sorting_variant"] == 0){
      $sort = "order by ads_sorting desc, ads_id desc";
    }elseif( $settings["ads_sorting_variant"] == 1 ){ 
      $sort = "order by ads_sorting desc, ads_id asc";   
    }else{
      $sort = "order by ads_sorting desc";
    }

}elseif($sorting == 'news'){
	$sort = "order by ads_datetime_add desc";
}elseif($sorting == 'price_asc'){
	$sort = "order by ads_price asc";
}elseif($sorting == 'price_desc'){
	$sort = "order by ads_price desc";
}else{

    if($settings["ads_sorting_variant"] == 0){
      $sort = "order by ads_sorting desc, ads_id desc";
    }elseif( $settings["ads_sorting_variant"] == 1 ){ 
      $sort = "order by ads_sorting desc, ads_id asc";   
    }else{
      $sort = "order by ads_sorting desc";
    }
    
}

$query[] = "clients_status IN(0,1) and ads_status='1' and ads_period_publication > now()";

if(!$only_ids){

	if($search && mb_strlen($search) >= 1){
		$query[] = $Filters->explodeSearch($search);
	}

	if(!empty($price_start) && !empty($price_end)){  

	  $query[] = "(ads_price BETWEEN ".$price_start." AND ".$price_end.")"; 

	}else{

	  if(!empty($price_start)){
	     $query[] = "(ads_price >= ".$price_start.")";
	  }elseif(!empty($price_end)){
	     $query[] = "(ads_price <= ".$price_end.")";
	  }

	}

	if($secure){
		if( $settings["secure_payment_service_name"] ){
		  $payment = findOne("uni_payments","code=?", array( $settings["secure_payment_service_name"] ));
		  $query[] = "category_board_secure='1' and clients_secure='1' and (ads_price BETWEEN ".round($payment["secure_min_amount_payment"],2)." AND ".round($payment["secure_max_amount_payment"],2).")";
		}else{
		  $query[] = "category_board_secure='1' and clients_secure='1'";
		}
	}

	if($vip){
	 	$query[] = "ads_vip='1'";
	}

	if($online_view){
	 	$query[] = "ads_online_view='1'";
	}

	if($auction){
		$query[] = "ads_auction='1'";	
	}

	if($booking){
		$query[] = "ads_booking='1'";
	}


  if($booking_date_start || $booking_date_end){  

     if($cat_id){
        $ids_cat = idsBuildJoin($CategoryBoard->idsBuild($cat_id, $CategoryBoard->getCategories("where category_board_visible=1")), $cat_id);
     }

     if(isset($booking_date_start) && isset($booking_date_end)){

         if($ids_cat){
            $get = getAll("SELECT * FROM uni_ads_booking_dates WHERE ads_booking_dates_id_cat IN(".$ids_cat.") and DATE(ads_booking_dates_date) BETWEEN '".date("Y-m-d", strtotime($booking_date_start))."' AND '".date("Y-m-d", strtotime($booking_date_end))."'");
         }else{
            $get = getAll("SELECT * FROM uni_ads_booking_dates WHERE DATE(ads_booking_dates_date) BETWEEN '".date("Y-m-d", strtotime($booking_date_start))."' AND '".date("Y-m-d", strtotime($booking_date_end))."'");
         }
             

         if($get){

            foreach ($get as $key => $value) {
              $ids_not[$value["ads_booking_dates_id_ad"]] = $value["ads_booking_dates_id_ad"];
            }
            
         }

     }elseif(isset($booking_date_start)){

         if($ids_cat){
            $get = getAll("SELECT * FROM uni_ads_booking_dates WHERE ads_booking_dates_id_cat IN(".$ids_cat.") and DATE(ads_booking_dates_date) = '".date("Y-m-d", strtotime($booking_date_start))."'"); 
         }else{
            $get = getAll("SELECT * FROM uni_ads_booking_dates WHERE DATE(ads_booking_dates_date) = '".date("Y-m-d", strtotime($booking_date_start))."'");
         }   

         if($get){

            foreach ($get as $key => $value) {
              $ids_not[$value["ads_booking_dates_id_ad"]] = $value["ads_booking_dates_id_ad"];
            }
            
         }

     }
  
     if($ids_not){
        $query[] = "ads_id NOT IN(".implode(',', $ids_not).")";
     }

  }


	if($topLat && $topLon && $bottomLat && $bottomLon){
		$query[] = "((ads_map_lat < '$topLat' and ads_map_lon < '$topLon') and (ads_map_lat > '$bottomLat' and ads_map_lon > '$bottomLon'))";
	}else{
		if($city_id){
			$query[] = "ads_city_id='".$city_id."'";
		}elseif($region_id){
			$query[] = "ads_region_id='".$region_id."'";
		}elseif($country_id){
			$query[] = "ads_country_id='".$country_id."'";
		}
	}

	if($cat_id){
		$ids_cat = idsBuildJoin($CategoryBoard->idsBuild($cat_id, $CategoryBoard->getCategories("where category_board_visible=1")), $cat_id);
		$query[] = "ads_id_cat IN(".$ids_cat.")";
	}

	if($filters){

	   foreach($filters AS $id_filter=>$filter_array){

	       $getFilter = findOne("uni_ads_filters", "ads_filters_id=?", array( intval($id_filter) ));

	       if($getFilter){

	         if($getFilter->ads_filters_type != "input" && $getFilter->ads_filters_type != "input_text"){

	             foreach($filter_array AS $filter_key=>$filter_val){

	                 if($filter_val != "" && $filter_val != "null"){
	                     
	                     if(!$forming[$id_filter]) $flCount++;
	                     $forming[$id_filter][] = "(ads_filters_variants_id_filter='".intval($id_filter)."' AND ads_filters_variants_val='".intval($filter_val)."')";
	                     
	                 } 
	               
	             }            
	        
	         }else{

	            $flCount++;

	            $forming[$id_filter][] = "ads_filters_variants_id_filter='".intval($id_filter)."' AND (ads_filters_variants_val BETWEEN ".round($filter_array["from"],2)." AND ".round($filter_array["to"],2).")";

	         }

	         if($forming[$id_filter]) $forming_filters[] = implode(" OR ",$forming[$id_filter]);    

	       }       
	  
	   }

	}

	if($forming_filters){

		$variants = getAll("SELECT ads_filters_variants_product_id, count(ads_filters_variants_product_id) AS cnt FROM `uni_ads_filters_variants` WHERE (".implode(" OR ",$forming_filters).") GROUP BY ads_filters_variants_product_id HAVING cnt >= ".$flCount);

		 if(count($variants) > 0){
		   foreach ($variants as $variant_value) {
		      $ids[$variant_value["ads_filters_variants_product_id"]] = $variant_value["ads_filters_variants_product_id"];
		   }
		 }

		 if($ids){
		    $query[] = "ads_id IN(".implode(",", $ids).")";
		 }else{
		    $query[] = "ads_id IN(0)";
		 }

	}

	$getAds = $Ads->getAll(["navigation"=>true,"page"=>$page,"output"=>$output,"query"=>implode(' and ', $query), "sort"=>$sort]);

	if($topLat && $topLon && $bottomLat && $bottomLon){
		$getAdsMap = $Ads->getAll(["query"=>implode(' and ', $query)]);	
	}

}else{

	$query[] = "ads_id IN(".implode(",", $only_ids).")";
	$getAdsMap = $Ads->getAll(["query"=>implode(' and ', $query)]);	

}

echo json_encode(['data'=>apiArrayDataAds($getAds,$idUser), 'map'=>apiArrayDataAds($getAdsMap,$idUser), 'count'=>$getAds['count'].' '.ending($getAds['count'], apiLangContent('объявление'), apiLangContent('объявления'), apiLangContent('объявлений')), 'pages'=>getCountPage($getAds['count'],$output)]);

?>