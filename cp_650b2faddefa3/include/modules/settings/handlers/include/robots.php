<?php

update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["robots_index_site"]),'robots_index_site'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["robots_manual_setting"]),'robots_manual_setting'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["robots_exclude_link"]),'robots_exclude_link'));

if( $_POST["robots_manual_setting"] ){

   if( !file_put_contents($config["basePath"] . "/robots.txt", $_POST["robots"]) ){
       $error[] = "Недостаточно прав на запись для файла robots.txt";
   }

}else{

   $robots_index_site = (int)$_POST["robots_index_site"];

   $content_robots = "User-agent: *\n";

   if(!$robots_index_site){
     $content_robots .= "Disallow: /\n";
   }
   
   $content_robots .= "Host: " . $config["urlPath"] . "\n";
   $content_robots .= "Sitemap: " . $config["urlPath"] . "/sitemap.xml\n";

   $content_robots .= "Disallow: /media/\n";
   $content_robots .= "Disallow: /temp/\n";
   $content_robots .= "Disallow: /templates/\n";

   if( $_POST["robots_exclude_link"] ){
       $links = explode(PHP_EOL, $_POST["robots_exclude_link"]);
       foreach ($links as $key => $value) {
          if($value) $content_robots .= "Disallow: $value";
       }
   }

   if( !file_put_contents($config["basePath"] . "/robots.txt", $content_robots) ){
       $error[] = "Недостаточно прав на запись для файла robots.txt";
   }

}

?>