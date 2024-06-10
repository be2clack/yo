<?php
if(!$_SESSION['profile']['id']) exit(json_encode(array( "auth"=>0 )));

$id_ad = intval($_POST["id_ad"]);

$findAd = findOne("uni_ads", "ads_id=?", array($id_ad));

if($findAd){

     $find = findOne("uni_favorites", "favorites_id_ad=? and favorites_from_id_user=?", array($id_ad,$_SESSION['profile']['id']));
     if($find){

        update("DELETE FROM uni_favorites WHERE favorites_id=?", array($find->favorites_id));
        unset($_SESSION['profile']["favorite"][$id_ad]);
        echo json_encode( array( "auth"=>1, "html" => $ULang->t("Добавить в избранное"), "status" => 0 ) );

     }else{
        
        insert("INSERT INTO uni_favorites(favorites_id_ad,favorites_from_id_user,favorites_to_id_user,favorites_date)VALUES(?,?,?,?)", [$id_ad,$_SESSION['profile']['id'],$findAd['ads_id_user'],date('Y-m-d H:i:s')]);
        $_SESSION['profile']["favorite"][$id_ad] = $id_ad;

        $Profile->sendChat( array("id_ad" => $id_ad, "action" => 1, "user_from" => $_SESSION["profile"]["id"], "user_to" => $findAd["ads_id_user"]) );

        $Main->addActionStatistics(['ad_id'=>$id_ad,'from_user_id'=>$_SESSION['profile']['id'],'to_user_id'=>$findAd["ads_id_user"]],"favorite");

        echo json_encode( array( "auth"=>1, "html" => $ULang->t("Удалить из избранного"), "status" => 1 ) );

     }

}
?>