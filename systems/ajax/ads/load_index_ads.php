<?php
$page = (int)$_POST["page"] ? (int)$_POST["page"] : 1;
$sortingVariant = $_POST["sorting"];
$content = "";
$ad_ids_interests = [];
$query = "";
$param_search = $Elastic->paramAdquery();

if($sortingVariant == "recommendations"){

  if($_SESSION['profile']['id']){

    $getInterests = getAll("select * from uni_ads_recommendations where user_id=? order by sort desc", [$_SESSION['profile']['id']]);

    if($getInterests){

      foreach ($getInterests as $key => $value) {
        $ad_ids_interests[] = $value["ad_id"];
      }

    }else{

      if( $settings["ads_sorting_variant"] == 0 ){
        $sorting = "order by ads_sorting desc, ads_id desc";
        $param_search["sort"]["ads_sorting"] = [ "order" => "desc" ];
        $param_search["sort"]["ads_id"] = [ "order" => "desc" ];
      }elseif( $settings["ads_sorting_variant"] == 1 ){ 
        $sorting = "order by ads_sorting desc, ads_id asc";
        $param_search["sort"]["ads_sorting"] = [ "order" => "desc" ];
        $param_search["sort"]["ads_id"] = [ "order" => "asc" ];      
      }else{
        $sorting = "order by ads_sorting desc";
        $param_search["sort"]["ads_sorting"] = [ "order" => "desc" ];
      } 

    }

  }else{

    if( $settings["ads_sorting_variant"] == 0 ){
      $sorting = "order by ads_sorting desc, ads_id desc";
      $param_search["sort"]["ads_sorting"] = [ "order" => "desc" ];
      $param_search["sort"]["ads_id"] = [ "order" => "desc" ];
    }elseif( $settings["ads_sorting_variant"] == 1 ){ 
      $sorting = "order by ads_sorting desc, ads_id asc";
      $param_search["sort"]["ads_sorting"] = [ "order" => "desc" ];
      $param_search["sort"]["ads_id"] = [ "order" => "asc" ];      
    }else{
      $sorting = "order by ads_sorting desc";
      $param_search["sort"]["ads_sorting"] = [ "order" => "desc" ];
    } 

  }

}elseif($sortingVariant == "fresh"){

    $sorting = "order by ads_id desc";
    $param_search["sort"]["ads_id"] = [ "order" => "desc" ]; 

}else{

  if( $settings["ads_sorting_variant"] == 0 ){
    $sorting = "order by ads_sorting desc, ads_id desc";
    $param_search["sort"]["ads_sorting"] = [ "order" => "desc" ];
    $param_search["sort"]["ads_id"] = [ "order" => "desc" ];
  }elseif( $settings["ads_sorting_variant"] == 1 ){ 
    $sorting = "order by ads_sorting desc, ads_id asc";
    $param_search["sort"]["ads_sorting"] = [ "order" => "desc" ];
    $param_search["sort"]["ads_id"] = [ "order" => "asc" ];      
  }else{
    $sorting = "order by ads_sorting desc";
    $param_search["sort"]["ads_sorting"] = [ "order" => "desc" ];
  }

}

if($ad_ids_interests){
  $query = " and ads_id IN(".implode(",", $ad_ids_interests).")";
}

if($settings["index_out_content_method"] == 0){

    $results = $Ads->getAll( ["sort"=>$sorting, "query"=>"ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() $query", "navigation" => true, "page" => $page, "output" => $settings["index_out_content"], "param_search" => $param_search ] );

}else{

    if($settings["main_type_products"] == 'physical'){
        $geo = $Ads->queryGeo() ? " and " . $Ads->queryGeo() : "";
    }

    $results = $Ads->getAll( ["sort"=>$sorting, "query"=>"ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() $query $geo", "navigation" => true, "page" => $page, "output" => $settings["index_out_content"], "param_search" => $param_search ] );

}

if($results["count"]){

  if($page <= getCountPage($results["count"],$settings["index_out_content"])){

      if($page == 1 && $sortingVariant == "recommendations"){
         shuffle($results["all"]);
      }

      foreach ($results["all"] as $key => $value) {
         $_SESSION['count_display_ads'][$value['ads_id']] = $value['ads_id_user'];
         ob_start();
         include $config["template_path"] . "/include/home_ad_grid.php";
         $content .= ob_get_clean();
      }

  }

  if($page + 1 <= getCountPage($results["count"],$settings["index_out_content"])){
    
    $found = true;

    if( $settings["type_content_loading"] == 1 ){
        $content .= '
          
          <div class="col-lg-12" >
          <div class="ajax-load-button action-index-load-ads text-center mt20" >
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

     $found = false;

  }


}else{

   $found = false;

   $content = '
       <div class="col-lg-12" >
       <div class="catalog-no-results" >
          <div class="catalog-no-results-box" >
              <img src="'.$settings["path_tpl_image"].'/person-shrugging_1f937.png" />
              <h5>'.$ULang->t("Объявлений нет").'</h5>
          </div>
       </div>           
       </div>
   ';

}


echo json_encode(array("content"=>$content, "found"=>$found));
?>