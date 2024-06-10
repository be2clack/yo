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

  $zip = new ZipArchive;

  $array_versions = [];

  $install_version = clear($_POST["version"]);

  $getUpdate = findOne("uni_updates", "version=?", [$install_version]);

  if($getUpdate["status"] == 1){
     exit(json_encode(["status"=>false, "answer"=>"Обновление успешно установлено"]));
  }

  if($settings['system_version'] == $install_version){
     exit(json_encode(["status"=>false, "answer"=>"У вас стоит актуальное обновление"]));
  }

  if ( !is_dir(__dir__."/updates") ) {
      if(!mkdir(__dir__."/updates", 0777) ){
        $Update->addLog("Недостаточно прав на запись в папку include/modules/settings/handlers","error",$install_version);
        exit;        
      }      
  }

  $system_version = explode(".", $settings['system_version']);
  $system_install_version = explode(".", $install_version);

  $count_updates = (int)$system_install_version[1] - (int)$system_version[1];

  for ($n = 1; $n <= $count_updates; $n++) {
    $array_versions[] = "4.".(intval($system_version[1]) + $n);
  }
  
  if($array_versions){
    foreach ($array_versions as $version) {

      $getUpdate = findOne("uni_updates", "version=?", [$version]);
     
      if(!$getUpdate){
         smart_insert("uni_updates",[
           "version"=>$version,
         ]);
      } 

      $get = file_get_contents_curl('https://api.unisitecloud.ru/updates/get_updates.php?lnc_key='.$settings['lnc_key'].'&install_version='.$version);

        if($get){

           $distFilename = md5($version.'_'.time());

           if(file_put_contents(__dir__."/updates/{$distFilename}.zip", $get)){

              $Update->addLog("Скачивание архива","success",$version);

              if(mkdir(__dir__."/updates/".$distFilename, 0777)){

                if ($zip->open(__dir__."/updates/{$distFilename}.zip") === TRUE) {

                    $zip->extractTo(__dir__."/updates/".$distFilename);
                    $zip->close();

                    unlink(__dir__."/updates/{$distFilename}.zip");

                    $Update->addLog("Распаковка архива","success",$version);

                    include(__dir__."/updates/{$distFilename}/install.php");

                }else{
                    $Update->addLog("Ошибка распаковки дистрибутива","error",$version);
                }

              }else{
                $Update->addLog("Недостаточно прав на запись в папку include/modules/settings/handlers/updates","error",$version);
                exit;
              }

           }else{
             $Update->addLog("Недостаточно прав на запись в папку include/modules/settings/handlers/updates","error",$version);
           }

        }else{
           $Update->addLog("Ошибка получения данных","error",$version);
        }

    }
  }

  echo json_encode(["status"=>true]);

}  
?>