<?php

$results = [];
$chainNested = [];

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");


if(count($getCategoryBoard)){

	foreach ($getCategoryBoard["category_board_id"] as $key => $value) {

		$chainNested = [];

		$breadcrumb = breadcrumbCategories($getCategoryBoard,$value['category_board_id']);

		if($getCategoryBoard['category_board_id_parent'][$value['category_board_id']]){

			foreach ($getCategoryBoard['category_board_id_parent'][$value['category_board_id']] as $nested) {

				$chainNested[] = [
					'category_board_id' => $nested['category_board_id'],
					'category_board_name' => $ULang->tApp($nested['category_board_name'], [ "table" => "uni_category_board", "field" => "category_board_name"]),
					'category_board_name_word_wrap' => $nested['category_board_name_word_wrap'] ? explode("|", $nested['category_board_name_word_wrap']) : null,
					'category_board_image' => $nested["category_board_image"] ? Exists($config["media"]["other"],$nested["category_board_image"],$config["media"]["no_image"]) : '',
					'category_board_id_parent' => $nested['category_board_id_parent'],
					'subcategory' => $getCategoryBoard['category_board_id_parent'][$nested['category_board_id']] ? true : false,
					'breadcrumb' => $breadcrumb ? $breadcrumb." - ".$ULang->tApp($nested['category_board_name'], [ "table" => "uni_category_board", "field" => "category_board_name"]) : $ULang->tApp($nested['category_board_name'], [ "table" => "uni_category_board", "field" => "category_board_name"]),
				];

			}

		}		

		$results["category"][$value['category_board_id']] = [
			'category_board_id' => $value['category_board_id'],
			'category_board_name' => $ULang->tApp($value['category_board_name'], [ "table" => "uni_category_board", "field" => "category_board_name"]),
			'category_board_name_word_wrap' => $value['category_board_name_word_wrap'] ? explode("|", $value['category_board_name_word_wrap']) : null,
			'category_board_image' => $value["category_board_image"] ? Exists($config["media"]["other"],$value["category_board_image"],$config["media"]["no_image"]) : '',
			'category_board_id_parent' => $value['category_board_id_parent'],
			'subcategory' => $getCategoryBoard['category_board_id_parent'][$value['category_board_id']] ? true : false,
			'breadcrumb' => $breadcrumb,
			'nested' => $chainNested ?: null,
		];

		$results["parent"][$value['category_board_id_parent']][] = [
			'category_board_id' => $value['category_board_id'],
			'category_board_name' => $ULang->tApp($value['category_board_name'], [ "table" => "uni_category_board", "field" => "category_board_name"]),
			'category_board_name_word_wrap' => $value['category_board_name_word_wrap'] ? explode("|", $value['category_board_name_word_wrap']) : null,
			'category_board_image' => $value["category_board_image"] ? Exists($config["media"]["other"],$value["category_board_image"],$config["media"]["no_image"]) : '',
			'category_board_id_parent' => $value['category_board_id_parent'],
			'subcategory' => $getCategoryBoard['category_board_id_parent'][$value['category_board_id']] ? true : false,
			'breadcrumb' => $breadcrumb,
			'nested' => $chainNested ?: null,
		];

	}

	echo json_encode(['data'=>$results ?: null]);

}else{

	echo json_encode(['data'=>$results ?: null]);
	
}

?>