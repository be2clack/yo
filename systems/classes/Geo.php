<?php

/**
 * UniSite CMS
 *
 * @copyright   2018 UniSite CMS
 * @link    https://unisitecms.ru
 * @author    Artur Zhur
 *
 */

class Geo{

   function alias(){
     if($_SESSION["geo"]["alias"]){
        return _link($_SESSION["geo"]["alias"]);
     }else{
        return _link("cities"); 
     }
   }
    
   function set( $param = array() ){
       
       if($param["city_id"]){
         $get = getOne("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id WHERE `uni_country`.country_status = '1' and `uni_city`.city_id = '".intval($param["city_id"])."'");
         if($get){
            $_SESSION["geo"] = [ "name" => $get["city_name"], "data" => $get, "change" => "city", "action" => $param["action"], "alias" => $get["city_alias"], "desc" => $get["city_desc"], "lat"=>$get["city_lat"], "lon"=>$get["city_lng"] ];
            $this->viewCity($get["city_id"]);
         }
       }elseif($param["region_id"]){
         $get = getOne("SELECT * FROM uni_region INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_region`.country_id  WHERE `uni_country`.country_status = '1' and `uni_region`.region_id = '".intval($param["region_id"])."'");
         if($get){
            $_SESSION["geo"] = [ "name" => $get["region_name"], "data" => $get, "change" => "region", "action" => $param["action"], "alias" => $get["region_alias"], "desc" => $get["region_desc"] ];
         }
       }elseif($param["country_id"]){
         $get = getOne("SELECT * FROM uni_country  WHERE `uni_country`.country_status = '1' and `uni_country`.country_id = '".intval($param["country_id"])."'");
         if($get){
            $_SESSION["geo"] = [ "name" => $get["country_name"], "data" => $get, "change" => "country", "action" => $param["action"], "alias" => $get["country_alias"], "desc" => $get["country_desc"] ];
         }
       }else{
          $_SESSION["geo"] = [];
       }

   }

   function change(){
       
      return isset($_SESSION["geo"]) ? $_SESSION["geo"] : [];

   }

   function viewCity($id=0){
    if(detectRobots($_SERVER['HTTP_USER_AGENT']) == false){  
      if(!$_SESSION["view-city"][$id]){
        update("UPDATE uni_city SET city_count_view=city_count_view+1 WHERE city_id=?", array( intval($id) )); 
        $_SESSION["view-city"][$id] = 1;
      }  
    }    
   }


