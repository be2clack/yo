<?php

$query = clearSearch( $_POST["q"] );

if($query && mb_strlen($query, "UTF-8") >= 2 ){

	 if($settings["region_id"]) $where_region = "and `uni_region`.region_id = '{$settings["region_id"]}'"; else $where_region = "";
    
	 $langSearch = $ULang->search($query);
debug($langSearch);
	 if($langSearch){
		$results = $langSearch;
	 }else{
		$results = getAll("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id WHERE `uni_country`.country_status = '1' $where_region and `uni_city`.city_name LIKE '%".$query."%' order by city_name asc");
	 }

    if($results){

       foreach($results AS $data){

        if($data["region_name"] == $data["country_name"]){

	          ?>
	            <div class="item-city" data-city="<?php echo $data["city_name"]; ?>"  id-city="<?php echo $data["city_id"]; ?>" >
	            	<strong><?php echo $ULang->t( $data["city_name"], [ "table" => "geo", "field" => "geo_name" ] ); ?></strong> <span class="span-subtitle" ><?php echo $ULang->t( $data["country_name"], [ "table" => "geo", "field" => "geo_name" ] ); ?></span>
	            </div>
	          <?php

        }else{

	          ?>
	            <div class="item-city"  data-city="<?php echo $data["city_name"]; ?>"  id-city="<?php echo $data["city_id"]; ?>" >
	            	<strong><?php echo $ULang->t( $data["city_name"], [ "table" => "geo", "field" => "geo_name" ] ); ?></strong> <span class="span-subtitle" ><?php echo $ULang->t( $data["region_name"], [ "table" => "geo", "field" => "geo_name" ] ); ?>, <?php echo $ULang->t( $data["country_name"], [ "table" => "geo", "field" => "geo_name" ] ); ?></span>
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

?>