<!doctype html>
<html lang="<?php echo getLang(); ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="<?php echo $data["shop"]["clients_shops_desc"]; ?>">

    <title><?php echo $data["meta_title"]; ?></title>

    <?php include $config["template_path"] . "/head.tpl"; ?>

  </head>

  <body data-prefix="<?php echo $config["urlPrefix"]; ?>" data-type-loading="<?php echo $settings["type_content_loading"]; ?>" data-template="<?php echo $config["template_folder"]; ?>" data-page-name="<?php echo $route_name; ?>" >
    
    <?php include $config["template_path"] . "/header.tpl"; ?>

    <div class="mobile-fixed-menu mobile-fixed-menu_shop-filters" >
        <div class="mobile-fixed-menu-header" >
            <span class="mobile-fixed-menu-header-close" ><i class="las la-arrow-left"></i></span>
            <span class="mobile-fixed-menu-header-title" ><?php echo $ULang->t('Категории и фильтры'); ?></span>
        </div>
        <div class="mobile-fixed-menu-content" >
            
            <div class="shop-category-list" >
            <?php
             echo $data["category_list"];
            ?>
            </div>

            <div class="mobile-fixed-menu_shop-filters-container" ></div>

        </div>
    </div>    

    <div class="container" >

        <?php if( $data["activity_shop"] ){ ?>

        <?php if( $data["shop"]["clients_shops_id_user"] == $_SESSION["profile"]["id"] ){ ?>
          <div class="btn-custom-mini btn-color-blue mb15 button-shop-edit-mobile open-modal width100" data-id-modal="modal-shop-edit" > <?php echo $ULang->t("Редактировать магазин"); ?> </div>
        <?php }?>

        <div class="<?php if( count($data["shop_sliders"]) ){ ?>shop-sliders<?php }else{ ?> shop-notsliders <?php } ?>" >
            
            <?php if( count($data["shop_sliders"]) ){ ?>
            <div class="shop-sliders-items" >

               <?php
                  foreach ($data["shop_sliders"] as $key => $value) {
                     ?>
                     <div style="background-image: linear-gradient(rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0.24) 75%, rgba(0, 0, 0, 0.64)), url(<?php echo $config["urlPath"] . "/" . $config["media"]["user_attach"] . "/" . $value["clients_shops_slider_image"]; ?>); height: 450px; width: 100%; background-position: center center; background-size: cover;" ></div>
                     <?php
                  }
               ?>

            </div>
            <?php } ?>

            <div class="shop-sliders-footer" >
                 <div class="row no-gutters" >
                     <div class="col-lg-6 col-12 d-none d-lg-block" >

                         <div class="shop-block-logo" >
                              <div class="shop-block-logo-img" >
                                  <img class="image-autofocus" alt="<?php echo $data["shop"]["clients_shops_title"]; ?>" src="<?php echo Exists($config["media"]["other"], $data["shop"]["clients_shops_logo"], $config["media"]["no_image"]); ?>">
                              </div>
                              <div class="shop-block-logo-name" >
                                   <div>
                                     <h5><?php echo $data["shop"]["clients_shops_title"]; ?></h5>
                                     <span><?php echo $ULang->t("На").' '.$settings["site_name"].' '.$ULang->t("с").' '. date("d.m.Y", strtotime($data["user"]["clients_datetime_add"])); ?></span>
                                   </div>
                              </div>
                              <div class="clr" ></div>
                         </div>

                     </div>
                     <div class="col-lg-6 text-right d-none d-lg-block" >
                         
                         <div class="shop-block-control" >

                              <div class="shop-block-control-indicators" >

                                   <a href="<?php echo _link( "user/" . $data["user"]["clients_id_hash"] . "/reviews" ); ?>" ><?php echo $data["user_reviews"]; ?> <br> <?php echo ending($data["user_reviews"], $ULang->t("Отзыв"), $ULang->t("Отзыва"), $ULang->t("Отзывов")) ?> </a>

                                   <span><?php echo $data["shop_count_ads"]; ?> <br> <?php echo ending($data["shop_count_ads"], $ULang->t("Объявление"), $ULang->t("Объявления"), $ULang->t("Объявлений")) ?> </span>
                                   
                                   <?php if( $data["shop"]["clients_shops_id_user"] == $_SESSION["profile"]["id"] ){ ?>
                                   <span class="open-modal hover-link-shop-subscribers" data-id-modal="modal-shop-subscribers" ><?php echo $data["shop_count_subscriptions"]; ?> <br> <?php echo ending($data["shop_count_subscriptions"], $ULang->t("Подписчик"), $ULang->t("Подписчика"), $ULang->t("Подписчиков")) ?> </span>
                                   <?php }else{ ?>
                                   <span><?php echo $data["shop_count_subscriptions"]; ?> <br> <?php echo ending($data["shop_count_subscriptions"], $ULang->t("Подписчик"), $ULang->t("Подписчика"), $ULang->t("Подписчиков")) ?> </span>
                                   <?php } ?>

                              </div>
                              
                              <?php if( $data["shop"]["clients_shops_id_user"] == $_SESSION["profile"]["id"] ){ ?>
                              <div class="shop-block-control-button open-modal" data-id-modal="modal-shop-edit" > <?php echo $ULang->t("Редактировать магазин"); ?> </div>
                              <?php }else{ ?>
                              <div class="shop-block-control-button user-subscribe" data-shop="<?php echo $data["shop"]["clients_shops_id"]; ?>" data-id="<?php echo $data["shop"]["clients_shops_id_user"]; ?>" > 
                                <?php if( !$data["user_status_subscribe"] ){ echo $ULang->t("Подписаться"); }else{ echo $ULang->t("Отписаться"); } ?> </div>
                              <?php } ?>

                         </div>

                     </div>
                     <div class="col-lg-6 col-12 d-block d-lg-none shop-slide-mobile" >

                         <div class="shop-block-logo" >
                              <div class="shop-block-logo-img" >
                                  <img class="image-autofocus" alt="<?php echo $data["shop"]["clients_shops_title"]; ?>" src="<?php echo Exists($config["media"]["other"], $data["shop"]["clients_shops_logo"], $config["media"]["no_image"]); ?>">
                              </div>
                              <div class="shop-block-logo-name" >
                                   <div>
                                     <h5><?php echo $data["shop"]["clients_shops_title"]; ?></h5>
                                     <span><?php echo $ULang->t("На").' '.$settings["site_name"].' '.$ULang->t("с").' '. date("d.m.Y", strtotime($data["user"]["clients_datetime_add"])); ?></span>
                                   </div>
                              </div>
                              <div class="clr" ></div>
                         </div>

                     </div>                                          
                 </div>
            </div>
          
        </div>

        <div class="d-block d-lg-none shop-mobile-counters" >
        <div class="row mt25" >
             
             <div class="col-lg-4 col-4" >
             <a href="<?php echo _link( "user/" . $data["user"]["clients_id_hash"] . "/reviews" ); ?>" ><?php echo $data["user_reviews"]; ?> <br> <?php echo ending($data["user_reviews"], $ULang->t("Отзыв"), $ULang->t("Отзыва"), $ULang->t("Отзывов")) ?> </a>
             </div>
             
             <div class="col-lg-4 col-4" >
             <span><?php echo $data["shop_count_ads"]; ?> <br> <?php echo ending($data["shop_count_ads"], $ULang->t("Объявление"), $ULang->t("Объявления"), $ULang->t("Объявлений")) ?> </span>
             </div>
             
             <div class="col-lg-4 col-4" >
             <?php if( $data["shop"]["clients_shops_id_user"] == $_SESSION["profile"]["id"] ){ ?>
             <span class="open-modal hover-link-shop-subscribers" data-id-modal="modal-shop-subscribers" ><?php echo $data["shop_count_subscriptions"]; ?> <br> <?php echo ending($data["shop_count_subscriptions"], $ULang->t("Подписчик"), $ULang->t("Подписчика"), $ULang->t("Подписчиков")) ?> </span>
             <?php }else{ ?>
             <span><?php echo $data["shop_count_subscriptions"]; ?> <br> <?php echo ending($data["shop_count_subscriptions"], $ULang->t("Подписчик"), $ULang->t("Подписчика"), $ULang->t("Подписчиков")) ?> </span>
             <?php } ?>
             </div>

        </div>
        </div>        

        <div class="mt20" ></div>

        <nav aria-label="breadcrumb">
 
          <ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">

            <li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
              <a itemprop="item" href="<?php echo $config["urlPath"]; ?>">
              <span itemprop="name"><?php echo $ULang->t("Главная"); ?></span></a>
              <meta itemprop="position" content="1">
            </li>

            <li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
              <a itemprop="item" href="<?php echo $Shop->linkShops(); ?>">
              <span itemprop="name"><?php echo $ULang->t("Магазины"); ?></span></a>
              <meta itemprop="position" content="2">
            </li>

            <?php if($data["breadcrumb"]){ echo $data["breadcrumb"]; }else{ echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><span itemprop="name">'.$ULang->t('Все объявления').'</span><meta itemprop="position" content="3"></li>'; } ?>

          </ol>

        </nav>

        <?php
        $outParent = outParentShopSubcategory($getCategoryBoard, [ "tpl_parent" => '<div class="col-lg-3 col-12 col-md-4 col-sm-4" ><a {ACTIVE} href="{PARENT_LINK}"><div class="catalog-subcategory-image" >{PARENT_IMAGE}</div><div class="catalog-subcategory-name" ><span>{PARENT_NAME} <span class="catalog-subcategory-count-ad" >{COUNT_AD}</span></span></div></a></div>', "tpl" => '{PARENT_CATEGORY}', "shop" => $data["shop"], "category" => $data["category"], "current_category" => $data["current_category"] ]);

        ?>
       
        <?php if( $outParent ){ ?>
        <div class="catalog-subcategory shop-catalog-subcategory mb15 mt25" >
          
              <div class="row" >
              <?php 
                echo $outParent; 
              ?>
              </div>

        </div>
        <?php } ?>

        <div class="mt20" ></div>   

        <?php if($data["tariff"]['services']['shop_page']){ ?> 
        
        <?php if( $data["pages"] ){ ?>
        <div class="shop-list-pages" >
           <a href="<?php echo $Shop->linkShop( $data["shop"]["clients_shops_id_hash"] ); ?>" <?php if(!$data["current_page"]["clients_shops_page_id"]){ echo 'class="active"'; } ?> ><?php echo $ULang->t("Объявления"); ?></a>
           <?php
              foreach ($data["pages"] as $value) {
                 ?>

                 <a <?php if( $data["current_page"]["clients_shops_page_id"] == $value["clients_shops_page_id"] ){ echo 'class="active"'; } ?> href="<?php echo $Shop->aliasPage( $data["shop"]["clients_shops_id_hash"], $value["clients_shops_page_alias"] ); ?>"><?php echo $value["clients_shops_page_name"]; ?></a>

                 <?php
              }

              if( $_SESSION["profile"]["id"] == $data["shop"]["clients_shops_id_user"] ){
                ?>
                <a href="#" class="open-modal link-shop-add-page" data-id-modal="modal-shop-add-page" ><?php echo $ULang->t("Добавить страницу"); ?></a>
                <?php
              }
           ?>
        </div>
        <?php }else{

            if( $_SESSION["profile"]["id"] == $data["shop"]["clients_shops_id_user"] ){
              ?>
              <div class="shop-list-pages" >
              <a href="#" class="open-modal link-shop-add-page" data-id-modal="modal-shop-add-page" ><?php echo $ULang->t("Добавить страницу"); ?></a>
              </div>
              <?php
            }

        }

        }

        if( !$data["current_page"] ){
        ?>
          
        <div class="row" >
            <div class="col-lg-9 col-12 minheight500" >
                
                <div class="row" >
                   <div class="col-lg-9" >
                     <h1 class="catalog-title" id="catalog-results" ><?php echo $data["h1"]; ?></h1>
                   </div>
                   <div class="col-lg-3 text-right" >
                     <div class="catalog-sort" >
                        <div><?php echo $Ads->outSorting(); ?></div>
                     </div>
                   </div>
                </div>
                
                <div class="mt30" ></div>

                <div class="catalog-results" >

                    <div class="preload" >

                        <div class="spinner-grow mt80 preload-spinner" role="status">
                          <span class="sr-only"></span>
                        </div>

                    </div>

                </div>

            </div>
            <div class="col-lg-3 d-none d-lg-block" >

              <?php include $config["template_path"] . "/shop_sidebar.tpl"; ?>

            </div>
        </div>

        <?php }else{ ?>

        <div class="row" >
            <div class="col-lg-12" >
                
                <noindex>
                  
                <h1 class="catalog-title" ><?php echo $data["current_page"]["clients_shops_page_name"]; ?></h1>

                <div class="mt30" ></div>

                <div class="bg-container" >
                
                <?php if( $data["shop"]["clients_shops_id_user"] == $_SESSION["profile"]["id"] ){ ?>
                <div class="shop-page-control" >
                    <span class="shop-page-control-save" data-shop="<?php echo $data["shop"]["clients_shops_id"]; ?>" data-page="<?php echo $data["current_page"]["clients_shops_page_id"]; ?>" > <?php echo $ULang->t("Сохранить"); ?> </span>
                    <span class="shop-page-control-delete" data-shop="<?php echo $data["shop"]["clients_shops_id"]; ?>" data-page="<?php echo $data["current_page"]["clients_shops_page_id"]; ?>" > <?php echo $ULang->t("Удалить"); ?> </span>
                </div>
                <?php } ?>

                <div class="shop-page-text ck-content" id="InlineEditor" ><?php if( $data["current_page"]["clients_shops_page_text"] ){ echo $data["current_page"]["clients_shops_page_text"]; }else{ echo $ULang->t("Ваш текст ..."); } ?></div>

                </noindex>

                </div>

            </div>
        </div>

        <?php } ?>
                   

       <div class="mt50" ></div>


     <?php 

     }else{

          
          if( $data["user"]["clients_status"] == 2 || $data["user"]["clients_status"] == 3 || !$data["shop"]["clients_shops_status"] ){
              ?>
                  <div class="row" >
                    <div class="col-lg-12" >
                       <div class="ads-status-block mt100" >
                         <div class="status-block-icon" >
                            <div><i class="las la-lock"></i></div>
                         </div>
                         <h5><strong><?php echo $ULang->t("Магазин заблокирован"); ?></strong></h5>
                       </div>
                    </div>
                  </div>              
              <?php
          }elseif( strtotime($data["shop"]["clients_shops_time_validity"]) < time() ){
              
              if( $data["shop"]["clients_shops_id_user"] == $_SESSION["profile"]["id"] ){
              ?>
                  <div class="row" >
                    <div class="col-lg-12" >
                       <div class="ads-status-block mt100" >
                         <div class="status-block-icon" >
                            <div><i class="las la-lock"></i></div>
                         </div>
                         <h5><strong><?php echo $ULang->t("Магазин временно не работает"); ?></strong></h5>
                         <p><?php echo $ULang->t("Для продления активируйте тариф"); ?></p>
                       </div>
                    </div>
                  </div>              
              <?php
              }else{
              ?>
                  <div class="row" >
                    <div class="col-lg-12" >
                       <div class="ads-status-block mt100" >
                         <div class="status-block-icon" >
                            <div><i class="las la-lock"></i></div>
                         </div>
                         <h5><strong><?php echo $ULang->t("Магазин временно не работает"); ?></strong></h5>
                       </div>
                    </div>
                  </div>              
              <?php
              }

          } 


     } 

     ?>


    </div>

    <?php if( $data["shop"]["clients_shops_id_user"] == $_SESSION["profile"]["id"] ){ ?>

    <div class="modal-custom-bg" style="display: none;" id="modal-shop-edit" >
        <div class="modal-custom animation-modal" style="max-width: 650px" >

          <span class="modal-custom-close" ><i class="las la-times"></i></span>

          <form class="modal-shop-edit-form" >

          <h4><?php echo $ULang->t("Основная информация"); ?></h4>

           <div class="user-data-item" >
           <div class="row" >
              <div class="col-lg-4 v-middle" >
                <label><?php echo $ULang->t("Логотип"); ?></label>
              </div>
              <div class="col-lg-8 user-shop-logo-container" >
                
                <?php if($data["shop"]["clients_shops_logo"]){ ?>
                <div>
                  <img src="<?php echo Exists($config["media"]["other"],$data["shop"]["clients_shops_logo"],$config["media"]["no_image"]); ?>" height="64px" >
                </div>
                <span class="user-shop-logo-delete user-list-change" ><?php echo $ULang->t("Удалить"); ?></span> 
                <input type="hidden" name="image_status" value="1" >
                <?php }else{ ?> 
                <input type="file" name="logo" class="input-user-shop-logo" >
                <?php } ?>

                <div class="msg-error" data-name="image" ></div>

              </div>
           </div>
           </div>

           <div class="user-data-item" >
           <div class="row" >
              <div class="col-lg-4 v-middle" >
                <label><?php echo $ULang->t("Название магазина"); ?></label>
              </div>
              <div class="col-lg-8" >
                 
                <input type="text" name="shop_title" class="form-control" value="<?php echo $data["shop"]["clients_shops_title"]; ?>" >
                <div class="msg-error" data-name="shop_title" ></div>

              </div>
           </div>
           </div>

           <div class="user-data-item" >
           <div class="row" >
              <div class="col-lg-4 v-middle" >
                <label><?php echo $ULang->t("Тематика магазина"); ?></label>
              </div>
              <div class="col-lg-8" >

                <select name="shop_theme_category" class="form-control" >
                    <option value="0"><?php echo $ULang->t("Общая тематика"); ?></option>
                    <?php
                       if( $getCategoryBoard["category_board_id_parent"][0] ){
                           foreach ($getCategoryBoard["category_board_id_parent"][0] as $value) {
                              ?>
                              <option <?php if( $data["shop"]["clients_shops_id_theme_category"] == $value["category_board_id"] ){ echo 'selected=""'; } ?> value="<?php echo $value["category_board_id"]; ?>"> <?php echo $ULang->t( $value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ); ?> </option>
                              <?php
                           }
                       }
                    ?>
                </select>
                 
              </div>
           </div>
           </div>

           <div class="user-data-item" >
           <div class="row" >
              <div class="col-lg-4 v-middle" >
                <label><?php echo $ULang->t("Краткое описание магазина"); ?></label>
              </div>
              <div class="col-lg-8" >
                 
                <textarea style="min-height: 160px;" name="shop_desc" class="form-control" ><?php echo $data["shop"]["clients_shops_desc"]; ?></textarea>

                <span class="user-info"><?php echo $ULang->t("Укажите краткое описание вашего магазина, данное описание будет отображаться в каталоге магазинов"); ?></span>
                
              </div>
           </div>
           </div>

           <?php if($_SESSION['profile']['tariff']['services']['unique_shop_address']){ ?>
           <div class="user-data-item" >
           <div class="row" >
              <div class="col-lg-4 v-middle" >
                <label><?php echo $ULang->t("Уникальный адрес"); ?></label>
              </div>
              <div class="col-lg-8" >
                 
                <input type="text" name="shop_id" class="form-control" value="<?php echo $data["shop"]["clients_shops_id_hash"]; ?>" >
                <div class="msg-error" data-name="shop_id" ></div>

                <span class="user-info"><?php echo $ULang->t("Укажите короткий адрес вашего магазина, чтобы он стал более удобным и запоминающимся"); ?></span>

              </div>
           </div>
           </div>
           <?php } ?>

           <hr>

           <div class="user-data-item" >
           <div class="row" >
              <div class="col-lg-4" >

              </div>
              <div class="col-lg-8" >
                 
                <span class="btn-custom btn-color-light open-modal display-inline" data-id-modal="modal-confirm-delete-shop" ><?php echo $ULang->t("Удалить магазин"); ?></span>

                <span class="user-info"><?php echo $ULang->t("Вы можете полностью удалить магазин, объявления не удалятся."); ?></span>

              </div>
           </div>
           </div>

          <h4><?php echo $ULang->t("Слайдер"); ?></h4>

          <div class="shop-container-sliders" >
             <div class="shop-container-sliders-add" > <?php echo $ULang->t("Добавить изображение"); ?> </div>

             <div class="shop-container-sliders-append" >
               <?php
                  if( count($data["shop_sliders"]) ){
                      foreach ($data["shop_sliders"] as $key => $value) {
                         ?>
                         <div class="shop-container-sliders-img" style="background-image: url(<?php echo $config["urlPath"] . "/" . $config["media"]["user_attach"] . "/" . $value["clients_shops_slider_image"]; ?>); background-position: center center; background-size: cover;" > <span class="shop-container-sliders-delete" ><i class="las la-times"></i></span> <input type="hidden" name="slider[<?php echo $value["clients_shops_slider_image"]; ?>]" value="<?php echo $value["clients_shops_slider_image"]; ?>" /> </div>
                         <?php
                      }
                  }
               ?>
             </div>

          </div>

          <p><?php echo $ULang->t("Размещайте важную информацию ближе к центру изображения для лучшего отображения на мобильных устройствах. Рекомендуемая ширина изображения - 1920px. Максимальное количество слайдов"); ?> <?php echo $settings["user_shop_count_sliders"]; ?> <?php echo $ULang->t("шт"); ?></p>

          <button class="button-style-custom color-blue mt25 modal-shop-edit-button" ><?php echo $ULang->t("Сохранить изменения"); ?></button>

          </form>

          <form class="modal-shop-slider-form" style="display: none;" >
            <input type="file" name="image" >
          </form>

        </div>
    </div>


    <div class="modal-custom-bg bg-click-close" style="display: none;" id="modal-shop-subscribers" >
        <div class="modal-custom animation-modal no-padding" style="max-width: 550px; min-height: 250px;" >

          <span class="modal-custom-close" ><i class="las la-times"></i></span>
          
          <h4 style="text-align: center;" class="mt20" ><strong><?php echo count($data["shop_subscribers"]) ?> <?php echo ending( count($data["shop_subscribers"]), $ULang->t("подписчик"), $ULang->t("подписчика"), $ULang->t("подписчиков") ); ?></strong></h4>

          
            <?php
              if( count($data["shop_subscribers"]) ){
                  ?>
                  <div class="shop-subscribers-list mt20 mb30" >
                  <?php
                  foreach ($data["shop_subscribers"] as $key => $value) {
                      $getUser = findOne("uni_clients", "clients_id=?", [ $value["clients_subscriptions_id_user_from"] ]);
                      ?>
                      <div class="shop-subscribers-list-item" >
                         <div class="shop-subscribers-list-item-img" > <img src="<?php echo $Profile->userAvatar($getUser); ?>" /> </div>
                         <div class="shop-subscribers-list-item-name" > <a href="<?php echo _link( "user/" . $getUser["clients_id_hash"] ); ?>"><?php echo $Profile->name( $getUser ); ?></a> </div>
                         <div class="clr" ></div>
                      </div>                      
                      <?php
                  }
                  ?>
                  </div>
                  <?php
              }else{
                ?>
                <p class="text-center mt40" ><?php echo $ULang->t("Список пуст."); ?></p>
                <?php
              }
            ?>

        </div>
    </div>

    <div class="modal-custom-bg"  id="modal-shop-add-page" style="display: none;" >
    <div class="modal-custom animation-modal" style="max-width: 500px;" >

      <span class="modal-custom-close" ><i class="las la-times"></i></span>

      <h4> <strong><?php echo $ULang->t("Добавление страницы"); ?></strong> </h4>
      
      <div class="mt30" ></div>

      <div class="create-info" >
      <?php echo $ULang->t("Можно добавить не более") . ' ' . $settings["user_shop_count_pages"] . ' ' . $ULang->t("страниц"); ?>
      </div>
      
      <form class="form-shop-add-page mt15" >
          
          <label> <strong><?php echo $ULang->t("Название"); ?></strong> </label>
          <input type="text" name="name" class="form-control" placeholder="<?php echo $ULang->t("Например: О нас"); ?>" maxlength="50" >

          <input type="hidden" name="id_shop" value="<?php echo $data["shop"]["clients_shops_id"]; ?>" >

      </form>
      

      <div class="mt30" ></div>

      <button class="button-style-custom color-blue action-shop-add-page mt25" ><?php echo $ULang->t("Добавить"); ?></button>

    </div>
    </div>

    <?php } ?>

    <?php include $config["template_path"] . "/footer.tpl"; ?>

    <noindex>
    
    <div class="modal-custom-bg bg-click-close" style="display: none;" id="modal-confirm-delete-shop" >
        <div class="modal-custom animation-modal" style="max-width: 400px" >

          <span class="modal-custom-close" ><i class="las la-times"></i></span>
          
          <div class="modal-confirm-content" >
              <h4><?php echo $ULang->t("Вы действительно хотите удалить магазин?"); ?></h4> 
              <p><?php echo $ULang->t("Восстановление магазина будет невозможным!"); ?></p>           
          </div>

          <div class="mt30" ></div>

          <div class="modal-custom-button" >
             <div>
               <button class="button-style-custom color-blue user-delete-shop" data-id="<?php echo $data["shop"]["clients_shops_id"]; ?>" ><?php echo $ULang->t("Удалить"); ?></button>
             </div> 
             <div>
               <button class="button-style-custom color-light button-click-close" ><?php echo $ULang->t("Отменить"); ?></button>
             </div>                                       
          </div>

        </div>
    </div>

    <div class="modal-custom-bg bg-click-close" style="display: none;" id="modal-confirm-block" >
        <div class="modal-custom animation-modal" style="max-width: 400px" >

          <span class="modal-custom-close" ><i class="las la-times"></i></span>
          
          <div class="modal-confirm-content" >
              <h4><?php echo $ULang->t("Внести пользователя в чёрный список?"); ?></h4>    
              <p class="mt15" ><?php echo $ULang->t("Пользователь не сможет писать вам в чатах и оставлять комментарии к объявлениям."); ?></p>        
          </div>

          <div class="mt30" ></div>

          <div class="modal-custom-button" >
             <div>
               <button class="button-style-custom color-blue profile-user-block" data-id="<?php echo $data["user"]["clients_id"]; ?>" ><?php echo $ULang->t("Внести"); ?></button>
             </div> 
             <div>
               <button class="button-style-custom color-light button-click-close" ><?php echo $ULang->t("Отменить"); ?></button>
             </div>                                       
          </div>

        </div>
    </div>

    </noindex>

    <?php if( $data["shop"]["clients_shops_id_user"] == $_SESSION["profile"]["id"] ){ ?>

    <script>

        InlineEditor
          .create( document.querySelector( '#InlineEditor' ), {
            ckfinder: {
                uploadUrl: '<?php echo $config["urlPath"] . "/systems/ajax/ckfinder.php"; ?>',
            },
            toolbar: {
              items: [
                'heading',
                '|',
                'bold',
                'italic',
                'link',
                'bulletedList',
                'numberedList',
                '|',
                'indent',
                'outdent',
                '|',
                'imageUpload',
                'blockQuote',
                'insertTable',
                //'mediaEmbed',
                'undo',
                'redo',
                'fontColor',
                'fontSize'
              ]
            },
            language: '<?php echo getLang(); ?>',
            image: {
              toolbar: [
                'imageTextAlternative',
                'imageStyle:full',
                'imageStyle:side'
              ]
            },
            table: {
              contentToolbar: [
                'tableColumn',
                'tableRow',
                'mergeTableCells'
              ]
            },
            
          } )
          .then( editor => {
              theEditor = editor;
          } )
          .catch( error => {

          } );

    </script>

    <?php } ?>

  </body>
</html>