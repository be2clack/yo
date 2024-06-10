<?php

$query = clearSearch($_GET["query"]);

$results = [];

if($query){

  $getCities = getAll("SELECT * FROM uni_boxberry_cities WHERE boxberry_cities_name LIKE '%".$query."%' order by boxberry_cities_name asc");

  if($getCities){

     foreach($getCities AS $data){

     	if($data['boxberry_cities_lat'] && $data['boxberry_cities_lon']){
     		$results[] = ['name'=>$data['boxberry_cities_name'], 'lat'=>$data['boxberry_cities_lat'], 'lon'=>$data['boxberry_cities_lon'], 'code'=>$data['boxberry_cities_code']];
     	}
     	
     }   

  }

}

echo json_encode($results);

?>