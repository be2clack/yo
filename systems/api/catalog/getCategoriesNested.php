<?php

$idCat = (int)$_GET["id"];

$results = [];

$getCategoryBoard = getAll("select * from uni_category_board where category_board_visible=? order by category_board_id_position desc", [1]);

if($getCategoryBoard){

	foreach ($getCategoryBoard as $key => $value) {
		$results[$value["category_board_id_parent"]][] = ["name"=>$ULang->tApp($value['category_board_name'], [ "table" => "uni_category_board", "field" => "category_board_name"]), "id"=>(int)$value['category_board_id']];
	}

	echo json_encode(['data'=>$results]);

}else{

	echo json_encode(['data'=>$results ?: null]);
	
}

?>