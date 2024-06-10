<?php

$id = (int)$_POST['id'];

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

if(isset($getCategoryBoard["category_board_id"][$id]['category_board_id_parent'])){
  ?>
  <span class="mobile-fixed-menu_prev-category" data-id="<?php echo $getCategoryBoard["category_board_id"][$id]['category_board_id_parent']; ?>" ><i class="las la-arrow-left"></i> <?php echo $ULang->t('Назад'); ?></span>
  <?php
}

?>
<a class="mobile-fixed-menu_link-category" href="<?php echo $CategoryBoard->alias($getCategoryBoard["category_board_id"][$id]["category_board_chain"]); ?>" data-parent="false"  >

<span class="mobile-fixed-menu_name-category" ><?php echo $ULang->t('Все категории'); ?></span>
<span class="mobile-fixed-menu_count-category" ><?php echo $CategoryBoard->getCountAd( $id ); ?></span>

</a>
<?php

if(count($getCategoryBoard["category_board_id_parent"][$id])){
foreach ($getCategoryBoard["category_board_id_parent"][$id] as $value) {

   ?>
   <a class="mobile-fixed-menu_link-category" href="<?php echo $CategoryBoard->alias($value["category_board_chain"]); ?>" data-id="<?php echo $value["category_board_id"]; ?>" data-parent="<?php if(isset($getCategoryBoard["category_board_id_parent"][$value["category_board_id"]])){ echo 'true'; }else{ echo 'false'; } ?>"  >
    
    <span class="mobile-fixed-menu_name-category" ><?php echo $ULang->t( $value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ); ?></span>
    <span class="mobile-fixed-menu_count-category" ><?php echo $CategoryBoard->getCountAd( $value["category_board_id"] ); ?></span>

   </a>
   <?php

}
}

?>