<?php

$idUser = (int)$_GET["id_user"];

echo json_encode(apiGetUserStories($idUser));

?>