<?php
$id_ad = intval($_POST["id_ad"]);

if($settings["ad_view_phone"] == 1){

 if($_SESSION["profile"]["id"]){

   $findAd = findOne("uni_ads", "ads_id=?", array($id_ad));

   if($_SESSION["ad-phone"][$id_ad] && $findAd){

     $Profile->sendChat( array("id_ad" => $id_ad, "action" => 2, "user_from" => $_SESSION["profile"]["id"], "user_to" => $findAd["ads_id_user"] ) );

     $Main->addActionStatistics(['ad_id'=>$id_ad,'from_user_id'=>$_SESSION['profile']['id'],'to_user_id'=>$findAd["ads_id_user"]],"show_phone");

     echo json_encode( array( "auth" => 1, "html" => '<a href="tel:+'.$_SESSION["ad-phone"][$id_ad].'" >+'.$_SESSION["ad-phone"][$id_ad].'</a>' ) );

   }   

 }else{
    echo json_encode( array("auth" => 0) );
 }

}else{

echo json_encode( array( "auth" => 1, "html" => '<a href="tel:+'.trim($_SESSION["ad-phone"][$id_ad], "+").'" >+'.trim($_SESSION["ad-phone"][$id_ad], "+").'</a>' ) );

}
?>