<?php

$id = (int)$_POST["id"];
$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$errors = [];
$slidersShop = [];

$title = clear($_POST["title"]);
$text = clear($_POST["text"]);
$id_theme_category = (int)$_POST["id_theme_category"];
$shop_id = clear($_POST["shop_id"]);
$sliders = $_POST['slider'] ? json_decode($_POST['slider'], true) : [];
$logo = $_POST['logo'] ? json_decode($_POST['logo'], true) : [];

$getShop = $Shop->getShop(['user_id'=>$idUser,'conditions'=>false]);

if(!$getShop){
	http_response_code(500); exit('Shop not found');
}

$getUser = findOne("uni_clients", "clients_id=?", [$idUser]);

$getTariff = apiGetTariff($getUser["clients_tariff_id"]);

if(!$title) $errors[] = apiLangContent("Пожалуйста, укажите название магазина");
if(!$id_theme_category) $errors[] = apiLangContent("Пожалуйста, выберите тематику");

if($shop_id && $getTariff['services']['unique_shop_address']){

 $getShopId = findOne("uni_clients_shops", "clients_shops_id_hash=? and clients_shops_id_user!=?", [$shop_id,$idUser]);

 if($getShopId) $errors[] = apiLangContent("Идентификатор") . " {$shop_id} " . apiLangContent("уже используется"); 

}else{

 $shop_id = md5($idUser);

}


if( !$errors ){

  if($logo){
   	$path = $config["basePath"] . "/" . $config["media"]["temp_images"];        
   	if(file_exists($path."/".$logo[0]['name'])){
     	if(copy($path."/".$logo[0]['name'], $config["basePath"]."/".$config["media"]["other"]."/".$logo[0]['name'])){
     		unlink($config["basePath"]."/".$config["media"]["other"]."/".$getShop['clients_shops_logo']);
			$getShop['clients_shops_logo'] = $logo[0]['name'];
     	}
   	} 
  }

  if($sliders){

     foreach ($sliders as $index => $value) {
        $slidersShop[$value["name"]] = $value["name"];
     }

     $getSliders = getAll('select * from uni_clients_shops_slider where clients_shops_slider_id_shop=?',[$getShop["clients_shops_id"]]);

     if(count($getSliders)){
        foreach ($getSliders as $value) {
            if(!in_array($value["clients_shops_slider_image"], $slidersShop)){
                @unlink($config["basePath"] . "/" . $config["media"]["user_attach"] . "/" . $value["clients_shops_slider_image"]);
                update("delete from uni_clients_shops_slider where clients_shops_slider_id=?", [$value['clients_shops_slider_id']]);
            }else{
                unset($slidersShop[$value["clients_shops_slider_image"]]);
            }
        }
     }

     $getSliders = getAll('select * from uni_clients_shops_slider where clients_shops_slider_id_shop=?',[$getShop["clients_shops_id"]]);

     if(count($getSliders) < $settings["user_shop_count_sliders"]){

         foreach (array_slice($slidersShop, 0, $settings["user_shop_count_sliders"]-count($getSliders)) as $value) {
             
             if(file_exists($config["basePath"]."/".$config["media"]["temp_images"]."/".$value)){

                if(copy($config["basePath"]."/".$config["media"]["temp_images"]."/".$value, $config["basePath"]."/".$config["media"]["user_attach"]."/".$value)){
                    insert("INSERT INTO uni_clients_shops_slider(clients_shops_slider_id_shop,clients_shops_slider_image,clients_shops_slider_id_user)VALUES(?,?,?)", [$getShop["clients_shops_id"],$value,$idUser]);
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
  
  update("update uni_clients_shops set clients_shops_id_hash=?,clients_shops_title=?,clients_shops_desc=?,clients_shops_logo=?,clients_shops_id_theme_category=? where clients_shops_id_user=?", [$shop_id,$title,$text,$getShop["clients_shops_logo"],$id_theme_category,$idUser]);

  $Admin->notifications("shops_edit", ["shop_name"=>$title, "shop_link"=>$Shop->linkShop($shop_id)]);

  echo json_encode( [ "status" => true ] );
}else{
  echo json_encode( [ "status" => false, "errors" => implode("\n", $errors) ] );
}

?>