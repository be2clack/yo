<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_manager']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

if( intval($_POST["dir"]) == 1 ){
   $path = $config["basePath"] . "/";
}else{
   $path = $config["basePath"] . "/" . $config["media"]["manager"];
}

$error = array();

  if(!empty($_FILES['manager']['name'])){
      
      $max_file_size = (int)$settings["manager_file_size"];
      if($settings["manager_extension"]){ $extensions = explode(",",$settings["manager_extension"]);}else{$extensions=array();}
      
      $ext = strtolower(pathinfo($_FILES['manager']['name'], PATHINFO_EXTENSION));
      
      if($_FILES["manager"]["size"] > $max_file_size*1024*1024){
          $error[] = 'Размер не должен превышать '.$max_file_size.' mb!';
      }
              
      if (in_array($ext, $extensions))
      {   

            if( intval($_POST["rename"]) == 1 ){
               $filename = md5( time() . $_FILES['manager']['name'] ) . "." . $ext; 
            }else{
               $filename = $_FILES['manager']['name'];
            }

            $path = $path . "/" . $filename;         
            if (!move_uploaded_file($_FILES['manager']['tmp_name'], $path))
            {

              $error[] = "Файл не загружен. Недостаточно прав на запись в директорию!";

            }  

      }else{

            $error[] = "Допустимые расширения (".$settings["manager_extension"].")"; 

      }
      
      if(count($error)==0){

       insert("INSERT INTO uni_filemanager(filemanager_name,filemanager_dir)VALUES(?,?)", array( $filename,intval($_POST["dir"]) ));

       echo true;
       $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!"; 

      }else{ $_SESSION["CheckMessage"]["warning"] = implode("\n",$error); }            
  }else{
      $_SESSION["CheckMessage"]["error"] = "Выберите файл для загрузки!";
  }        

}
?>