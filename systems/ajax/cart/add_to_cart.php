<?php

$id = (int)$_POST['id'];

if(!$id) exit;

    $getAd = findOne('uni_ads', 'ads_id=?', [$id]);

if($getAd["ads_available_unlimitedly"]){
    $next = true;
}elseif($getAd["ads_available"]){
    $next = true;
}else{
    $next = false;
}

if($next){

    if( !isset($_SESSION['cart'][$id]) ){
            $_SESSION['cart'][$id] = 1; 
            if($_SESSION['profile']['id']){
                $Main->addActionStatistics(['ad_id'=>$id,'from_user_id'=>$_SESSION['profile']['id'],'to_user_id'=>$getAd['ads_id_user']],"add_to_cart");
            }
            echo json_encode(['status'=>true, 'action'=>'add', 'view_cart'=>$settings["marketplace_view_cart"], 'link_cart'=>_link('cart')]);
    }else{
            unset($_SESSION['cart'][$id]);

            if($_SESSION['profile']['id']){
                    update("DELETE FROM uni_cart WHERE cart_ad_id=? and cart_user_id=?", [$id,$_SESSION["profile"]["id"]]);
            }

            echo json_encode(['status'=>true, 'action'=>'delete', 'view_cart'=>$settings["marketplace_view_cart"], 'link_cart'=>_link('cart')]);
    }

    $Cart->refresh();

}else{
    echo json_encode(['status'=>false, 'answer'=>$ULang->t('Данного товара уже нет в наличии')]);
}

?>