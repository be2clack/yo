<?php
if(!$_SESSION['profile']['id']){ exit(json_encode(["status"=>false])); }

$id_ad = (int)$_POST["id_ad"];
$id_msg = (int)$_POST["id_msg"];
$text = clear($_POST["text"]);

if($id_msg){
 if( $_POST["token"] != md5($config["private_hash"].$id_msg.$id_ad) ){
     exit(json_encode(["status"=>false]));
 }
}

$getAd = findOne( "uni_ads", "ads_id=?", [$id_ad]);

if(!$getAd){
  exit(json_encode(["status"=>false]));
}

$getUser = findOne( "uni_clients", "clients_id=?", [$getAd["ads_id_user"]]);

if(!$settings["ads_comments"] || !$getUser["clients_comments"]){
  exit(json_encode(["status"=>false]));
}

$locked = $Profile->getUserLocked( $getAd["ads_id_user"], $_SESSION["profile"]["id"] );

if( $locked ){
 exit(json_encode(["status"=>false]));
}

if($text){

 insert("INSERT INTO uni_ads_comments(ads_comments_id_user,ads_comments_text,ads_comments_date,ads_comments_id_parent,ads_comments_id_ad)VALUES(?,?,?,?,?)", [$_SESSION['profile']['id'],$text,date("Y-m-d H:i:s"),$id_msg,$id_ad]);

 echo json_encode( ["status"=>true] );

}else{
 echo json_encode( ["status"=>false] );
}
?>