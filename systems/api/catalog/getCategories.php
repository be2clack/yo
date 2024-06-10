<?php

$idCat = (int)$_GET["id"];

$results = [];

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

if(count($getCategoryBoard)){

	if($idCat){

		foreach ($getCategoryBoard['category_board_id_parent'][$idCat] as $key => $value) {

			$breadcrumb = breadcrumbCategories($getCategoryBoard,$idCat);

			$results[] = [
				'category_board_id' => $value['category_board_id'],
				'category_board_name' => $ULang->tApp($value['category_board_name'], [ "table" => "uni_category_board", "field" => "category_board_name"]),
				'category_board_name_word_wrap' => $value['category_board_name_word_wrap'] ? explode("|", $value['category_board_name_word_wrap']) : null,
				'category_board_image' => $value["category_board_image"] ? Exists($config["media"]["other"],$value["category_board_image"],$config["media"]["no_image"]) : '',
				'category_board_id_parent' => $value['category_board_id_parent'],
				'subcategory' => $getCategoryBoard['category_board_id_parent'][$value['category_board_id']] ? true : false,
				'breadcrumb' => $breadcrumb ? $breadcrumb." - ".$ULang->tApp($value['category_board_name'], [ "table" => "uni_category_board", "field" => "category_board_name"]) : $ULang->tApp($value['category_board_name'], [ "table" => "uni_category_board", "field" => "category_board_name"]),
			];

		}

		echo json_encode(['main'=>false,'title'=>$getCategoryBoard["category_board_id"][$idCat]["category_board_name"], 'data'=>$results ?: null]);

	}else{

		foreach ($getCategoryBoard["category_board_id_parent"][0] as $key => $value) {
			
			$results[] = [
				'category_board_id' => $value['category_board_id'],
				'category_board_name' => $ULang->tApp($value['category_board_name'], [ "table" => "uni_category_board", "field" => "category_board_name"]),
				'category_board_name_word_wrap' => $value['category_board_name_word_wrap'] ? explode("|", $value['category_board_name_word_wrap']) : null,
				'category_board_image' => $value["category_board_image"] ? Exists($config["media"]["other"],$value["category_board_image"],$config["media"]["no_image"]) : '',
				'category_board_id_parent' => $value['category_board_id_parent'],
				'subcategory' => $getCategoryBoard['category_board_id_parent'][$value['category_board_id']] ? true : false,
				'breadcrumb' => $ULang->tApp($value['category_board_name'], [ "table" => "uni_category_board", "field" => "category_board_name"]),
			];

		}

		echo json_encode(['main'=>true, 'data'=>$results ?: null]);

	}

}else{

	echo json_encode(['main'=>true, 'data'=>$results ?: null]);
	
}

?>