   function cityDefault( $country_alias = "", $limit = 30, $list_country_status = true ){
    global $settings;

    $ULang = new ULang();
    $Cache = new Cache();
    $return = "";
    $span = "";
    $list_country = "";

    if( !$country_alias ) $country_alias = $settings["country_default"];

      if( !$Cache->get( [ "table" => "cityDefault", "key" => $country_alias ] ) ){

          if(!$settings["region_id"]){
            $get = getAll("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id WHERE `uni_city`.city_default = '1' and `uni_country`.country_status = '1' and `uni_country`.country_alias='".$country_alias."' order by city_count_view desc limit $limit");
            if(!count($get)){
               $get = getAll("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id WHERE `uni_country`.country_status = '1' and `uni_country`.country_alias='".$country_alias."' order by city_count_view desc limit $limit");
            }
          }else{
            $get = getAll("SELECT * FROM uni_city WHERE region_id='{$settings["region_id"]}' order by city_count_view desc limit $limit");
          }

          $Cache->set( [ "table" => "cityDefault", "key" => $country_alias, "data" => $get ] );

      }else{
         $get = $Cache->get( [ "table" => "cityDefault", "key" => $country_alias ] );
      }

      $getCountry = getAll("SELECT * FROM `uni_country` WHERE country_status = '1' order by country_name asc");

      if( count($getCountry) && $list_country_status ){

         if( count($getCountry) > 1 ){

             foreach ($getCountry as $key => $value) {
                $list_country .= '<div class="col-lg-4 col-md-6 col-sm-6 col-12" ><span class="item-city-country item-country-hover" id-city="0" id-region="0" id-country="'.$value["country_id"].'" data-alias="'.$value["country_alias"].'" >'.$ULang->t( $value["country_name"] , [ "table"=>"geo", "field"=>"geo_name" ] ).'</span></div>';
             }

         }

      }

      $findCountry = findOne("uni_country", "country_alias=?", [$country_alias]);

      if( $settings["region_id"] ){
         $findRegion = findOne("uni_region", "region_id=?", [$settings["region_id"]]);
         $list[] = '<span class="item-city" id-city="0" id-region="'.$settings["region_id"].'" id-country="0" data-alias="'.$findRegion["region_alias"].'" >'.$ULang->t("Все города").'</span>';
      }else{
         $list[] = '<span class="item-city" id-city="0" id-region="0" id-country="'.$findCountry["country_id"].'" data-alias="'.$findCountry["country_alias"].'" >'.$ULang->t("Все города").'</span>';
      }

      if( count($get) > 0 ){
         
           foreach ( $get  as $key => $value) {

              $list[] = '<span class="item-city" id-city="'.$value["city_id"].'" id-region="0" id-country="0" >'.$ULang->t( $value["city_name"] , [ "table"=>"geo", "field"=>"geo_name" ] ).'</span>';

           }
 
      }
     
      if($list){
         
         $part = ceil($limit / 3);
         $array_chunk = array_chunk($list, $part, true);

         foreach ( $array_chunk  as $key => $nested) {

           foreach ( $nested  as $key => $value) {

              $span .= $value;

           }
 
           $return .= '<div class="col-lg-4 col-md-6 col-sm-6 col-12" >'.$span.'</div>';
           $span = "";

         }

      }

     if($list_country_status){
        if($list_country){
           return '<div class="row" >'.$list_country.'</div> <hr> <div class="row modal-country-container" >' . $return . '</div>';
        }else{
           return '<div class="row modal-country-container" >' . $return . '</div>';
        }
     }else{
        return $return;
     }

   } 

   function cityDefaultFooter( $country_alias = "" ){
    global $settings;

    $ULang = new ULang();
    $Cache = new Cache();

    if( !$country_alias ) $country_alias = $settings["country_default"];

      if( !$Cache->get( [ "table" => "cityDefault", "key" => $country_alias ] ) ){

          if(!$settings["region_id"]){
            $get = getAll("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id WHERE `uni_city`.city_default = '1' and `uni_country`.country_status = '1' and `uni_country`.country_alias='".$country_alias."' order by city_count_view desc limit 40");
            if(!count($get)){
               $get = getAll("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id WHERE `uni_country`.country_status = '1' and `uni_country`.country_alias='".$country_alias."' order by city_count_view desc limit 40");
            }
          }else{
            $get = getAll("SELECT * FROM uni_city WHERE region_id='{$settings["region_id"]}' order by city_count_view desc limit 40");
          }

          $Cache->set( [ "table" => "cityDefault", "key" => $country_alias, "data" => $get ] );

      }else{
         $get = $Cache->get( [ "table" => "cityDefault", "key" => $country_alias ] );
      }


      if( count($get) > 0 ){
         
           foreach ( $get  as $key => $value) {

              $list[] = '<a href="'._link($value["city_alias"]).'" >'.$ULang->t( $value["city_name"] , [ "table"=>"geo", "field"=>"geo_name" ] ).'</a>';

           }
 
      }
     
      $list[] = '<a href="'._link("cities").'" class="link_active" >'.$ULang->t("Все города").'</a>';

      if( count($list) > 0 ){
         
         $part = ceil(count($list) / 4);
         $array_chunk = array_chunk($list, $part, true);

         foreach ( $array_chunk  as $key => $nested) {

           foreach ( $nested  as $key => $value) {

              $span .= $value;

           }
 
           $return .= '<div class="col-lg-3 col-md-6 col-sm-6 col-6" >'.$span.'</div>';
           $span = "";

         }

      }

      return '<div class="row" >' . $return . '</div>';

   }

