<?php

update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["pwa_name"]),'pwa_name'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["pwa_short_name"]),'pwa_short_name'));
update("UPDATE uni_settings SET value=? WHERE name=?", array( mb_substr(clear($_POST["pwa_desc"]), 0, 255, "UTF-8") ,'pwa_desc'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["pwa_status"]),'pwa_status'));

$manifest = [];
$manifest["dir"] = "ltr";
$manifest["lang"] = "Russian";
$manifest["name"] = $_POST["pwa_name"];
$manifest["short_name"] = $_POST["pwa_short_name"];
$manifest["scope"] = $config["urlPrefix"];
$manifest["display"] = "standalone";
$manifest["start_url"] = $config["urlPath"] . "/";
$manifest["url"] = $config["urlPath"] . "/";
$manifest["background_color"] = "#FFFFFF";
$manifest["theme_color"] = "#FFFFFF";
$manifest["description"] = mb_substr($_POST["pwa_desc"], 0, 255, "UTF-8");
$manifest["orientation"] = "any";
$manifest["related_applications"] = [];
$manifest["prefer_related_applications"] = false;

$manifest["icons"][] = [ "src" => $config["urlPath"] . '/' . $config["template_folder"] . "/icons_pwa/icon-192x192.png", "sizes" => "192x192", "type" => "image/png", "purpose" => "any maskable" ];
$manifest["icons"][] = [ "src" => $config["urlPath"] . '/' . $config["template_folder"] . "/icons_pwa/icon-256x256.png", "sizes" => "256x256", "type" => "image/png" ];
$manifest["icons"][] = [ "src" => $config["urlPath"] . '/' . $config["template_folder"] . "/icons_pwa/icon-384x384.png", "sizes" => "384x384", "type" => "image/png" ];
$manifest["icons"][] = [ "src" => $config["urlPath"] . '/' . $config["template_folder"] . "/icons_pwa/icon-512x512.png", "sizes" => "512x512", "type" => "image/png" ];
$manifest["screenshots"][] = [ "src" => $config["urlPath"] . '/' . $config["template_folder"] . "/icons_pwa/icon-512x512.png", "sizes" => "512x512", "type" => "image/png" ];

if(!empty($_FILES['pwa_icon']['name'])){

    $path = $config["template_path"] . "/icons_pwa/";
    $max_file_size = 1;
    $extensions = array('png');
    $ext = strtolower(pathinfo($_FILES['pwa_icon']['name'], PATHINFO_EXTENSION));
    
    if($_FILES["pwa_icon"]["size"] > $max_file_size*1024*1024){
        $error[] = "Иконка для pwa не загружена. Максимальный размер файла ".$max_file_size.' mb!';
    }{
            
    if (in_array($ext, $extensions))
    {
            
            $image_name = 'icon-512x512.png';
            $path = $path . $image_name;

            if (!move_uploaded_file($_FILES['pwa_icon']['tmp_name'], $path))
            {                    
                $error[] = "Иконка для pwa не загружена. Недостаточно прав на запись.";                                   
            }else{
                resize($path, $config["template_path"] . "/icons_pwa/icon-384x384.png" , 384, 384, 100);
                resize($path, $config["template_path"] . "/icons_pwa/icon-256x256.png" , 256, 256, 100);
                resize($path, $config["template_path"] . "/icons_pwa/icon-192x192.png" , 192, 192, 100);
            }
            
    update("UPDATE uni_settings SET value=? WHERE name = ?", array($image_name,'pwa_image')); 

    }else{
            $error[] = "Иконка для pwa не загружена. Допустимые форматы ".implode(",",$extensions); 
    }

    }       
    
}

if( intval($_POST["pwa_status"]) ){
    file_put_contents( $config["basePath"] . "/manifest.json" , json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) );
}

?>