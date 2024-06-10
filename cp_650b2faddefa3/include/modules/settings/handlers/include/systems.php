<?php

if(!intval($_POST["catalog_out_content"])){
  $_POST["catalog_out_content"] = 60;
}

if(!intval($_POST["blog_out_content"])){
  $_POST["blog_out_content"] = 20;
}

if(!intval($_POST["index_out_content"])){
  $_POST["index_out_content"] = 32;
}

if(!intval($_POST["index_out_count_shops"])){
  $_POST["index_out_count_shops"] = 3;
}

if(!$_POST["board_price_ad_publication"]){
  $_POST["board_price_ad_publication"] = 100;
}

update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["main_type_products"]),'main_type_products'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(round($_POST["min_deposit_balance"],2),'min_deposit_balance'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(round($_POST["max_deposit_balance"],2),'max_deposit_balance'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["secure_status"]),'secure_status'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["visible_lang_site"]),'visible_lang_site'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["auto_lang_detection"]),'auto_lang_detection'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["logo_color_inversion"]),'logo_color_inversion'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["abbreviation_million"]),'abbreviation_million'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["banner_markup"]),'banner_markup'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["display_count_ads_categories"]),'display_count_ads_categories'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["type_content_loading"]),'type_content_loading'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["ads_sorting_variant"]),'ads_sorting_variant'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["main_timezone"]),'main_timezone'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["lang_site_default"]),'lang_site_default'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["catalog_city_distance"]),'catalog_city_distance'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["catalog_out_content"]),'catalog_out_content'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["shops_out_content"]),'shops_out_content'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["blog_out_content"]),'blog_out_content'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["index_out_content"]),'index_out_content'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["index_out_count_shops"]),'index_out_count_shops'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["booking_status"]),'booking_status'));

if($_POST["board_type_ad_publication"] == "free"){

  if($settings["board_type_ad_publication"] == "paid"){
     if($settings["board_price_ad_publication"]){
        update("update uni_category_board set category_board_status_paid=?, category_board_price=? where category_board_price=?", [0,0,$settings["board_price_ad_publication"]]);
     }else{
        update("update uni_category_board set category_board_status_paid=?, category_board_price=? where category_board_status_paid=?", [0,0,1]);
     }
  }

}elseif($_POST["board_type_ad_publication"] == "paid"){

  if($settings["board_type_ad_publication"] == "free"){
     update("update uni_category_board set category_board_status_paid=?, category_board_price=? where category_board_price=?", [1,round($_POST["board_price_ad_publication"], 2),0]);
  }elseif($settings["board_price_ad_publication"] != round($_POST["board_price_ad_publication"], 2)){
     update("update uni_category_board set category_board_status_paid=?, category_board_price=? where category_board_price=?", [1,round($_POST["board_price_ad_publication"], 2),$settings["board_price_ad_publication"]]);
  }

}

update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["board_type_ad_publication"]),'board_type_ad_publication'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(round($_POST["board_price_ad_publication"], 2),'board_price_ad_publication'));

if(!empty($_FILES['logo']['name'])){

      $path = $config["basePath"] . "/" . $config["media"]["other"] . "/";
      $max_file_size = 1;
      $extensions = array('png','jpg','jpeg', 'gif', 'svg');
      $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
      
      if($_FILES["logo"]["size"] > $max_file_size*1024*1024){
          $error[] = "Основной логотип не загружен. Максимальный размер файла ".$max_file_size.' mb!';
      }{
              
        if (in_array($ext, $extensions))
        {
              
              unlink($path.'/'.$settings['logo-image']);

              $image_name = 'logo_'.uniqid().".".$ext;
              $path = $path . $image_name;

              if (!move_uploaded_file($_FILES['logo']['tmp_name'], $path))
              {      
                  $error[] = "Основной логотип не загружен. Недостаточно прав на запись в директорию " . $config["media"]["other"];                                   
              }else{
                  update("UPDATE uni_settings SET value=? WHERE name = ?", array($image_name,'logo-image'));
              }
              
        }else{
              $error[] = "Основной логотип не загружен. Допустимые форматы ".implode(",",$extensions); 
        }

      }       
      
  }

  if(!empty($_FILES['logo-mobile']['name'])){

      $path = $config["basePath"] . "/" . $config["media"]["other"] . "/";
      $max_file_size = 1;
      $extensions = array('png','jpg','jpeg', 'gif', 'svg');
      $ext = strtolower(pathinfo($_FILES['logo-mobile']['name'], PATHINFO_EXTENSION));
      
      if($_FILES["logo-mobile"]["size"] > $max_file_size*1024*1024){
          $error[] = "Логотип для мобильной версии не загружен. Максимальный размер файла ".$max_file_size.' mb!';
      }{
              
        if (in_array($ext, $extensions))
        {
              
              unlink($path.'/'.$settings['logo-image-mobile']);

              $image_name = "logo_mobile_".uniqid().".".$ext;
              $path = $path . $image_name;

              if (!move_uploaded_file($_FILES['logo-mobile']['tmp_name'], $path))
              {      
                  $error[] = "Логотип для мобильной версии не загружен. Недостаточно прав на запись в директорию " . $config["media"]["other"];                                   
              }else{
                  update("UPDATE uni_settings SET value=? WHERE name = ?", array($image_name,'logo-image-mobile'));
              }
              
        }else{
              $error[] = "Логотип для мобильной версии не загружен. Допустимые форматы ".implode(",",$extensions); 
        }

      }       
      
  }

  if(!empty($_FILES['favicon']['name'])){

      $path = $config["basePath"] . "/";
      $max_file_size = 1;
      $extensions = array('png');
      $ext = strtolower(pathinfo($_FILES['favicon']['name'], PATHINFO_EXTENSION));
      
      if($_FILES["favicon"]["size"] > $max_file_size*1024*1024){
          $error[] = "Favicon не загружен. Максимальный размер файла ".$max_file_size.' mb!';
      }{
              
        if (in_array($ext, $extensions))
        {
              
              $image_name = 'favicon-120x120.'.$ext;
              $path = $path . $image_name;

              if (!move_uploaded_file($_FILES['favicon']['tmp_name'], $path))
              {                    
                  $error[] = "Favicon не загружен. Недостаточно прав на запись.";                                   
              }else{
                  resize($path, $config["basePath"] . "/favicon.ico" , 32, 32, 100);
                  resize($path, $config["basePath"] . "/favicon-120x120.".$ext , 120, 120, 100);
                  resize($path, $config["basePath"] . "/favicon-96x96.".$ext , 96, 96, 100);
                  resize($path, $config["basePath"] . "/favicon-32x32.".$ext , 32, 32, 100);
                  resize($path, $config["basePath"] . "/favicon-16x16.".$ext , 16, 16, 100);

                  update("UPDATE uni_settings SET value=? WHERE name = ?", array($image_name,'favicon-image'));
              }
              
        }else{
              $error[] = "Favicon не загружен. Допустимые форматы ".implode(",",$extensions); 
        }

      }       
      
  }

  $Cache->update( "uni_category_board" );

?>