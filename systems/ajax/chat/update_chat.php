<?php

if(!isset($_SESSION['profile']['id'])){ exit(json_encode(["auth" => false])); }

$id_hash = clear($_POST["id"]);
$viewLastMessage = [];

$getActiveDialogs = $Profile->getActiveChatDialogs($_SESSION["profile"]["id"]);

if( count($getActiveDialogs) ){
    foreach ($getActiveDialogs as $id_hash => $value) {

        $statusViewLastMessage = findOne("uni_chat_messages","chat_messages_id_hash=? order by chat_messages_id desc", array($id_hash));

        if($statusViewLastMessage['chat_messages_id_user'] == $_SESSION['profile']['id']){
            $viewLastMessage[$id_hash] = (int)$statusViewLastMessage['chat_messages_status'];
        }
        
    }
}

$hash = $Profile->getMessage($_SESSION["profile"]["id"],$id_hash);
$total = $Profile->getMessage($_SESSION["profile"]["id"]);

if($id_hash){
echo json_encode( [ "auth" => true, "all" => $total['total'], "active" => $hash['total'], "hash_counts" => isset($total['hash_counts']) ? $total['hash_counts'] : "", 'view'=>$viewLastMessage ] );
}else{
echo json_encode( [ "auth" => true, "all" => $total['total'], "active" => "", "hash_counts" => isset($total['hash_counts']) ? $total['hash_counts'] : "", 'view'=>$viewLastMessage ] );
}

?>