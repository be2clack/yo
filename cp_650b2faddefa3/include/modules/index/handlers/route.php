<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if(!$_SESSION['cp_auth'][ $config["private_hash"] ]){
  exit;
}

$Geo = new Geo();


if(isAjax() == true){

     $get = getOne("SELECT * FROM uni_metrics where id=?", array( intval($_POST["id"]) ));

     $geo = $Geo->geoIp($get["ip"]);

     ?>
     <h1>Маршрут пользователя из <?php if($geo["city"]){ echo $geo["city"]; }else{ echo "-"; } ?></h1>

     <ul>
     <?php

     $key = 1;

     if($get["route"]){
        $route_user = json_decode($get["route"], true);
        if(count($route_user) > 0){

          foreach ($route_user as $ip => $array_links) {
            foreach ($array_links as $value_links) {

               ?>
               <li> <a href="<?php echo urldecode($value_links["link"]); ?>" target="_blank" ><?php if( $value_links["link"] == $get["view_page_link"] && (strtotime($get["date_view"]) + 180) > time()){ echo '<span class="online badge-pulse-green-small"></span>'; } echo $value_links["title"]; ?></a> </li>
               
               <?php if( count($array_links) != $key ){ ?>
               <li class="route-arrow" > <i class="la la-arrow-down"></i> </li>
               <?php }else{

                 if((strtotime($get["date_view"]) + 180) < time()){
                    ?>
                    <li class="route-arrow" > <i class="la la-arrow-down"></i> </li>
                    <li class="route-finish" > <span>Посетитель закрыл сайт</span> </li>
                    <?php
                 }

               }
              
              $key++;

            }
          }

        }
     }

     ?>
     </ul>
     <?php

}
?>