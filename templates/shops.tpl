<!doctype html>
<html lang="<?php echo getLang(); ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="<?php echo $data["meta_desc"]; ?>">

    <title><?php echo $data["meta_title"]; ?></title>

    <?php include $config["template_path"] . "/head.tpl"; ?>

  </head>

  <body data-prefix="<?php echo $config["urlPrefix"]; ?>"  data-header-sticky="true" data-type-loading="<?php echo $settings["type_content_loading"]; ?>" data-template="<?php echo $config["template_folder"]; ?>" data-page-name="<?php echo $route_name; ?>" >
    
    <?php include $config["template_path"] . "/header.tpl"; ?>

    <div class="container" >

       <nav aria-label="breadcrumb">
 
          <ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">

            <li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
              <a itemprop="item" href="<?php echo $config["urlPath"]; ?>">
              <span itemprop="name"><?php echo $ULang->t("Главная"); ?></span>
              </a>
              <meta itemprop="position" content="1">
            </li>

            <li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
              <span itemprop="name"><?php echo $data["h1"]; ?></span>
              <meta itemprop="position" content="2">
            </li>                 
          </ol>

        </nav>

         <div class="row" >

             <div class="col-lg-12" >
                <h1 class="catalog-title"> <?php echo $data["h1"]; ?> </h1>
                <div class="mt50" ></div>               
             </div>

             <div class="col-lg-9 min-height-600" >

                <div class="catalog-results" >

                    <div class="preload" >

                        <div class="spinner-grow mt80 preload-spinner" role="status">
                          <span class="sr-only"></span>
                        </div>

                    </div>

                </div>

             </div>
             <div class="col-lg-3 d-none d-lg-block" >

             <div class="shop-category-list">

                 <a href="<?php echo $Shop->linkShops(); ?>" <?php if( !$data["current_category"] ){ echo 'class="active"'; } ?> > <?php echo $ULang->t( "Все категории" ); ?></a>

                 <ul>

                  <?php
                      if( $getCategoryBoard["category_board_id_parent"][0] ){
                          foreach ($getCategoryBoard["category_board_id_parent"][0] as $value) {

                              $countShop = (int)getOne( 'select count(*) as total from uni_clients_shops INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_clients_shops`.clients_shops_id_user where (clients_shops_time_validity > now() or clients_shops_time_validity IS NULL) and clients_shops_status=1 and clients_status IN(0,1) and (clients_shops_id_theme_category=? or clients_shops_id_theme_category=?)', [ $value["category_board_id"], 0 ] )['total'];
                              ?>
                              <li>
                                <a <?php if( $value["category_board_id"] == $data["current_category"]["category_board_id"] ){ echo 'class="active"'; } ?> href="<?php echo $Shop->linkShopsCategory($value["category_board_chain"]); ?>"><?php echo $ULang->t( $value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ); ?><span class="shop-category-list-count" ><?php echo $countShop; ?></span></a>
                              </li>                              
                              <?php
                          }
                      }
                  ?>

                 </ul>

             </div>

             <input type="hidden" name="id_c" value="<?php echo $data["current_category"]["category_board_id"]; ?>" />
             
             </div>

         </div>

         <?php if($data["seo_text"]){ ?> <div class="mt15" > <?php echo $data["seo_text"]; ?> </div> <?php } ?>
        
    </div>
    
    <div class="mt35" ></div>

    <noindex>

    <div class="mobile-fixed-menu mobile-fixed-menu_shops-category" >
        <div class="mobile-fixed-menu-header" >
            <span class="mobile-fixed-menu-header-close" ><i class="las la-arrow-left"></i></span>
            <span class="mobile-fixed-menu-header-title" ><?php echo $ULang->t('Категории'); ?></span>
        </div>
        <div class="mobile-fixed-menu-content" >

             <div class="shop-category-list">

                 <a href="<?php echo $Shop->linkShops(); ?>" <?php if( !$data["current_category"] ){ echo 'class="active"'; } ?> > <?php echo $ULang->t( "Все категории" ); ?></a>

                 <ul>

                  <?php
                      if( $getCategoryBoard["category_board_id_parent"][0] ){
                          foreach ($getCategoryBoard["category_board_id_parent"][0] as $value) {

                              $countShop = (int)getOne( 'select count(*) as total from uni_clients_shops INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_clients_shops`.clients_shops_id_user where (clients_shops_time_validity > now() or clients_shops_time_validity IS NULL) and clients_shops_status=1 and clients_status IN(0,1) and (clients_shops_id_theme_category=? or clients_shops_id_theme_category=?)', [ $value["category_board_id"], 0 ] )['total'];
                              ?>
                              <li>
                                <a <?php if($value["category_board_id"] == $data["current_category"]["category_board_id"]){ echo 'class="active"'; } ?> href="<?php echo $Shop->linkShopsCategory($value["category_board_chain"]); ?>"><?php echo $ULang->t($value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ]); ?><span class="shop-category-list-count" ><?php echo $countShop; ?></span></a>
                              </li>                              
                              <?php
                          }
                      }
                  ?>

                 </ul>

             </div>          

        </div>
    </div>

    </noindex>
    
    <?php include $config["template_path"] . "/footer.tpl"; ?>

  </body>
</html>