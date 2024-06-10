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

  $install_version = clear($_POST["version"]);

  $getUpdate = findOne("uni_updates", "version=?", [$install_version]);

  if($getUpdate["status"] == 1){
     exit(json_encode(["status"=>false, "answer"=>"Патч успешно установлен"]));
  }

  if ( !is_dir(__dir__."/patches") ) {
      if(!mkdir(__dir__."/patches", 0777) ){
        $Update->addLog("Недостаточно прав на запись в папку include/modules/settings/handlers","error",$install_version);
        exit;        
      }      
  }

  $get = file_get_contents_curl('https://api.unisitecloud.ru/updates/get_patches.php?lnc_key='.$settings['lnc_key'].'&install_version='.$install_version);

    if($get){

       $distFilename = md5($install_version.'_'.time());

       if(file_put_contents(__dir__."/patches/{$distFilename}.zip", $get)){

          $getUpdate = findOne("uni_updates", "version=?", [$install_version]);
         
          if(!$getUpdate){
             smart_insert("uni_updates",[
               "version"=>$install_version,
               "patch"=>1,
             ]);
          }   

          $Update->addLog("Скачивание архива","success",$install_version);

          if(mkdir(__dir__."/patches/".$distFilename, 0777)){

            if ($zip->open(__dir__."/patches/{$distFilename}.zip") === TRUE) {

                $zip->extractTo(__dir__."/patches/".$distFilename);
                $zip->close();

                unlink(__dir__."/patches/{$distFilename}.zip");

                $Update->addLog("Распаковка архива","success",$install_version);

                include(__dir__."/patches/{$distFilename}/install.php");

            }else{
                $Update->addLog("Ошибка распаковки дистрибутива","error",$install_version);
            }

          }else{
            $Update->addLog("Недостаточно прав на запись в папку include/modules/settings/handlers/patches","error",$install_version);
            exit;
          }

       }else{
         $Update->addLog("Недостаточно прав на запись в папку include/modules/settings/handlers/patches","error",$install_version);
       }

    }else{
       $Update->addLog("Ошибка получения данных","error",$install_version);
    }

  echo json_encode(["status"=>true]);

}  
?>