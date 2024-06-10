<div class="left-list-category" >
 
     <?php
     if(count($getCategoryBoard["category_board_id_parent"][0])){
          foreach ($getCategoryBoard["category_board_id_parent"][0] as $key => $value) {

            ?>
                <a href="<?php echo $CategoryBoard->alias($value["category_board_chain"]); ?>" ><?php echo $ULang->t( $value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ); ?> <span class="category-label-count" ><?php echo $CategoryBoard->getCountAd( $value["category_board_id"] ); ?></span></a>
            <?php

          }
     }
     ?>

</div>

<?php echo $Banners->out( ["position_name"=>"index_sidebar"] ); ?>