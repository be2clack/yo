<?php

$query = clearSearch( $_POST["q"] );

if($query && mb_strlen($query, "UTF-8") >= 2 ){

  $get = getAll("SELECT * FROM uni_boxberry_cities WHERE boxberry_cities_name LIKE '%".$query."%' order by boxberry_cities_name asc");

  if(count($get)){

     foreach($get AS $data){

          ?>
            <div class="item-city" data-city="<?php echo $data["boxberry_cities_name"]; ?>"  id-city="<?php echo $data["boxberry_cities_code"]; ?>" >
              <strong><?php echo $data["boxberry_cities_name"]; ?></strong>
            </div>
          <?php

     }   

  }else{
    echo false;
  }

}else{
echo false;
}

?>