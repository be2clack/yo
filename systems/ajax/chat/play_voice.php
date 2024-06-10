<?php

$id_hash = clear($_POST['id_hash']);
$id = (int)$_POST['id'];

if($id_hash && $id){

    $getMessage = findOne('uni_chat_messages', 'chat_messages_id_hash=? and chat_messages_id=?', [$id_hash,$id]);

 if($getMessage["chat_messages_attach"]){
      $attach = json_decode($getMessage["chat_messages_attach"], true);
      if(file_exists($config["basePath"] . "/" . $config["media"]["attach"] . "/voice/" . $attach['voice'])){
          $getVoice = file_get_contents($config["basePath"] . "/" . $config["media"]["attach"] . "/voice/" . $attach['voice']);
          echo 'data:audio/mp3;base64,' . base64_encode(decrypt($getVoice));
      }
 }

}

?>