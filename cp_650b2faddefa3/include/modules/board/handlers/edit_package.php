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

$Main = new Main();

$getCategories = $CategoryBoard->getCategories();

$errors = [];

$package_id = (int)$_POST['id'];

if(empty($_POST['cat_id'])){ $errors[] = "Пожалуйста, выберите категорию"; }    
if(empty($_POST['period'])){ $errors[] = "Пожалуйста, укажите срок пакета"; } 
if(empty($_POST['count_ad'])){ $errors[] = "Пожалуйста, укажите количество объявлений"; } 
if(empty($_POST['price_ad'])){ $errors[] = "Пожалуйста, укажите стоимость размещения"; }

if (!$errors) {
    
    update("update uni_ads_packages set count_ad=?,price_ad=?,period=? where id=?", array(intval($_POST['count_ad']),round($_POST['price_ad'],2),intval($_POST['period']),$package_id));   

    update("DELETE FROM uni_ads_packages_categories WHERE package_id=?", [$package_id]);

    foreach ($_POST['cat_id'] as $key => $id) {
        $idsBuild = idsBuildJoin($CategoryBoard->idsBuild($id, $getCategories));
        if($idsBuild){
            foreach (explode(',', $idsBuild) as $key => $subid) {
                $getId = findOne("uni_ads_packages_categories", "package_id=? and cat_id=?", [$package_id,$subid]);
                if(!$getId) insert("INSERT INTO uni_ads_packages_categories(package_id,cat_id)VALUES(?,?)", array($package_id,$subid)); 
            }
        }else{
            $getId = findOne("uni_ads_packages_categories", "package_id=? and cat_id=?", [$package_id,$id]);
            if(!$getId) insert("INSERT INTO uni_ads_packages_categories(package_id,cat_id)VALUES(?,?)", array($package_id,$id)); 
        }
    }

    echo json_encode(array("status" => true));

    $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!"; 

} else {
    echo json_encode(array("status" => false ));
    $_SESSION["CheckMessage"]["error"] = implode("<br/>",$errors);
}

}
?>