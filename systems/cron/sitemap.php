<?php
defined('unisitecms') or exit();

$getCategories = $CategoryBoard->getCategories("where category_board_visible=1");

$lines = '';

if($settings["sitemap_services"]){

   $getAll = getAll("SELECT * FROM uni_pages WHERE visible=1");

   if(count($getAll)){
      foreach ($getAll as $key => $value) {
         $lines .= '
             <url>
              <loc>'._link($value["alias"]).'</loc>
              <lastmod>'.date("Y-m-d").'</lastmod>
              <priority>0.6</priority>
             </url>
         ';
      }
   }

}


if($settings["sitemap_seo_filters"]){
   
   $getAll = getAll("select * from uni_seo_filters");

   if(count($getAll)){
      foreach ($getAll as $key => $value) {
         $lines .= '
             <url>
              <loc>'._link($value["seo_filters_alias"]).'</loc>
              <lastmod>'.date("Y-m-d").'</lastmod>
              <priority>0.6</priority>
             </url>
         ';
      }
   }

}

if($settings["sitemap_alias_filters"] || $settings["sitemap_cities"]){

   $getAds = getAll("select * from uni_ads INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user where ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() group by ads_city_id");

   if(count($getAds)){
      foreach ($getAds as $key => $ad_value) {

         if($settings["main_type_products"] == 'physical'){

            $getCity = findOne("uni_city", "city_id=?", [ $ad_value["ads_city_id"] ]);
            
            if($settings["sitemap_alias_filters"]){

                $getAll = getAll("select * from uni_ads_filters_alias where ads_filters_alias_id_cat=?", [ $ad_value["ads_id_cat"] ]);

                if(count($getAll)){
                   foreach ($getAll as $key => $value) {
                      $lines .= '
                          <url>
                           <loc>'.$Filters->alias( [ "geo_alias" => $getCity["city_alias"], "category_alias"=>$getCategories['category_board_id'][$value["ads_filters_alias_id_cat"]]['category_board_chain'], "filter_alias"=>$value["ads_filters_alias_alias"]] ).'</loc>
                           <lastmod>'.date("Y-m-d").'</lastmod>
                           <priority>0.6</priority>
                          </url>
                      ';
                   }
                }

            }

            if($settings["sitemap_cities"]){

                $lines .= '
                    <url>
                     <loc>'.$config["urlPath"].'/'.$getCity["city_alias"].'</loc>
                     <lastmod>'.date("Y-m-d").'</lastmod>
                     <priority>0.6</priority>
                    </url>
                ';

            }

         }else{

            if($settings["sitemap_alias_filters"]){

                $getAll = getAll("select * from uni_ads_filters_alias where ads_filters_alias_id_cat=?", [ $ad_value["ads_id_cat"] ]);

                if(count($getAll)){
                   foreach ($getAll as $key => $value) {
                      $lines .= '
                          <url>
                           <loc>'.$Filters->alias( [ "category_alias"=>$getCategories['category_board_id'][$value["ads_filters_alias_id_cat"]]['category_board_chain'], "filter_alias"=>$value["ads_filters_alias_alias"]] ).'</loc>
                           <lastmod>'.date("Y-m-d").'</lastmod>
                           <priority>0.6</priority>
                          </url>
                      ';
                   }
                }

            }            

         }

      }
   }
   
}

if($settings["sitemap_category"]){

   $getAds = getAll("select * from uni_ads INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user where ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() group by ads_id_cat");

   if(count($getAds)){
      foreach ($getAds as $key => $ad_value) {

         if($settings["main_type_products"] == 'physical'){

            $getCity = findOne("uni_city", "city_id=?", [ $ad_value["ads_city_id"] ]);

            if( $getCity ){
                  $lines .= '
                      <url>
                       <loc>'.$CategoryBoard->alias( $getCategories["category_board_id"][$ad_value["ads_id_cat"]]["category_board_chain"], $getCity["city_alias"] ).'</loc>
                       <lastmod>'.date("Y-m-d").'</lastmod>
                       <priority>0.6</priority>
                      </url>
                  ';
            }

         }else{

            $lines .= '
                <url>
                 <loc>'.$CategoryBoard->alias( $getCategories["category_board_id"][$ad_value["ads_id_cat"]]["category_board_chain"] ).'</loc>
                 <lastmod>'.date("Y-m-d").'</lastmod>
                 <priority>0.6</priority>
                </url>
            ';

         }

      }
   }
   
}

if($settings["sitemap_blog"]){

   $get = $Blog->getAll( ["query"=>"blog_articles_visible='1'"] );

   if( $get["count"] > 0 ){
      foreach ($get["all"] as $key => $value) {
         $lines .= '
             <url>
              <loc>'.$Blog->aliasArticle($value).'</loc>
              <lastmod>'.date("Y-m-d").'</lastmod>
              <priority>0.6</priority>
             </url>
         ';
      }
   }

}

if($settings["sitemap_blog_category"]){

   $getCategoryBlog = $Blog->getCategories("where blog_category_visible=1");

   if($getCategoryBlog){
      foreach ($getCategoryBlog["blog_category_id"] as $id => $value) {
         $lines .= '
             <url>
              <loc>'.$Blog->aliasCategory($value["blog_category_chain"]).'</loc>
              <lastmod>'.date("Y-m-d").'</lastmod>
              <priority>0.6</priority>
             </url>
         ';
      }
   }

}

if($settings["sitemap_shops"]){

   $getShops = getAll( "select * from uni_clients_shops INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_clients_shops`.clients_shops_id_user where clients_shops_time_validity > now() and clients_status IN(0,1) and clients_shops_status=?", [1]);

   if($getShops){
      foreach ($getShops as $value) {
         $lines .= '
             <url>
              <loc>'.$Shop->linkShop($value["clients_shops_id_hash"]).'</loc>
              <lastmod>'.date("Y-m-d").'</lastmod>
              <priority>0.6</priority>
             </url>
         ';
      }
   }

}

if($lines){

    file_put_contents($config["basePath"] . '/sitemap.xml', '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . $lines . '</urlset>');

}


?>