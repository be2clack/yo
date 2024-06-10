<?php

$getFilters = (new Filters())->getFilters();
$getCategories = (new CategoryBoard())->getCategories();

function outCategory($id_parent = 0, $level = 0) {
    
    global $getCategories, $settings;

    if (isset($getCategories["category_board_id_parent"][$id_parent])) {
        foreach ($getCategories["category_board_id_parent"][$id_parent] as $value) {

            $labels = "";
            $plus = "";
            $visible = '';
       
            $itemsBuild = itemsBuild($value["category_board_id"]);

            if($getCategories["category_board_id_parent"][$value["category_board_id"]]){
               $plus = '<i class="la la-plus icon-open-cat load-podcategory" data-id-cat="' . $value["category_board_id"] . '" data-status="hide" data-ids="'.$itemsBuild.'" ></i>'; 
            }

            if($value["category_board_status_paid"]){
               $status_paid = '<td><span class="status-check-circle" ><i class="la la-check"></i><span></td>';
            }else{ $status_paid = '<td><span class="status-times-circle" ><i class="la la-times"></i><span></td>'; }
            
            if($value["category_board_secure"]){
               $status_secure = '<td><span class="status-check-circle" ><i class="la la-check"></i><span></td>';
            }else{ $status_secure = '<td><span class="status-times-circle" ><i class="la la-times"></i><span></td>'; }
            
            if($settings["functionality"]["auction"]){
              if($value["category_board_auction"]){
                 $status_auction = '<td><span class="status-check-circle" ><i class="la la-check"></i><span></td>';
              }else{ $status_auction = '<td><span class="status-times-circle" ><i class="la la-times"></i><span></td>'; }
            }

            if($settings["functionality"]["marketplace"]){
              if($value["category_board_marketplace"]){
                 $status_marketplace = '<td><span class="status-check-circle" ><i class="la la-check"></i><span></td>';
              }else{ $status_marketplace = '<td><span class="status-times-circle" ><i class="la la-times"></i><span></td>'; }
            }

            if($settings["functionality"]["booking"]){
              if($value["category_board_booking"]){
                 $status_booking = '<td><span class="status-check-circle" ><i class="la la-check"></i><span></td>';
              }else{ $status_booking = '<td><span class="status-times-circle" ><i class="la la-times"></i><span></td>'; }
            }

            if($value["category_board_show_index"]){
              $status_show_index = '<td><span class="status-check-circle" ><i class="la la-check"></i><span></td>';
            }else{ $status_show_index = '<td><span class="status-times-circle" ><i class="la la-times"></i><span></td>'; }

            echo 
            '<tr id="item' . $value["category_board_id"] . '" class="item-load-podcategory" >
               <td><div style="margin-left:'. $level .'px;" class="category-box-title" > <span class="icon-move move-sort" ><i class="la la-arrows-v"></i></span> <a style="position: relative;" >'.$value["category_board_name"].$plus.'<div class="box-labels" >'.$labels.'</div></a></div></td>
               '.$status_paid.'
               '.$status_secure.'                         
               '.$status_auction.'     
               '.$status_marketplace.' 
               '.$status_booking.'                  
               '.$status_show_index.'                        
               <td class="td-actions" style="text-align: right;" >
                <a href="?route=edit_category_board&id='.$value['category_board_id'].'"><i class="la la-edit edit"></i></a>
                <a href="#" class="delete-board-category" data-id="'.$value['category_board_id'].'" ><i class="la la-close delete"></i></a>
               </td>
            </tr>';

        }
    }
}

function outCategoryOptions($id_parent = 0, $level = 0) {
    
    global $getCategories,$cat_ids;

    if (isset($getCategories["category_board_id_parent"][$id_parent])) {
        foreach ($getCategories["category_board_id_parent"][$id_parent] as $value) {

            $selected = "";

            if($cat_ids){
                if( in_array($value["category_board_id"], $cat_ids) ){
                    $selected = 'selected=""';
                }
            }

            while ($x++<$level) $retreat .= "-";

            echo '<option '.$selected.' value="' . $value["category_board_id"] . '" >'.$retreat.$value["category_board_name"].'</option>';

            $level++;
            
            outCategoryOptions($value["category_board_id"], $level);
            
            $level--;
            
        }
    }
}

function itemsBuild($parent_id=0){
    
    global $getCategories;

    $ids = [];
             
    if(isset($getCategories['category_board_id_parent'][$parent_id])){

          foreach($getCategories['category_board_id_parent'][$parent_id] as $cat){

            $ids[] = "#item" . $cat['category_board_id'];
            
            if( $getCategories['category_board_id_parent'][$cat['category_board_id']] ){
              $ids[] = itemsBuild($cat['category_board_id']);
            }
                                                                
          }

    }

    return implode(",", $ids);

}

function idsBuild($parent_id=0){
    
    global $getCategories;

    $ids = [];
      
    if(isset($getCategories['category_board_id_parent'][$parent_id])){

          foreach($getCategories['category_board_id_parent'][$parent_id] as $cat){
            
            $ids[] = $cat['category_board_id'];
            
            if( $getCategories['category_board_id_parent'][$cat['category_board_id']] ){
              $ids[] = idsBuild($cat['category_board_id']);
            }
                                                                
          }

    }

    return implode(",", $ids);

}

