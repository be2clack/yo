<!doctype html>
<html lang="<?php echo getLang(); ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <meta name="description" content="<?php echo $Seo->out(array("page" => "index", "field" => "meta_desc")); ?>">
    <meta property="og:image" content="<?php echo $settings["logotip"]; ?>">

    <title><?php echo $Seo->out(array("page" => "index", "field" => "meta_title")); ?></title>
    
    <?php include $config["template_path"] . "/head.tpl"; ?>

  </head>

  <body data-prefix="<?php echo $config["urlPrefix"]; ?>" data-template="<?php echo $config["template_folder"]; ?>" data-header-sticky="true" data-type-loading="<?php echo $settings["type_content_loading"]; ?>" data-page-name="<?php echo $route_name; ?>" >

    <?php include $config["template_path"] . "/header.tpl"; ?>
    
    <div class="container mt15" >

       <div class="row" >

          <div class="<?php if($settings["home_sidebar_status"]){ echo 'col-lg-10 col-12'; }else{ echo 'col-lg-12'; } ?>" >

           <?php
              
              echo $Banners->out( ["position_name"=>"index_top"] );
       
              foreach ($settings["home_widget_sorting"] as $key => $widgetName) {

                if($widgetName == "category_slider" && $settings["home_category_slider_status"]){

                   ?>
                   <div class="d-none d-lg-block" >
                     
                     
                   
                   
<div class="inner-position-not-empty main-inner-position-not-empty">
                       <div class="theme-default">
                           <div class="rubricator-grid">
                               <?php
                               $medium_indices = [1, 3, 5, 7, 10, 11, 14]; // Порядковые номера, для которых нужно применить класс item-medium
                               $counter = 0;
                               foreach ($getCategoryBoard["category_board_id_parent"][0] as $key => $value):
                                   if ($counter >= 14) break; // Ограничиваем вывод первыми 14 значениями
                                   $counter++;
                                   $item_class = in_array($counter, $medium_indices) ? 'medium' : 'small'; // Определяем класс элемента в зависимости от порядкового номера
                               ?>
                               <a class="rubricator-grid-item item-<?php echo $item_class; ?>" style="background-color: <?php echo generateRandomColor(); ?>;grid-row:<?php echo ($counter <= 7) ? 1 : 2; ?>" href="<?php echo $CategoryBoard->alias($value["category_board_chain"]); ?>">
                                   <div class="rubricator-item-title">
                                       <article>
                                           <p class="item-title"><?php echo $ULang->t($value["category_board_name"], ["table" => "uni_category_board", "field" => "category_board_name"]); ?></p>
                                       </article>
                                   </div>
                                   <img alt="<?php echo $ULang->t($value["category_board_name"], ["table" => "uni_category_board", "field" => "category_board_name"]); ?>" src="<?php echo Exists($config["media"]["other"], $value["category_board_image"], $config["media"]["no_image"]); ?>">
                               </a>
                               <?php endforeach; ?>
                           </div>
                       </div>
                   </div>



                   
                   
                   </div>
                   
                   <?php

                }elseif($widgetName == "stories" && $settings["home_stories_status"] && $settings["user_stories_status"]){

                    echo $Profile->outUserStories(true);

                }elseif($widgetName == "shop" && $settings["home_shop_status"]){

                    $data["shops"] = getAll("select * from uni_clients_shops INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_clients_shops`.clients_shops_id_user where (clients_shops_time_validity > now() or clients_shops_time_validity IS NULL) and clients_shops_status=1 and clients_status IN(0,1) order by rand() limit ?", [$settings["index_out_count_shops"] ?: 16]);

                    if($data["shops"]){ ?>
                    <div class="mb25 title-and-link h3" > <strong><?php echo $ULang->t( "Магазины" ); ?></strong> <a href="<?php echo $Shop->linkShops(); ?>"><?php echo $ULang->t( "Все магазины" ); ?> <i class="las la-arrow-right"></i> </a> </div>
                      <div class="row no-gutters gutters10 mb25" >
                          <?php 
                          
                             foreach ($data["shops"] as $key => $value) {
                                 include $config["template_path"] . "/include/shop_grid.php";
                             }
                          
                          ?>
                      </div>
                    <?php
                    }

                }elseif($widgetName == "promo" && $settings["home_promo_status"]){
                    
                    $data["sliders"] = getAll("select * from uni_sliders where sliders_visible=? order by sliders_sort asc", [1]);

                    if($data["sliders"]){
                        ?>
                        <div class="load-sliders-wide mb25" >
                        <div class="sliders-wide" >
                           
                           <?php
                           foreach ($data["sliders"] as $key => $value) {
                               ?>
                               <a title="<?php echo $ULang->t( $value["sliders_title1"] , [ "table"=>"uni_sliders", "field"=>"sliders_title1" ] ); ?>. <?php echo $ULang->t( $value["sliders_title2"] , [ "table"=>"uni_sliders", "field"=>"sliders_title2" ] ); ?>" target="_blank"  href="<?php echo $Main->sliderLink( $value["sliders_link"] ); ?>">
                                     <div class="sliders-wide-item" style="background-color: <?php echo $value["sliders_color_bg"]; ?>;" data-id="<?php echo $value["sliders_id"]; ?>" >

                                        <span class="sliders-wide-title">
                                          <span class="sliders-wide-title1" style="color: <?php echo $value["sliders_color_text"] ?: 'color: black;'; ?>!important;" ><?php echo $ULang->t( $value["sliders_title1"] , [ "table"=>"uni_sliders", "field"=>"sliders_title1" ] ); ?></span>
                                          <span class="sliders-wide-title2" style="color: <?php echo $value["sliders_color_text"] ?: 'color: black;'; ?>!important;" ><?php echo $ULang->t( $value["sliders_title2"] , [ "table"=>"uni_sliders", "field"=>"sliders_title2" ] ); ?></span>
                                        </span>

                                        <?php if($value["sliders_image"]){ ?>
                                        <img src="<?php echo Exists($config["media"]["other"],$value["sliders_image"],$config["media"]["no_image"]); ?>">
                                        <?php } ?>

                                    </div> 
                                </a>              
                               <?php
                           }
                           ?>
                        </div>
                        </div>
                     <?php
                    }

                }elseif($widgetName == "vip" && $settings["home_vip_status"]){
                    
                    if($settings["main_type_products"] == 'physical'){
                       $geo = $Ads->queryGeo() ? " and " . $Ads->queryGeo() : "";
                    }
                    
                    if($settings["index_out_content_method"] == 0){
                      $data["vip"] = $Ads->getAll( ["query"=>"ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_vip='1' order by rand() limit 16", "param_search" => $param_search, "output" => 16 ] );
                    }else{
                      $data["vip"] = $Ads->getAll( ["query"=>"ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_vip='1' $geo order by rand() limit 16", "param_search" => $param_search, "output" => 16 ] );
                    }

                    if($settings["main_type_products"] == 'physical'){
                        if($_SESSION["geo"]["alias"]){
                          $data["vip_link"] = _link($_SESSION["geo"]["alias"]."/vip");
                        }else{
                          $data["vip_link"] = _link($settings["country_default"]."/vip"); 
                        }
                    }else{
                        $data["vip_link"] = _link("vip");
                    }

                    if($data["vip"]["count"]){
                        ?>
                        <div class="vip-block mb25" >
                          <div class="mb25 title-and-link h3" > <strong><?php echo $ULang->t( "VIP объявления" ); ?></strong> <a href="<?php echo $data["vip_link"]; ?>" ><?php echo $ULang->t( "Больше объявлений" ); ?> <i class="las la-arrow-right"></i></a> </div>
                          <div class="slider-item-grid init-slider-grid" >
                              <?php 
                              
                                 foreach ( $data["vip"]["all"] as $key => $value) {
                                     include $config["template_path"] . "/include/vip_ad_grid.php";
                                 }
                              
                              ?>
                          </div>
                        </div>                    
                        <?php
                    }                 

                }elseif($widgetName == "blog" && $settings["home_blog_status"]){

                    $data["articles"] = $Blog->getAll( ["query"=>"blog_articles_visible=1", "sort"=>"order by rand() limit 9"] );

                    if($data["articles"]["count"]){
                        ?>
                          <div class="mb25 title-and-link h3" > <strong><?php echo $ULang->t( "Блог" ); ?></strong> <a href="<?php echo _link("blog"); ?>" ><?php echo $ULang->t( "Наш блог" ); ?> <i class="las la-arrow-right"></i></a> </div>
                          <div class="slider-item-grid init-slider-grid mb25" >
                              <?php 
                              
                                 foreach ( $data["articles"]["all"] as $key => $value) {
                                     include $config["template_path"] . "/include/slider_articles_blog.php";
                                 }
                              
                              ?>
                          </div>                    
                        <?php
                    }
                    
                }elseif($widgetName == "category_ads" && $settings["home_category_ads_status"]){

                    $data["slider_ad_category"] = $Main->outSlideAdCategory(16);

                    if($data["slider_ad_category"]){
                        foreach ($data["slider_ad_category"] as $id_category => $nested) {
                            ?>
                              <div class="mb25 title-and-link h3" > <strong><?php echo $ULang->t( $getCategoryBoard["category_board_id"][$id_category]["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ); ?></strong> 
                              <a href="<?php echo $CategoryBoard->alias($getCategoryBoard["category_board_id"][$id_category]["category_board_chain"]); ?>" >
                                    <?php echo $ULang->t( "Больше объявлений" ); ?> <i class="las la-arrow-right"></i>
                              </a>                            
                              </div>
                              <div class="slider-item-grid init-slider-grid mb25" >
                                  <?php 
                                  
                                    foreach ($nested as $key => $value) {
                                        include $config["template_path"] . "/include/slider_ad_grid.php";
                                    }
                                  
                                  ?>
                              </div>
                            <?php

                        }
                    }                
                    
                }elseif($widgetName == "auction" && $settings["home_auction_status"]){
                    
                    if($settings["main_type_products"] == 'physical'){
                       $geo = $Ads->queryGeo() ? " and " . $Ads->queryGeo() : "";
                    }
                    
                    if($settings["index_out_content_method"] == 0){
                      $data["auction"] = $Ads->getAll( ["query"=>"ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_auction='1' order by rand() limit 16", "output" => 16 ] );
                    }else{
                      $data["auction"] = $Ads->getAll( ["query"=>"ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_auction='1' $geo order by rand() limit 16", "output" => 16 ] );
                    }

                    if($settings["main_type_products"] == 'physical'){
                        if($_SESSION["geo"]["alias"]){
                          $data["auction_link"] = _link($_SESSION["geo"]["alias"]."/auction");
                        }else{
                          $data["auction_link"] = _link($settings["country_default"]."/auction"); 
                        }
                    }else{
                        $data["auction_link"] = _link("auction");
                    }

                    if($data["auction"]["count"]){
                        ?>
                        <div class="auction-block mb25" >
                          <div class="mb25 title-and-link h3" > <strong><?php echo $ULang->t( "Аукционы" ); ?></strong> <a href="<?php echo $data["auction_link"]; ?>" ><?php echo $ULang->t( "Больше объявлений" ); ?> <i class="las la-arrow-right"></i></a> </div>
                          <div class="slider-item-grid init-slider-grid" >
                              <?php 
                              
                                 foreach ( $data["auction"]["all"] as $key => $value) {
                                     include $config["template_path"] . "/include/auction_ad_grid.php";
                                 }
                              
                              ?>
                          </div>
                        </div>                    
                        <?php
                    }                 

                }
            }
           ?>

           <?php echo $Banners->out( ["position_name"=>"index_center"] ); ?>

          <div class="home-tabs-ads" >
              <div data-tab="recommendations" class="active" ><?php echo $ULang->t( "Рекомендации" ); ?></div>
              <div data-tab="fresh" ><?php echo $ULang->t( "Свежие" ); ?></div>
          </div>

          <div class="catalog-results" >
          
              <div class="preload" >

                  <div class="spinner-grow mt35 preload-spinner" role="status">
                    <span class="sr-only"></span>
                  </div>

              </div>

          </div>

          <h1 style="font-size: 1.75rem;" class="mb25 mt35" > <strong><?php echo $data["h1"]; ?></strong> </h1>

          <div class="schema-text" >
             <?php if($data["seo_text"]){ ?> <div class="mt35" > <?php echo $data["seo_text"]; ?> </div> <?php } ?>
          </div>

          <?php echo $Banners->out( ["position_name"=>"index_bottom"] ); ?>

          </div>

          <?php if($settings["home_sidebar_status"]){ ?>
          <div class="col-lg-2 d-none d-lg-block" >

             <?php include $config["template_path"] . "/index_sidebar.tpl"; ?>

          </div>
          <?php } ?>
          
       </div>

    </div>

    <div class="mt35" ></div>

    <?php include $config["template_path"] . "/footer.tpl"; ?>

  </body>
</html>