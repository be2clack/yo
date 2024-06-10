<?php

$query = clearSearch( $_POST["q"] );

if($query && mb_strlen($query, "UTF-8") >= 2 ){

	$langSearch = $ULang->search($query);

	if($langSearch){
		$results = $langSearch;
	}else{
		$results = $Geo->search($query);
	}

	 if(count($results)){

	    foreach($results AS $data){
	          
	          if($data["region_name"]){
	       	 $list["region"][$data["region_name"]] = '
	            <div class="item-city" data-name="'.$data["region_name"].'" id-country="0"  id-city="0"  id-region="'.$data["region_id"].'" >
	            	<strong>'.$ULang->t( $data["region_name"], [ "table" => "geo", "field" => "geo_name" ] ).'</strong>
	            </div>
	       	 ';
	       	 }
	          
	          if($data["city_name"]){
	       	 $list["city"][] = '
	            <div class="item-city"  data-name="'.$data["city_name"].'" id-country="0" id-region="0" id-city="'.$data["city_id"].'" >
	            	<strong>'.$ULang->t( $data["city_name"], [ "table" => "geo", "field" => "geo_name" ] ).'</strong> <span class="span-subtitle" >'.$ULang->t( $data["region_name"], [ "table" => "geo", "field" => "geo_name" ] ).', '.$ULang->t( $data["country_name"], [ "table" => "geo", "field" => "geo_name" ] ).'</span>
	            </div>
	       	 ';
	       	 }
	       	 
	       	 if($data["country_name"]){
	       	 $list["country"][$data["country_name"]] = '
	            <div class="item-city" data-name="'.$data["country_name"].'"  id-city="0"  id-region="0" id-country="'.$data["country_id"].'" >
	            	<strong>'.$ULang->t( $data["country_name"], [ "table" => "geo", "field" => "geo_name" ] ).'</strong>
	            </div>
	       	 ';
	       	 }

	    }


	    if($list["region"]) echo implode("", $list["region"]);
	    echo implode("", $list["city"]);
	    if($list["country"]) echo implode("", $list["country"]);

	 }else{
	 	echo false;
	 }

}else{
	echo false;
}

?>