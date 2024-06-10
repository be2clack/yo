<?php

$id_hash = clear($_POST["id"]);
$support = (int)$_POST["support"];
$text = clear( urldecode($_POST["text"]) );
$attach = $_POST["attach"] ? array_slice($_POST["attach"],0, 10) : [];
$voice = clear($_POST["voice"]);
$duration = (int)$_POST["duration"];

if(!$support){
      $getUser = getOne("select * from uni_chat_users where chat_users_id_hash=? and chat_users_id_user=?", array($id_hash,intval($_SESSION["profile"]["id"])) );
   $Profile->sendChat( array( "id_ad" => $getUser["chat_users_id_ad"], "id_hash" => $id_hash, "text" => $text, "user_from" => intval($_SESSION["profile"]["id"]), "user_to" => $getUser["chat_users_id_interlocutor"], "attach" => $attach, 'voice' => $voice, 'duration' => $duration, "firebase" => true ) );
   }else{
         $Profile->sendChat( array( "support" => 1, "id_hash" => $id_hash, "text" => $text, "user_from" => intval($_SESSION["profile"]["id"]), "user_to" => 0, "attach" => $attach, "firebase" => true ) );
   }

echo json_encode( array( "dialog"=> $Profile->chatDialog($id_hash,$support) ) );

?>