   function listCity($data = []){
    global $settings;

      $ULang = new ULang();

      $limit = 30;
    
      if(!$settings["region_id"]){
        $get = getAll("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id WHERE `uni_city`.city_default = '1' and `uni_country`.country_status = '1' and `uni_country`.country_alias='".$settings["country_default"]."' order by city_count_view desc limit $limit");
        if(!count($get)){
           $get = getAll("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id WHERE `uni_country`.country_status = '1' and `uni_country`.country_alias='".$settings["country_default"]."' order by city_count_view desc limit $limit");
        }
      }else{
        $get = getAll("SELECT * FROM uni_city WHERE region_id='{$settings["region_id"]}' order by city_count_view desc limit $limit");
      }

      if(count($get) > 0){
         
         $part = ceil($limit / 4);
         $array_chunk = array_chunk($get, $part, true);

         foreach ( $array_chunk  as $key => $nested) {

           foreach ( $nested  as $key => $value) {

              $list .= '<a href="'._link( trim($value["city_alias"] . "/" . $data["category_board_chain"], "/") , true ).'" >'.$ULang->t( $value["city_name"] , [ "table"=>"geo", "field"=>"geo_name" ] ).'</a>';

           }

           $return .= '<div class="col-lg-3 col-md-3 col-sm-3 col-12" >'.$list.'</div>';
           $list = "";

         }

      }

     return $return;

   }

   function detect($param = array()){
    global $settings;

      if($settings["city_auto_detect"]){
         
         $result = $this->geoIp( $_SERVER["REMOTE_ADDR"] , true);

         if($result["city"]) $getCity = $this->getCity(0,$result["city"]); else $getCity = [];
         
             if( $settings["city_id"] ){
                  
                  if($getCity){

                      if( $settings["city_id"] == $getCity["city_id"] ){
                        return $this->set( array( "city_id" => $getCity["city_id"], "action" => "uri" ) );
                      }

                  }else{

                      $this->set( [ "city_id" => $settings["city_id"], "action" => "uri" ] );

                  }
                    
             }elseif( $settings["region_id"] ){
                  
                  if($getCity){

                      if( $settings["region_id"] == $getCity["region_id"] ){
                        return $this->set( array( "city_id" => $getCity["city_id"], "action" => "uri" ) );
                      }

                  }else{

                      $this->set( [ "region_id" => $settings["region_id"], "action" => "uri" ] );

                  }
                    
             }else{
                  
                  if($getCity){
                     return $this->set( array( "city_id" => $getCity["city_id"], "action" => "uri" ) ); 
                  }

             }

      }else{

          if($settings["city_id"]){
             $this->set( [ "city_id" => $settings["city_id"], "action" => "uri" ] );
          }elseif($settings["region_id"]){
             $this->set( [ "region_id" => $settings["region_id"], "action" => "uri" ] );
          }         

      }

     
   }

   function getCity($city_id = 0, $alias = ""){
      global $settings;

      if($settings["region_id"]) $where_region = "and `uni_region`.region_id = '{$settings["region_id"]}'"; else $where_region = "";

      if($city_id){

         return getOne("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id WHERE `uni_country`.country_status = '1' $where_region  and `uni_city`.city_id='".intval($city_id)."'");

      }elseif($alias){

         return getOne("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id WHERE `uni_country`.country_status = '1' $where_region  and `uni_city`.city_alias='".translite($alias)."'");

      }else{
         return [];
      }

   } 

