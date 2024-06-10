<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_chat']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(count($_FILES) > 0){

  $count_images_add = 10;
  $max_file_size = 10;

  foreach (array_slice($_FILES, 0, $count_images_add) as $key => $value) {

      $path = $config["basePath"] . "/" . $config["media"]["temp_images"];

      $extensions = array('jpeg', 'jpg', 'png');
      $ext = strtolower(pathinfo($value["name"], PATHINFO_EXTENSION));
      
      if($value['size'] > $max_file_size*1024*1024){

        echo false;

      }else{

        if (in_array($ext, $extensions))
        {
              
              $uid = md5(time().uniqid());
              $name = "attach_" . $uid . ".jpg";
              
              if (move_uploaded_file($value["tmp_name"], $path."/".$name))
              {
                
                 rotateImage( $path . "/" . $name );
                 resize($path . "/" . $name, $path . "/" . $name, 1024, 0);
                
                 ?>

                   <div class="id<?php echo $uid; ?> admin-chat-users-dialog-footer-attach-files-preview" ><img class="image-autofocus" src="<?php echo $config["urlPath"] . "/" . $config["media"]["temp_images"] . "/" . $name; ?>" /><input type="hidden" name="attach[<?php echo $uid; ?>]" value="<?php echo $name; ?>" /> <span class="admin-chat-users-dialog-footer-attach-delete" ><i class="la la-trash"></i></span> </div>

                 <?php

              }
              
        }else{

           echo false;

        }

      }

  }

}

?>
 

