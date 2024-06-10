<?php
function outCategoryOptionsDelivery($id_parent = 0, $level = 0, $getCategories = []) {
    global $settings;
    
    if (isset($getCategories["category_board_id_parent"][$id_parent])) {
        foreach ($getCategories["category_board_id_parent"][$id_parent] as $value) {

            $selected = "";

            if($settings['delivery_available_categories']){
                if(in_array($value["category_board_id"], explode(',', $settings['delivery_available_categories']))){
                    $selected = 'selected=""';
                }
            }

            while ($x++<$level) $retreat .= "-";

            echo '<option '.$selected.' value="' . $value["category_board_id"] . '" >'.$retreat.$value["category_board_name"].'</option>';

            $level++;
            
            outCategoryOptionsDelivery($value["category_board_id"], $level, $getCategories);
            
            $level--;
            
        }
    }
}
?>