  function geoIp($ip, $array=true){
     global $config;

     if(file_exists($config["basePath"].'/systems/libs/GeoIp/GeoLite2-City.mmdb')){

        try {

         $reader = new GeoIp2\Database\Reader($config["basePath"].'/systems/libs/GeoIp/GeoLite2-City.mmdb');

         if($ip){

           $record = $reader->city($ip);

           if($array){
               return array("city"=>$record->city->names['ru'],"region"=>$record->subdivisions[0]->names['ru'],"country"=>$record->country->names['ru']);
           }else{
               if(!empty($record->city->names['ru']) && !empty($record->subdivisions[0]->names['ru'])){
                 if($record->city->names['ru'] != $record->subdivisions[0]->names['ru']){
                    return $record->city->names['ru'].', '.$record->subdivisions[0]->names['ru'];
                 }else{
                    return $record->city->names['ru'];
                 }
               }elseif(!empty($record->city->names['ru'])){
                  return $record->city->names['ru'];
               }elseif(!empty($record->subdivisions[0]->names['ru'])){
                  return $record->subdivisions[0]->names['ru'];
               }elseif($record->country->names['ru']){
                  return $record->country->names['ru'];
               }else{
                  return '-';
               }         
           }

         }

        } catch (Exception $e) {}

     }

     return '-';

  }

  function userGeo( $array = [] ){

     if( $array["city_id"] ){
      $getCity = getOne("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id WHERE `uni_city`.city_id='".intval($array["city_id"])."'");
      return $getCity["city_name"] . ", " . $getCity["region_name"];
     }elseif( $array["ip"] ){
       return $this->geoIp($array["ip"], false);
     }else{
       return "-";
     }

  } 
  