function outFilters($filters=[],$parent_id=0,$margin=0){
  global $getCategories;

        if(!empty($filters['id_parent'])){               
           foreach($filters['id_parent'][$parent_id] as $value){

              $list_category = [];

              $getCategory = getAll("select * from uni_ads_filters_category INNER JOIN `uni_category_board` ON `uni_category_board`.category_board_id = `uni_ads_filters_category`.ads_filters_category_id_cat where ads_filters_category_id_filter=?", [$value["ads_filters_id"]]);
              
               if( $getCategory ){
                  foreach ($getCategory as $value_category) {
                     $list_category[] = $value_category["category_board_name"];
                  }
               }

              if($value["ads_filters_type"] == "select"){
                  $plus_podfilter = '<a class="action-load-add-podfilter" data-toggle="modal" data-target="#modal-add-podfilter" data-id-filter="'.$value["ads_filters_id"].'" title="Добавить подфильтр" ><i class="la la-plus edit"></i></a>';
              }else{
                  $plus_podfilter = '';
              }

              if( ($value["ads_filters_type"] == "select" || $value["ads_filters_type"] == "select_multi") && !$value["ads_filters_id_parent"] && $list_category ){
                  $edit_alias = '<a href="#" class="action-load-alias-filter" data-id="'.$value["ads_filters_id"].'" data-toggle="modal" data-target="#modal-alias-filters" title="Алиасы фильтра" ><i class="la la-filter edit"></i></a>';
              }else{
                  $edit_alias = '';
              }

              if($value["ads_filters_visible"]){
                 $status = '<span class="badge-text badge-text-small info" >Активен</span>';
              }else{
                 $status = '<span class="badge-text badge-text-small danger" >Не активен</span>';
              }

              if($parent_id == 0){
                 $copy = '<a href="#" class="action-load-copy-filter" data-id="'.$value["ads_filters_id"].'" data-toggle="modal" data-target="#modal-load-copy-filter" title="Копировать фильтр" ><i class="la la-copy edit"></i></a>';
              }else{
                 $copy = '';
              }

              if($value["ads_filters_id_parent"]){
                 $edit = '<a href="#" class="action-load-edit-podfilter" data-id="'.$value["ads_filters_id"].'" data-id-parent="'.$parent_id.'" data-toggle="modal" data-target="#modal-edit-podfilter" title="Редактировать" ><i class="la la-edit edit"></i></a>';
              }else{
                 $edit = '<a href="#" class="action-load-edit-filter" data-id="'.$value["ads_filters_id"].'" data-toggle="modal" data-target="#modal-edit-filter" title="Редактировать" ><i class="la la-edit edit"></i></a>';
              }


               if(count($getCategory) > 3){
                  $all_category = '<div class="filters-list-category-hide" >'.implode("<br>",$list_category).'</div> <span class="filters-list-category-toggle" >Все категории</span>';
               }else{
                  $all_category = implode("<br>",$list_category);
               }

               if($_GET["cat_id"]){
                  $move_sort = '<span class="icon-move move-sort-filter" ><i class="la la-arrows-v"></i></span>';
               }

               $item .= '
                <tr id="item'.$value["ads_filters_id"].'" >
                   <td>
                   '.$move_sort.' 
                   <a style="margin-left: '.$margin.'px;" >'.$value["ads_filters_name"].'</a>
                   </td>
                   <td> '.$all_category.' </td>    
                   <td>'.$status.'</td>                      
                   <td class="td-actions"  style="text-align: right;"  >
                    '.$plus_podfilter.'
                    '.$edit_alias.'
                    '.$edit.'
                    '.$copy.'
                    <a class="filter-delete" data-id="'.$value["ads_filters_id"].'" title="Удалить фильтр" ><i class="la la-close delete"></i></a>
                   </td>
                </tr>
                ';        
                      
               $item .= outFilters($filters,$value['ads_filters_id'],$margin + 15);    
                                                                            
           }
        }

  return $item;   
}

function outCategoryDropdown($id_parent = 0, $level = 0) {
    
    global $getCategories;

    $Ads = new Ads();

    if (isset($getCategories["category_board_id_parent"][$id_parent])) {
        foreach ($getCategories["category_board_id_parent"][$id_parent] as $value) {
          
            if($_GET["cat_id"]){
              if($_GET["cat_id"] == $value["category_board_id"]){
                $selected = 'active';
              }else{
                $selected = "";
              }
            }
            
            $idsBuild = idsBuild($value["category_board_id"]);
            
            $countAd = $Ads->getCount("ads_id_cat IN(".idsBuildJoin($idsBuild,$value['category_board_id']).") and clients_status!='3' and ads_status!='8'");

            while ($x++<$level) $retreat .= "-";
            
            echo '<a class="dropdown-item '.$selected.'" href="?route=board&cat_id=' . $value["category_board_id"] . '&cat_name=' . $value["category_board_name"] . '">'.$retreat.$value["category_board_name"].' (' . $countAd . ')</a>';

            $level++;
            
            outCategoryDropdown($value["category_board_id"], $level);

            $level--;

        }
    }
}

function outCategoryDropdownFilters($id_parent = 0, $level = 0) {
    
    global $getCategories;

    $Ads = new Ads();

    if (isset($getCategories["category_board_id_parent"][$id_parent])) {
        foreach ($getCategories["category_board_id_parent"][$id_parent] as $value) {
          
            if($_GET["cat_id"]){
              if($_GET["cat_id"] == $value["category_board_id"]){
                $selected = 'active';
              }else{
                $selected = "";
              }
            }
            
            $idsBuild = idsBuild($value["category_board_id"]);
            
            while ($x++<$level) $retreat .= "-";
            
            echo '<a class="dropdown-item '.$selected.'" href="?route=filters&cat_id=' . $value["category_board_id"] . '&cat_name=' . $value["category_board_name"] . '">'.$retreat.$value["category_board_name"].'</a>';

            $level++;
            
            outCategoryDropdownFilters($value["category_board_id"], $level);

            $level--;

        }
    }
}

?>