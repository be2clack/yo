<?php
defined('unisitecms') or exit();

$getChatResponder = findOne('uni_chat_responders', 'chat_responders_status=? order by chat_responders_id asc', [0]);

if($getChatResponder){

    $getActiveUsers = getAll('select * from uni_clients where clients_status=?', [1]);

    update("update uni_chat_responders set chat_responders_status=?,chat_responders_count_users=? where chat_responders_id=?", [1,count($getActiveUsers),$getChatResponder["chat_responders_id"]]);

    if(count($getActiveUsers)){
       foreach ($getActiveUsers as $user) {
           $Profile->sendChat(array( "support" => 1, "id_responder" => $getChatResponder["chat_responders_id"], "id_hash" => md5('support'.$user['clients_id']), "text" => urldecode($getChatResponder['chat_responders_text']), "user_from" => 0, "user_to" => $user['clients_id'], "firebase" => true));
       }
    }

}
?>