  function vendorMap( $latitude = "", $longitude = "" ){
    global $settings;

      if($settings["map_vendor"] == "google"){

        $js = '<script src="https://maps.googleapis.com/maps/api/js?key='.$settings["map_google_key"].'&libraries=places"></script><script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>';

        if( $latitude && $longitude ){
           
           $js .= '
            <script type="text/javascript">

                function initMap() {
                  var myLatLng = {lat: '.$latitude.', lng: '.$longitude.'};
                  var map = new google.maps.Map(document.getElementById("mapAd"), {
                    zoom: 14,
                    center: myLatLng
                  });
                  var marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map
                  });
                }
                
                google.maps.event.addDomListener(window, "load", initMap);

            </script>
           ';

        }

      }elseif($settings["map_vendor"] == "yandex"){
        
        $js = '<script src="//api-maps.yandex.ru/2.1/?apikey='.$settings["map_yandex_key"].'&lang=ru_RU" type="text/javascript"></script>';
        
        if( $latitude && $longitude ){

            $js .= '
                <script type="text/javascript">
                    ymaps.ready(init);

                    var myMap, 
                        myPlacemark;

                    $( window ).resize(function() {
                      myMap.destroy();  
                      ymaps.ready(init);
                    });
            
                    function init(){ 
                        myMap = new ymaps.Map("mapAd", {
                            center: ['.$latitude.', '.$longitude.'],
                            zoom: 14
                        }); 
                        myMap.behaviors.enable("scrollZoom");

                        myPlacemark = new ymaps.Placemark(['.$latitude.', '.$longitude.']);
                        
                        myMap.geoObjects.add(myPlacemark);
                    }
                </script>
            ';

        }

      }elseif($settings["map_vendor"] == "openstreetmap"){

        $js = '
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
              integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
              crossorigin=""/>
            <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
              integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
              crossorigin=""></script>
        ';
        
        if( $latitude && $longitude ){

            $js .= '
              <script type="text/javascript">
              
              function loadOpenstreetmap(){

                var mymap = L.map("mapAd").setView(['.$latitude.', '.$longitude.'], 13);

                L.tileLayer("https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token='.$settings["map_openstreetmap_key"].'", {
                  maxZoom: 18,
                  attribution: `Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>`,
                  id: "mapbox/streets-v11",
                  tileSize: 512,
                  zoomOffset: -1
                }).addTo(mymap);

                L.marker(['.$latitude.', '.$longitude.']).addTo(mymap);

              }

              $(document).ready(function () {

               loadOpenstreetmap();

              });

              </script>
            ';

        }        

      }

      return $js;

  }

  function metrics_visit_add( $enter,$referrer,$title,$unique_visit ){

      $insert_id = insert("INSERT INTO uni_metrics(ip,view_page_link,referrer,user_agent,route,view_page_title,id_user,date,date_view,unique_visit)VALUES(?,?,?,?,?,?,?,?,?,?)", array($_SERVER['REMOTE_ADDR'],$enter,$referrer,$_SERVER['HTTP_USER_AGENT'],"",$title,intval($_SESSION["profile"]["id"]),date("Y-m-d H:i:s"),date("Y-m-d H:i:s"),intval($unique_visit)));  
      $_SESSION["metrics_visit"] = $insert_id;
      setcookie('metrics_visit',$insert_id,time()+3600*24*31, "/"); 

  }

  function metrics(){
    global $config,$settings;

    if(!isset($_SESSION["geo"]["data"])){
        $this->detect();
    }
    
    $enter = $config["urlPath"] . "/" . trim($_SERVER['REQUEST_URI'], "/");
    $referrer = $_SERVER['HTTP_REFERER'];
    $title = $enter;

    $_SESSION["metrics_route"] = [];

    if(detectRobots($_SERVER['HTTP_USER_AGENT']) == false){
         
         $getIp = findOne("uni_metrics","ip=? order by date desc", [ clear( $_SERVER["REMOTE_ADDR"] ) ] );
         
         if( $getIp ){
            $id = $getIp->id;
         }elseif($_SESSION["metrics_visit"]){
            $id = (int)$_SESSION["metrics_visit"];
         }elseif($_COOKIE["metrics_visit"]){
            $id = (int)$_COOKIE["metrics_visit"];
         }else{
            $id = 0;
         }       
        
        if(!$id){ 

          $this->metrics_visit_add( $enter,$referrer,$title,1 );

        }else{ 
           
          $getVisit = findOne("uni_metrics", "id=?", [$id]);
          
          if( $getVisit ){
            if( date( "Y-m-d", strtotime( $getVisit->date ) ) != date( "Y-m-d" ) ){
               $this->metrics_visit_add( $enter,$referrer,$title,0 );             
            }else{
               update("UPDATE uni_metrics SET view_page_link=?,route=?,user_agent=?,id_user=?,view_page_title=?,date_view=? WHERE id=?", array( $enter,"",$_SERVER['HTTP_USER_AGENT'],intval($_SESSION["profile"]["id"]),$title,date("Y-m-d H:i:s"),$id ));
            }          
          }else{
            $this->metrics_visit_add( $enter,$referrer,$title,1 );          
          }

        }
        
    }

  }

  function aliasCheckOut($alias = ""){
      global $settings;

      if($settings["region_id"]) $where_region = "and `uni_region`.region_id = '{$settings["region_id"]}'"; else $where_region = "";

      $data = getOne("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id WHERE `uni_country`.country_status = '1' $where_region  and `uni_city`.city_alias='".translite($alias)."'");
      
      if(!$data){

        $data = getOne("SELECT * FROM uni_region INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_region`.country_id WHERE `uni_country`.country_status = '1' $where_region  and `uni_region`.region_alias='".translite($alias)."'");

      }

      if(!$data){

         $data = getOne("SELECT * FROM uni_country WHERE country_status = '1' and country_alias='".translite($alias)."'"); 
      } 

      return $data;  

  }

  function aliasOneOf($alias = ""){
      global $settings;

      $data = getOne("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id WHERE `uni_country`.country_status = '1' and `uni_city`.city_alias='".translite($alias)."'");
      
      if(!$data){

        $data = getOne("SELECT * FROM uni_region INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_region`.country_id WHERE `uni_country`.country_status = '1' and `uni_region`.region_alias='".translite($alias)."'");

      }else{
        
        return ["name"=>$data["city_name"], "id"=>$data["city_id"], "table"=>"city"];

      }

      if(!$data){

         $data = getOne("SELECT * FROM uni_country WHERE country_status = '1' and country_alias='".translite($alias)."'"); 
         return ["name"=>$data["country_name"], "id"=>$data["country_id"], "table"=>"country"];

      }else{

         return ["name"=>$data["region_name"], "id"=>$data["region_id"], "table"=>"region"];

      }
  
  }

  function current(){
      $geo = trim($_SERVER['REQUEST_URI'], "/");
      if($_SESSION["geo"]["alias"]){
        return $_SESSION["geo"]["alias"];
      }elseif($geo){
        return explode( "/" , $geo )[0];
      }
  }

  function search($query = ""){
    global $settings;
    
    if($settings["city_id"]){
              
      $where = "and `uni_city`.city_id = '{$settings["city_id"]}'";

    }elseif($settings["region_id"]){
      
      $where = "and `uni_region`.region_id = '{$settings["region_id"]}'";

    }

    $get = getAll("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id WHERE `uni_country`.country_status = '1' $where and `uni_city`.city_name LIKE '%".$query."%' order by city_name asc");

    if(!$get){

      $get = getAll("SELECT * FROM uni_region INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_region`.country_id WHERE `uni_country`.country_status = '1' $where and `uni_region`.region_name LIKE '%".$query."%' order by region_name asc");

      if(!$get){

             $get = getAll("SELECT * FROM uni_country WHERE country_status = '1' and country_name LIKE '%".$query."%' order by country_name asc");

      }

    }

    return $get;

  }

  function outOptionsMetro( $data = [] ){

     if(isset($data["city_metro"])){
       $length = floor(count($data["city_metro"]) / 2);
       $array_chunk = array_chunk($data["city_metro"], $length ? $length : 1, true);

       foreach ($array_chunk as $key => $nested) {
         ?>
         
         <div class="col-lg-6 col-6" >
         <?php
         foreach ($nested as $key => $value) {

             $getMetro = findOne("uni_metro", "id=?", [$value["parent_id"]]);

             $checked = '';

             if( is_array($data["param_filter"]["filter"]["metro"]) ){
                 if( in_array($value["id"], $data["param_filter"]["filter"]["metro"]) ){ $checked = 'checked=""'; }
             }else{
                 if( $value["id"] == $data["param_filter"]["filter"]["metro"] ){ $checked = 'checked=""'; } 
             }

             ?>

                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="filter[metro][]" class="custom-control-input" <?php echo $checked; ?>  id="flMetro<?php echo $value["id"]; ?>" value="<?php echo $value["id"]; ?>" >
                     <label class="custom-control-label" for="flMetro<?php echo $value["id"]; ?>"> <?php echo $value["name"]; ?> <i class="metro-station-color" style="background-color:<?php echo $getMetro["color"]; ?>;" ></i> <?php echo $getMetro["name"]; ?> </label>
                </div>                        

             <?php
         }
         ?>
         </div>
         
         <?php
       }
     }

  }

  function outOptionsArea( $data = [] ){

     $ULang = new ULang();

     if(isset($data["city_areas"])){
       $length = floor(count($data["city_areas"]) / 2.7);
       $array_chunk = array_chunk($data["city_areas"], $length ? $length : 1, true);

       foreach ($array_chunk as $key => $nested) {
         ?>
         
         <div class="col-lg-4 col-6" >
         <?php
         foreach ($nested as $key => $value) {

             $checked = '';

             if( is_array($data["param_filter"]["filter"]["area"]) ){
                 if( in_array($value["city_area_id"], $data["param_filter"]["filter"]["area"]) ){ $checked = 'checked=""'; }
             }else{
                 if( $value["city_area_id"] == $data["param_filter"]["filter"]["area"] ){ $checked = 'checked=""'; } 
             }

             ?>

                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="filter[area][]" class="custom-control-input" <?php echo $checked; ?>  id="flArea<?php echo $value["city_area_id"]; ?>" value="<?php echo $value["city_area_id"]; ?>" >
                    <label class="custom-control-label" for="flArea<?php echo $value["city_area_id"]; ?>"><?php echo $ULang->t( $value["city_area_name"] , [ "table"=>"uni_city_area", "field"=>"city_area_name" ] ); ?></label>
                </div>                        

             <?php
         }
         ?>
         </div>
         
         <?php
       }
     }

  }

  function outGeoDeclination($declination=''){
    global $settings;

    $ULang = new ULang();

    if($settings["main_type_products"] == 'physical'){

        if($declination) return $ULang->t($declination, [ "table" => "geo", "field" => "geo_name" ] );

        if(isset($_SESSION["geo"]["data"]["city_declination"])){
            return $ULang->t($_SESSION["geo"]["data"]["city_declination"], [ "table" => "geo", "field" => "geo_name" ] );
        }elseif(isset($_SESSION["geo"]["data"]["region_declination"])){
            return $ULang->t($_SESSION["geo"]["data"]["region_declination"], [ "table" => "geo", "field" => "geo_name" ] );
        }elseif(isset($_SESSION["geo"]["data"]["country_declination"])){
            return $ULang->t($_SESSION["geo"]["data"]["country_declination"], [ "table" => "geo", "field" => "geo_name" ] );
        }
    
    }

  }

  function geolocation($ip=""){

   global $config;

   if(file_exists($config["basePath"].'/systems/libs/GeoIp/GeoLite2-City.mmdb')){

       $reader = new GeoIp2\Database\Reader($config["basePath"].'/systems/libs/GeoIp/GeoLite2-City.mmdb');

       if($ip){

         $record = $reader->city($ip);

         if($record){
             return array("iso"=>$record->country->isoCode,"city"=>$record->city->names['ru'],"region"=>$record->subdivisions[0]->names['ru'],"country"=>$record->country->names['ru']);
         }

       }

   }
 
}

