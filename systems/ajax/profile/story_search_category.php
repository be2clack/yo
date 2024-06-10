<?php

$query = clearSearchBack($_POST["search"]);

if($query && mb_strlen($query, 'UTF-8') > 2){
  
  $getCategories = getAll("select * from uni_category_board where category_board_visible=? and category_board_name LIKE '%$query%'", [1]);

  if($getCategories){
    foreach ($getCategories as $value) {
        ?>
        <div data-name="<?php echo $ULang->t( $value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ); ?>" id-cat="<?php echo $value["category_board_id"]; ?>" ><?php echo $ULang->t( $value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ); ?></div>
        <?php
    }
  }

}else{

  $getCategories = $CategoryBoard->getCategories("where category_board_visible=1");

  if($getCategories){
    foreach ($getCategories["category_board_id_parent"][0] as $value) {
        ?>
        <div data-name="<?php echo $ULang->t( $value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ); ?>" id-cat="<?php echo $value["category_board_id"]; ?>" ><?php echo $ULang->t( $value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ); ?></div>
        <?php
    }
  }

}

?>