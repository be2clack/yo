<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_settings']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){
    
    if($_POST["token"]){
		$get = file_get_contents_curl( "https://api.telegram.org/bot".$_POST["token"]."/getUpdates" );
	    $json = json_decode($get, true);
		$id = $json["result"][0]["message"]["chat"]["id"];
		if($id){
			echo json_encode( array("status"=>true, "answer" => $id ) );
		}else{
			echo json_encode( array("status"=>false, "answer" => $get ) );
		}
    }

}     
?>