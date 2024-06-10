<?php

function outCategoryDropdownPackages($id_parent = 0, $level = 0) {
    
    global $getPackagesCategories;

    $Ads = new Ads();

    if (isset($getPackagesCategories["category_board_id_parent"][$id_parent])) {
        foreach ($getPackagesCategories["category_board_id_parent"][$id_parent] as $value) {
          
            if($_GET["cat_id"]){
              if($_GET["cat_id"] == $value["category_board_id"]){
                $selected = 'active';
              }else{
                $selected = "";
              }
            }
            
            $idsBuild = itemsBuildPackages($value["category_board_id"]);
            
            while ($x++<$level) $retreat .= "-";
            
            echo '<a class="dropdown-item '.$selected.'" href="?route=ad_packages&cat_id=' . $value["category_board_id"] . '&cat_name=' . $value["category_board_name"] . '">'.$retreat.$value["category_board_name"].'</a>';

            $level++;
            
            outCategoryDropdownPackages($value["category_board_id"], $level);

            $level--;

        }
    }
}

function outCategoryOptionsPackages($id_parent = 0, $level = 0, $getCategories = [], $ids=[]) {
    global $settings;
    
    if (isset($getCategories["category_board_id_parent"][$id_parent])) {
        foreach ($getCategories["category_board_id_parent"][$id_parent] as $value) {

            $selected = "";

            if($ids){
                if(in_array($value["category_board_id"], $ids)){
                    $selected = 'selected=""';
                }
            }

            while ($x++<$level) $retreat .= "-";

            echo '<option '.$selected.' value="' . $value["category_board_id"] . '" >'.$retreat.$value["category_board_name"].'</option>';

            $level++;
            
            outCategoryOptionsPackages($value["category_board_id"], $level, $getCategories, $ids);
            
            $level--;
            
        }
    }
}

function itemsBuildPackages($parent_id=0){
    
    global $getPackagesCategories;

    $ids = [];
             
    if(isset($getPackagesCategories['category_board_id_parent'][$parent_id])){

          foreach($getPackagesCategories['category_board_id_parent'][$parent_id] as $cat){

            $ids[] = "#item" . $cat['category_board_id'];
            
            if( $getPackagesCategories['category_board_id_parent'][$cat['category_board_id']] ){
              $ids[] = itemsBuildPackages($cat['category_board_id']);
            }
                                                                
          }

    }

    return implode(",", $ids);

}

?>