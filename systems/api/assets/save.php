<?php
$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
    http_response_code(500); exit('Authorization token error');
}

$results = [];

$Watermark = new Watermark();

$type = $_POST['type'];
$action = $_POST['action'];

if(!file_exists($config["basePath"]."/temp")) @mkdir($config["basePath"]."/temp", $config["create_mode"] );
if(!file_exists($config["basePath"]."/temp/images")) @mkdir($config["basePath"]."/temp/images", $config["create_mode"] );
if(!file_exists($config["basePath"]."/temp/video")) @mkdir($config["basePath"]."/temp/video", $config["create_mode"] );
if(!file_exists($config["basePath"]."/temp/cache")) @mkdir($config["basePath"]."/temp/cache", $config["create_mode"] );

if($_POST['assets']){

    if($action == 'adAdd'){

        $path = $config["basePath"] . "/" . $config["media"]["temp_images"];

        $assets = json_decode($_POST['assets'], true);

        foreach ($assets as $value) {

            $name = md5($idUser.'_'.uniqid());

            if(file_put_contents($path."/".$name.'.jpg', base64_decode($value))){

                rotateImage($path."/".$name.'.jpg');

                $Watermark->create($path."/".$name.'.jpg', $path."/".$name.'.jpg');

                resize($path . "/" . $name.'.jpg', $path . "/big_" . $name.'.webp', $settings["ads_images_big_width"], $settings["ads_images_big_height"], 90, 'webp');
                resize($path . "/" . $name.'.jpg', $path . "/small_" . $name.'.webp', $settings["ads_images_small_width"], $settings["ads_images_small_height"], 100, 'webp');
                
                unlink($path."/".$name.'.webp');

                $name = $name . ".webp";

                $results = ['name'=>$name, 'link'=>$config["urlPath"]."/".$config["media"]["temp_images"].'/small_'.$name];

            }
        

        }

    }elseif($action == 'reviewAdd'){

        $path = $config["basePath"] . "/" . $config["media"]["temp_images"];

        $assets = json_decode($_POST['assets'], true);

        foreach ($assets as $value) {

            $name = md5($idUser.'_'.uniqid()).'.webp';

            if(file_put_contents($path."/".$name, base64_decode($value))){

                resize($path."/".$name, $path."/".$name, 1024, 0, 90, 'webp');
                
                $results = ['name'=>$name, 'link'=>$config["urlPath"]."/".$config["media"]["temp_images"].'/'.$name];

            }
        
        }

    }elseif($action == 'storyAdd'){

        $path = $config["basePath"] . "/" . $config["media"]["temp_images"];

        $name = md5($idUser.'_'.uniqid()).'.webp';

        if(file_put_contents($path."/".$name, base64_decode($_POST['assets']))){

            rotateImage($path . "/" . $name);

            resize($path . "/" . $name, $path . "/" . $name, 1024, 0, 90, 'webp');
            
            $results = ['name'=>$name, 'link'=>$config["urlPath"]."/".$config["media"]["temp_images"]."/".$name];

        }

    }elseif($action == 'userAvatar'){

        $path = $config["basePath"] . "/" . $config["media"]["temp_images"];

        $name = md5($idUser.'_'.uniqid()).'.webp';

        if(file_put_contents($path."/".$name, base64_decode($_POST['assets']))){

            resize($path . "/" . $name, $path . "/" . $name, 1024, 0, 90, 'webp');
            $results = ['name'=>$name, 'link'=>$config["urlPath"]."/".$config["media"]["temp_images"]."/".$name];

        }

    }elseif($action == 'chat'){

        $path = $config["basePath"] . "/" . $config["media"]["temp_images"];

        $assets = json_decode($_POST['assets'], true);

        foreach ($assets as $value) {

            $name = md5($idUser.'_'.uniqid()).'.webp';

            if(file_put_contents($path."/".$name, base64_decode($value))){

                resize($path."/".$name, $path."/".$name, 1024, 0, 90, 'webp');
                
                $results = ['name'=>$name, 'link'=>$config["urlPath"]."/".$config["media"]["temp_images"].'/'.$name];

            }
        
        }        

    }elseif($action == 'secureDispute'){

        $path = $config["basePath"] . "/" . $config["media"]["temp_images"];

        $assets = json_decode($_POST['assets'], true);

        foreach ($assets as $value) {

            $name = md5($idUser.'_'.uniqid()).'.webp';

            if(file_put_contents($path."/".$name, base64_decode($value))){

                resize($path."/".$name, $path."/".$name, 1024, 0, 90, 'webp');
                
                $results = ['name'=>$name, 'link'=>$config["urlPath"]."/".$config["media"]["temp_images"].'/'.$name];

            }
        
        }

    }elseif($action == 'shopAvatar'){

        $path = $config["basePath"] . "/" . $config["media"]["temp_images"];

        $name = md5($idUser.'_shop_logo_'.uniqid()).'.webp';

        if(file_put_contents($path."/".$name, base64_decode($_POST['assets']))){

            resize($path . "/" . $name, $path . "/" . $name, 1024, 0, 90, 'webp');
            $results = ['name'=>$name, 'link'=>$config["urlPath"]."/".$config["media"]["temp_images"]."/".$name];

        }

    }elseif($action == 'shopSlider'){

        $path = $config["basePath"] . "/" . $config["media"]["temp_images"];

        $name = md5($idUser.'_shop_slider_'.uniqid()).'.webp';

        if(file_put_contents($path."/".$name, base64_decode($_POST['assets']))){

            resize($path . "/" . $name, $path . "/" . $name, 1024, 0, 90, 'webp');
            $results = ['name'=>$name, 'link'=>$config["urlPath"]."/".$config["media"]["temp_images"]."/".$name];

        }

    }

}

echo json_encode(['data'=>$results ?: null]);

?>