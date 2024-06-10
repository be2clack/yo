<?php

if( intval($_POST["watermark_caption_opacity"]) < 0 || intval($_POST["watermark_caption_opacity"]) > 100 ){
   $_POST["watermark_caption_opacity"] = 100;
}

update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["watermark_status"]),'watermark_status'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["watermark_type"]),'watermark_type'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["watermark_pos"]),'watermark_pos'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["watermark_caption"]),'watermark_caption'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["watermark_caption_font"]),'watermark_caption_font'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["watermark_caption_size"]),'watermark_caption_size'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["watermark_caption_opacity"]),'watermark_caption_opacity'));

if(!empty($_FILES['watermark_img']['name'])){

  $path = $config["basePath"] . "/" . $config["media"]["other"] . "/";
  $max_file_size = 1;
  $extensions = array('png');
  $ext = strtolower(pathinfo($_FILES['watermark_img']['name'], PATHINFO_EXTENSION));
  
  if($_FILES["watermark_img"]["size"] > $max_file_size*1024*1024){
      $error[] = "Watermark не загружен. Максимальный размер файла ".$max_file_size.' mb!';
  }else{

    if (in_array($ext, $extensions))
    {
          
          $image_name = md5("watermark") . "." . $ext;
          $path = $path . $image_name;

          if (!move_uploaded_file($_FILES['watermark_img']['tmp_name'], $path))
          {                    
              $error[] = "Watermark не загружен. Недостаточно прав на запись в директорию " . $config["media"]["other"];                                   
          }else{
              update("UPDATE uni_settings SET value=? WHERE name = ?", array($image_name,'watermark_img'));
          }
          
    }else{
          $error[] = "Watermark не загружен. Допустимые форматы ".implode(",",$extensions); 
    }

  }
                   
}

?>