<?php
$error = [];

$shop_title = clear($_POST["shop_title"]);
$shop_desc = clear($_POST["shop_desc"]);
$shop_id = translite($_POST["shop_id"]);
$shop_theme_category = (int)$_POST["shop_theme_category"];
$sliders = $_POST['slider'] ? array_slice($_POST['slider'], 0, $settings["user_shop_count_sliders"]) : [];

$getShop = $Shop->getShop(['user_id'=>$_SESSION["profile"]["id"],'conditions'=>false]);

if(!$shop_title) $error["shop_title"] = $ULang->t("Пожалуйста, укажите название магазина.");

if($shop_id && $_SESSION['profile']['tariff']['services']['unique_shop_address']){

 $getShopId = findOne("uni_clients_shops", "clients_shops_id_hash=? and clients_shops_id_user!=?", [$shop_id,$_SESSION["profile"]["id"]]);

 if($getShopId) $error["shop_id"] = $ULang->t("Идентификатор") . " {$shop_id} " . $ULang->t("уже используется на сайте."); 

}else{
 $shop_id = md5($_SESSION["profile"]["id"]);
}

if(!$_POST["image_status"]){

   if(!$_FILES['logo']['tmp_name']){

        $getShop["clients_shops_logo"] = "";
        @unlink( $config["basePath"] . "/" . $config["media"]["other"] . "/" .  $getShop["clients_shops_logo"] );

   }else{

        if(count($error) == 0){
            $image = $Main->uploadedImage( ["files"=>$_FILES["logo"], "path"=>$config["media"]["other"], "prefix_name"=>"shop_logo"] );
            if($image["error"]){
                $error["image"] = $image["error"][0];
            }else{
                if($image["name"]) $getShop["clients_shops_logo"] = $image["name"];
            }    
        }

   }
}

if( count($error) == 0 ){

  if(count($sliders)){

     $getSliders = getAll('select * from uni_clients_shops_slider where clients_shops_slider_id_shop=?',[$getShop["clients_shops_id"]]);
     if(count($getSliders)){
        foreach ($getSliders as $value) {
            if(!in_array($value["clients_shops_slider_image"], $sliders)){
                @unlink($config["basePath"] . "/" . $config["media"]["user_attach"] . "/" . $value["clients_shops_slider_image"]);
                update("delete from uni_clients_shops_slider where clients_shops_slider_id=?", [$value['clients_shops_slider_id']]);
            }else{
                unset($sliders[$value["clients_shops_slider_image"]]);
            }
        }
     }

     if(count($sliders)){
         foreach ($sliders as $value) {
             if(file_exists($config["basePath"]."/".$config["media"]["temp_images"]."/".$value)){

                if(copy($config["basePath"]."/".$config["media"]["temp_images"]."/".$value, $config["basePath"]."/".$config["media"]["user_attach"]."/".$value)){
                    insert("INSERT INTO uni_clients_shops_slider(clients_shops_slider_id_shop,clients_shops_slider_image,clients_shops_slider_id_user)VALUES(?,?,?)", [$getShop["clients_shops_id"],$value,$_SESSION["profile"]["id"]]);
                }

             }
         }
     }  

  }else{
     $getSliders = getAll('select * from uni_clients_shops_slider where clients_shops_slider_id_shop=?',[$getShop["clients_shops_id"]]);
     if(count($getSliders)){
        foreach ($getSliders as $value) {
            @unlink($config["basePath"] . "/" . $config["media"]["user_attach"] . "/" . $value["clients_shops_slider_image"]);
            update("delete from uni_clients_shops_slider where clients_shops_slider_id=?", [$value['clients_shops_slider_id']]);
        }
     }
  }
  
  update("update uni_clients_shops set clients_shops_id_hash=?,clients_shops_title=?,clients_shops_desc=?,clients_shops_logo=?,clients_shops_id_theme_category=? where clients_shops_id_user=?", [$shop_id,$shop_title,$shop_desc,$getShop["clients_shops_logo"],$shop_theme_category,$_SESSION["profile"]["id"]]);

  $Admin->notifications("shops_edit", ["shop_name"=>$shop_title, "shop_link"=>$Shop->linkShop($shop_id)]);

  echo json_encode( [ "status" => true, "redirect" => $Shop->linkShop($shop_id) ] );
}else{
  echo json_encode( [ "status" => false, "answer" => $error ] );
}
?>