function searchAddressByLatLon($lat=0, $lon=0){

   if($lat && $lon){

      $curl=curl_init('https://nominatim.openstreetmap.org/reverse?lat='.$lat.'&lon='.$lon.'&format=json&accept-language=ru');

      curl_setopt_array($curl,array(
            CURLOPT_USERAGENT=>'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:60.0) Gecko/20100101 Firefox/60.0',
            CURLOPT_ENCODING=>'gzip, deflate',
            CURLOPT_RETURNTRANSFER=>1,
            CURLOPT_HTTPHEADER=>array(
                  'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                  'Accept-Language: en-US,en;q=0.5',
                  'Accept-Encoding: gzip, deflate',
                  'Connection: keep-alive',
                  'Upgrade-Insecure-Requests: 1',
            ),
      ));

      $results_decode = json_decode(curl_exec($curl), true);

      if($results_decode){

         if(isset($results_decode["address"]["country_code"])) unset($results_decode["address"]["country_code"]);
         if(isset($results_decode["address"]["country"])) unset($results_decode["address"]["country"]);
         if(isset($results_decode["address"]["postcode"])) unset($results_decode["address"]["postcode"]);
         if(isset($results_decode["address"]["region"]))  unset($results_decode["address"]["region"]);
         if(isset($results_decode["address"]["state"])) unset($results_decode["address"]["state"]);
         if(isset($results_decode["address"]["city"])) unset($results_decode["address"]["city"]);
         if(isset($results_decode["address"]["region"])) unset($results_decode["address"]["region"]);
         if(isset($results_decode["address"]["ISO3166-2-lvl3"])) unset($results_decode["address"]["ISO3166-2-lvl3"]);
         if(isset($results_decode["address"]["ISO3166-2-lvl4"])) unset($results_decode["address"]["ISO3166-2-lvl4"]);

         if($results_decode["address"]){
            return implode(', ',$results_decode["address"]);
         }
         
      }

   }

}

 
}


?>