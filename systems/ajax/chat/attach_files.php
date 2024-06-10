<?php

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

                     <div class="id<?php echo $uid; ?> attach-files-preview" ><img class="image-autofocus" src="<?php echo $config["urlPath"] . "/" . $config["media"]["temp_images"] . "/" . $name; ?>" /><input type="hidden" name="attach[<?php echo $uid; ?>]" value="<?php echo $name; ?>" /> <span class="chat-dialog-attach-delete" ><i class="las la-trash-alt"></i></span> </div>

                   <?php

                }
                
          }else{

             echo false;

          }

        }

    }

  }

?>