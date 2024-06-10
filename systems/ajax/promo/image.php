<?php

if( $_SESSION['cp_auth'][ $config["private_hash"] ] && $_SESSION["cp_control_page"] ){

  $name_file = "promo_" . uniqid() . ".jpg";

  base64_to_image( $_POST["image"] , $config["basePath"] . "/" . $config["media"]["other"] . "/" . $name_file );

  echo $config["media"]["other"] . "/" . $name_file ;

}

?>