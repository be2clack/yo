<?php

class Cart{

	function calcTotalPrice(){
		global $settings;

		$cart = $this->getCart();

		$total = 0;

		if(count($cart)){
			foreach ($cart as $id => $value) {

				if($value['ad']["ads_status"] == 1 && strtotime($value['ad']["ads_period_publication"]) > time()){

					if($settings["main_type_products"] == 'physical'){
						if(!$value['ad']['ads_available_unlimitedly']){
							if($value['ad']['ads_available'] < $value['count'] && $value['ad']['ads_available']){
								$value['count'] = $value['ad']['ads_available'];
							}
						}
					}

					$total += $value['ad']['ads_price'] * $value['count'];

				}

			}
		}

		return $total;

	}

	function getCart(){

		$Ads = new Ads();

		$cart = [];

		if(isset($_SESSION['cart'])){
			foreach ($_SESSION['cart'] as $id => $count) {

				$getAd = $Ads->get('ads_id=?', [$id]);

				if($getAd){
				    $cart[$id]['count'] = $count;
				    $cart[$id]['ad'] = $getAd;
				}else{
					unset($_SESSION['cart'][$id]);
				}

			}
		}	

		return $cart;	

	}

	function refresh(){

		$cart = $this->getCart();

		if($_SESSION['profile']['id']){

			if(count($cart)){
				foreach ($cart as $id => $value) {

					$get = findOne('uni_cart', 'cart_ad_id=? and cart_user_id=?', [$id, intval($_SESSION['profile']['id'])]);

					if(!$get){
						insert("INSERT INTO uni_cart(cart_ad_id,cart_user_id,cart_date_add,cart_count)VALUES(?,?,?,?)", [$id,$_SESSION["profile"]["id"],date("Y-m-d H:i:s"),$value['count']]);
					}else{
						update("UPDATE uni_cart SET cart_count=? WHERE cart_id=?", [$value['count'],$get['cart_id']]);
					}

				}
			}

			$getAllCart = getAll('select * from uni_cart where cart_user_id=?', [intval($_SESSION['profile']['id'])]);

			if(count($getAllCart)){
				foreach ($getAllCart as $value) {
					$_SESSION['cart'][$value['cart_ad_id']] = $value['cart_count'];
				}
			}


		}

	}

	function totalCount(){

		$total = 0;

		$cart = $this->getCart();

		if(count($cart)){
			foreach ($cart as $id => $value) {

				if($value['ad']["ads_status"] == 1 && strtotime($value['ad']["ads_period_publication"]) > time()){
					$total = $total + $value['count'];
				}

			}
		}

		return $total;

	}

	function modeAvailableCart($category=[],$id_cat=0,$id_user=0){
		 global $settings;

		 $Shop = new Shop();

		 if( $category["category_board_id"][$id_cat]["category_board_marketplace"] ){

	          if($settings['marketplace_available_cart'] == 'all'){
	                return true;
	          }elseif($settings['marketplace_available_cart'] == 'shop'){
	                $getShop = $Shop->getUserShop($id_user);
	                if($getShop){
	                    return true;
	                }
	          }

         }

         return false;	

	}

	function returnAvailable($id){

		 $Ads = new Ads();

		 $getOrder = findOne("uni_clients_orders", "clients_orders_uniq_id=? and (clients_orders_from_user_id=? or clients_orders_to_user_id=?)", [ $id, $_SESSION['profile']['id'], $_SESSION['profile']['id'] ]);

		 if($getOrder){

	         $getAds = getAll('select * from uni_secure_ads where secure_ads_order_id=?', [$id]);

	         if(count($getAds)){
	             foreach ($getAds as $key => $value) {
	                 $get = $Ads->get('ads_id=?', [$value['secure_ads_ad_id']]);
	                 if($get && $get["category_board_marketplace"] && !$get['ads_available_unlimitedly']){
	                    update('update uni_ads set ads_available=ads_available+'.$value['secure_ads_count'].' where ads_id=?', [$value['secure_ads_ad_id']]);
	                 }
	             }
	         }

     	 }

	}

	function checkCartDeliveryAds(){
		
		$Ads = new Ads();

		$cart = $this->getCart();

		if(count($cart)){
			foreach ($cart as $id => $value) {

				if($Ads->getStatusDelivery($value['ad'])){
					return true;
				}

			}
		}

		return false;

	}



}

?>