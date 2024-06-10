<?php

if(!$_SESSION['profile']['id']){ echo json_encode( ["status"=>false] ); exit; }

$id_article = (int)$_POST["id_article"];
$id_msg = (int)$_POST["id_msg"];
$text = clear($_POST["text"]);

if($id_msg){
    if( $_POST["token"] != md5($config["private_hash"].$id_msg.$id_article) ){
        echo json_encode( ["status"=>false] ); exit;
    }
}

if($text){

    insert("INSERT INTO uni_blog_comments(blog_comments_id_user,blog_comments_text,blog_comments_date,blog_comments_id_parent,blog_comments_id_article)VALUES(?,?,?,?,?)", [$_SESSION['profile']['id'],$text,date("Y-m-d H:i:s"),$id_msg,$id_article]);

    echo json_encode( ["status"=>true] );

}else{
    echo json_encode( ["status"=>false] );
}

?>