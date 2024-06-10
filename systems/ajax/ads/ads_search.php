<?php
$query = clearSearchBack($_POST["search"]);
$id_s = (int)$_POST["id_s"];
$page = clear($_POST['page']);
$results = [];
$temp = [];
$main_id_categories = [];
$getShop = [];
$delete_words = ['с','в','на','или'];

if(!$query || mb_strlen($query, 'UTF-8') <= 1) exit;

if($id_s){
   $getShop = $Shop->getShop(['shop_id'=>$id_s,'conditions'=>true]);
   $getTariff = $Profile->getOrderTariff($getShop["clients_shops_id_user"]);
   if(!$getTariff['services']['search_shop']){
       $getShop = [];
   }
}

$query = str_replace('-', ' ', $query);
$queryNotDeleteWord = str_replace('-', ' ', $query);

foreach ($delete_words as $value) {
   $query = preg_replace('/\b'.$value.'\b/u','',$query);
}

$getCategories = $CategoryBoard->getCategories("where category_board_visible=1");

$split = preg_split("/( )+/", $query);
$splitNotDeleteWord = preg_split("/( )+/", $queryNotDeleteWord);

if($page != 'shops'){

    if(count($splitNotDeleteWord) > 1 && $page != 'shop'){
        $endWord = $splitNotDeleteWord[ count($splitNotDeleteWord) - 1 ];
        $penultimateWord = $splitNotDeleteWord[ count($splitNotDeleteWord) - 2 ];
        if(mb_strlen($endWord, 'UTF-8') >= 3) $searchCity = getOne("select * from uni_city where city_name LIKE '".$endWord."' or city_declination LIKE '".$penultimateWord.' '.$endWord."'", []);
    }

   if($getShop["clients_shops_id_theme_category"]){
        $shop_get_category_ids = idsBuildJoin($CategoryBoard->idsBuild($getShop["clients_shops_id_theme_category"], $getCategories), $getShop["clients_shops_id_theme_category"]);
        if($shop_get_category_ids){
            $search = getAll("select * from uni_ads_keywords where ads_keywords_id_cat IN(".$shop_get_category_ids.") and (ads_keywords_tag LIKE '%".$split[0]."%' or ads_keywords_tag LIKE '%".searchSubstr($split[0],1)."%') order by ads_keywords_count_click desc limit 100");
        }
   }else{
        $search = getAll("select * from uni_ads_keywords where ads_keywords_tag LIKE '%".$split[0]."%' or ads_keywords_tag LIKE '%".searchSubstr($split[0],1)."%' order by ads_keywords_count_click desc limit 100");
   }

   if(count($search)){
      if(count($split) > 1){
          foreach ($search as $value) {

              if(count($split) == 2){
                  if(searchCheckWord($value['ads_keywords_tag'],searchSubstr($split[1],1))){
                       $results[] = $value;
                  }
              }elseif(count($split) == 3){
                  if(searchCheckWord($value['ads_keywords_tag'],searchSubstr($split[1],1)) && searchCheckWord($value['ads_keywords_tag'],searchSubstr($split[2],1))){
                       $results[] = $value;
                  }else{
                     if(searchCheckWord($value['ads_keywords_tag'],searchSubstr($split[1],1))){
                        $results[] = $value;
                     }
                  }
              }elseif(count($split) == 4){
                  if(searchCheckWord($value['ads_keywords_tag'],searchSubstr($split[1],1)) && searchCheckWord($value['ads_keywords_tag'],searchSubstr($split[2],1)) && searchCheckWord($value['ads_keywords_tag'],searchSubstr($split[3],1))){
                       $results[] = $value;
                  }else{
                     if(searchCheckWord($value['ads_keywords_tag'],searchSubstr($split[1],1)) && searchCheckWord($value['ads_keywords_tag'],searchSubstr($split[2],1))){
                         $results[] = $value;
                     }                        
                  }
              }

          }

      }else{
          $results = $search;
      }
   }

   if(count($results)){

      foreach ($results as $value) {
        $get_main_id = $CategoryBoard->reverseMainId($getCategories,$value['ads_keywords_id_cat']);
        if($get_main_id) $main_id_categories[$get_main_id] = $get_main_id;
      }

   }

   if(count($results)){

         foreach (array_slice($results,0,10,true) as $value) {

            $params = [];

            if($value['ads_keywords_params']){
                $params[] = $value['ads_keywords_params'];
            }

            $params[] = 's_id='.$value['ads_keywords_id'];

            if($getShop){
                $link = $Shop->linkShop($getShop['clients_shops_id_hash']).'/'.$getCategories["category_board_id"][$value["ads_keywords_id_cat"]]["category_board_chain"].'?'.implode('&',$params);
            }else{
                $link = $CategoryBoard->alias($getCategories["category_board_id"][$value["ads_keywords_id_cat"]]["category_board_chain"], $searchCity['city_alias']).'?'.implode('&',$params);
            }
            
            ?> 
              <a href="<?php echo $link; ?>" > 
                  <span class="main-search-results-name" ><?php echo $value["ads_keywords_tag"]; ?> <span class="main-search-results-city" ><?php if($page != 'shop'){ echo $Geo->outGeoDeclination($searchCity['city_declination']); } ?></span> </span>
                  <?php if(!$value['ads_keywords_params']){ ?>
                  <span class="main-search-results-category" ><?php echo $getCategories["category_board_id"][$value["ads_keywords_id_cat"]]["category_board_name"]; ?></span>
                  <?php } ?>

              </a>              
            <?php
         }

   }

}

