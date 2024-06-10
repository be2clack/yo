<?php

update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["balance_payment_requisites"]),'balance_payment_requisites'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(encrypt(json_encode($_POST["requisites"])),'requisites'));

if($_POST['requisites_image_signature_delete']){
  if(file_exists($config["basePath"] . "/" . $config["media"]["other"] . "/" . $settings['requisites_image_signature'])) unlink($config["basePath"] . "/" . $config["media"]["other"] . "/" . $settings['requisites_image_signature']);
  update("UPDATE uni_settings SET value=? WHERE name = ?", array('','requisites_image_signature'));
}

if($_POST['requisites_image_print_delete']){
  if(file_exists($config["basePath"] . "/" . $config["media"]["other"] . "/" . $settings['requisites_image_print'])) unlink($config["basePath"] . "/" . $config["media"]["other"] . "/" . $settings['requisites_image_print']);
  update("UPDATE uni_settings SET value=? WHERE name = ?", array('','requisites_image_print'));
}

if(!empty($_FILES['requisites_image_signature']['name'])){

  $path = $config["basePath"] . "/" . $config["media"]["other"] . "/";
  $max_file_size = 1;
  $extensions = array('png');
  $ext = strtolower(pathinfo($_FILES['requisites_image_signature']['name'], PATHINFO_EXTENSION));
  
  if($_FILES["requisites_image_signature"]["size"] > $max_file_size*1024*1024){
      $error[] = "Подпись не загружена. Максимальный размер файла ".$max_file_size.' mb!';
  }{
          
    if (in_array($ext, $extensions))
    {
          
          unlink($path.'/'.$settings['requisites_image_signature']);

          $image_name = "requisites_image_signature.".$ext;
          $path = $path . $image_name;

          if (!move_uploaded_file($_FILES['requisites_image_signature']['tmp_name'], $path))
          {      
              $error[] = "Подпись не загружена. Недостаточно прав на запись в директорию " . $config["media"]["other"];                                   
          }else{
              update("UPDATE uni_settings SET value=? WHERE name = ?", array($image_name,'requisites_image_signature'));
          }
          
    }else{
          $error[] = "Подпись не загружена. Допустимые форматы ".implode(",",$extensions); 
    }

  }       
  
}

if(!empty($_FILES['requisites_image_print']['name'])){

  $path = $config["basePath"] . "/" . $config["media"]["other"] . "/";
  $max_file_size = 1;
  $extensions = array('png');
  $ext = strtolower(pathinfo($_FILES['requisites_image_print']['name'], PATHINFO_EXTENSION));
  
  if($_FILES["requisites_image_print"]["size"] > $max_file_size*1024*1024){
      $error[] = "Печать не загружена. Максимальный размер файла ".$max_file_size.' mb!';
  }{
          
    if (in_array($ext, $extensions))
    {
          
          unlink($path.'/'.$settings['requisites_image_print']);

          $image_name = "requisites_image_print.".$ext;
          $path = $path . $image_name;

          if (!move_uploaded_file($_FILES['requisites_image_print']['tmp_name'], $path))
          {      
              $error[] = "Печать не загружена. Недостаточно прав на запись в директорию " . $config["media"]["other"];                                   
          }else{
              update("UPDATE uni_settings SET value=? WHERE name = ?", array($image_name,'requisites_image_print'));
          }
          

    }else{
          $error[] = "Печать не загружена. Допустимые форматы ".implode(",",$extensions); 
    }

  }       
  
}

?>