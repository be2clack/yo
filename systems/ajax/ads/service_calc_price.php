<?php
$id_s = (int)$_POST["id_s"];

$getService = findOne("uni_services_ads", "services_ads_uid=?", array($id_s));

if($getService["services_ads_variant"] == 1){

	echo $Main->outPrices( array("new_price"=> array("price"=>$getService["services_ads_new_price"], "tpl"=>'<p class="ads-services-tariffs-price-now" > <strong>{price}</strong> </p>'), "price"=>array("price"=>$getService["services_ads_price"], "tpl"=>'<p class="ads-services-tariffs-price-old" >'.$ULang->t("Цена без скидки").' <span>{price}</span></p>') ) );

}else{

	$services_order_count_day = abs($_POST["service"][$id_s]) ? abs($_POST["service"][$id_s]) : 1;

	if($getService["services_ads_new_price"]){
	    echo $Main->outPrices( array("new_price"=> array("price"=>$getService["services_ads_new_price"] * $services_order_count_day, "tpl"=>'<p class="ads-services-tariffs-price-now" > <strong>{price}</strong> </p>'), "price"=>array("price"=>$getService["services_ads_price"] * $services_order_count_day, "tpl"=>'<p class="ads-services-tariffs-price-old" >'.$ULang->t("Цена без скидки").' <span>{price}</span></p>') ) );
	}else{
	    echo $Main->outPrices( array("new_price"=> array("price"=>0, "tpl"=>'<p class="ads-services-tariffs-price-now" > <strong>{price}</strong> </p>'), "price"=>array("price"=>$getService["services_ads_price"] * $services_order_count_day, "tpl"=>'<p class="ads-services-tariffs-price-old" >'.$ULang->t("Цена без скидки").' <span>{price}</span></p>') ) );
	}

}
?>