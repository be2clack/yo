<?php

if( $_SESSION['cp_auth'][ $config["private_hash"] ] && $_SESSION["cp_control_page"] ){
 	echo file_get_contents( $config["template_path"]."/include/promo/" . $_POST["name"] . ".html" );
}

?>