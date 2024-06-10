<?php

$page = (int)$_POST["page"] ? (int)$_POST["page"] : 1;
$query = clearSearchBack($_POST["search"]);
$output = $settings["catalog_out_content"];

$param_search = $Elastic->paramAdSearch($query);
$param_search["sort"]["ads_sorting"] = [ "order" => "desc" ];

if( $query ){
  
  if($settings["main_type_products"] == 'physical'){
      $geoQuery = $Ads->queryGeo();
      $geoQuery = $geoQuery ? ' and ' . $geoQuery : '';
  }

  $results = $Ads->getAll( array( "query"=>"ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() $geoQuery and " . $Filters->explodeSearch($query), "navigation"=>true, "output"=>$output, "page"=>$page, "param_search" => $param_search ) );
  
}else{

  $results = $Filters->queryFilter($_POST, ["navigation"=>true, "output"=>$output, "page"=>$page]);

}

unset($_SESSION['current_load']['total']);

$getCategoryBoard = (new CategoryBoard())->getCategories("where category_board_visible=1");

if($results["count"]){

  $_SESSION['current_load']['total'] = $results["count"];

  if($page <= getCountPage($results["count"],$output)){

    if($_SESSION["catalog_ad_view"] == "grid" || !$_SESSION["catalog_ad_view"]){
      foreach ($results["all"] as $key => $value) {
         $ad_not_city_distance[] = $value["ads_city_id"];
         $_SESSION['count_display_ads'][$value['ads_id']] = $value['ads_id_user'];
         ob_start();
         include $config["template_path"] . "/include/catalog_ad_grid.php";
         $content .= ob_get_clean();
      }
    }elseif($_SESSION["catalog_ad_view"] == "list"){
      foreach ($results["all"] as $key => $value) {
         $ad_not_city_distance[] = $value["ads_city_id"];
         $_SESSION['count_display_ads'][$value['ads_id']] = $value['ads_id_user'];
         ob_start();
         include $config["template_path"] . "/include/catalog_ad_list.php";
         $content .= ob_get_clean();
      }
    }
    
  }

  $getCityDistance = $Ads->getCityDistance( $_POST, $ad_not_city_distance );

  if($page + 1 <= getCountPage($results["count"],$output)){

    $found = true;
    
    if( $settings["type_content_loading"] == 1 ){
        $content .= '
          
          <div class="col-lg-12" >
          <div class="action-catalog-load-ads text-center mt20" >
              <button class="btn-custom btn-color-blue width250 button-inline" > <span class="action-load-span-start" > <span class="spinner-border spinner-border-sm button-ajax-loader" role="status" aria-hidden="true"></span> '.$ULang->t("Загрузка").'</span> <span class="action-load-span-end" >'.$ULang->t("Показать еще").' <i class="la la-angle-down"></i></span> </button>
          </div>
          </div>

        ';
    }else{
        $content .= '
          
          <div class="col-lg-12" >
          <div class="text-center mt20 preload-scroll" >

              <div class="spinner-grow preload-spinner" role="status">
                <span class="sr-only"></span>
              </div>
              
          </div>
          </div>

        ';           
    }

  }else{

     $content .= '
       <div class="col-lg-12" >
       <p class="text-center mt15" >'.$ULang->t("Измените условия поиска, чтобы увидеть больше объявлений").'</p>
       </div>
     ';

     if( $getCityDistance["count"] ){

         $content .= '
             <div class="col-lg-12 text-center" >
             <h4 class="mt40 mb40" ><strong>'.$ULang->t("Объявления в ближайших городах").'</strong></h4>
             </div>
         ';

         foreach ($getCityDistance["all"] as $key => $value) {
             $_SESSION['count_display_ads'][$value['ads_id']] = $value['ads_id_user'];
             ob_start();
             include $config["template_path"] . "/include/catalog_ad_grid.php";
             $content .= ob_get_clean();

         }

     }

  }


}else{

   $getCityDistance = $Ads->getCityDistance( $_POST );

   $found = false;

   $content = '
       <div class="col-lg-12" >
       <div class="catalog-no-results" >
          <div class="catalog-no-results-box" >
              <img src="'.$settings["path_tpl_image"].'/person-shrugging_1f937.png" />
              <h5>'.$ULang->t("Ничего не найдено").'</h5>
              <p>'.$ULang->t("Увы, мы не нашли то, что вы искали. Смягчите условия поиска и попробуйте еще раз.").'</p>
          </div>
       </div>           
       </div>
   ';

   if( $getCityDistance["count"] ){

       $content .= '
           <div class="col-lg-12 text-center" >
           <h4 class="mt40 mb40" ><strong>'.$ULang->t("Объявления в ближайших городах").'</strong></h4>
           </div>
       ';

       foreach ($getCityDistance["all"] as $key => $value) {
           $_SESSION['count_display_ads'][$value['ads_id']] = $value['ads_id_user'];
           ob_start();
           include $config["template_path"] . "/include/catalog_ad_grid.php";
           $content .= ob_get_clean();

       }

   }


}


echo json_encode(array("content"=>$content, "found"=>$found, "count" => $results["count"]));

?>