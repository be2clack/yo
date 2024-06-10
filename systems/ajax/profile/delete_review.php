<?php

$getReview = findOne("uni_clients_reviews", "clients_reviews_id=? and clients_reviews_from_id_user=?", [intval($_POST["id"]),intval($_SESSION["profile"]["id"])] );
     
if($getReview["clients_reviews_files"]){
   $files = explode(",", $getReview["clients_reviews_files"]);
   if($files){
      foreach ($files as $name) {
          @unlink( $config["basePath"] . "/" . $config["media"]["user_attach"] . "/" . $name );
      }
   }
}

update("delete from uni_clients_reviews where clients_reviews_id=? and clients_reviews_from_id_user=?", [intval($_POST["id"]),intval($_SESSION["profile"]["id"])]);

?>