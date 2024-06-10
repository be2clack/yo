<?php

$idUser = (int)$_GET["id_user"];
$city_id = (int)$_GET["city_id"];
$region_id = (int)$_GET["region_id"];
$country_id = (int)$_GET["country_id"];

$results = [];

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

if(count($getCategoryBoard)){
	foreach ($getCategoryBoard["category_board_id_parent"][0] as $value) {
		$results['categories'][] = [
			'category_board_id' => $value['category_board_id'],
			'category_board_name' => $ULang->tApp($value['category_board_name'], [ "table" => "uni_category_board", "field" => "category_board_name"]),
			'category_board_image' => $value["category_board_image"] ? Exists($config["media"]["other"],$value["category_board_image"],$config["media"]["no_image"]) : 'null',
			'subcategory' => $getCategoryBoard['category_board_id_parent'][$value['category_board_id']] ? true : false,
			'breadcrumb' => $ULang->tApp($value['category_board_name'], [ "table" => "uni_category_board", "field" => "category_board_name"]),
		];
	}
}

echo json_encode(['categories'=>$results['categories']]);

?>