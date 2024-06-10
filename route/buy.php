<?php
$config = require "./config.php";

$route_name = "buy";
$visible_footer = false;

$Main = new Main();
$settings = $Main->settings();

$Ads = new Ads();
$Main = new Main();
$Seo = new Seo();
$Geo = new Geo();
$Banners = new Banners();
$Profile = new Profile();
$ULang = new ULang();

if( !$_SESSION["profile"]["id"] ){
    header( "Location: " . _link("auth") );
}

$data["ad"] = $Ads->get("ads_id=? and ads_status IN(1,4,5)", [intval($id_ad)]);

if(!$data["ad"]){
    $Main->response(404);
}

$data["order"] = $Main->getSecureAdOrder('secure_ads_ad_id=? and secure_status NOT IN(3,5) and (secure_id_user_buyer=? or secure_id_user_seller=?)', [intval($id_ad),$_SESSION["profile"]["id"],$_SESSION["profile"]["id"]]);

if($data["order"]){
    header("Location: " . _link("order/".$data["order"]["secure_id_order"]) );
    exit;
}

if($data["ad"]["ads_auction"]){

    if( $data["ad"]["ads_status"] == 1 ){

        if( $data["ad"]["ads_auction_price_sell"] ){
            $data["ad"]["ads_price"] = $data["ad"]["ads_auction_price_sell"];
        }else{
            $Main->response(404);
        }

    }elseif( $data["ad"]["ads_status"] == 4 ){

        $auction_user_winner = $Ads->getAuctionWinner(intval($id_ad));

        if( !$auction_user_winner || $_SESSION["profile"]["id"] != $auction_user_winner["ads_auction_id_user"] ){
            $Main->response(404);
        }

    }

}

if(!$Ads->getStatusSecure($data["ad"])){
    $Main->response(404);
}

$data["ad"]["ads_images"] = $Ads->getImages($data["ad"]["ads_images"]);

echo $Main->tpl("buy.tpl", compact( 'Seo','Geo','Main','visible_footer','route_name','settings','config','Banners','Profile',"Ads","data","ULang" ) );

?>