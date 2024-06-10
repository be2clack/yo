<?php

Router::route('', function(){
    require 'route/index.php';
});

Router::route('ref/([a-zA-Z0-9\-]+)', function($ref_id_hash){
    require 'route/index.php';
});

Router::route('([a-zA-Z0-9\-]+)/vip', function($alias_city){
    require 'route/vip.php';
});

Router::route('vip', function(){
    require 'route/vip.php';
});

Router::route('([a-zA-Z0-9\-]+)/auction', function($alias_city){
    require 'route/auction.php';
});

if($settings["main_type_products"] == 'physical'){
    Router::route('map', function(){
        require 'route/map.php';
    });

    Router::route('map/([a-zA-Z0-9\-]+)', function($alias_city){
        require 'route/map.php';
    });
}else{

    Router::route('catalog', function(){
        require 'route/catalog.php';
    });   

}

Router::route('cart', function(){
    require 'route/cart.php';
});

Router::route('cart/order', function(){
    require 'route/cart.php';
});

Router::route('ad/create', function(){
    require 'route/ad_create.php';
});

Router::route('ad/update/([0-9]+)', function($id_ad){
    require 'route/ad_update.php';
});

Router::route('ad/publish/([0-9]+)', function($id_ad){
    require 'route/ad_publish.php';
});

Router::route('tariffs', function(){
    require 'route/tariff.php';
});

Router::route('user/([a-zA-Z0-9\-]+)/([a-zA-Z0-9\-]+)', function($id_user, $action){
    require 'route/profile.php';
});

Router::route('user/([a-zA-Z0-9\-]+)', function($id_user){
    require 'route/profile.php';
});

Router::route($settings['user_shop_alias_url_page'].'/([a-zA-Z0-9\-]+)/page/([a-zA-Z0-9\-]+)', function($id_shop,$alias_page){
    require 'route/shop.php';
});

Router::route($settings['user_shop_alias_url_page'].'/(.*?)', function($params){
    require 'route/shop.php';
});

Router::route($settings['user_shop_alias_url_all'], function(){
    require 'route/shops.php';
});

Router::route($settings['user_shop_alias_url_all'].'/(.*?)', function($alias_category){
    require 'route/shops.php';
});

Router::route('auth', function(){
    require 'route/auth.php';
});

if($settings["main_type_products"] == 'physical'){
    Router::route('cities', function(){
        require 'route/cities.php';
    });
}

Router::route('buy/([0-9]+)', function($id_ad){
    require 'route/buy.php';
});

Router::route('order/([0-9]+)', function($id_order){
    require 'route/order.php';
});

Router::route('booking/([0-9]+)', function($id_order){
    require 'route/order_booking.php';
});

Router::route('pay/status/([a-zA-Z=]+)', function($status){
    require 'route/pay_status.php';
});

Router::route('pay/status', function(){
    require 'route/pay_status.php';
});

Router::route('unsubscribe', function(){
    require 'route/unsubscribe.php';
});

Router::route('subscribe', function(){
    require 'route/subscribe.php';
});

Router::route('feedback', function(){
    require 'route/feedback.php';
});

Router::route('blog', function(){
    require 'route/blog.php';
});

Router::route('blog/(.*?)', function($riddle){
    require 'route/blog.php';
});

Router::route('promo/([a-zA-Z0-9\-]+)', function($name){
    require 'route/promo.php';
});

Router::route('page/([a-zA-Z0-9\-]+)', function($name){
    require 'route/other_page.php';
});

