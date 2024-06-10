<?php

$getCategories = (new Blog())->getCategories();

function outCategory($id_parent = 0, $level = 0) {
    
    global $getCategories;

    if (isset($getCategories["blog_category_id_parent"][$id_parent])) {
        foreach ($getCategories["blog_category_id_parent"][$id_parent] as $value) {

            $labels = "";
            $plus = "";
            $visible = '';
       
            $itemsBuild = itemsBuild($value["blog_category_id"]);
            $idsBuild = idsBuild($value["blog_category_id"]);

            if($id_parent != 0){ 
              $visible = 'style="display: none;"';
              if($idsBuild){
                  $plus = ' <i class="la la-plus icon-open-cat"></i>';
              }              
            } else { 

              $visible = ''; 
              if( $getCategories["blog_category_id_parent"][$value["blog_category_id"]] ){
                 $plus = '<i class="la la-plus icon-open-cat"></i>'; 
              }

            }

            echo 
            '<tr id="item' . $value["blog_category_id"] . '" parent-id="' . $value["blog_category_id_parent"] . '" '.$visible.' >
               <td><div style="margin-left:'. ($level * 15) .'px;" class="category-box-title" > <span class="icon-move move-sort" ><i class="la la-arrows-v"></i></span> <a style="position: relative; cursor:pointer;" class="board-open-podcat" uid="' . $value["blog_category_id"] . '" status="hide" data-ids="'.$itemsBuild.'" >'.$value["blog_category_name"].$plus.'<div class="box-labels" >'.$labels.'</div></a></div></td>                     
               <td class="td-actions" style="text-align: right;" >
                <a href="?route=edit_category_blog&id='.$value['blog_category_id'].'"><i class="la la-edit edit"></i></a>
                <a href="#" class="delete-category" data-id="'.$value['blog_category_id'].'" ><i class="la la-close delete"></i></a>
               </td>
            </tr>';

            $level++;
            outCategory($value["blog_category_id"], $level);
            $level--;

        }
    }
}

function outCategoryOptions($id_parent = 0, $level = 0) {
    
    global $getCategories,$cat_ids;

    if (isset($getCategories["blog_category_id_parent"][$id_parent])) {
        foreach ($getCategories["blog_category_id_parent"][$id_parent] as $value) {

            $selected = "";

            if($cat_ids){
                if( in_array($value["blog_category_id"], $cat_ids) ){
                    $selected = 'selected=""';
                }
            }

            while ($x++<$level) $retreat .= "-";

            echo '<option '.$selected.' value="' . $value["blog_category_id"] . '" >'.$retreat.$value["blog_category_name"].'</option>';

            $level++;
            
            outCategoryOptions($value["blog_category_id"], $level);
            
            $level--;
            
        }
    }
}

function itemsBuild($parent_id=0){
    
    global $getCategories;

    $ids = [];
             
    if(isset($getCategories['blog_category_id_parent'][$parent_id])){

          foreach($getCategories['blog_category_id_parent'][$parent_id] as $cat){

            $ids[] = "#item" . $cat['blog_category_id'];
            
            if( $getCategories['blog_category_id_parent'][$cat['blog_category_id']] ){
              $ids[] = itemsBuild($cat['blog_category_id']);
            }
                                                                
          }

    }

    return implode(",", $ids);

}

function idsBuild($parent_id=0){
    
    global $getCategories;

    $ids = [];
      
    if(isset($getCategories['blog_category_id_parent'][$parent_id])){

          foreach($getCategories['blog_category_id_parent'][$parent_id] as $cat){
            
            $ids[] = $cat['blog_category_id'];
            
            if( $getCategories['blog_category_id_parent'][$cat['blog_category_id']] ){
              $ids[] = idsBuild($cat['blog_category_id']);
            }
                                                                
          }

    }

    return implode(",", $ids);

}

function outCategoryDropdown($id_parent = 0, $level = 0) {
    
    global $getCategories;

    $Ads = new Ads();

    if (isset($getCategories["blog_category_id_parent"][$id_parent])) {
        foreach ($getCategories["blog_category_id_parent"][$id_parent] as $value) {
          
            if($_GET["cat_id"]){
              if($_GET["cat_id"] == $value["blog_category_id"]){
                $selected = 'active';
              }else{
                $selected = "";
              }
            }
            
            $idsBuild = idsBuild($value["blog_category_id"]);
            
            $countArticle = getOne("select count(*) as total from uni_blog_articles where blog_articles_id_cat IN(".idsBuildJoin($idsBuild,$value['blog_category_id']).")")["total"];

            while ($x++<$level) $retreat .= "-";
            
            echo '<a class="dropdown-item '.$selected.'" href="?route=blog&cat_id=' . $value["blog_category_id"] . '&cat_name=' . $value["blog_category_name"] . '">'.$retreat.$value["blog_category_name"].' (' . intval($countArticle) . ')</a>';

            $level++;
            
            outCategoryDropdown($value["blog_category_id"], $level);

            $level--;

        }
    }
}


?>