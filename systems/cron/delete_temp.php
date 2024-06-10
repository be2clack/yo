<?php
defined('unisitecms') or exit();

$dir = $config["basePath"] . "/" . $config["media"]["temp_images"] . "/";

$files = scandir($dir);

unset($files[0]);
unset($files[1]);

if(!$files) exit;

foreach ($files as $fileName) {
    
    if( $fileName != ".htaccess" ){
	    $unix_time = filemtime( $dir . "/" . $fileName ) + 3600;
	    if( $unix_time < time() ){
	        unlink($dir . "/" . $fileName);
	    }
    }

}

$dir = $config["basePath"] . "/" . $config["media"]["temp_video"] . "/";

$files = scandir($dir);

unset($files[0]);
unset($files[1]);

if(!$files) exit;

foreach ($files as $fileName) {
    
    if( $fileName != ".htaccess" ){
	    $unix_time = filemtime( $dir . "/" . $fileName ) + 3600;
	    if( $unix_time < time() ){
	        unlink($dir . "/" . $fileName);
	    }
    }

}

?>