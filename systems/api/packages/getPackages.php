<?php

$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$cat_id = (int)$_GET["cat_id"];
$packages = [];
$old_total_price = 0;

$getPackagesCategories = $Profile->getPackagesCategories();

if($cat_id){

	$ids_cat = $CategoryBoard->reverseId($getPackagesCategories,$cat_id);

	if(!$getPackagesCategories["category_board_id_parent"][$cat_id]){

		$getPackagesCategory = getAll("select * from uni_ads_packages_categories where cat_id=?", [$cat_id]);

		if($getPackagesCategory){

			  foreach ($getPackagesCategory as $key => $value) {
			  	$getPackage = findOne("uni_ads_packages", "id=?", [$value["package_id"]]);
			  	if($getPackage){

	              	if($getPackagesCategories["category_board_id"][$cat_id]["category_board_price"]){
	              	  $old_total_price = apiPrice($getPackagesCategories["category_board_id"][$cat_id]["category_board_price"] * $getPackage["count_ad"]);
	              	}				                  	

			  		$packages[] = [
			  			"id" => $getPackage["id"],
			  			"total_price" => apiPrice($getPackage["price_ad"] * $getPackage["count_ad"]),
			  			"int_total_price" => $getPackage["price_ad"] * $getPackage["count_ad"],
			  			"ad_price" => apiPrice($getPackage["price_ad"]) . ' ' . apiLangContent("за объявление"),
			  			"count_ad" => $getPackage["count_ad"] . ' ' . ending($getPackage["count_ad"], apiLangContent('размещение'), apiLangContent('размещения'), apiLangContent('размещений')),
			  			"period" => apiLangContent("Пакет на") . ' ' . $getPackage["period"] . ' ' . ending($getPackage["period"], apiLangContent('день'), apiLangContent('дня'), apiLangContent('дней')),
			  			"old_total_price" => $old_total_price ?: null,
			  		];
			 	}
			  }

		}

	}

}

echo json_encode(['packages'=>$packages]);

?>