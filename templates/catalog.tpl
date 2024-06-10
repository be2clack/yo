<?php
include $config["template_path"] . "/extra/fn.php";
?>
<!doctype html>
<html lang="<?php echo getLang(); ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="<?php echo $data["meta_desc"]; ?>">

    <title><?php echo $data["meta_title"]; ?></title>

    <link rel="canonical" href="<?php echo _link( explode("?", $_SERVER['REQUEST_URI'])[0] ); ?>"/>

    <?php include $config["template_path"] . "/head.tpl"; ?>

  </head>

  <body data-prefix="<?php echo $config["urlPrefix"]; ?>" data-template="<?php echo $config["template_folder"]; ?>" data-header-sticky="true" data-type-loading="<?php echo $settings["type_content_loading"]; ?>" data-page-name="<?php echo $route_name; ?>" >
    
    <?php include $config["template_path"] . "/header.tpl"; ?>

    <div class="container" >

       <?php echo $Banners->out( ["position_name"=>"catalog_top", "current_id_cat"=>$data["category"]["category_board_id"], "categories"=>$getCategoryBoard] ); ?>

       <nav aria-label="breadcrumb">
 
          <ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">

            <li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
              <a itemprop="item" href="<?php echo $config["urlPath"]; ?>">
              <span itemprop="name"><?php echo $ULang->t("Главная"); ?></span></a>
              <meta itemprop="position" content="1">
            </li>

            <?php echo $data["breadcrumb"]; ?>

          </ol>

        </nav>
          
          <div class="row" >
              <div class="col-lg-12 min-height-600" >

                <div class="row" >
                   <div class="col-lg-12" >
                     <h1 class="catalog-title" ><?php echo $data["h1"]; ?> <span class="catalog-count-ad" ></span></h1>
                   </div>
                </div>

                <?php

                echo $Profile->outUserStories(false,$data["category"]["category_board_id"],'<div class="mt25" ></div>');

                $outParent = $CategoryBoard->outParent($getCategoryBoard, [ "tpl_parent" => '<div class="col-lg-3 col-12 col-md-4 col-sm-4" ><a {ACTIVE} href="{PARENT_LINK}">{PARENT_IMAGE} {PARENT_NAME} {COUNT_AD}</a></div>', "tpl" => '{PARENT_CATEGORY}', "category" => $data["category"]]);
                ?>
               
                <?php if( $outParent ){ ?>
                <div class="catalog-subcategory mb15 mt25" >
                  
                  <div class="row" >
                  <?php 
                    echo $outParent; 
                  ?>
                  </div>

                </div>
                <?php } ?>

                <?php if($data["seo_alias_category"]){ ?>
                <div class="slider-list-seo-filters list-seo-filters" >
                   <?php echo $data["seo_alias_category"]; ?>
                </div> 
                <?php } ?>               

                <div class="mt25" ></div>
                
                <div class="row" >

                   <div class="col-lg-3 d-none d-lg-block" >

                      <?php include $config["template_path"] . "/catalog_sidebar.tpl"; ?>
                     
                      <div class="form-search-subscribe" >
                          <div> <i class="las la-bell"></i> </div>
                          <p> <strong><?php echo $ULang->t("Оформите подписку"); ?></strong> <?php echo $ULang->t("на поиск и получайте первым новые объявления по Вашим параметрам"); ?></p>
                          <div> <span class="btn-custom btn-color-blue catalog-ads-subscriptions-add" ><?php echo $ULang->t("Подписаться"); ?></span> </div>
                      </div>

                   </div>
                  
                   <div class="col-lg-9" >
                     
                      <?php if( $data["vip"]["count"] && !$data["param_filter"]["filter"]["vip"] ){ ?>
                      <div class="mb20" >
                        <div class="mb25 title-and-link h4" > <strong><?php echo $ULang->t( "VIP объявления" ); ?></strong> <a href="<?php echo $data["vip_link"]; ?>" ><?php echo $ULang->t( "Больше объявлений" ); ?> <i class="las la-arrow-right"></i></a> </div>
                        <div class="slider-item-grid vip-slider-item-grid init-slider-grid" >
                            <?php 
                            
                               foreach ( $data["vip"]["all"] as $key => $value) {
                                   include $config["template_path"] . "/include/vip_ad_grid.php";
                               }
                            
                            ?>
                        </div>
                      </div>
                      <?php
                      } 
                      ?>

                      <div class="catalog-sort text-right" >

                         <div>
                           
                            <?php echo $Ads->outSorting(); ?>

                         </div>

                         <?php if($settings["main_type_products"] == 'physical'){ ?>
                         <div>

                          <a class="catalog-sort-link-button" data-tippy-placement="bottom" title="<?php echo $ULang->t("Поиск на карте"); ?>" href="<?php echo $Ads->linkMap( $data ); ?>"><i class="las la-map-marked-alt"></i> </a>

                         </div> 
                         <?php } ?>

                         <div>
                           
                           <span class="catalog-sort-link-button catalog-ads-subscriptions-add" data-tippy-placement="bottom" title="<?php echo $ULang->t("Подписка на поиск"); ?>" ><i class="las la-bell"></i></span>

                         </div>

                         <div data-view="grid" class="catalog-ad-view <?php if($_SESSION["catalog_ad_view"] == "grid" || !$_SESSION["catalog_ad_view"]){ echo 'active'; } ?>" > <i class="las la-border-all"></i> </div>
                         <div data-view="list" class="catalog-ad-view <?php if($_SESSION["catalog_ad_view"] == "list"){ echo 'active'; } ?>" > <i class="las la-list"></i> </div>

                      </div>

                      <div class="catalog-results" >
                        
                          <div class="preload" >

                              <div class="spinner-grow mt80 preload-spinner" role="status">
                                <span class="sr-only"></span>
                              </div>

                          </div>

                      </div>

                   </div>


                </div>


              </div>
          </div>
          
          <?php if($data["seo_text"]){ ?> <div class="mt35 schema-text" > <?php echo $data["seo_text"]; ?> </div> <?php } ?>

       <div class="mt50" ></div>
       
       <?php echo $Banners->out( ["position_name"=>"catalog_bottom", "current_id_cat"=>$data["category"]["category_board_id"], "categories"=>$getCategoryBoard] ); ?>

    </div>

    <noindex>

    <div class="modal-custom-bg bg-click-close" id="modal-ads-subscriptions" style="display: none;" >
        <div class="modal-custom" style="max-width: 500px;" >

          <span class="modal-custom-close" ><i class="las la-times"></i></span>
          
          <div class="modal-ads-subscriptions-block-1" >

              <h4> <strong><?php echo $ULang->t("Подписка на объявления"); ?></strong> </h4>

              <p><?php echo $ULang->t("Новые объявления будут приходить на электронную почту"); ?></p>
              
              <?php if( !$_SESSION["profile"]["id"] ){ ?>
              <div class="create-info" >
                <?php echo $ULang->t("Для удобного управления подписками"); ?> - <a href="<?php echo _link("auth"); ?>"><?php echo $ULang->t("войдите в личный кабинет"); ?></a>
              </div>
              <?php } ?>
              
              <form class="modal-ads-subscriptions-form mt20" >
                 
                 <label><?php echo $ULang->t("Ваш e-mail"); ?></label>

                 <input type="text" name="email" class="form-control" value="<?php echo $_SESSION["profile"]["data"]["clients_email"]; ?>" >
                 
                 <label class="mt15" ><?php echo $ULang->t("Частота уведомлений"); ?></label>

                 <select name="period" class="form-control" >
                    <option value="1" selected="" ><?php echo $ULang->t("Раз в день"); ?></option>
                    <option value="2" ><?php echo $ULang->t("Сразу при публикации"); ?></option>
                 </select>

                 <input type="hidden" name="url" value="<?php echo $Ads->buildUrlCatalog( $data ); ?>" >

              </form>

              <div class="mt30" >
                 <button class="btn-custom btn-color-blue width100 modal-ads-subscriptions-add mb5" ><?php echo $ULang->t("Подписаться"); ?></button>
              </div>

              <p style="font-size: 13px; color: #7a7a7a;" class="mt15" ><?php echo $ULang->t("При подписке вы принимаете условия"); ?> <a href="<?php echo _link("polzovatelskoe-soglashenie"); ?>"><?php echo $ULang->t("Пользовательского соглашения"); ?></a> <?php echo $ULang->t("и"); ?> <a href="<?php echo _link("privacy-policy"); ?>"><?php echo $ULang->t("Политики конфиденциальности"); ?></a></p>

          </div>

          <div class="modal-ads-subscriptions-block-2" style="text-align: center;" >

              <i class="las la-check checkSuccess"></i>

              <h3> <strong><?php echo $ULang->t("Подписка оформлена"); ?></strong> </h3>

              <p><?php echo $ULang->t("Если вы захотите отписаться от рассылки - просто нажмите на соответствующую кнопку в тексте письма, либо перейдите в раздел"); ?> <a href="<?php if($_SESSION["profile"]["id"]){ echo _link( "user/" . $_SESSION["profile"]["data"]["clients_id_hash"] . "/subscriptions" ); }else{ echo _link( "auth" ); } ?>"><?php echo $ULang->t("управления подписками"); ?></a></p>

          </div>

        </div>
    </div>

    </noindex>

    <div class="mt35" ></div>
    
    <?php include $config["template_path"] . "/footer.tpl"; ?>

  </body>
</html>