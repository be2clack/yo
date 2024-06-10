<?php

$query = clearSearch( $_POST["q"] );

if($query && mb_strlen($query, "UTF-8") >= 2 ){

	if($settings["region_id"]) $where_region = "and `uni_region`.region_id = '{$settings["region_id"]}'"; else $where_region = "";
    
    $get = getAll("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id WHERE `uni_country`.country_status = '1' $where_region and `uni_city`.city_name LIKE '%".$query."%' order by city_name asc");

    if($get){

       ?>
       <div class="row" >
       <?php

	       foreach($get AS $data){

             if($_SESSION["temp_change_category"]["category_board_chain"]){
                $alias = _link( $data["city_alias"] . "/" . $_SESSION["temp_change_category"]["category_board_chain"] );
             }else{
                $alias = _link( $data["city_alias"] );
             }

             ?>
             <div class="col-12" >
                 <div class="row" >
	                  <div class="col-lg-3 col-md-3 col-sm-4 col-12" >
	                      <a href="<?php echo $alias; ?>" ><?php echo $ULang->t( $data["city_name"], [ "table" => "geo", "field" => "geo_name" ] ); ?></a>
	                  </div> 
                 </div> 
             </div>                   
             <?php

	       }   

       ?>
       </div>                   
       <?php

    }

}else{

   if($settings["region_id"]) $where_region = "and `uni_region`.region_id = '{$settings["region_id"]}'"; else $where_region = "";

   if( $_SESSION["geo"]["data"] ){
       $country_alias = $_SESSION["geo"]["data"]["country_alias"];
   }else{
       $country_alias = $settings["country_default"];
   }

   $getCities = getAll("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id WHERE `uni_country`.country_status = '1' and `uni_country`.country_alias='".$country_alias."' and `uni_city`.city_default = '1' $where_region order by city_count_view desc");

   if(count($getCities)){

        ?>
        <div class="row" >
        <?php

        foreach ($getCities as $key => $value) {

              $value["city_name"] = $ULang->t( $value["city_name"], [ "table" => "geo", "field" => "geo_name" ] );
              
              if($_SESSION["temp_change_category"]["category_board_chain"]){
                 $alias = _link( $value["city_alias"] . "/" . $_SESSION["temp_change_category"]["category_board_chain"] );
              }else{
                 $alias = _link( $value["city_alias"] );
              }

              ?>
              <div class="col-lg-3 col-md-3 col-sm-4 col-12" >
                  <a href="<?php echo $alias; ?>" ><?php echo $value["city_name"]; ?></a>
              </div>
              <?php

        }

        ?>
        </div>                   
        <?php

   }		   

}

?>