<?php

$id = (int)$_POST['id'];
$variant = $_POST['variant'];

$getAd = findOne('uni_ads', 'ads_id=?', [$id]);

if($getAd){

    if(!$getAd['ads_available'] && !$getAd['ads_available_unlimitedly']){
        echo json_encode(['status'=>false, 'available'=>0]);
        exit;
    }
    
    if($variant == 'minus'){

            $_SESSION['cart'][$id]--;

            if( abs($_SESSION['cart'][$id]) == 0 ){
                    $_SESSION['cart'][$id] = 1;
            }

    }elseif($variant == 'plus'){
            
            if($getAd['ads_available_unlimitedly']){
                    $_SESSION['cart'][$id]++;
            }else{

                    $_SESSION['cart'][$id]++;

                    if(abs($_SESSION['cart'][$id]) > $getAd['ads_available']){
                            $_SESSION['cart'][$id] = $getAd['ads_available'];
                    }

            }

    }

}else{
    unset($_SESSION['cart'][$id]);
}

$info = $Cart->totalCount() . ' ' . ending($Cart->totalCount(), $ULang->t('товар'), $ULang->t('товара'), $ULang->t('товаров')) . ' '.$ULang->t('на сумму').' ' . $Main->price( $Cart->calcTotalPrice() );

$total = '
<span class="cart-goods-item-content-price" >'.intval($_SESSION['cart'][$id]).' x '.$Main->price( $getAd["ads_price"] ).'</span>
<span class="cart-goods-item-content-price-total" >'.$Main->price( $getAd["ads_price"] * intval($_SESSION['cart'][$id]) ).'</span>
';

$itog = $Main->price( $Cart->calcTotalPrice() );

echo json_encode(['status'=>true, 'count'=>$_SESSION['cart'][$id],'total'=>$total, 'counter'=>$Cart->totalCount(), 'info'=>$info, "itog"=>$itog]);

?>