if($settings["user_shop_status"]){

if($page == 'shop'){

   if($getShop){
       $results = $Ads->getAll( array("navigation"=>false,"output"=>10,"query"=>"ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_id_user='".$getShop["clients_shops_id_user"]."' and ".$Filters->explodeSearch($query), "sort"=>"ORDER By ads_datetime_add DESC limit 10", "param_search" => $Elastic->paramAdSearch($query,$getShop["clients_shops_id_user"])));

       if($results["count"]){

             foreach ($results["all"] as $key => $value) {
                $value = $Ads->getDataAd($value);
                $image = $Ads->getImages($value["ads_images"]);
                $service = $Ads->adServices($value["ads_id"]);
                ?>
                  <a href="<?php echo $Ads->alias($value); ?>" > 
                    <div class="main-search-results-img" ><img src="<?php echo Exists($config["media"]["small_image_ads"],$image[0],$config["media"]["no_image"]); ?>"></div>
                    <div class="main-search-results-cont" >

                      <span class="main-search-results-name" ><?php echo $value["ads_title"]; echo $service[2] || $service[3] ? '<span class="main-search-results-item-vip" >Vip</span>' : ""; ?></span>
                      <span class="main-search-results-category" ><?php echo $value["category_board_name"]; ?></span>

                    </div>
                    <div class="clr" ></div>
                  </a>              
                <?php
             }

       }
   }

}else{

   foreach ($split as $value) {
       $shop_like_query[] = "clients_shops_title LIKE '%".$value."%'";
   }

   if(count($main_id_categories)){
        $getShops = getAll("select * from uni_clients_shops where (clients_shops_time_validity > now() or clients_shops_time_validity IS NULL) and clients_shops_status=1 and (clients_shops_id_theme_category IN(".implode(',', $main_id_categories).") or (".implode(' and ', $shop_like_query).")) order by rand() limit 5", []);
   }else{
        $getShops = getAll("select * from uni_clients_shops where (clients_shops_time_validity > now() or clients_shops_time_validity IS NULL) and clients_shops_status=1 and ".implode(' and ', $shop_like_query)." order by rand() limit 10", []);
   }

   if(count($getShops)){
        ?>
        <div class="search-store-offers" >
        <?php
            foreach ($getShops as $key => $value) {
               $count_ads = $Ads->getCount("ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_id_user='{$value["clients_shops_id_user"]}'");
               ?>
                  <a href="<?php echo $Shop->linkShop($value["clients_shops_id_hash"]); ?>" > 
                    <div class="main-search-results-img" ><img src="<?php echo Exists($config["media"]["other"], $value["clients_shops_logo"], $config["media"]["no_image"]); ?>"></div>
                    <div class="main-search-results-cont" >

                      <span class="main-search-results-name" ><?php echo custom_substr($value["clients_shops_title"], 35, "..."); ?></span>
                      <span class="main-search-results-category" ><?php if($value["clients_shops_id_theme_category"]){ echo $getCategories["category_board_id"][$value["clients_shops_id_theme_category"]]["category_board_name"].' &bull; '; } ?> <?php echo $count_ads; ?> <?php echo ending($count_ads, $ULang->t("объявление"), $ULang->t("объявления"), $ULang->t("объявлений") ) ?></span>

                    </div>
                    <div class="clr" ></div>
                  </a>
               <?php
            }
        ?>
        </div>
        <?php
   }

}

}
?>