if($settings["main_type_products"] == 'physical'){

    Router::route('([a-zA-Z\-]+)/(.*?)', function($alias_city, $riddle){

        $getCategoryBoard = (new CategoryBoard())->getCategories("where category_board_visible=1");

        $data["geo"] = (new Geo())->aliasCheckOut($alias_city);
        $data["category"] = $getCategoryBoard["category_board_chain"][$riddle];
        if($data["geo"] && $data["category"]){
           require 'route/catalog.php';
        }else{
           
           $Ads = new Ads();

           $param = parseUriAd( $riddle );

           $data["ad"] = $Ads->get("ads_id = ? and ads_alias = ? and city_alias = ? and category_board_alias = ?", array( $param["id"], $param["alias_ad"], $alias_city, $param["alias_cat"] ) );
           $data["category"] = $getCategoryBoard["category_board_id"][$data["ad"]["ads_id_cat"]];

           if($data["ad"]){

              require 'route/ad_view.php';

           }else{

              $data["category"] = $getCategoryBoard["category_board_chain"][$alias_city."/".$riddle];
              if($data["category"]){
                  
                  $_SESSION["temp_change_category"] = $data["category"];
                  header("Location: " . _link("cities") ); 

              }else{

                  $param = parseUriFilter( $riddle );

                  $data["category"] = $getCategoryBoard["category_board_chain"][$param["alias_cat"]];

                  $data["filter"] = getOne("select * from uni_ads_filters_alias INNER JOIN `uni_ads_filters_items` ON `uni_ads_filters_alias`.ads_filters_alias_id_filter_item = `uni_ads_filters_items`.ads_filters_items_id INNER JOIN `uni_ads_filters` ON `uni_ads_filters`.ads_filters_id = `uni_ads_filters_items`.ads_filters_items_id_filter where ads_filters_alias_alias=? and ads_filters_alias_id_cat=? and ads_filters_visible=?", [ $param["alias_filter"], $data["category"]["category_board_id"], 1 ]);
                  
                  if($data["filter"] && $data["geo"] && $data["category"]){
                      require 'route/catalog.php';
                  }else{
                      (new Main())->response(404);
                  }

              }

           }
           
        }
    });

    Router::route('([a-zA-Z0-9\-]+)', function($alias){
      
      $getCategoryBoard = (new CategoryBoard())->getCategories("where category_board_visible=1");
      $data["category"] = $getCategoryBoard["category_board_chain"][$alias];
      $data["geo"] = (new Geo())->aliasCheckOut($alias);

      if($data["geo"]){
         
         require 'route/catalog.php';

      }else{
         
         $data["page"] = findOne("uni_pages", "alias=?", array($alias));
         
         if($data["page"]){
            require 'route/page.php';
         }else{
            if($data["category"]){
               $_SESSION["temp_change_category"] = $data["category"];
               header("Location: " . _link("cities") ); 
            }else{
               $data["seo_filter"] = getOne("select * from uni_seo_filters where seo_filters_alias=?", [ clear($alias) ]);
               if($data["seo_filter"]){
                  $data["category"] = $getCategoryBoard["category_board_chain"][$data["seo_filter"]["seo_filters_alias_category"]];
                  if($data["category"]){
                     $data["geo"] = (new Geo())->aliasCheckOut($data["seo_filter"]["seo_filters_alias_geo"]);               
                     require 'route/catalog.php';
                  }else{
                     (new Main())->response(404);
                  }
               }else{
                  (new Main())->response(404);
               }
            }
         }

      }

    });

}else{

    Router::route('(.*?)', function($riddle){

        $getCategoryBoard = (new CategoryBoard())->getCategories("where category_board_visible=1");

        $data["category"] = $getCategoryBoard["category_board_chain"][$riddle];

        if($data["category"]){
           require 'route/catalog.php';
        }else{
           
           $Ads = new Ads();

           $param = parseUriAd( $riddle );

           $data["ad"] = $Ads->get("ads_id = ? and ads_alias = ? and category_board_alias = ?", array( $param["id"], $param["alias_ad"], $param["alias_cat"] ) );
           $data["category"] = $getCategoryBoard["category_board_id"][$data["ad"]["ads_id_cat"]];

           if($data["ad"]){

              require 'route/ad_view.php';

           }else{

              $data["page"] = findOne("uni_pages", "alias=?", array($riddle));

              if($data["page"]){

                  require 'route/page.php';

              }else{

                  $param = parseUriFilter($riddle);

                  $data["category"] = $getCategoryBoard["category_board_chain"][$param["alias_cat"]];

                  $data["filter"] = getOne("select * from uni_ads_filters_alias INNER JOIN `uni_ads_filters_items` ON `uni_ads_filters_alias`.ads_filters_alias_id_filter_item = `uni_ads_filters_items`.ads_filters_items_id INNER JOIN `uni_ads_filters` ON `uni_ads_filters`.ads_filters_id = `uni_ads_filters_items`.ads_filters_items_id_filter where ads_filters_alias_alias=? and ads_filters_alias_id_cat=? and ads_filters_visible=?", [ $param["alias_filter"], $data["category"]["category_board_id"], 1 ]);
                  
                  if($data["filter"] && $data["category"]){
                      require 'route/catalog.php';
                  }else{
                      
                       $data["seo_filter"] = getOne("select * from uni_seo_filters where seo_filters_alias=?", [$riddle]);
                       if($data["seo_filter"]){
                          $data["category"] = $getCategoryBoard["category_board_chain"][$data["seo_filter"]["seo_filters_alias_category"]];
                          if($data["category"]){               
                             require 'route/catalog.php';
                          }else{
                             (new Main())->response(404);
                          }
                       }else{
                          (new Main())->response(404);
                       }

                  }

              }

           }
           
        }
    });

}

?>