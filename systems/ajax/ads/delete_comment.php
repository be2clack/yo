<?php
$id = intval($_POST["id"]);

if( $_SESSION['cp_auth'][ $config["private_hash"] ] ){

 $getMsg = findOne("uni_ads_comments", "ads_comments_id=?", [$id]);

 $nested_ids = idsBuildJoin($Ads->idsComments($id,$Ads->getComments($getMsg["ads_comments_id_ad"])),$id);

 if($nested_ids){
    foreach (explode(",", $nested_ids) as $key => $value) {
      
       update( "delete from uni_ads_comments where ads_comments_id=?", array( $value ) );

    }
 }

}else{

 $getMsg = findOne("uni_ads_comments", "ads_comments_id=? and ads_comments_id_user=?", [$id,intval($_SESSION["profile"]["id"])]);
 
 $nested_ids = idsBuildJoin($Ads->idsComments($id,$Ads->getComments($getMsg["ads_comments_id_ad"])),$id);

 if($nested_ids && $getMsg){
    foreach (explode(",", $nested_ids) as $key => $value) {
      
       update( "delete from uni_ads_comments where ads_comments_id=?", array( $value ) );

    }
 }

}

echo json_encode( ["status"=>true] );
?>