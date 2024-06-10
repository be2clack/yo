<?php

if($_FILES['voice']){
    $name = md5($_FILES['voice']['tmp_name'].time()).'.mp3';
    if (move_uploaded_file($_FILES['voice']['tmp_name'], $config["basePath"] . "/" . $config["media"]["attach"] . "/voice/" . $name)) {
         $getVoice = file_get_contents($config["basePath"] . "/" . $config["media"]["attach"] . "/voice/" . $name);
         file_put_contents($config["basePath"] . "/" . $config["media"]["attach"] . "/voice/" . $name, encrypt($getVoice));
        echo json_encode(['status'=>true, 'name'=>$name]);
    }
}

?>