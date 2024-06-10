<?php

if($_POST["ids"]){

$ids = iteratingArray( explode(",", $_POST["ids"]) , "int");

$param_search = $Elastic->paramAdquery();

$param_search["query"]["bool"]["filter"][]["terms"]["ads_id"] = $ids;

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

$result = $Ads->getAll( array("query"=>"ads_id IN(".implode(",",$ids).")", "sort"=>$sorting, "navigation"=>true, "page"=>intval($_POST["page"]), "param_search" => $param_search) );

foreach ($result["all"] as $key => $value) {

   $_SESSION['count_display_ads'][$value['ads_id']] = $value['ads_id_user'];
   
   ob_start();
   include $config["template_path"] . "/include/map_ad_grid.php";
   $offers .= ob_get_clean();

}

$navigation = '
    <div>
      <ul class="pagination pagination-map-offers justify-content-center mt15">  
         '.out_navigation( array("count"=>$result["count"], "output" => $settings["catalog_out_content"], "prev"=>'<i class="la la-long-arrow-left"></i>', "next"=>'<i class="la la-arrow-right"></i>', "page_count" => intval($_POST["page"]), "page_variable" => "page") ).'
      </ul>
    </div>
';

echo json_encode( [ "offers" => '<div class="row no-gutters">' . $offers . '</div>' . $navigation, "count" => $result["count"], "status" => true, "countHtml" => $result["count"] . " " . ending($result["count"],$ULang->t("объявление"),$ULang->t("объявления"),$ULang->t("объявлений") ) ] );

}else{

$offers = '
  <div class="map-no-result" >
  <i class="las la-search-location"></i>
  <h6><strong>'.$ULang->t("К сожалению, нет объявлений в этой области карты").'</strong></h6>
  <p>'.$ULang->t("Попробуйте сменить масштаб или область карты.").'</p>
  </div>
';

echo json_encode( [ "offers" => $offers, "count" => 0, "status" => false ] );

}

?>