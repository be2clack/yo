<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_board']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

  if( $settings["demo_view"] || $settings["demo_installment"] ){

    $_SESSION["CheckMessage"]["error"] = "При рассрочке и тест драйве модуль импорта недоступен!";
    echo json_encode( ["status"=>false] );
    exit;

  }

  $max_file_size = 200;

  if(!empty($_FILES['file']['name'])){

      $path = "../temp/";
      $extensions = array('csv');
      $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
      
      if($_FILES["file"]["size"] > $max_file_size*1024*1024){

          $_SESSION["CheckMessage"]["error"] = 'Размер файла не должен превышать '.$max_file_size.' mb!';

          echo json_encode( ["status"=>false] );
      }else{

        if (in_array($ext, $extensions))
        {
              
              $path = $path . $_FILES['file']['name'];
              $uniq = md5( time() . mt_rand(1000000,9000000) );

              if (move_uploaded_file($_FILES['file']['tmp_name'], $path))
              {                    
                 
                 csvToUtf8($path);

                 $file = @file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                 $count = (int)count($file)-1;

                  $insert = insert("INSERT INTO uni_ads_import(ads_import_uniq,ads_import_file,ads_import_count,ads_import_date)VALUES(?,?,?,?)", array( $uniq,$_FILES['file']['name'],$count,date("Y-m-d H:i:s") ));
                          
                  echo json_encode( ["status"=>true, "id"=>$insert] );     

              }else{
                  $_SESSION["CheckMessage"]["error"] = "Не удалось сохранить файл. Недостаточно прав на запись в директорию " . $config["folder_admin"] . "/include/ads_import/temp";
                  echo json_encode( ["status"=>false] );
              }
              
     
        }else{
              $_SESSION["CheckMessage"]["error"] = "Допустимые расширения (csv)";
              echo json_encode( ["status"=>false] );
        }

      }
                       
  }else{

  	  $_SESSION["CheckMessage"]["error"] = "Выберите файл для импорта";
  	  echo json_encode( ["status"=>false] );

  }


}  
?>