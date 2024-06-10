<?php

$filters = $_POST["filter"] ? $_POST["filter"] : [];
$id_c = (int)$_POST["id_c"];

unset($_POST["_"]);
unset($_POST["page"]);
unset($_POST["action"]);
unset($_POST["id_c"]);
unset($_POST["search"]);

$getCategories = $CategoryBoard->getCategories("where category_board_visible=1");

if($filters){
foreach ($filters as $id_filter => $nested) {

  if( is_array($nested) ){

      foreach ($nested as $key => $value) {
        if($value){
            $count_change_fl += 1;
            $id_filter_item = $value;
        } 
      }

  }else{

     $count_change_fl += 1;

  }

}
}

$filters = http_build_query($_POST, 'flags_');

$filters = $filters ? "?" . $filters : "";

if($id_c){
   
   $params = $CategoryBoard->alias( $getCategories['category_board_id'][$id_c]['category_board_chain'] ) . $filters;
   
}else{

   if($settings["main_type_products"] == 'physical'){

       if($_SESSION["geo"]["alias"]){
          $params = _link($_SESSION["geo"]["alias"]) . $filters;
       }else{
          $params = _link($settings["country_default"]) . $filters; 
       }

   }else{

       $params = _link('catalog') . $filters;

   }

}

if($count_change_fl == 1 && $id_c){

$getAlias = findOne("uni_ads_filters_alias", "ads_filters_alias_id_filter_item=? and ads_filters_alias_id_cat=?", [ intval($id_filter_item),$id_c ]);

if($getAlias){

    echo json_encode( [ "params" => $Filters->alias( ["category_alias"=>$getCategories['category_board_id'][$id_c]['category_board_chain'], "filter_alias"=>$getAlias["ads_filters_alias_alias"]] ) . $var, "count" => $count_change_fl ] );

}else{
    echo json_encode( [ "params" => $params, "count" => $count_change_fl ] );
}

}else{

echo json_encode( [ "params" => $params, "count" => $count_change_fl ] );

}

?>