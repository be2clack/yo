<?php

$page = (int)$_POST["page"] ? (int)$_POST["page"] : 1;
$query = clear( $_POST["search"] );
$id_user = (int)$_POST["id_u"];

$param_search = $Elastic->paramAdSearch( $query, $id_user );
$param_search["sort"]["ads_sorting"] = [ "order" => "desc" ];

if( $query ){

  $results = $Ads->getAll( array( "query"=>"ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_id_user='{$id_user}' and " . $Filters->explodeSearch( $query ), "navigation"=>true, "page"=>$page, "param_search" => $param_search ) );
  
}else{

  $results = $Filters->queryFilter($_POST, ["navigation"=>true, "page"=>$page, "disable_query_geo" => true]);

}

if($results["count"]){

  if($page <= getCountPage($results["count"],$settings["catalog_out_content"])){

      foreach ($results["all"] as $key => $value) {
         ob_start();
         include $config["template_path"] . "/include/shop_ad_grid.php";
         $content .= ob_get_clean();
      }
    
  }

  if($page + 1 <= getCountPage($results["count"],$settings["catalog_out_content"])){

    $found = true;
    
    if( $settings["type_content_loading"] == 1 ){
        $content .= '
          
          <div class="col-lg-12" >
          <div class="action-shop-load-ads text-center mt20" >
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
  }


}else{

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

}


echo json_encode( array("content"=>$content, "found"=>$found) );

?>