<?php
defined('unisitecms') or exit();

$getAdsAutoRenewal = $Ads->getAll( ["query"=>"ads_status='1' and clients_status='1' and ads_period_publication < now() and ads_auto_renewal='1' limit 5000"], [], false );

if($getAdsAutoRenewal['count']){
   foreach ($getAdsAutoRenewal['all'] as $key => $value) {

      $value = $Ads->getDataAd($value);

		$period = $Ads->adPeriodPub($value["ads_period_day"]);

		if(!$value["ads_id_import"]){
			$tariff = $Profile->getOrderTariff($value["ads_id_user"]);

			if($tariff['services']['scheduler']){

				update("UPDATE uni_ads SET ads_period_publication=?,ads_datetime_add=?,ads_period_day=? WHERE ads_id=?", [$period['date'],date("Y-m-d H:i:s"),$period['days'],$value["ads_id"]], true);

			}
		}else{
			update("UPDATE uni_ads SET ads_period_publication=?,ads_datetime_add=?,ads_period_day=? WHERE ads_id=?", [$period['date'],date("Y-m-d H:i:s"),$period['days'],$value["ads_id"]], true);
		}

	}

}

$getAdsPeriodEnd = $Ads->getAll( ["query"=>"ads_status='1' and clients_status='1' and ads_period_publication < now()"], [], false );

if( $getAdsPeriodEnd['count'] ){
   foreach ($getAdsPeriodEnd['all'] as $key => $value) {

      $value = $Ads->getDataAd($value);

      update("update uni_ads set ads_status=? where ads_id=?", [ 2, $value["ads_id"] ]);

      $image = $Ads->getImages($value["ads_images"]);

      $notifications = $Profile->paramNotifications($value["clients_notifications"]);

      if($notifications["answer_ad"]){

      $data = array("{AD_LINK}"=>'<a href="'.$Ads->alias($value).'" >'.$value["ads_title"].'</a>',
                   "{USER_NAME}"=>$value["clients_name"],
                   "{AD_IMAGE}"=>Exists($config["media"]["small_image_ads"],$image[0],$config["media"]["no_image"]),
                   "{AD_TITLE}"=>$value["ads_title"],
                   "{UNSUBCRIBE}"=>"",
                   "{LINK_NOTIFICATIONS}"=>_link("user/".$value["clients_id_hash"]."/settings?modal=notifications"),
                   "{EMAIL_TO}"=>$value["clients_email"]
                   );

      email_notification( array( "variable" => $data, "code" => "END_AD" ) );

      }

   }
   
}

?>