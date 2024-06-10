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

   $id = (int)$_POST["id"];

   $getTariffActive = getOne('select count(*) as total from uni_services_tariffs_orders where services_tariffs_orders_id_tariff=? and services_tariffs_orders_date_completion > now()', [$id]);

   if(!intval($getTariffActive['total'])){
      update("DELETE FROM uni_services_tariffs WHERE services_tariffs_id=?", [$id]);   

      $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
      echo true;
   }else{
      $_SESSION["CheckMessage"]["warning"] = "Нельзя удалить тариф пока его используют пользователи!";
   }


}              
?>