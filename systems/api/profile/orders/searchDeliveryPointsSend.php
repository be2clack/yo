<?php

$query = clearSearch($_GET["query"]);

$results = [];

if($query && mb_strlen($query, "UTF-8") >= 2 ){

  $getPoint = getAll("SELECT * FROM uni_boxberry_points WHERE boxberry_points_send=1 and boxberry_points_address LIKE '%".$query."%' order by boxberry_points_address asc limit 100");

  if($getPoint){

     foreach($getPoint AS $data){

        $results[] = ['name'=>$data['boxberry_points_address'], 'code'=>$data['boxberry_points_code']];

     }   

  }

}

echo json_encode(['data'=>$results]);

?>