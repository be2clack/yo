<?php
defined('unisitecms') or exit();

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

$getSubscriptions = getAll("select * from uni_ads_subscriptions where ads_subscriptions_period=2");
if( count($getSubscriptions) ){
    foreach ($getSubscriptions as $key => $value) {

       $query = [];
       $ads_list = "";
        
       $url_vars = explode("?", $value["ads_subscriptions_params"]);
       $url_parse = explode("/", $url_vars[0]);

       if( $url_vars[1] ){

           parse_str($url_vars[1], $param_filter);

       }        

       if( $url_parse[0] ){
           $getGeo = $Geo->aliasOneOf($url_parse[0]);
           if( $getGeo["table"] == "city" ){
               $param_filter["geo_array"] = ["ads_city_id" => $getGeo["id"]];
               $param_filter["geo"] = "ads_city_id='{$getGeo["id"]}'";
           }elseif( $getGeo["table"] == "region" ){
               $param_filter["geo_array"] = ["ads_region_id" => $getGeo["id"]];
               $param_filter["geo"] = "ads_region_id='{$getGeo["id"]}'";
           }elseif( $getGeo["table"] == "country" ){
               $param_filter["geo_array"] = ["ads_country_id" => $getGeo["id"]];
               $param_filter["geo"] = "ads_country_id='{$getGeo["id"]}'";
           }
       }

       if( $url_parse[1] ){

           unset($url_parse[0]);
           $alias = implode("/", $url_parse);
           $category_board_id = (int)$getCategoryBoard["category_board_chain"][$alias]["category_board_id"];

           if($category_board_id){
             $param_filter["id_c"] = $category_board_id;
           }

       }

       $param_filter["filter"]["period"] = "min";
       
       $results = $Filters->queryFilter($param_filter, [ "navigation"=>false, "ads_subscriptions"=>true, "ads_subscriptions_date" => $value["ads_subscriptions_date_update"] ]);

       if( $results["count"] ){

           foreach ( array_slice($results["all"], 0,5) as $ad_value) {
               $image = $Ads->getImages($ad_value["ads_images"]);
               $ads_list .= '
                   <div class="list-ads" >
                      <div class="list-ads-image" >
                        <img src="'.Exists($config["media"]["small_image_ads"],$image[0],$config["media"]["no_image"]).'"  >
                      </div>
                      <div class="list-ads-content" >
                        <div><a href="'.$Ads->alias($ad_value).'" >'.custom_substr($ad_value["ads_title"], 35).'</a></div>
                        <strong>'.$Main->price($ad_value["ads_price"], $ad_value["ads_currency"]).'</strong>
                      </div>
                      <div class="clr" ></div>
                   </div>
               ';  
           }


          $data = array("{SUBSCR_NAME}"=>$Ads->buildNameSubscribe( $value["ads_subscriptions_params"] ),
                        "{SUBSCR_ADS_LIST}"=>$ads_list,
                        "{SUBSCR_ADS_COUNT}"=> $results["count"],
                        "{SUBSCR_DISABLE}"=>_link( "unsubscribe/?hash=" . hash('sha256', $value["ads_subscriptions_id"].$config["private_hash"]) . "&id=" . $value["ads_subscriptions_id"] ),
                        "{SUBSCR_ALL_LINK}"=>_link($value["ads_subscriptions_params"]),
                        "{UNSUBCRIBE}"=>"",
                        "{EMAIL_TO}"=>$value["ads_subscriptions_email"]
                       );

          email_notification( array( "variable" => $data, "code" => "NEW_ADS_SUBSCRIPTIONS" ) );


       }


       update("update uni_ads_subscriptions set ads_subscriptions_date_update=? where ads_subscriptions_id=?", [ date("Y-m-d H:i:s"), $value["ads_subscriptions_id"] ]);


    }
}

?>