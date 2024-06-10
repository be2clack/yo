<?php

$langIso = $_GET["lang_iso"];
$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

$filterIdUser = (int)$_GET["filter_id_user"];
$search = clear($_GET["search"]);

$page = (int)$_GET["page"];
$cat_id = (int)$_GET["cat_id"];
$sorting = clear($_GET["sorting"]);

$city_id = (int)$_GET["city_id"];
$region_id = (int)$_GET["region_id"];
$country_id = (int)$_GET["country_id"];

$recommendations = $_GET["recommendations"] == 'true' ? true : false;
$advertisement = $_GET["advertisement"] == 'true' ? true : false;
$auction = $_GET["auction"] == 'true' ? true : false;
$fresh = $_GET["fresh"] == 'true' ? true : false;
$shop = (int)$_GET["shop"];


$query = [];
$output = 30;
$ad_ids_interests = [];
$getAdsVip = [];
$not_ids = [];


if($search && mb_strlen($search) >= 2){
	$query[] = $Filters->explodeSearch($search);
}

if($filterIdUser){

	if(!$shop){

		if($sorting == 'active'){
			$query[] = "ads_id_user='".$filterIdUser."' and ads_status='1' and ads_period_publication > now()";
		}elseif($sorting == 'sold'){
			$query[] = "ads_id_user='".$filterIdUser."' and ads_status IN(5,4)";
		}elseif($sorting == 'archive'){
			$query[] = "ads_id_user='".$filterIdUser."' and (ads_status NOT IN(0,1,5,4) or ads_period_publication < now()) and ads_status!=8";
		}elseif($sorting == 'all'){
			$query[] = "ads_id_user='".$filterIdUser."' and ads_status IN(0,1,2,3,4,6,7)";
		}elseif($sorting == 'moderation'){
			$query[] = "ads_id_user='".$filterIdUser."' and ads_status='0'";
		}else{
			$query[] = "ads_id_user='".$filterIdUser."' and ads_status IN(0,1,2,3,4,6,7)";
		}

		$getAds = $Ads->getAll(["navigation"=>true,"page"=>$page,"output"=>$output,"query"=>implode(' and ', $query), "sort"=>"ORDER By ads_datetime_add DESC"]);

	}else{

    if($settings["ads_sorting_variant"] == 0){
      $sorting = "order by ads_sorting desc, ads_id desc";
    }elseif( $settings["ads_sorting_variant"] == 1 ){ 
      $sorting = "order by ads_sorting desc, ads_id asc";   
    }else{
      $sorting = "order by ads_sorting desc";
    }

		$query[] = "clients_status IN(0,1) and ads_status='1' and ads_period_publication > now() and ads_id_user='".$filterIdUser."'";

		$getAds = $Ads->getAll(["navigation"=>true,"page"=>$page,"output"=>$output,"query"=>implode(' and ', $query), "sort"=>$sorting]);

	}

}else{

	if($recommendations){

	  if($idUser){

	    $getInterests = getAll("select * from uni_ads_recommendations where user_id=? order by sort desc", [$idUser]);

	    if($getInterests){

	      foreach ($getInterests as $key => $value) {
	        $ad_ids_interests[] = $value["ad_id"];
	      }

	    }else{

		    if($settings["ads_sorting_variant"] == 0){
		      $sorting = "order by ads_sorting desc, ads_id desc";
		    }elseif( $settings["ads_sorting_variant"] == 1 ){ 
		      $sorting = "order by ads_sorting desc, ads_id asc";   
		    }else{
		      $sorting = "order by ads_sorting desc";
		    }

	    }

	  }else{

	    if($settings["ads_sorting_variant"] == 0){
	      $sorting = "order by ads_sorting desc, ads_id desc";
	    }elseif( $settings["ads_sorting_variant"] == 1 ){ 
	      $sorting = "order by ads_sorting desc, ads_id asc";   
	    }else{
	      $sorting = "order by ads_sorting desc";
	    }

	  }

	  if($ad_ids_interests){
	     $query[] = "ads_id IN(".implode(",", $ad_ids_interests).")";
	  }

	}elseif($fresh){

    $sorting = "order by ads_id desc";

	}else{

    if($settings["ads_sorting_variant"] == 0){
      $sorting = "order by ads_sorting desc, ads_id desc";
    }elseif( $settings["ads_sorting_variant"] == 1 ){ 
      $sorting = "order by ads_sorting desc, ads_id asc";   
    }else{
      $sorting = "order by ads_sorting desc";
    }

	}

	if(!$auction){
		if($city_id){
			$query[] = "ads_city_id='".$city_id."'";
		}elseif($region_id){
			$query[] = "ads_region_id='".$region_id."'";
		}elseif($country_id){
			$query[] = "ads_country_id='".$country_id."'";
		}
	}else{
		$query[] = "ads_auction='1'";
	}

	$query[] = "clients_status IN(0,1) and ads_status='1' and ads_period_publication > now()";

	$getAds = $Ads->getAll(["navigation"=>true,"page"=>$page,"output"=>$output,"query"=>implode(' and ', $query), "sort"=>$sorting]);

  if($page == 1 && $recommendations){
     shuffle($getAds["all"]);
  }

  if($advertisement && $getAds['count'] > $output){

  	$query[] = "ads_vip='1'";

  	// if($getAds["count"]){
  	// 	foreach ($getAds["all"] as $key => $value) {
  	// 		 $not_ids[$value["ads_id"]] = $value["ads_id"];
  	// 	}
  	// 	$query[] = "ads_id NOT IN(".implode(",", $not_ids).")";
  	// }

		$getAdsVip = $Ads->getAll(["navigation"=>false,"query"=>implode(' and ', $query), "sort"=>"order by ads_id desc limit 60"]);

		shuffle($getAdsVip["all"]);

		$getAdsVip = apiArrayDataAds($getAdsVip,$idUser);

  }

}


echo json_encode(['data'=>apiArrayDataAds($getAds,$idUser), 'count'=>$getAds['count'].' '.ending($getAds['count'], apiLangContent('объявление', $langIso), apiLangContent('объявления', $langIso), apiLangContent('объявлений', $langIso)), 'pages'=>getCountPage($getAds['count'],$output),'advertisement'=>$getAdsVip?:null]);
?>