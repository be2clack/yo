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

 $id = (int)$_POST["id"];

 $find = findOne("uni_admin","id=?", array($id));

 if($find){
 
 if(isset($_POST["privileges"])){
   $privileges = implode(",",$_POST["privileges"]);
 }else{ $privileges = ""; } 
    
 $error = array();
 
   if(!$_POST["name"]){$error[] = "Пожалуйста, укажите имя пользователя";}
   if(!$_POST["role"]){$error[] = "Пожалуйста, укажите роль пользователя";}

   if(validateEmail($_POST["email"]) == false){
        $error[] = "Пожалуйста, укажите корректный E-mail!";
   }else{
        if( findOne("uni_admin", "email=? and id!=?", [ $_POST["email"], $id ]) ){
            $error[] = "Пользователь с указанным email уже существует";
        }
   }

   if(!empty($_POST["pass"])){   
     if (mb_strlen($_POST["pass"], "UTF-8") < 6 || mb_strlen($_POST["pass"], "UTF-8") > 50){$error[] = "Укажите пароль от 6-ти до 50 символов";}else{
      $password =  password_hash($_POST["pass"].$config["private_hash"], PASSWORD_DEFAULT);
      $find->pass = $password;
     }     
   }

   if(count($error) == 0){
      $image = $Main->uploadedImage( ["files"=>$_FILES["image"], "path"=>$config["media"]["avatar_admin"], "prefix_name"=>"admin"] );
      if($image["error"]){
          $error = array_merge($error,$image["error"]);
      }    
   }

   if($image["name"]) $find->image = $image["name"];

    if (count($error) == 0) {

        update("update uni_admin set fio=?, email=?, phone=?, role=?, privileges=?, pass=?, image=? where id=?", [clear($_POST["name"]), clear($_POST["email"]),clear($_POST["phone"]),intval($_POST["role"]),$privileges,$find->pass,$find->image,$id]);

        if( $_SESSION['cp_auth'][ $config["private_hash"] ]["id"] == $id ){
            $_SESSION['cp_auth'][ $config["private_hash"] ] = getOne("select fio,image,role,id from uni_admin where id=?", array($id));
        }

        $_SESSION["CheckMessage"]["success"] = "Данные успешно сохранены!";
        echo true;
                              
        } else {
                  $_SESSION["CheckMessage"]["error"] = implode("\n", $error);        
               }

   }

}
?>