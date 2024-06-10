<?php
define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_clients']) ){
   exit("Ограничение прав доступа!");
}

$date = date("Y-m-d");

$get = getAll("select * from uni_subscription order by subscription_id desc");

if(!$get){ exit; }

if($_GET["format"] == "xls"){

}elseif($_GET["format"] == "txt"){

      $fp=fopen("subscribers_".$date.".txt","a");
        foreach($get as $array_data) {  
          fwrite($fp, "\r\n" . $array_data["subscription_name"] . "," . $array_data["subscription_email"]);  
        }
      fclose($fp);
      $filename = "subscribers_".$date.".txt";
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Length: ' . filesize($filename));          
      header("Content-Disposition:attachment;filename=".$filename); 
      readfile($filename);
      unlink($filename);

}
  
?>