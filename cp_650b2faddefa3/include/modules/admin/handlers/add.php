<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_admin']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){
 
   $Main = new Main();

   $error = array();

   if(isset($_POST["privileges"])){
     $privileges = implode(",",$_POST["privileges"]);
   }else{ $privileges = ""; }   
 
   if(!$_POST["name"]){$error[] = "Пожалуйста, укажите имя пользователя";}
   if(!$_POST["role"]){$error[] = "Пожалуйста, укажите роль пользователя";}

   if(validateEmail($_POST["email"]) == false){
        $error[] = "Пожалуйста, укажите корректный E-mail!";
   }else{
        if( findOne("uni_admin", "email=?", [$_POST["email"]]) ){
            $error[] = "Пользователь с указанным email уже существует";
        }
   } 

   if (mb_strlen($_POST["pass"], "UTF-8") < 6 || mb_strlen($_POST["pass"], "UTF-8") > 50){$error[] = "Укажите пароль от 6-ти до 50 символов";}
   
   if(count($error) == 0){
      $image = $Main->uploadedImage( ["files"=>$_FILES["image"], "path"=>$config["media"]["avatar_admin"], "prefix_name"=>"admin"] );
      if($image["error"]){
          $error = array_merge($error,$image["error"]);
      }    
   }

  if (count($error) == 0) {

        $password =  password_hash($_POST["pass"].$config["private_hash"], PASSWORD_DEFAULT);

        insert("INSERT INTO uni_admin(fio,pass,email,phone,role,image,privileges)VALUES(?,?,?,?,?,?,?)", array($_POST["name"],$password,$_POST["email"],$_POST["phone"],intval($_POST["role"]),$image["name"],$privileges));        
          
        $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
        echo true;             
      
      } else {
                 $_SESSION["CheckMessage"]["error"] = implode("<br/>", $error);        
             }

}
?>