<?php

$getCategoryBoard = (new CategoryBoard())->getCategories("where category_board_visible=1");

?>

  <div class="container" >
  <div class="row no-gutters" >
     <div class="col-lg-4" >
         <div class="header-big-category-menu-left" >
         <?php

            if(count($getCategoryBoard["category_board_id_parent"][0])){
                  foreach ($getCategoryBoard["category_board_id_parent"][0] as $key => $value) {

                    ?>
                     <div data-id="<?php echo $value["category_board_id"]; ?>" >

                        <a href="<?php echo $CategoryBoard->alias($value["category_board_chain"]); ?>" >
                        <?php if( $value["category_board_image"] ){ ?>
                        <div class="category-menu-left-image" >
                          <img src="<?php echo Exists($config["media"]["other"],$value["category_board_image"],$config["media"]["no_image"]); ?>" >
                        </div>
                        <?php } ?>
                        <div class="category-menu-left-name" ><?php echo $ULang->t( $value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ); ?><span class="header-big-category-count" ><?php echo $CategoryBoard->getCountAd( $value["category_board_id"] ); ?></span></div>
                        <div class="clr" ></div>
                        </a>

                     </div>
                    <?php

                  }
            }

         ?>
         </div>
     </div>
     <div class="col-lg-8" >
         <div class="header-big-category-menu-right" >

         <?php

            $count_key = 0;

            if(count($getCategoryBoard["category_board_id_parent"][0])){
                  foreach ($getCategoryBoard["category_board_id_parent"][0] as $key => $value) {

                       if( $getCategoryBoard["category_board_id_parent"][ $value["category_board_id"] ] ){
                            
                            $show = '';

                            if( $count_key == 0 ){
                                $show = ' style="display: block;" ';
                            }

                            $count_key++;

                            echo '
                              <div class="header-big-subcategory-menu-list" '.$show.' data-id-parent="'.$value["category_board_id"].'" >
                              <h4>'.$Seo->replace($ULang->t( $value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] )).'</h4>
                              <div class="row no-gutters" >
                            ';

                            foreach ($getCategoryBoard["category_board_id_parent"][ $value["category_board_id"] ] as $subvalue1) {

                                echo '
                                   <div class="col-lg-6" >
                                   <div data-id="'.$subvalue1["category_board_id"].'" >
                                     <a href="'.$CategoryBoard->alias($subvalue1["category_board_chain"]).'">'.$ULang->t( $subvalue1["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ).'<span class="header-big-category-count" >'.$CategoryBoard->getCountAd( $subvalue1["category_board_id"] ).'</span></a>
                                   </div>
                                   </div>
                                ';

                            }

                            echo '
                              </div>
                              </div>
                            ';

                       }

                  }
            }

         ?>

         </div>
     </div>
  </div>
  </div>