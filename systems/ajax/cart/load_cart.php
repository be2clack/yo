<?php

$id = (int)$_POST['id'];

$cart = $Cart->getCart();

if(count($cart)){

        foreach ($cart as $id => $value) {

                $count = $value['count'];

                $image = $Ads->getImages($value['ad']["ads_images"]);

                $price_info = '
                    <span class="cart-goods-item-content-price" >'.$count.' x '.$Main->price( $value['ad']["ads_price"] ).'</span>
                    <span class="cart-goods-item-content-price-total" >'.$Main->price( $value['ad']["ads_price"] * $count ).'</span>
                ';

                $getShop = $Shop->getShop(['user_id'=>$value['ad']["ads_id_user"],'conditions'=>true]);

                if( $getShop ){
                    $link = '<a href="'.$Shop->linkShop($getShop["clients_shops_id_hash"]).'" class="cart-goods-item-content-seller"  >'.$getShop["clients_shops_title"].'</a>';
                }else{
                    $link = '<a href="'._link( "user/" . $value['ad']["clients_id_hash"] ).'" class="cart-goods-item-content-seller"  >'.$Profile->name($value['ad']).'</a>';
                }

                if($value['ad']['ads_available'] == 1){
                    if($settings["main_type_products"] == 'physical'){
                        $notification_available = '<div class="mt10" >'.$ULang->t('Остался 1 товар').'</div>';
                    }
                }else{
                    $notification_available = '
                        <div class="input-group input-group-change-count input-group-sm mt10">
                            <div class="input-group-prepend">
                            <button class="cart-goods-item-count-change" data-action="minus" ><i class="las la-minus"></i></button>
                            </div>
                            <input type="text" class="form-control cart-goods-item-count" value="'.$count.'" >
                            <div class="input-group-append">
                            <button class="cart-goods-item-count-change" data-action="plus" ><i class="las la-plus"></i></button>
                            </div>
                        </div>
                    ';
                }

                if( $value['ad']["ads_status"] != 1 || strtotime($value['ad']["ads_period_publication"]) < time() ){
                        
                        $status = '<div class="cart-goods-item-label-status" >'.$Ads->publicationAndStatus($value['ad']).'</div>';
                        $group = '';

                }else{ 

                if(!$value['ad']['ads_available_unlimitedly']){

                    if($value['ad']['ads_available']){
                        $group = '<div class="cart-goods-item-box-flex" ><div class="cart-goods-item-content-price-info cart-goods-item-box-flex1" >'.$price_info.'</div><div class="cart-goods-item-box-flex2" >'.$notification_available.'</div></div>';
                    }else{
                        $group = '<div class="cart-goods-item-box-flex" ><div class="cart-goods-item-content-price-info cart-goods-item-box-flex1" >'.$price_info.'</div><div class="cart-not-available cart-goods-item-box-flex2" >'.$ULang->t('Нет в наличии').'</div></div>';
                    }

                }else{
                    $group = '<div class="cart-goods-item-box-flex" ><div class="cart-goods-item-content-price-info cart-goods-item-box-flex1" >'.$price_info.'</div><div class="cart-goods-item-box-flex2" >'.$notification_available.'</div></div>';
                }

                        $status = '';

                }

                $items .= '
                <div class="cart-goods-item" data-id="'.$value['ad']["ads_id"].'" >

                    <div class="row" >
                        <div class="col-lg-3 col-12 col-md-3 col-sm-3" >
                            <div class="cart-goods-item-image" >
                                <img class="image-autofocus" alt="'.$value['ad']["ads_title"].'" src="'.Exists($config["media"]["small_image_ads"],$image[0],$config["media"]["no_image"]).'"  >
                            </div>
                        </div>
                        <div class="col-lg-9 col-12 col-md-9 col-sm-9" >

                                <div class="cart-goods-item-content" >
                                '.$status.'
                                <a href="'.$Ads->alias($value['ad']).'" >'.$value['ad']["ads_title"].'</a>
                                '.$link.'
                                '.$group.'
                                </div>

                                <div class="text-right" ><span class="cart-goods-item-delete" >'.$ULang->t('Удалить').'</span></div>

                        </div>               
                    </div>
                    
                </div>
                ';

        }

        $container = '

                <div class="cart-goods" >
                        '.$items.'
                </div>

                <div class="cart-buttons" >

                    <a class="btn-custom btn-color-blue mb5 width100" href="'._link('cart').'" >
                    <span>'.$ULang->t("Перейти к оформлению").'</span>
                    </a>
                
                </div>

        ';

}else{

        $container = '

            <div class="cart-empty" >
            
                <div class="cart-empty-icon" >
                <i class="las la-shopping-basket"></i>
                <p>'.$ULang->t('Корзина пуста').'</p>
                </div>         

            </div>

        ';

}

$info = $Cart->totalCount() . ' ' . ending($Cart->totalCount(), $ULang->t('товар'), $ULang->t('товара'), $ULang->t('товаров')) . ' '.$ULang->t('на сумму').' ' . $Main->price( $Cart->calcTotalPrice() );

$itog = $Main->price( $Cart->calcTotalPrice() );

echo json_encode(['items'=>$container, 'counter'=>$Cart->totalCount(), 'info'=>$info, 'itog'=>$itog]);

?>