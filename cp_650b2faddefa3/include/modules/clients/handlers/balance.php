<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_clients']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

$Main = new Main();
$Profile = new Profile();

$id = (int)$_POST["id"];
$title = clear($_POST["title"]);
$action = $_POST["action"];
$summa = $_POST['summa'] ? str_replace([','],[''],$_POST['summa']) : 0;

if($summa > 1000000){
   $summa = 1000000;
}

$error = array();

if(empty($action)){$error[] = 'Выберите действие';}
if(empty($summa)){$error[] = 'Укажите сумму';}
if(empty($title)){$error[] = 'Укажите назначение';}

if (!$error) {

    $getUser = findOne("uni_clients","clients_id=?", array($id));

    if($getUser){

        if($action == '+'){

            $Profile->actionBalance(array("id_user"=>$id,"summa"=>$summa,"title"=>$title,"id_order"=>generateOrderId(),"email" => $getUser->clients_email,"name" => $getUser->clients_name, "note" => $_POST['note']),"+");
            $_SESSION["CheckMessage"]["success"] = "Баланс успешно пополнен!";
            echo true;

        }elseif($action == '-'){

            if($getUser['clients_balance'] >= $summa){
                $Profile->actionBalance(array("id_user"=>$id,"summa"=>$summa,"title"=>$title,"id_order"=>generateOrderId(),"email" => $getUser->clients_email,"name" => $getUser->clients_name, "note" => $_POST['note']),"-");
                $_SESSION["CheckMessage"]["success"] = "Списание успешно выполнено!";
                echo true;
            }else{
                $_SESSION["CheckMessage"]["warning"] = 'Недостаточно средств на балансе';
            }

        } 

    }

} else {
    $_SESSION["CheckMessage"]["warning"] = implode("<br/>",$error);
}

}
?>