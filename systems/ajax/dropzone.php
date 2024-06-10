<?php

session_start();
define('unisitecms', true);

$config = require "./../../config.php";
include_once( $config["basePath"] . "/systems/unisite.php" );
$static_msg = require $config["basePath"] . "/static/msg.php";

verify_csrf_token();

$Watermark = new Watermark();

if(isAjax() == true){

    if(!$settings["count_images_add_ad"]) $count_images_add_ad = 8; else $count_images_add_ad = $settings["count_images_add_ad"];
    if(!$settings["size_images_add_ad"]) $size_images_add_ad = 10; else $size_images_add_ad = $settings["size_images_add_ad"];

    if($_FILES['file']['tmp_name']){
      
          $extensions = array('jpg', 'png', 'jpeg');
          $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
          $path = $config["basePath"] . "/" . $config["media"]["temp_images"];

          if (in_array($ext, $extensions)){
            if($_FILES["file"]["size"] <= $size_images_add_ad*1024*1024){
            
              $uid = uniqid();
            
              if ( move_uploaded_file( $_FILES['file']['tmp_name'], $path . "/" . $uid . "." . $ext ) )
              {
                
                rotateImage( $path . "/" . $uid . "." . $ext );

                $Watermark->create( $path . "/" . $uid . "." . $ext, $path . "/" . $uid . "." . $ext );

                resize($path . "/" . $uid . "." . $ext, $path . "/big_" . $uid . "." . $ext, $settings["ads_images_big_width"], $settings["ads_images_big_height"], 90, $settings["ad_format_photo"]);
                resize($path . "/" . $uid . "." . $ext, $path . "/small_" . $uid . "." . $ext, $settings["ads_images_small_width"], $settings["ads_images_small_height"], 100, $settings["ad_format_photo"]);
                
                $name = $uid . "." . $settings["ad_format_photo"];

                unlink( $path . "/" . $uid . "." . $ext );

                $input = '<input type="hidden" name="gallery['.$uid.']" value="'.$name.'" style="display: none;" />';
                $link = $config["urlPath"] . "/" . $config["media"]["temp_images"] . "/small_" . $name;

                echo json_encode( [ "input" => $input, "link" => $link ] );

              }


            }else{
               echo false;
            }

          }else{
              echo false;
          }


    }


}

?>