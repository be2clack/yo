<?php
$id_cat = (int)$_POST["id_cat"];
$id_ad = (int)$_POST["id_ad"];

if($id_ad && $id_cat && $settings["ad_similar_count"]){

  $getAd = findOne("uni_ads", "ads_id=?", [$id_ad] );

  if(!$getAd) exit;

  $getTariff = $Profile->getOrderTariff($getAd["ads_id_user"]);

  $ids_cat = idsBuildJoin( $CategoryBoard->idsBuild($id_cat, $CategoryBoard->getCategories()), $id_cat );

  $param_search = $Elastic->paramAdquery();

  if($getTariff['services']['hiding_competitors_ads']){
      $param_search["query"]["bool"]["filter"][]["terms"]["ads_id_user"] = $getAd["ads_id_user"];
      $ads_id_user = "and ads_id_user='{$getAd["ads_id_user"]}'";
  }

  $param_search["query"]["bool"]["filter"][]["terms"]["ads_id_cat"] = explode(",", $ids_cat);
  $param_search["sort"]["ads_sorting"] = [ "order" => "desc" ];

  $data["similar"] = $Ads->getAll( [ "query" => "ads_id_cat IN(".$ids_cat.") and clients_status IN(0,1) and ads_status='1' and ads_period_publication > now() and ads_id!=".$id_ad." {$ads_id_user} order by ads_sorting desc limit " . $settings["ad_similar_count"], "param_search" => $param_search, "output" => $settings["ad_similar_count"] ] );

   if($data["similar"]["all"]){

      foreach ($data["similar"]["all"] as $key => $value) {
         $_SESSION['count_display_ads'][$value['ads_id']] = $value['ads_id_user'];
         ob_start();
         include $config["template_path"] . "/include/ad_grid.php";
         $content .= ob_get_clean();
      }

      echo json_encode(array("content"=>$content));

   }

}

?>