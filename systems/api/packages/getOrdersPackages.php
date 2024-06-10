<?php

$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$active = [];
$completed = [];

$getCategoryBoard = $Profile->getPackagesCategories();

$getActive = getAll("SELECT * FROM uni_ads_packages_orders where user_id=? and status_pay=? and completion_date > ? order by id desc", [$idUser,1,date("Y-m-d H:i:s")]);

if(count($getActive)){
	foreach ($getActive as $value) {

		$getCategory = findOne("uni_category_board", "category_board_id=?", [$value["cat_id"]]);
		$getPackage = findOne("uni_ads_packages", "id=?", [$value["package_id"]]);

		$dateDiff = $Ads->dateDiff($value["completion_date"]);
		$progress = ((time() - strtotime($value["create_date"])) / (strtotime($value["completion_date"]) - strtotime($value["create_date"]))) * 10;
		$countAds = (int)getOne("select count(*) as total from uni_ads_packages_placements where user_id=? and cat_id=? and order_id=?", [$idUser, $value["cat_id"],$value["id"]])["total"];

        if($progress >= 100 || $progress >= 10){
        	$progressLine = '1.0';
        }else{
        	$progressLine = '0.' . str_replace('.', '' , $progress);
        }

        $breadcrumb = breadcrumbCategories($getCategoryBoard,$value["cat_id"]);

		$active[] = [
			"count_ad" => $value["count_ad"] . ' ' . ending($value["count_ad"], apiLangContent('размещение'), apiLangContent('размещения'), apiLangContent('размещений')),
			"create_date" => date("d.m.Y", strtotime($value["create_date"])),
			"remain_day" => $dateDiff["day"] . ' ' . ending($dateDiff["day"], apiLangContent('день'), apiLangContent('дня'), apiLangContent('дней')),
			"progress" => $progressLine,
			"employed" => $countAds . ' ' . apiLangContent("из") . ' ' . $value["count_ad"],
			"cat_id" => $value["cat_id"],
			"category_name" => $ULang->tApp( $getCategory["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ),
			"category_breadcrumb" => $breadcrumb,
		];

	}
}

$getCompleted = getAll("SELECT * FROM uni_ads_packages_orders where user_id=? and status_pay=? and completion_date <= ? order by id desc", [$idUser,1,date("Y-m-d H:i:s")]);

if(count($getCompleted)){
	foreach ($getCompleted as $value) {

		$getCategory = findOne("uni_category_board", "category_board_id=?", [$value["cat_id"]]);
		$getPackage = findOne("uni_ads_packages", "id=?", [$value["package_id"]]);

		$dateDiff = $Ads->dateDiff($value["completion_date"]);
		$countAds = (int)getOne("select count(*) as total from uni_ads_packages_placements where user_id=? and cat_id=? and order_id=?", [$idUser, $value["cat_id"],$value["id"]])["total"];

        $breadcrumb = breadcrumbCategories($getCategoryBoard,$value["cat_id"]);

		$completed[] = [
			"count_ad" => $value["count_ad"] . ' ' . ending($value["count_ad"], apiLangContent('размещение'), apiLangContent('размещения'), apiLangContent('размещений')),
			"create_date" => date("d.m.Y", strtotime($value["create_date"])),
			"remain_day" => $dateDiff["day"] . ' ' . ending($dateDiff["day"], apiLangContent('день'), apiLangContent('дня'), apiLangContent('дней')),
			"progress" => '1.0',
			"employed" => $countAds . ' ' . apiLangContent("из") . ' ' . $value["count_ad"],
			"cat_id" => $value["cat_id"],
			"category_name" => $ULang->tApp( $getCategory["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ),
			"category_breadcrumb" => $breadcrumb,
		];

	}
}

echo json_encode(['active'=>$active?:null, 'completed'=>$completed?:null]);

?>