<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_clients']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

    $query = clearSearch( $_POST["q"] );

	if($query && mb_strlen($query, "UTF-8") >= 2 ){

	    $get = getAll("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id WHERE `uni_country`.country_status = '1' and `uni_city`.city_name LIKE '%".$query."%' order by city_name asc");

	    if(count($get)>0){


	       foreach($get AS $data){

	        if($data["region_name"] == $data["country_name"]){

		          ?>
		            <div class="item-city" data-city="<?php echo $data["city_name"]; ?>"  id-city="<?php echo $data["city_id"]; ?>" >
		            	<strong><?php echo $data["city_name"]; ?></strong> <span class="span-subtitle" ><?php echo $data["country_name"]; ?></span>
		            </div>
		          <?php

	        }else{

		          ?>
		            <div class="item-city"  data-city="<?php echo $data["city_name"]; ?>"  id-city="<?php echo $data["city_id"]; ?>" >
		            	<strong><?php echo $data["city_name"]; ?></strong> <span class="span-subtitle" ><?php echo $data["region_name"]; ?>, <?php echo $data["country_name"]; ?></span>
		            </div>
		          <?php

	        }


	       }   

	    }else{
	    	echo false;
	    }

	}else{
		echo false;
	}

}

?>