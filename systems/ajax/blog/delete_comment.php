<?php

$id = intval($_POST["id"]);

if( $_SESSION['cp_auth'][ $config["private_hash"] ] ){

    $getMsg = findOne("uni_blog_comments", "blog_comments_id=?", [$id]);

    $nested_ids = idsBuildJoin($Blog->idsComments($id,$Blog->getComments($getMsg["blog_comments_id_article"])),$id);

    if($nested_ids){
    foreach (explode(",", $nested_ids) as $key => $value) {
        
        update( "delete from uni_blog_comments where blog_comments_id=?", array( $value ) );

    }
    }

}else{

    $getMsg = findOne("uni_blog_comments", "blog_comments_id=? and blog_comments_id_user=?", [$id,intval($_SESSION["profile"]["id"])]);
    
    $nested_ids = idsBuildJoin($Blog->idsComments($id,$Blog->getComments($getMsg["blog_comments_id_article"])),$id);

    if($nested_ids && $getMsg){
    foreach (explode(",", $nested_ids) as $key => $value) {
        
        update( "delete from uni_blog_comments where blog_comments_id=?", array( $value ) );

    }
    }

}

echo json_encode( ["status"=>true] );

?>