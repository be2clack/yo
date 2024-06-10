<?php
require_once("{$config["basePath"]}/systems/vendor/autoload.php");
include_once("{$config["basePath"]}/systems/libs/query.php");
include_once("{$config["basePath"]}/systems/libs/fn.php");
include_once("{$config["basePath"]}/systems/libs/mail.php");
include_once("{$config["basePath"]}/systems/libs/resize.php");
include_once("{$config["basePath"]}/systems/libs/Watermark.php");
include_once("{$config["basePath"]}/systems/libs/Mobile_Detect.php");
include_once("{$config["basePath"]}/systems/libs/rest.inc.php");
include_once("{$config["basePath"]}/systems/libs/Slugify.php");

$classList = [];
$classes = glob($config["basePath"] . '/systems/classes/*.php');

if(count($classes)){
    foreach ($classes as $file){
        require_once "$file";
        $pathinfo = pathinfo($file);
        if(class_exists($pathinfo["filename"])) {
            ${$pathinfo["filename"]} = new $pathinfo["filename"]();
        }
    }
}

$ErrorHandler->register();

$settings = $Main->settings();

$Main->setTimeZone();

getRealIp();

?>