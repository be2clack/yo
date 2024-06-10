<!doctype html>
<html lang="<?php echo getLang(); ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?php echo $ULang->t("Выбор города"); ?></title>

    <?php include $config["template_path"] . "/head.tpl"; ?>

  </head>

  <body data-prefix="<?php echo $config["urlPrefix"]; ?>" data-template="<?php echo $config["template_folder"]; ?>" >
    
    <?php include $config["template_path"] . "/header.tpl"; ?>

    <div class="container" >
       
       <nav aria-label="breadcrumb">
 
          <ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">

            <li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
              <a itemprop="item" href="<?php echo $config["urlPath"]; ?>">
              <span itemprop="name"><?php echo $ULang->t("Главная"); ?></span>
              </a>
              <meta itemprop="position" content="1">
            </li>

            <li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
              <span itemprop="name"><?php echo $ULang->t("Выбор города"); ?></span>
              <meta itemprop="position" content="2">
            </li>                 
          </ol>

        </nav>
          
        <div class="row" >
            <div class="col-lg-12" >

              <h1 class="cities-list-title" ><?php echo $ULang->t("Выберите город"); ?></h1>

              <?php
               if( !$_GET["country"] ){

                   if( $_SESSION["geo"]["data"] ){
                       $country_alias = $_SESSION["geo"]["data"]["country_alias"];
                   }else{
                       $country_alias = $settings["country_default"];
                   }

               }else{

                   $country_alias = clear( $_GET["country"] );

               }

               if($_SESSION["temp_change_category"]["category_board_chain"]){
                   $all_cities = _link( $country_alias . "/" . $_SESSION["temp_change_category"]["category_board_chain"] );
               }else{
                   $all_cities = _link( $country_alias );
               }

               $getCountry = findOne('uni_country', 'country_alias=?', [$country_alias]);

              ?>

              <div class="row mb15 mt25" >
                  <div class="col-lg-3 col-md-3 col-sm-4 col-12" >
                      <a class="btn-custom btn-color-light width100" href="<?php echo $all_cities; ?>" ><strong><?php echo $ULang->t("Объявления"); ?> <?php echo $getCountry["country_declination"] ? $ULang->t($getCountry["country_declination"]) : $ULang->t( $getCountry["country_name"] , [ "table"=>"geo", "field"=>"geo_name" ] ); ?></strong></a>
                  </div>
              </div>

              <div class="cities-list minheight400" >

              <form action="#" autocomplete="off" onsubmit="return false" >
              <div class="mb25 cities-search-input" >
                  <input type="text" name="q" autocomplete="off" placeholder="<?php echo $ULang->t("Поиск"); ?>" >
              </div>
              </form>

              <?php  

                $getCountry = getAll("SELECT * FROM `uni_country` WHERE country_status = '1' order by country_name asc");

                if( count($getCountry) ){

                   if( count($getCountry) > 1 ){

                       ?>
                       <div class="row" >
                       <?php

                       foreach ($getCountry as $key => $value) {

                          if( $value["country_alias"] == $country_alias ){ $activeCountry = 'cities_active_country'; }else{ $activeCountry = ''; }

                          echo '<div class="col-lg-4 col-md-4 col-sm-4 col-12" ><a class="citiesLinkCountry '.$activeCountry.'" href="'._link( "cities" ).'?country='.$value["country_alias"].'" >'.$ULang->t( $value["country_name"] , [ "table"=>"geo", "field"=>"geo_name" ] ).'</a></div>';
                       }

                       ?>

                       </div>

                       <div class="mt20" ></div>
                       <?php

                   }

                }

                if($settings["region_id"]) $where_region = "and `uni_region`.region_id = '{$settings["region_id"]}'"; else $where_region = "";

                $getCities = getAll("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id WHERE `uni_country`.country_status = '1' and `uni_country`.country_alias='".$country_alias."' and `uni_city`.city_default = '1' $where_region order by city_count_view desc");
                
                ?>
                <div class="cities-search-list-container" >
                <?php
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
                ?>
                </div>

              </div>

            </div>
        </div>
         
          
       <div class="mt50" ></div>


    </div>


    <?php include $config["template_path"] . "/footer.tpl"; ?>


  </body>
</html>