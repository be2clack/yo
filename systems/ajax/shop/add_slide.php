<?php

$getShop = $Shop->getShop(['user_id'=>$_SESSION["profile"]["id"],'conditions'=>false]);

if(!$getShop) exit;

$countSliders = (int)getOne("select count(*) as total from uni_clients_shops_slider where clients_shops_slider_id_user=?", [ $_SESSION["profile"]["id"] ])["total"];

if( $countSliders >= $settings["user_shop_count_sliders"] ){
  echo json_encode( ["status" => false, "answer" => $ULang->t("Максимальное количество слайдов") . ' ' . $settings["user_shop_count_sliders"] . " " . $ULang->t("шт.") ] );
  exit;
}

$image = $Main->uploadedImage(["files"=>$_FILES["image"], "path"=>$config["media"]["temp_images"], "prefix_name"=>"slide"], 10);
if($image["error"]){
  echo json_encode(["status" => false, "answer" => implode("\n", $image["error"])]);
}else{

  resize( $config["basePath"] . "/" . $config["media"]["temp_images"] . "/" . $image["name"] , $config["basePath"] . "/" . $config["media"]["temp_images"] . "/" . $image["name"], 1920, 0);

  echo json_encode( ["status" => true, "img" => '<div class="shop-container-sliders-img" style="background-image: url('.$config["urlPath"] . "/" . $config["media"]["temp_images"] . "/" . $image["name"].'); background-position: center center; background-size: cover;" > <span class="shop-container-sliders-delete" ><i class="las la-times"></i></span> <input type="hidden" name="slider['.$image["name"].']" value="'.$image["name"].'" /> </div>' ] );
}

?>