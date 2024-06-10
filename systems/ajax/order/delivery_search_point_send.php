<?php

$query = clearSearch( $_POST["q"] );

if($query && mb_strlen($query, "UTF-8") >= 2 ){

  $get = getAll("SELECT * FROM uni_boxberry_points WHERE boxberry_points_send=1 and boxberry_points_address LIKE '%".$query."%' order by boxberry_points_address asc");

  if(count($get)){

     foreach($get AS $data){

          ?>
            <div class="item-city" data-city="<?php echo $data["boxberry_points_address"]; ?>"  id-point="<?php echo $data["boxberry_points_code"]; ?>" >
              <strong><?php echo $data["boxberry_points_address"]; ?></strong>
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