<?php

$getCategoriesBoard = (new CategoryBoard())->getCategories();
$getCategoriesBlog = (new Blog())->getCategories();

function outCategoryOptionsBoard($id_parent = 0, $level = 0) {
    
    global $getCategoriesBoard,$array_data;

    if (isset($getCategoriesBoard["category_board_id_parent"][$id_parent])) {
        foreach ($getCategoriesBoard["category_board_id_parent"][$id_parent] as $value) {

            $selected = "";

            if($array_data->advertising_ids_cat){
                if( in_array($value["category_board_id"], $array_data->advertising_ids_cat) ){
                    $selected = 'selected=""';
                }
            }

            while ($x++<$level) $retreat .= "-";

            echo '<option '.$selected.' value="' . $value["category_board_id"] . '" >'.$retreat.$value["category_board_name"].'</option>';

            $level++;
            
            outCategoryOptionsBoard($value["category_board_id"], $level);
            
            $level--;
            
        }
    }
}

function outCategoryOptionsBlog($id_parent = 0, $level = 0) {
    
    global $getCategoriesBlog,$array_data;

    if (isset($getCategoriesBlog["blog_category_id_parent"][$id_parent])) {
        foreach ($getCategoriesBlog["blog_category_id_parent"][$id_parent] as $value) {

            $selected = "";

            if($array_data->advertising_ids_cat){
                if( in_array($value["blog_category_id"], $array_data->advertising_ids_cat) ){
                    $selected = 'selected=""';
                }
            }

            while ($x++<$level) $retreat .= "-";

            echo '<option '.$selected.' value="' . $value["blog_category_id"] . '" >'.$retreat.$value["blog_category_name"].'</option>';

            $level++;
            
            outCategoryOptionsBlog($value["blog_category_id"], $level);
            
            $level--;
            
        }
    }
}
?>