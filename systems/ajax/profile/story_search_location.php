<?php

$query = clearSearchBack($_POST["search"]);
$list = [];

if($query && mb_strlen($query, 'UTF-8') > 2){

    $get = getAll("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id WHERE `uni_country`.country_status = '1' and `uni_city`.city_name LIKE '%".$query."%' order by city_name asc");

    if(!$get){

      $get = getAll("SELECT * FROM uni_region INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_region`.country_id WHERE `uni_country`.country_status = '1' and `uni_region`.region_name LIKE '%".$query."%' order by region_name asc");

      if(!$get){

          $get = getAll("SELECT * FROM uni_country WHERE country_status = '1' and country_name LIKE '%".$query."%' order by country_name asc");

      }

    }

    if(count($get)){

        foreach ($get as $data) {

             if($data["region_name"]){
             $list["region"][$data["region_name"]] = '
                <div data-name="'.$data["region_name"].'" id-country="0"  id-city="0"  id-region="'.$data["region_id"].'" >
                    <strong>'.$ULang->t( $data["region_name"], [ "table" => "geo", "field" => "geo_name" ] ).'</strong>
                </div>
             ';
             }
             
             if($data["city_name"]){
             $list["city"][] = '
                <div data-name="'.$data["city_name"].'" id-country="0" id-region="0" id-city="'.$data["city_id"].'" >
                    <strong>'.$ULang->t( $data["city_name"], [ "table" => "geo", "field" => "geo_name" ] ).'</strong> <span class="span-subtitle" >'.$ULang->t( $data["region_name"], [ "table" => "geo", "field" => "geo_name" ] ).', '.$ULang->t( $data["country_name"], [ "table" => "geo", "field" => "geo_name" ] ).'</span>
                </div>
             ';
             }
             
             if($data["country_name"]){
             $list["country"][$data["country_name"]] = '
                <div data-name="'.$data["country_name"].'"  id-city="0"  id-region="0" id-country="'.$data["country_id"].'" >
                    <strong>'.$ULang->t( $data["country_name"], [ "table" => "geo", "field" => "geo_name" ] ).'</strong>
                </div>
             ';
             }

        }

        if($list["region"]) echo implode("", $list["region"]);
        echo implode("", $list["city"]);
        if($list["country"]) echo implode("", $list["country"]);    

    }

}else{

      if(!$settings["region_id"]){
        $get = getAll("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id WHERE `uni_city`.city_default = '1' and `uni_country`.country_status = '1' order by city_count_view desc limit 15");
        if(!count($get)){
           $get = getAll("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id WHERE `uni_country`.country_status = '1' order by city_count_view desc limit 15");
        }
      }else{
        $get = getAll("SELECT * FROM uni_city WHERE region_id='{$settings["region_id"]}' order by city_count_view desc limit 15");
      }

      ?>
      <div data-name="<?php echo $ULang->t("Все города"); ?>" id-country="0" id-region="0" id-city="0" ><?php echo $ULang->t("Все города"); ?></div>
      <?php

      if($get){
        foreach ($get as $key => $value) {
            ?>
            <div data-name="<?php echo $value["city_name"]; ?>" id-country="0" id-region="0" id-city="<?php echo $value["city_id"]; ?>" ><?php echo $value["city_name"]; ?></div>
            <?php
        }
      }

}

?>