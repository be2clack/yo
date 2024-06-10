<?php

$Geo->set( array( "city_id" => intval($_POST["city_id"]) , "region_id" => intval($_POST["region_id"]) , "country_id" => intval($_POST["country_id"]), "action" => "modal" ) );

$city_areas = getAll("select * from uni_city_area where city_area_id_city=? order by city_area_name asc", [ intval($_SESSION["geo"]["data"]["city_id"]) ]);
$city_metro = getAll("select * from uni_metro where city_id=? and parent_id!=0 Order by name ASC", [ intval($_SESSION["geo"]["data"]["city_id"]) ]); 

if($city_areas){
?>

  <div class="uni-select" data-status="0" >

       <div class="uni-select-name" data-name="<?php echo $ULang->t("Район"); ?>" > <span><?php echo $ULang->t("Район"); ?></span> <i class="la la-angle-down"></i> </div>
       <div class="uni-select-list" >
           <?php
           foreach ($city_areas as $value) {
              ?>
              <label> <input type="checkbox" name="filter[area][]" value="<?php echo $value["city_area_id"]; ?>" > <span><?php echo $ULang->t( $value["city_area_name"], [ "table" => "uni_city_area", "field" => "city_area_name" ] ); ?></span> <i class="la la-check"></i> </label>
              <?php
           }
           ?>
       </div>
  
  </div>

<?php
}

if($city_metro){
?>

  <div class="container-custom-search">
    <input type="text" class="ads-create-input action-input-search-metro" placeholder="<?php echo $ULang->t("Поиск станций метро"); ?>">
    <div class="custom-results SearchMetroResults" style="display: none;"></div>
  </div>

  <div class="ads-container-metro-station"></div>

<?php
}

?>