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

  $id = (int)$_POST['id'];

  $getUpdate = findOne("uni_updates", "id=?", [$id]);

  if($getUpdate["logs"]){
     $getUpdate["logs"] = json_decode($getUpdate["logs"], true);
     if($getUpdate["logs"]){
       foreach ($getUpdate["logs"] as $key => $value) {
          if($value["status"] == "error"){
            echo '<p class="update-log-line-error" ><i class="la la-times"></i> ['.$value["time"].'] '.$value["line"].'</p>';
          }else{
            echo '<p class="update-log-line-success" ><i class="la la-check"></i> ['.$value["time"].'] '.$value["line"].'</p>';
          }
       }
     }
  }else{
     ?>
     <div style="text-align: center;" > <p>Лог пуст</p> </div>
     <?php
  }

}  
?>