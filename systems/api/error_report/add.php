<?php

if(trim($_POST["error"])){
	file_put_contents($config["basePath"].'/systems/api/error_'.date("Y-m-d").'.log', $_POST["error"], FILE_APPEND);
}

?>