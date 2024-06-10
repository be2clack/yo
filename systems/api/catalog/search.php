<?php

$query = clearSearchBack($_GET["query"]);

$city_id = (int)$_GET["city_id"];
$region_id = (int)$_GET["region_id"];
$country_id = (int)$_GET["country_id"];

$result = [];
$results = [];
$main_id_categories = [];
$delete_words = ['с','в','на','или'];
$queryGeo = "";

if($city_id){
	$queryGeo = " and ads_city_id='".$city_id."'";
}elseif($region_id){
	$queryGeo = " and ads_region_id='".$region_id."'";
}elseif($country_id){
	$queryGeo = " and ads_country_id='".$country_id."'";
}

if(mb_strlen($query, 'UTF-8') >= 2){

	$query = str_replace('-', ' ', $query);
	$queryNotDeleteWord = str_replace('-', ' ', $query);

	foreach ($delete_words as $value) {
	   $query = preg_replace('/\b'.$value.'\b/u','',$query);
	}

	$getCategories = $CategoryBoard->getCategories("where category_board_visible=1");

	$split = preg_split("/( )+/", $query);
	$splitNotDeleteWord = preg_split("/( )+/", $queryNotDeleteWord);

	if(count($splitNotDeleteWord) > 1 && $page != 'shop'){
	    $endWord = $splitNotDeleteWord[ count($splitNotDeleteWord) - 1 ];
	    $penultimateWord = $splitNotDeleteWord[ count($splitNotDeleteWord) - 2 ];
	    if(mb_strlen($endWord, 'UTF-8') >= 3) $searchCity = getOne("select * from uni_city where city_name LIKE '".$endWord."' or city_declination LIKE '".$penultimateWord.' '.$endWord."'", []);
	}

	if($getShop["clients_shops_id_theme_category"]){
	    $shop_get_category_ids = idsBuildJoin($CategoryBoard->idsBuild($getShop["clients_shops_id_theme_category"], $getCategories), $getShop["clients_shops_id_theme_category"]);
	    if($shop_get_category_ids){
	        $search = getAll("select * from uni_ads_keywords where ads_keywords_id_cat IN(".$shop_get_category_ids.") and (ads_keywords_tag LIKE '%".$split[0]."%' or ads_keywords_tag LIKE '%".searchSubstr($split[0],1)."%') order by ads_keywords_count_click desc limit 100");
	    }
	}else{
	    $search = getAll("select * from uni_ads_keywords where ads_keywords_tag LIKE '%".$split[0]."%' or ads_keywords_tag LIKE '%".searchSubstr($split[0],1)."%' order by ads_keywords_count_click desc limit 100");
	}

	if(count($search)){
	  if(count($split) > 1){
	      foreach ($search as $value) {

	          if(count($split) == 2){
	              if(searchCheckWord($value['ads_keywords_tag'],searchSubstr($split[1],1))){
	                   $result[] = $value;
	              }
	          }elseif(count($split) == 3){
	              if(searchCheckWord($value['ads_keywords_tag'],searchSubstr($split[1],1)) && searchCheckWord($value['ads_keywords_tag'],searchSubstr($split[2],1))){
	                   $result[] = $value;
	              }else{
	                 if(searchCheckWord($value['ads_keywords_tag'],searchSubstr($split[1],1))){
	                    $result[] = $value;
	                 }
	              }
	          }elseif(count($split) == 4){
	              if(searchCheckWord($value['ads_keywords_tag'],searchSubstr($split[1],1)) && searchCheckWord($value['ads_keywords_tag'],searchSubstr($split[2],1)) && searchCheckWord($value['ads_keywords_tag'],searchSubstr($split[3],1))){
	                   $result[] = $value;
	              }else{
	                 if(searchCheckWord($value['ads_keywords_tag'],searchSubstr($split[1],1)) && searchCheckWord($value['ads_keywords_tag'],searchSubstr($split[2],1))){
	                     $result[] = $value;
	                 }                        
	              }
	          }

	      }

	  }else{
	      $result = $search;
	  }
	}

	if(count($result)){

	  foreach ($result as $value) {
	    $get_main_id = $CategoryBoard->reverseMainId($getCategories,$value['ads_keywords_id_cat']);
	    if($get_main_id) $main_id_categories[$get_main_id] = $get_main_id;
	  }

	}

	if(count($result)){

	     foreach (array_slice($result,0,10,true) as $value) {

	     	$keywordsParamsList = [];

	     	if($value['ads_keywords_params']){
	     		parse_str($value['ads_keywords_params'], $keywords_params);
	     		if ($keywords_params) {
	     			foreach ($keywords_params['filter'] as $filterId => $nested) {
		     			foreach ($nested as $param_item) {
		     				$getFilterItem = findOne('uni_ads_filters_items', 'ads_filters_items_id=?', [$param_item]);
		     				$keywordsParamsList[] = ['filterId'=>(string)$filterId, 'item'=>(string)$param_item, 'name'=>(string)$getFilterItem['ads_filters_items_value']];
		     			}
	     			}
	     		}
	     	}

	     	$results['tags'][] = ['tag'=>$value["ads_keywords_tag"], 'cat_name'=>$ULang->tApp($getCategories["category_board_id"][$value["ads_keywords_id_cat"]]["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name"]), 'cat_id'=>$value["ads_keywords_id_cat"], 'params'=>$keywordsParamsList ?: null];
	        
	     }

	}

	// Ads

   $getAds = $Ads->getAll(["query"=>"ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ".$Filters->explodeSearch(clearSearchBack($_GET["query"])).$queryGeo, "sort"=>"ORDER By ads_datetime_add DESC limit 5"]);

   if($getAds["count"]){

      foreach ($getAds["all"] as $key => $value) {
        $image = $Ads->getImages($value["ads_images"]);
        $getShop = $Shop->getUserShop($value["ads_id_user"]);
        $results['ads'][] = ['id'=>$value["ads_id"], 'title'=>$value["ads_title"], 'image'=>Exists($config["media"]["small_image_ads"],$image[0],$config["media"]["no_image"]), 'price'=>apiOutPrice(['data'=>$value, 'shop'=>$getShop])];
      }

   }

	// Shops

    foreach ($split as $value) {
       $shop_like_query[] = "clients_shops_title LIKE '%".$value."%'";
    }

    if(count($main_id_categories)){
        $getShops = getAll("select * from uni_clients_shops where (clients_shops_time_validity > now() or clients_shops_time_validity IS NULL) and clients_shops_status=1 and (clients_shops_id_theme_category IN(".implode(',', $main_id_categories).") or (".implode(' and ', $shop_like_query).")) order by rand() limit 5", []);
    }else{
        $getShops = getAll("select * from uni_clients_shops where (clients_shops_time_validity > now() or clients_shops_time_validity IS NULL) and clients_shops_status=1 and ".implode(' and ', $shop_like_query)." order by rand() limit 5", []);
    }

    if(count($getShops)){

		$results['shops'] = apiArrayDataShops($getShops);

    }


}

echo json_encode(['data'=>$results]);

?>