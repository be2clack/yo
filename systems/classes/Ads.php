<?php

class Ads{
   
   function get($query = "", $param = array() ){
        global $settings;

        if($query) $query = " where " . $query;
        
        if($settings["main_type_products"] == 'physical'){
            return getOne("SELECT * FROM `uni_ads` 
            INNER JOIN `uni_city` ON `uni_city`.city_id = `uni_ads`.ads_city_id
            INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_ads`.ads_region_id
            INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_ads`.ads_country_id
            INNER JOIN `uni_category_board` ON `uni_category_board`.category_board_id = `uni_ads`.ads_id_cat
            INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user $query", $param);
        }else{
            return getOne("SELECT * FROM `uni_ads` 
            INNER JOIN `uni_category_board` ON `uni_category_board`.category_board_id = `uni_ads`.ads_id_cat
            INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user $query", $param);
        }

   }

   function getCount($query = "", $param = array() ){

      $Cache = new Cache();

      $getCache = $Cache->get( [ "table" => "uni_ads", "key" => $query ] );

      if( $getCache !== false ){

         return (int)$getCache;

      }else{
         
         $result = (int)getOne("SELECT count(*) as total FROM `uni_ads` 
        INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user where $query", $param)["total"];

         $Cache->set( [ "table" => "uni_ads", "key" => $query, "data" => $result ] );

         return $result;

      }
        
   }

   function getAll( $array = [], $param = [], $caching = false){
      global $settings,$config;

      $Elastic = new Elastic();
      $Cache = new Cache();

        if(!isset($array["output"])) $array["output"] = $settings["catalog_out_content"];
      
        if(isset($array["param_search"]) && $config["elasticsearch"]["status"]){

            if(isset($array["navigation"])){
                if($array["navigation"] == true){
                    $from = $Elastic->navigationOffset( $array["page"], $array["output"] );
                }else{
                    $from = 0;
                }
            }else{
               $from = 0;
            }

            $results = $Elastic->search( [ "index" => "uni_ads", "type" => "ad", "from" => $from, "size" => $array["output"], "body" => $array["param_search"] ] );

            if($results){

              return array("count"=>intval($results['hits']['total']['value']), "all"=>$Elastic->array_map( $results['hits']['hits'] ));

            }

        }

        if(isset($array["query"])){
            if(isset($array["sort"])){
                $array["query"] = " where " . $array["query"] . " " . $array["sort"];
            }else{
                $array["query"] = " where " . $array["query"];
            }
        }else{
            if(isset($array["sort"])){
                $array["query"] = $array["sort"];
            }
        }

        if($caching){

             $key = "count_" . $array["query"] . '_' . implode(',', $param);

             if( $Cache->get( [ "table" => "uni_ads", "key" => $key ] ) ){
                $getOne = $Cache->get( [ "table" => "uni_ads", "key" => $key ] );
             }else{
                $getOne = getOne("select count(*) as total from uni_ads INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user {$array["query"]}",$param);
                $Cache->set( [ "table" => "uni_ads", "key" => $key, "data" => $getOne ] );
             }

             if(isset($array["navigation"])){ 
                if($array["navigation"] == true){

                     $key = $array["query"] . '_' . navigation_offset( array( "count"=>$getOne["total"], "output"=>$array["output"], "page"=>$array["page"] ) ) . '_' . implode(',', $param);

                     if( $Cache->get( [ "table" => "uni_ads", "key" => $key ] ) ){
                       $getAll = $Cache->get( [ "table" => "uni_ads", "key" => $key ] );
                     }else{
                        $getAll = getAll("select * from uni_ads INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user {$array["query"]} ".navigation_offset( array( "count"=>$getOne["total"], "output"=>$array["output"], "page"=>$array["page"] ) ),$param);
                        $Cache->set( [ "table" => "uni_ads", "key" => $key, "data" => $getAll ] );
                     }

                }else{

                     $key = $array["query"] . '_' . implode(',', $param);

                     if( $Cache->get( [ "table" => "uni_ads", "key" => $key ] ) ){
                        $getAll = $Cache->get( [ "table" => "uni_ads", "key" => $key ] );
                     }else{
                        $getAll = getAll("select * from uni_ads INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user {$array["query"]} ",$param);
                        $Cache->set( [ "table" => "uni_ads", "key" => $key, "data" => $getAll ] );
                     }

                }
             }else{

                 $key = $array["query"] . '_' . implode(',', $param);

                 if( $Cache->get( [ "table" => "uni_ads", "key" => $key ] ) ){
                   $getAll = $Cache->get( [ "table" => "uni_ads", "key" => $key ] );
                 }else{
                    $getAll = getAll("select * from uni_ads INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user {$array["query"]} ",$param);
                    $Cache->set( [ "table" => "uni_ads", "key" => $key, "data" => $getAll ] );
                 }

             }

             return array("count"=>$getOne["total"], "all"=>$getAll, "key_caching"=>$key);

        }else{

             $getOne = getOne("select count(*) as total from uni_ads INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user {$array["query"]}",$param);

             if(isset($array["navigation"])){ 
                if($array["navigation"] == true){
                     $getAll = getAll("select * from uni_ads INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user {$array["query"]} ".navigation_offset( array( "count"=>$getOne["total"], "output"=>$array["output"], "page"=>$array["page"] ) ),$param);
                }else{
                     $getAll = getAll("select * from uni_ads INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user {$array["query"]} ",$param);
                }
             }else{
                 $getAll = getAll("select * from uni_ads INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user {$array["query"]} ",$param);
             }

             return array("count"=>$getOne["total"], "all"=>$getAll);

        }

   }

   function getDataAd($data){

        $Cache = new Cache();

        if($data){

             $key = "city_id=".$data["ads_city_id"];
             $array = $Cache->get( [ "table" => "uni_city", "key" => $key ] );

             if( $array ){
                $getCity = $array;
             }else{
                $getCity = getOne("select * from uni_city where city_id=?", [ $data["ads_city_id"] ]);
                $Cache->set( [ "table" => "uni_city", "key" => $key, "data" => $getCity ] );
             }

             $key = "region_id=".$data["ads_region_id"];
             $array = $Cache->get( [ "table" => "uni_region", "key" => $key ] );

             if( $array ){
                $getRegion = $array;
             }else{
                $getRegion = getOne("select * from uni_region where region_id=?", [ $data["ads_region_id"] ]);
                $Cache->set( [ "table" => "uni_region", "key" => $key, "data" => $getRegion ] );
             }

             $key = "country_id=".$data["ads_country_id"];
             $array = $Cache->get( [ "table" => "uni_country", "key" => $key ] );

             if( $array ){
                $getCountry = $array;
             }else{
                $getCountry = getOne("select * from uni_country where country_id=?", [ $data["ads_country_id"] ]);
                $Cache->set( [ "table" => "uni_country", "key" => $key, "data" => $getCountry ] );
             }             

             $key = "category_board_id=".$data["ads_id_cat"];
             $array = $Cache->get( [ "table" => "uni_category_board", "key" => $key ] );

             if( $array ){
                $getCategory = $array;
             }else{
                $getCategory = getOne("select * from uni_category_board where category_board_id=?", [ $data["ads_id_cat"] ]);
                $Cache->set( [ "table" => "uni_category_board", "key" => $key, "data" => $getCategory ] );
             }            

             if($getCity) $data = array_merge($data, $getCity);
             if($getRegion) $data = array_merge($data, $getRegion);
             if($getCountry) $data = array_merge($data, $getCountry);
             if($getCategory) $data = array_merge($data, $getCategory);

             return $data;
        }

        return [];

   }

   function getImages($json = ""){
       $result = [];
       if($json && $json != "[]"){
          $result = json_decode(urldecode($json), true);
          if($result){
            return $result;
          }
       }
       return [];
   }


   function outSorting(){
     global $config;

     $ULang = new ULang();

     $explode_request = explode("?", trim($_SERVER['REQUEST_URI'], "/") );

     parse_str( $explode_request[1] , $query_params);

     unset($query_params["filter"]["sort"]);

     $params = http_build_query($query_params, 'flags_');

     if( $params ){
        $link = $config["urlPath"] . "/" . $explode_request[0] . "?" . $params . "&";
     }else{
        $link = $config["urlPath"] . "/" . $explode_request[0] .  "?";
     }

     if($_GET["filter"]["sort"] == "default"){
        $title = $ULang->t("По умолчанию");
     }elseif($_GET["filter"]["sort"] == "news"){
        $title = $ULang->t("По новизне");
     }elseif($_GET["filter"]["sort"] == "price"){
        $title = $ULang->t("По стоимости");
     }else{
        $title = $ULang->t("По умолчанию");
     }

        return '
             <div class="uni-dropdown uni-dropdown-align" >
                <span class="uni-dropdown-name" > <span>'.$title.'</span> <i class="las la-angle-down"></i> </span>
                <div class="uni-dropdown-content" >
                   <a href="'.$link.'filter[sort]=default" >'.$ULang->t("По умолчанию").'</a>
                   <a href="'.$link.'filter[sort]=news" >'.$ULang->t("По новизне").'</a>
                   <a href="'.$link.'filter[sort]=price" >'.$ULang->t("По стоимости").'</a>
                </div>
             </div>        
        ';
        
   }

   function viewAds($id = 0, $id_user = 0){
      if(detectRobots($_SERVER['HTTP_USER_AGENT']) == false){
        if($id){    
            if(empty($_SESSION["view-ads"][$id])){ 
              insert("INSERT INTO uni_ads_views(ads_views_id_ad,ads_views_date,ads_views_id_user)VALUES(?,?,?)", array($id,date("Y-m-d H:i:s"),$id_user)); 
              update("update uni_ads set ads_count_view=ads_count_view+1 where ads_id=?", [$id]); 
              $_SESSION["view-ads"][$id] = 1;
            }  
        }
      }   
   }

   function viewAdsUser($id = 0, $id_user = 0){
        if($id && $id_user){  
            $get = findOne("uni_ads_views_user", "ads_id=? and user_id=?", [$id,$id_user]);  
            if(!$get){
                insert("INSERT INTO uni_ads_views_user(ads_id,time_create,user_id)VALUES(?,?,?)", array($id,date("Y-m-d H:i:s"),$id_user));  
            }
        }  
   }

   function clickKeyword($id = 0){
      if(detectRobots($_SERVER['HTTP_USER_AGENT']) == false){
        if($id){    
            if(empty($_SESSION["click-keyword"][$id])){ 
              update("UPDATE uni_ads_keywords SET ads_keywords_count_click=ads_keywords_count_click+1 WHERE ads_keywords_id=?", array($id)); 
              $_SESSION["click-keyword"][$id] = 1;
            }  
        }
      }   
   }

   function addUserKeyword($text = ''){ 
      $text = clearSearch($text);
      if(detectRobots($_SERVER['HTTP_USER_AGENT']) == false){
        if($text){    
            if(!isset($_SESSION["user-search-keyword"][$text])){ 
               $find = findOne('uni_ads_keywords_users', 'ads_keywords_users_text=?', [$text]);
               if($find){
                  update("UPDATE uni_ads_keywords_users SET ads_keywords_users_count_view=ads_keywords_users_count_view+1 WHERE ads_keywords_users_id=?", array($find['ads_keywords_users_id']));
               }else{
                  insert("INSERT INTO uni_ads_keywords_users(ads_keywords_users_text,ads_keywords_users_ip,ads_keywords_users_date)VALUES(?,?,?)", array($text,$_SERVER['REMOTE_ADDR'],date("Y-m-d H:i:s")));
               }
               $_SESSION["user-search-keyword"][$text] = 1;
            }  
        }
      }   
   }

   function getCountView($id, $date = ""){
      if($date){
        return (int)getOne("select count(*) as total from uni_ads_views where ads_views_id_ad=? and date(ads_views_date) = ?", [$id,$date])["total"];
      }else{
        $getAd = findOne("uni_ads", "ads_id=?", [$id]);
        return (int)$getAd["ads_count_view"];
      }
   }

   function getDisplayView($id, $date = ""){
      if($date){
         $get = findOne('uni_ads_views_display','ads_views_display_id_ad=? and date(ads_views_display_date)=?',[$id,$date]);
      }else{
         $get = findOne('uni_ads_views_display','ads_views_display_id_ad=?',[$id]);
      }   
      return (int)$get['ads_views_display_count'];
   }

   function alias($array=array()){   
      global $settings;
      if($settings["main_type_products"] == 'physical'){
         return _link($array["city_alias"]."/".$array["category_board_alias"]."/".$array["ads_alias"]."-".$array["ads_id"]);
      }else{
         return _link($array["category_board_alias"]."/".$array["ads_alias"]."-".$array["ads_id"]);
      }
   }  

   function statusCount($status){
    
    if($status == 1){
      return $this->getCount( "ads_status='0' and clients_status='1'" );
    }elseif($status == 2){
      return $this->getCount( "ads_period_publication > now() and ads_status='1' and clients_status='1'" ); 
    }elseif($status == 3){
      return $this->getCount( "(ads_status = '3' or clients_status='2') and clients_status!='3' and ads_status!='8'" );
    }elseif($status == 4){
      return $this->getCount( "(ads_period_publication < now() and ads_status='1') or ads_status = '2'" );
    }elseif($status == 5){
      return $this->getCount( "ads_status = '4' and clients_status='1'" );
    }elseif($status == 6){
      return $this->getCount( "ads_status = '5'" );
    }elseif($status == 7){
      return $this->getCount( "ads_status = '6' and clients_status='1'" );
    }elseif($status == 8){
      return $this->getCount( "ads_status = '7' and clients_status='1'" ); 
    }elseif($status == 9){
      return $this->getCount( "(ads_images='' or ads_images='null' or ads_images='[]') and clients_status!='3' and ads_status!='8'" ); 
    }elseif($status == 11){
      return $this->getCount( "ads_status='8'" ); 
    }

        
   }
   
  function validationAdForm($array = [], $extra = []){
    global $settings;

        $Filters = new Filters();
        $ULang = new Ulang();

        $error = array();

        if(!intval($array["c_id"])){ $error["c_id"] = $ULang->t("Пожалуйста, выберите категорию"); }else{
           if(!isset($extra["categories"]["category_board_id"][$array["c_id"]]) || isset($extra["categories"]["category_board_id_parent"][$array["c_id"]])){
              $error["c_id"] = $ULang->t("Пожалуйста, выберите категорию");
           }
        }
        
        if( !$extra["categories"]["category_board_id"][$array["c_id"]]["category_board_auto_title"] ){
            if(empty($array["title"])){ $error["title"] = $ULang->t("Пожалуйста, укажите заголовок объявления"); }
        }
        
        if($settings["main_type_products"] == 'electron'){
            if(empty($array["electron_product_links"])){ $error["electron_product_links"] = $ULang->t("Пожалуйста, укажите ссылку на электронный товар"); }
            $price = round(preg_replace('/\s/', '', $array["price"]),2);
            if(!$price){
                $error["price"] = $ULang->t("Пожалуйста, укажите цену");
            }
        }

        if($extra["categories"]["category_board_id"][$array["c_id"]]["category_board_measures_price"]){
            if($extra["categories"]["category_board_id"][$array["c_id"]]["category_board_rules"]["measure_booking"]){
                if($array["booking"]){
                    if(empty($array["measure"])){ $error["measure"] = $ULang->t("Пожалуйста, выберите вариант измерения"); }
                }
            }else{
                if(empty($array["measure"])){ $error["measure"] = $ULang->t("Пожалуйста, выберите вариант измерения"); }
            }
        }

        if( $settings["ad_create_always_image"] ){
            if(!$_POST["gallery"]){
              $error["gallery"] = $ULang->t("Загрузите хотя бы одну фотографию");
            }
        }

        if(empty($array["text"])){ $error["text"] = $ULang->t("Пожалуйста, укажите описание объявления"); }

        if($array["always"]){
          foreach($array["always"] AS $alw_id=>$alw_name){
             if(empty($array["filter"][$alw_id]) || $array["filter"][$alw_id][0] == ""){
                $error["filter".$alw_id] = $ULang->t("Обязательно для заполнения");
             }else{
                $value = $Filters->getInputValue($alw_id);
                if($value){
                   if( $array["filter"][$alw_id][0] < $value["min"] || $array["filter"][$alw_id][0] > $value["max"] ){
                      $error["filter".$alw_id] = $ULang->t("Укажите значение от") . " " . $value["min"] . " " . $ULang->t("до") . " " . $value["max"];
                   }
                }
             }
          }
        }

        if($settings["main_type_products"] == 'physical'){ 

            if(!$settings["city_id"]){
              if(empty($array["city_id"])){ $error["city_id"] = $ULang->t("Пожалуйста, укажите город"); }else{
                $getCity = findOne("uni_city","city_id=?", array(intval($array["city_id"])));
                if(count($getCity) == 0){
                  $error["city_id"] = $ULang->t("Пожалуйста, укажите город");
                }
              }
            }

            if($_SESSION['profile']['data']['clients_delivery_status']){
              if($array["delivery_status"]){
                 if(!intval($array["delivery_weight"])){
                    $error["delivery_weight"] = $ULang->t("Пожалуйста, укажите вес товара");
                 }else{
                    if(intval($array["delivery_weight"]) < $settings['delivery_weight_min']){
                       $error["delivery_weight"] = $ULang->t("Вес не должен быть меньше ".$settings['delivery_weight_min']." грамм");
                    }elseif(intval($array["delivery_weight"]) > $settings['delivery_weight_max']){
                      $error["delivery_weight"] = $ULang->t("Вес не должен быть больше ".$settings['delivery_weight_max']." грамм");
                    }
                 }
              }
            }

        }

        if( $array["action"] == "ads/ad_create" ){

            if( $settings["ad_create_phone"] ){
                if( $_SESSION["profile"]["id"] ){
                    if( !$_SESSION["profile"]["data"]["clients_phone"] ){
                         if( !$_SESSION["create-verify-phone"]["phone"] ){
                              $error["phone"] = $ULang->t("Пожалуйста, подтвердите номер телефона");
                         }
                    }
                }
            }
        
        }

        if(count($error) == 0){
          return array();
        }else{
          return $error;
        }

  }

  function mapAdAddress($lat = 0, $lon = 0){
    global $settings;

    if(!$lat) $lat = $settings["country_lat"];
    if(!$lon) $lon = $settings["country_lng"];

     if($settings["map_vendor"] == "yandex"){

      ?>
      <script type="text/javascript">
      var myMap;
      var search_result = [];

      ymaps.ready(function () {

          myMap = new ymaps.Map("mapAddress", {
              center: [<?php echo $lat; ?>, <?php echo $lon; ?>],
              zoom: 12,
              behaviors: ['default', 'scrollZoom']
          });
          myMap.controls.add('zoomControl');

      });


      $(document).ready(function(){

       var url_path = $("body").data("prefix");
       var loadEnabled = false;

       $(document).on('input click','.searchMapAddress', function () {
          
          if(loadEnabled == false){
              loadEnabled = true;

              $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "query="+$(this).val()+"&city_id="+$("input[name=city_id]").val()+"&action=geo/search_address_by_api",dataType: "html",cache: false,success: function (data) {

                if(data){
                  $(".SearchAddressResults").html(data).show();
                }else{
                  $(".SearchAddressResults").html('').hide();
                }

                loadEnabled = false;

              }}); 
          }

       });

       $(document).on('click','.SearchAddressResults .item-city', function () {    
       
              var myPlacemark = new ymaps.Placemark([$(this).data('lat'), $(this).data('lon')]);

              $(".searchMapAddress").val($(this).html());

              $('input[name="map_lat"]').val($(this).data('lat'));
              $('input[name="map_lon"]').val($(this).data('lon'));

              myMap.geoObjects.removeAll();
              myMap.geoObjects.remove();
              myMap.geoObjects.add(myPlacemark);

              myMap.events.add('click', function (e) {
                  var coords = e.get('coords');

                  myPlacemark.geometry.setCoordinates(e.get("coords"));

                  ymaps.geocode(coords).then(function(res) {
                      var first = res.geoObjects.get(0);
                      $(".searchMapAddress").val(first.properties.get('text'));
                      $('input[name="map_lat"]').val(coords[0]);
                      $('input[name="map_lon"]').val(coords[1]);                            
                  });
              });

              myMap.setCenter([$(this).data('lat'), $(this).data('lon')], 13);
         
              $(".SearchAddressResults").hide();

       }); 

      });
      </script>
      <?php

     }elseif($settings["map_vendor"] == "google"){

       ?>

       <script type="text/javascript">

        var url_path = $("body").data("prefix");
        var loadEnabled = false;
        var a,lat,long;

        $(document).on('input click','.searchMapAddress', function () {
          
          if(loadEnabled == false){
              loadEnabled = true;

              $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "query="+$(this).val()+"&city_id="+$("input[name=city_id]").val()+"&action=geo/search_address_by_api",dataType: "html",cache: false,success: function (data) {

                if(data){
                  $(".SearchAddressResults").html(data).show();
                }else{
                  $(".SearchAddressResults").html('').hide();
                }

                loadEnabled = false;

              }}); 
          }

        });

        $(document).on('click','.SearchAddressResults .item-city', function () {    
       
            $(".searchMapAddress").val($(this).html());

            $('input[name="map_lat"]').val($(this).data('lat'));
            $('input[name="map_lon"]').val($(this).data('lon'));

            initialize($(this).data('lat'),$(this).data('lon'));
        
            $(".SearchAddressResults").hide();

        });

        function initialize(lat=0,lon=0) {

            if(lat && lon){
                var myLatlng = new google.maps.LatLng(lat, lon);
            }else{
                var myLatlng = new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lon; ?>);
            }
            
            var myOptions = {
                zoom: 12,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                animation:google.maps.Animation.BOUNCE
            };
            map = new google.maps.Map(document.getElementById("mapAddress"), myOptions);

            var marker = new google.maps.Marker({
                draggable: true,
                position: myLatlng,
                map: map
            });

            google.maps.event.addListener(marker, 'dragend', function (event) {

                $("input[name=map_lat]").val(event.latLng.lat());
                $("input[name=map_lon]").val(event.latLng.lng());

                geocoder.geocode({
                  'latLng': event.latLng
                }, function(results, status) {

                  if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                      $(".searchMapAddress").val(results[0].formatted_address);
                    }
                  }

                });

            });

            var geocoder = new google.maps.Geocoder();

            google.maps.event.addListener(map, 'click', function(event) {

              marker.setPosition(event.latLng);

              $("input[name=map_lat]").val(event.latLng.lat());
              $("input[name=map_lon]").val(event.latLng.lng());

              geocoder.geocode({
                'latLng': event.latLng
              }, function(results, status) {

                if (status == google.maps.GeocoderStatus.OK) {
                  if (results[0]) {
                    $(".searchMapAddress").val(results[0].formatted_address);
                  }
                }

              });

            });

             
        };

        google.maps.event.addDomListener(window, 'load', initialize);

       </script>

       <?php

     }elseif($settings["map_vendor"] == "openstreetmap"){

       ?>

       <script type="text/javascript">
          
          $(document).ready(function () {

          var search_result = [];
          var searchTimeout = null; 
          var listSearch = []; 
          var collationListSearch = []; 
          var url_path = $("body").data("prefix");
          var loadEnabled = false;
        
          $(document).on('input click','.searchMapAddress', function () {
          
            if(loadEnabled == false){
                loadEnabled = true;

                $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "query="+$(this).val()+"&city_id="+$("input[name=city_id]").val()+"&action=geo/search_address_by_api",dataType: "html",cache: false,success: function (data) {

                  if(data){
                    $(".SearchAddressResults").html(data).show();
                  }else{
                    $(".SearchAddressResults").html('').hide();
                  }

                  loadEnabled = false;

                }}); 
            }

          });
          
          var map = null;
          var marker = null;
          var markersList = [];

          map = L.map('mapAddress').setView([<?php echo $lat; ?>, <?php echo $lon; ?>], 12);

          L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/streets-v11/tiles/{z}/{x}/{y}?access_token=<?php echo $settings["map_openstreetmap_key"]; ?>', {
              attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
          }).addTo(map);

          function centerLeafletMapOnMarker(map, marker) {
            var latLngs = [ marker.getLatLng() ];
            var markerBounds = L.latLngBounds(latLngs);
            map.fitBounds(markerBounds);
            map.setZoom(14);
          }

          function shortName(data){

            listName = [];

            if( data.address.city != undefined ){

                if( listName.indexOf( data.address.city ) == -1 )
                {  
                    listName.push( data.address.city );
                }

            }

            if( data.address.road != undefined ){

                if( listName.indexOf( data.address.road ) == -1 )
                {  
                    listName.push( data.address.road );
                }

            }

            if( data.address.house_number != undefined ){

                if( listName.indexOf( data.address.house_number ) == -1 )
                {  
                    listName.push( data.address.house_number );
                }

            }

            if( data.address.state != undefined ){

                if( listName.indexOf( data.address.state ) == -1 )
                {  
                    listName.push( data.address.state );
                }

            }

            if( data.address.suburb != undefined ){

                if( listName.indexOf( data.address.suburb ) == -1 )
                {  
                    listName.push( data.address.suburb );
                }

            }

            if( data.address.country != undefined ){

                if( listName.indexOf( data.address.country ) == -1 )
                {  
                    listName.push( data.address.country );
                }

            }

            return listName.join(', ');

          }

          function addMarker(lat, lng){

              if( markersList.length ){

                  $.each(markersList,function(index,value){

                      map.removeLayer(marker);

                  });

              }

              var geojsonFeature = {

                  "type": "Feature",
                  "properties": {},
                  "geometry": {
                          "type": "Point",
                          "coordinates": [lat, lng]
                  }
              }

              L.geoJson(geojsonFeature, {

                  pointToLayer: function(feature, latlng){

                      marker = L.marker( { 'lat': lat, 'lng': lng } , {

                          riseOnHover: true,
                          draggable: false,

                      });

                      markersList.push( marker );

                      return marker;
                  }

              }).addTo(map);

              return marker;

          }

          $(document).on('click','.SearchAddressResults .item-city', function () {    
       
                $(".searchMapAddress").val($(this).html());

                $('input[name="map_lat"]').val($(this).data('lat'));
                $('input[name="map_lon"]').val($(this).data('lon'));
                
                $(".searchMapAddress").val( $(this).html() );

                getMarker = addMarker($(this).data('lat'), $(this).data('lon'));

                centerLeafletMapOnMarker(map, getMarker);

                $(".SearchAddressResults").hide();

          });

          map.on('click', function(e) {
              
              addMarker(e.latlng.lat, e.latlng.lng);

              $('input[name="map_lat"]').val(e.latlng.lat);
              $('input[name="map_lon"]').val(e.latlng.lng);

              $.getJSON('https://nominatim.openstreetmap.org/reverse?format=json&lat='+e.latlng.lat+'&lon='+e.latlng.lng+'&addressdetails=1', function(data) {
                   $('.searchMapAddress').val(shortName(data));
              });

          });

          function mapUpdate(){

              map.invalidateSize();
              map._onResize(); 

          }

          $(function(){
            
            setInterval(function() { mapUpdate() }, 1000);

          });

          });

       </script>

       <?php

     }

  }


  function getMetro($ids = ""){
     if($ids){
        $get = getAll("select * from uni_metro where id IN(".$ids.")");

        if(count($get)) {
           foreach ($get as $key => $value) {
              $getMetroParent = getOne("select * from uni_metro where id=?", array(intval($value["parent_id"]))); 
              $metro[] = array("station"=>$value["name"], "metro_name"=>$getMetroParent["name"], "metro_color"=>$getMetroParent["color"]);
           }
        }
         
        return $metro;     
     }else{
        return array();
     }
  }

  function bonusBanners(){
        $key_banner = 0;
        $bonus = getAll("select * from uni_bonus_program where status=? and img!=?", array(1,"") ); 
        if(count($bonus)){
          foreach ($bonus as $key => $value) {

            if($value["action"] == "register_profile"){
              $bonus_array[$key_banner] = '<div class="d-none d-lg-block" ><a href="'._link("add_ad").'"><img style="width: 100%" src="'.urldecode($value["img"]).'"></a></div>';
              $key_banner++;
            }
            
            if($value["action"] == "balance_profile"){
              $bonus_array[$key_banner] = '<div class="d-none d-lg-block" ><a href="'._link("profile").'?tab=balance"><img style="width: 100%" src="'.urldecode($value["img"]).'"></a></div>';
              $key_banner++;
            }
           
          }
        }

        if(count($bonus_array)) return $bonus_array[mt_rand(0, count($bonus_array) - 1 )];    
  }

  function addMetroVariants($array = array(),$ads_id=0){
    $query = array();

    $Filters = new Filters();
    
    update("DELETE FROM uni_metro_variants WHERE ads_id=?", array($ads_id));

      if( $Filters->getEmpty($array) ){       
          foreach($array AS $key=>$val){
             if($val != "null" && !empty($val)) { $query[] = "('".intval($ads_id)."','".intval($val)."')"; }        
          }
          if(count($query) > 0){

            insert("INSERT INTO uni_metro_variants(ads_id,metro_id)VALUES ".implode(",", $query));

          }
        $query = array();                      
      }

  }

  function addAreaVariants($array = array(),$ads_id=0){
    $query = array();

    $Filters = new Filters();
    
    update("DELETE FROM uni_city_area_variants WHERE city_area_variants_id_ad=?", array($ads_id));

      if( $Filters->getEmpty($array) ){       
          foreach(array_slice($array, 0,10) AS $key=>$val){
             if($val != "null" && !empty($val)) { $query[] = "('".intval($ads_id)."','".intval($val)."')"; }        
          }
          if(count($query) > 0){

            insert("INSERT INTO uni_city_area_variants(city_area_variants_id_ad,city_area_variants_id_area)VALUES ".implode(",", $query));

          }
        $query = array();                      
      }

  }

  function status($status=0){

    $ULang = new ULang();

     if($status == 0){
        return $ULang->t("На модерации");
     }elseif($status == 1){
        return $ULang->t("Активно");
     }elseif($status == 2){
        return $ULang->t("Снято с публикации");
     }elseif($status == 3){
        return $ULang->t("Заблокировано");
     }elseif($status == 4){
        return $ULang->t("Зарезервировано");
     }elseif($status == 5){
        return $ULang->t("Продано");
     }elseif($status == 6){
        return $ULang->t("Ждет оплаты");
     }elseif($status == 7){
        return $ULang->t("Отклонено");
     }elseif($status == 8){
        return $ULang->t("Удалено");
     }

  }

  function arrayStatus(){

    $ULang = new ULang();

    return [0=>$ULang->t("На модерации"), 1=>$ULang->t("Активные"), 2=>$ULang->t("Снятые с публикации"), 3=>$ULang->t("Заблокированные"), 4=>$ULang->t("Зарезервированные"), 5=>$ULang->t("Проданные"), 6=>$ULang->t("Ждут оплаты"), 7=>$ULang->t("Отклоненные"), 8=>$ULang->t("Удаленные")];

  }

  function adminAdStatus($value){

     if($value["ads_status"] == 3 || $value["clients_status"] == 2){

        if($value["clients_status"] == 1){
          ?>

            <button class="btn btn-danger dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Заблокировано
            </button>
            
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item change-status-ads" data-id="<?php echo $value["ads_id"]; ?>" data-status="1" href="#">Опубликовать</a>
            </div>

          <?php
        }else{

          ?>
            <div class="btn btn-danger btn-sm">
              Заблокировано
            </div>                                      
          <?php

        }



     }else{

       if($value["ads_status"] == 0){

         ?>
          <button class="btn btn-warning dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            На модерации
          </button>          
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

              <a class="dropdown-item change-status-ads" data-id="<?php echo $value["ads_id"]; ?>" data-status="1" href="#">Опубликовать</a>
              <a class="dropdown-item change-status-ads" data-id="<?php echo $value["ads_id"]; ?>" data-status="7" href="#">Отклонить</a>
              <a class="dropdown-item change-status-ads" data-id="<?php echo $value["ads_id"]; ?>" data-status="3" href="#">Заблокировать</a>                                            
          </div>                                                 
         <?php

       }elseif($value["ads_status"] == 1){
        
         if(strtotime($value["ads_period_publication"]) > time()){

             ?>

              <button class="btn btn-success dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Активен
              </button>
              
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <a class="dropdown-item change-status-ads" data-id="<?php echo $value["ads_id"]; ?>" data-status="2" href="#">Снять с публикации</a>
              <a class="dropdown-item change-status-ads" data-id="<?php echo $value["ads_id"]; ?>" data-status="7" href="#">Отклонить</a>
              <a class="dropdown-item change-status-ads" data-id="<?php echo $value["ads_id"]; ?>" data-status="3" href="#">Заблокировать</a>
              </div>

             <?php

         }else{
             ?>
              <button class="btn btn-warning dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Истек срок
              </button> 
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item change-status-ads" data-id="<?php echo $value["ads_id"]; ?>" data-status="1" href="#">Опубликовать</a>
                  <a class="dropdown-item change-status-ads" data-id="<?php echo $value["ads_id"]; ?>" data-status="7" href="#">Отклонить</a>
                  <a class="dropdown-item change-status-ads" data-id="<?php echo $value["ads_id"]; ?>" data-status="3" href="#">Заблокировать</a>                                            
              </div>                                                                             
             <?php
         }

       }elseif($value["ads_status"] == 2){
        
         ?>
          <button class="btn btn-alert dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Снято с публикации
          </button>
          
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <a class="dropdown-item change-status-ads" data-id="<?php echo $value["ads_id"]; ?>" data-status="1" href="#">Опубликовать</a>
          <a class="dropdown-item change-status-ads" data-id="<?php echo $value["ads_id"]; ?>" data-status="7" href="#">Отклонить</a>
          <a class="dropdown-item change-status-ads" data-id="<?php echo $value["ads_id"]; ?>" data-status="3" href="#">Заблокировать</a>
          </div>

         <?php

       }elseif($value["ads_status"] == 4){
        
         ?>
          <div class="btn btn-warning btn-sm">
            Зарезервировано
          </div>                                       
         <?php

       }elseif($value["ads_status"] == 5){
        
         ?>
          <div class="btn btn-info btn-sm">
            Продано
          </div>                                       
         <?php

       }elseif($value["ads_status"] == 6){
        
         ?>
          <button class="btn btn-info dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Ждет оплаты
          </button>          
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

              <a class="dropdown-item change-status-ads" data-id="<?php echo $value["ads_id"]; ?>" data-status="1" href="#">Опубликовать</a>
              <a class="dropdown-item change-status-ads" data-id="<?php echo $value["ads_id"]; ?>" data-status="7" href="#">Отклонить</a>
              <a class="dropdown-item change-status-ads" data-id="<?php echo $value["ads_id"]; ?>" data-status="3" href="#">Заблокировать</a>                                            
          </div>                                                 
         <?php

       }elseif($value["ads_status"] == 7){
        
         ?>
          <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Отклонено
          </button>           
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            
              <a class="dropdown-item change-status-ads" data-id="<?php echo $value["ads_id"]; ?>" data-status="1" href="#">Опубликовать</a>
              <a class="dropdown-item change-status-ads" data-id="<?php echo $value["ads_id"]; ?>" data-status="3" href="#">Заблокировать</a>                                            
          </div>                                                
         <?php

       }elseif($value["ads_status"] == 8){
        
         ?>
          <div class="btn btn-dark btn-sm">
            Удалено
          </div>                                                 
         <?php

       }

     }

  }

  function adActionButton($data=[]){

    $ULang = new ULang();

    if( $data["ad"]["ads_status"] == 3 ){

       ?>
        <div class="board-view-button">
            <span class="btn-custom btn-color-danger mb5 open-modal width100" data-id="<?php echo $data["ad"]["ads_id"]; ?>" data-id-modal="modal-delete-ads" >
              <span><?php echo $ULang->t("Удалить объявление"); ?></span>
            </span>
        </div>
       <?php

    }elseif( $data["ad"]["ads_status"] == 1 ){

          if( strtotime($data["ad"]["ads_period_publication"]) < time() ){

              ?>
              <div class="board-view-button">
                  <div class="btn-custom btn-color-green mb5 ads-extend width100" data-id="<?php echo $data["ad"]["ads_id"]; ?>" >
                    <span><?php echo $ULang->t("Продлить"); ?></span>
                  </div>

                  <a href="<?php echo _link("ad/update/".$data["ad"]["ads_id"]); ?>" class="btn-custom btn-color-light mb5 width100" >
                    <span><?php echo $ULang->t("Редактировать"); ?></span>
                  </a> 
              </div>           
              <?php

          }else{

            ?>
            <div class="board-view-button">
            <?php

              if($data["services_ads"]){
              ?>
                <div class="btn-custom btn-color-green mb5 open-modal width100" data-id-modal="modal-order-service">
                  <span><?php echo $ULang->t("Продать быстрее"); ?></span> <?php if(findOne("uni_services_ads", "services_ads_new_price!=?", [0])){ echo '<span class="label-sale-mini" >'.$ULang->t("акция").'!</span>'; } ?>
                </div> 
              <?php 
              } 
              ?>

                  <div class="btn-custom btn-color-light mb5 open-modal width100" data-id-modal="modal-remove-publication" >
                    <span><?php echo $ULang->t("Снять с публикации"); ?></span>
                  </div> 

                  <a href="<?php echo _link("ad/update/".$data["ad"]["ads_id"]); ?>" class="btn-custom btn-color-light mb5 width100" >
                    <span><?php echo $ULang->t("Редактировать"); ?></span>
                  </a>

            </div> 
            <?php

          }          
          
    }elseif( $data["ad"]["ads_status"] == 2 ){

          ?>
          <div class="board-view-button">
          <?php
          
          if( !$data["ad"]["ads_auction"] ){
          ?>

              <div class="btn-custom btn-color-light mb5 ads-publication width100" data-id="<?php echo $data["ad"]["ads_id"]; ?>" >
                <span><?php echo $ULang->t("Опубликовать"); ?></span>
              </div>

          <?php 
          }
          ?>

              <a href="<?php echo _link("ad/update/".$data["ad"]["ads_id"]); ?>" class="btn-custom btn-color-light mb5 width100" >
                <span><?php echo $ULang->t("Редактировать"); ?></span>
              </a>

              <span class="btn-custom btn-color-danger mb5 open-modal width100" data-id="<?php echo $data["ad"]["ads_id"]; ?>" data-id-modal="modal-delete-ads" >
                <span><?php echo $ULang->t("Удалить объявление"); ?></span>
              </span>

          </div>

           <?php

    }elseif( $data["ad"]["ads_status"] == 5 ){

           ?>
           <div class="board-view-button">
               <span class="btn-custom btn-color-danger mb5 open-modal width100" data-id="<?php echo $data["ad"]["ads_id"]; ?>" data-id-modal="modal-delete-ads" >
                  <span><?php echo $ULang->t("Удалить объявление"); ?></span>
               </span>
           </div>
           <?php

    }elseif( $data["ad"]["ads_status"] == 0 || $data["ad"]["ads_status"] == 7 ){

           ?>
           <div class="board-view-button">
              <a href="<?php echo _link("ad/update/".$data["ad"]["ads_id"]); ?>" class="btn-custom btn-color-light mb5 width100" >
                <span><?php echo $ULang->t("Редактировать"); ?></span>
              </a>

              <span class="btn-custom btn-color-danger mb5 open-modal width100" data-id="<?php echo $data["ad"]["ads_id"]; ?>" data-id-modal="modal-delete-ads" >
                <span><?php echo $ULang->t("Удалить объявление"); ?></span>
              </span>
            </div>
           <?php

    }


  }

  function adActionFavorite($data=[], $page="", $class=""){
    
    $Main = new Main();
    $return = "";

    if( $data["ads_id_user"] == $_SESSION['profile']['id'] ){
        return false;
    }

    if($page == "ad"){

          $return .= '<div '.$Main->modalAuth( ["attr"=>'class="'.$class.' toggle-favorite" data-id="'.$data["ads_id"].'"', "class"=>$class] ).'  >';
          
          if( isset($_SESSION['profile']["favorite"][$data["ads_id"]]) ){
            $return .= '<i class="ion-ios-heart"></i>'; 
          }else{
            $return .= '<i class="ion-ios-heart-outline"></i>';
          }

          $return .= '</div>';
      
    }elseif($page == "catalog"){

          $return .= '<div '.$Main->modalAuth( ["attr"=>'class="'.$class.' toggle-favorite" data-id="'.$data["ads_id"].'"', "class"=>$class] ).' >';
          
          if( isset($_SESSION['profile']["favorite"][$data["ads_id"]]) ){
            $return .= '<i class="ion-ios-heart"></i>'; 
          }else{
            $return .= '<i class="ion-ios-heart-outline"></i>';
          }

          $return .= '</div>';

    }elseif($page == "map"){

          $return .= '<div '.$Main->modalAuth( ["attr"=>'class="'.$class.' toggle-favorite" data-id="'.$data["ads_id"].'"', "class"=>$class] ).' >';
          
          if( isset($_SESSION['profile']["favorite"][$data["ads_id"]]) ){
            $return .= '<i class="ion-ios-heart"></i>'; 
          }else{
            $return .= '<i class="ion-ios-heart-outline"></i>';
          }

          $return .= '</div>';

    }

    return $return;

  }

  function serviceActivation( $param=[] ){

    $getServiceOrder = getAll("select * from uni_services_order where services_order_id_ads IN(".$param["id_ad"].") and services_order_status='0'");

    if(count($getServiceOrder)){

      foreach ($getServiceOrder as $key => $value) {
        $services_order_time_validity = date( "Y-m-d H:i:s", strtotime("+".$value["services_order_count_day"]." days", time()) );
        update("UPDATE uni_services_order SET services_order_time_validity=?,services_order_status=? WHERE services_order_id=?", array($services_order_time_validity,1,$value["services_order_id"]));
      }

    }


  }

  function dateDiff( $time = "" ){

      $day = date_diff(new DateTime(), new DateTime($time))->days;

      $interval = date_diff( date_create() , date_create($time) );

      $day = $interval->format('%a');
      $hour = $interval->format('%h');
      $min = $interval->format('%i');
      $sec = $interval->format('%s');

      return [ "day" => $day, "hour" => $hour, "min" => $min, "sec" => $sec ];

  }

  function auctionTime( $time = "" ){

    $ULang = new ULang();

    $strTime = [];
     
      if( strtotime($time) > time() ){

        $diff = $this->dateDiff( $time );

        if($diff["day"]){

           $strTime[] = $diff["day"] . ' ' . ending( $diff["day"], $ULang->t("день"), $ULang->t("дня"), $ULang->t("дней"));

           if($diff["hour"]){
              $strTime[] = $diff["hour"] . ' ' . ending( $diff["hour"], $ULang->t("час"), $ULang->t("часа"), $ULang->t("часов"));
           }

           if($diff["min"]){
              $strTime[] = $diff["min"] . ' ' . ending( $diff["min"], $ULang->t("минута"), $ULang->t("минуты"), $ULang->t("минут"));
           }

           echo implode(" ", $strTime);

        }else{

           if($diff["hour"]){

             $strTime[] = $diff["hour"] . ' ' . ending( $diff["hour"], $ULang->t("час"), $ULang->t("часа"), $ULang->t("часов"));

             if($diff["min"]){
                $strTime[] = $diff["min"] . ' ' . ending( $diff["min"], $ULang->t("минута"), $ULang->t("минуты"), $ULang->t("минут"));
             }

             echo implode(" ", $strTime); 

           }elseif($diff["min"]){

               $strTime[] = $diff["min"] . ' ' . ending( $diff["min"], $ULang->t("минута"), $ULang->t("минуты"), $ULang->t("минут"));

               if($diff["sec"]){
                  $strTime[] = $diff["sec"] . ' ' . ending( $diff["sec"], $ULang->t("секунда"), $ULang->t("секунды"), $ULang->t("секунд"));
               }

               echo implode(" ", $strTime); 

           }else{

               echo $diff["sec"] . ' ' . ending( $diff["sec"], $ULang->t("секунда"), $ULang->t("секунды"), $ULang->t("секунд"));

           }

        }

      }else{

        echo $ULang->t('Аукцион завершен');

      }

  }

  function auctionTimeCompletion( $time = "", $class = "" ){
      
      if( strtotime($time) > time() ){

        $diff = $this->dateDiff( $time );

        if(!$diff["day"] && !$diff["hour"] && $diff["min"]){
           if( $diff["min"] <= 30 ){
              return 'class="'.$class.'" data-date="'.date("Y/m/d H:i:s", strtotime( $time ) ).'" data-countdown="true"';
           }
        }

      }

  }

  function outServicesAd($data = []){

    $strTime = [];
    $ULang = new ULang();

     if($data["services_order"]){

        foreach ($data["services_order"] as $key => $value) {

            $strTime = [];
            
            if($value["services_order_status"]){

              $diff = $this->dateDiff( $value["services_order_time_validity"] );

              $progress = ((time() - strtotime($value["services_order_time_create"])) / (strtotime($value["services_order_time_validity"]) - strtotime($value["services_order_time_create"]))) * 100;

            }

           ?>

           <div class="board-view-service" >

              <h5><?php echo $ULang->t( $value["services_ads_name"], [ "table"=>"uni_services_ads", "field"=>"services_ads_name" ] ); ?></h5>

              <?php if($value["services_order_status"]){ ?>
              <p> <?php echo $ULang->t("Осталось:"); ?> 
              <?php

              if($diff["day"]){ 

                   $strTime[] = $diff["day"] . ' ' . ending( $diff["day"], $ULang->t("день"), $ULang->t("дня"), $ULang->t("дней"));

                   if($diff["hour"]){
                      $strTime[] = $diff["hour"] . ' ' . ending( $diff["hour"], $ULang->t("час"), $ULang->t("часа"), $ULang->t("часов"));
                   }

                   echo implode(" ", $strTime);

              }else{

                 if($diff["hour"]){

                     $strTime[] = $diff["hour"] . ' ' . ending( $diff["hour"], $ULang->t("час"), $ULang->t("часа"), $ULang->t("часов"));

                     if($diff["min"]){
                        $strTime[] = $diff["min"] . ' ' . ending( $diff["min"], $ULang->t("минута"), $ULang->t("минуты"), $ULang->t("минут"));
                     }

                     echo implode(" ", $strTime);  

                 }else{

                     echo $diff["min"] . ' ' . ending( $diff["min"], $ULang->t("минута"), $ULang->t("минуты"), $ULang->t("минут"));

                 }

              } 
 
              ?></p>
              <?php }else{ ?>
              <p><?php echo $ULang->t("Идет активация"); ?></p>
              <?php } ?>

              <div class="progress" style="height: 0.4rem!important;" >
                <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $progress; ?>%" aria-valuenow="<?php echo $progress; ?>" aria-valuemin="0" aria-valuemax="100"></div>
              </div> 

           </div>

           <?php
        }

     }

  }

  function getOrderServiceIds($id){

     $ids = [];

     $get = getAll( "select services_order_id_service from uni_services_order where services_order_id_ads=? and services_order_time_validity > now()", array($id) );

     if(count($get)){
        foreach ($get as $key => $value) {
           $ids[$value["services_order_id_service"]] = $value["services_order_id_service"];
        }
     }

     return $ids;

  }

  function getAvailableServiceIds($id){

     $orderServicesIds = $this->getOrderServiceIds($id);
     $servicesIds = [];

     if(count($orderServicesIds)){

        if($orderServicesIds[3]){
            return [];
        }else{
            $orderServicesIds[3] = 3;
        }

        $get = getAll("select * from uni_services_ads where services_ads_visible=? and services_ads_uid NOT IN(".implode(",", $orderServicesIds).")", [1]);
        if(count($get)){
            foreach ($get as $value) {
                $servicesIds[$value['services_ads_uid']] = $value['services_ads_uid'];
            }
            return $servicesIds;
        }

     }else{

        $get = getAll("select * from uni_services_ads where services_ads_visible=?", [1]);
        if(count($get)){
            foreach ($get as $value) {
                $servicesIds[$value['services_ads_uid']] = $value['services_ads_uid'];
            }
            return $servicesIds;
        }

     }

  }

  function statusAd( $param = [], $update = false ){
    global $config,$settings;
      
      if( $settings["ad_black_list_words"] ){

          $words = explode(",", trim($settings["ad_black_list_words"], ","));
          if($words){
              foreach ($words as $key => $value) {
                if(trim($value)){

                     if (preg_match('/\b'.mb_strtolower(trim($value), "UTF-8").'\b/u', mb_strtolower($param["text"] . " " . $param["title"], "UTF-8"))) {
                         return [ "status" => 7, "message" => "" ];
                     }
 
                }           
              }
          }

      }

      if( $settings["ads_publication_auto_moderat"] ){

          if(preg_match('/([A-Za-z0-9_\-]+\.)*[A-Za-z0-9_\-]+@([A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9]\.)+[A-Za-z]{2,4}/u',$param["text"] . " " . $param["title"])){
              return [ "status" => 7, "message" => "Нельзя указывать контактные данные и ссылки в описании" ];
          }

          foreach ($config["domain_zones"] as $zone) {
              if(@strpos($param["text"] . " " . $param["title"], $zone) !== false){
                return [ "status" => 7, "message" => "Нельзя указывать контактные данные и ссылки в описании" ];
              }
          }

          if( @strpos($param["text"] . " " . $param["title"], "://") !== false || @strpos($text . " " . $title, "www.") !== false ){
              return [ "status" => 7, "message" => "Нельзя указывать контактные данные и ссылки в описании" ];
          }
          
          if( !$param["categories"]["category_board_id"][$param["id_cat"]]["category_board_auto_title"] ){
            if( mb_strlen( $param["title"], "UTF-8" ) < 3 ){
                return [ "status" => 7, "message" => "Некорректный заголовок" ];
            }
          }        

      }

      if( $param["categories"]["category_board_id"][$param["id_cat"]]["category_board_status_paid"] ){

        if($this->userCountAvailablePaidAddCategory($param["id_cat"], $param["id_user"]) >= $param["categories"]["category_board_id"][$param["id_cat"]]["category_board_count_free"]){

           if($update == false){

               if($settings["board_type_ad_publication"] == "paid"){

                  $getPackages = getAll("select * from uni_ads_packages_orders where user_id=? and cat_id=? and status_pay=? and completion_date > ?", [$param["id_user"],$param["id_cat"],1,date("Y-m-d H:i:s")]);

                  if($getPackages){

                      foreach ($getPackages as $key => $value) {

                         $countAds = (int)getOne("select count(*) as total from uni_ads_packages_placements where user_id=? and cat_id=? and order_id=?", [$param["id_user"], $param["id_cat"], $value["id"]])["total"];

                         if($countAds < (int)$value["count_ad"]){
                            return [ "status" => $settings["ads_publication_moderat"] ? 0 : 1 , "message" => "" ];
                            break;
                         }

                      }

                      return [ "status" => 6, "message" => "" ];

                  }else{
                    return [ "status" => 6, "message" => "" ];
                  }

               }else{
                 return [ "status" => 6, "message" => "" ];
               }

           }else{

               if($settings["board_type_ad_publication"] == "paid"){

                  $getPackages = getAll("select * from uni_ads_packages_orders where user_id=? and cat_id=? and status_pay=? and completion_date > ?", [$param["id_user"],$param["id_cat"],1,date("Y-m-d H:i:s")]);

                  if(!$getPackages){

                     if(strtotime($param["period_publication"]) <= time()){

                        return [ "status" => 6, "message" => "" ];

                     }else{

                        $findOrder = findOne('uni_orders', 'orders_id_ad=? and orders_action_name=? and orders_status_pay=?', [$param["ad_id"], 'category', 1]);

                        if(!$findOrder){
                            return [ "status" => 6, "message" => "" ];
                        }

                     }

                  }

               }else{

                 if(strtotime($param["period_publication"]) <= time()){

                    return [ "status" => 6, "message" => "" ];

                 }else{

                    $findOrder = findOne('uni_orders', 'orders_id_ad=? and orders_action_name=? and orders_status_pay=?', [$param["ad_id"], 'category', 1]);

                    if(!$findOrder){
                        return [ "status" => 6, "message" => "" ];
                    }

                 }

               }

           }

        }

      }

      if($update == false){
        return [ "status" => $settings["ads_publication_moderat"] ? 0 : 1 , "message" => "" ];
      }else{
        return [ "status" => $this->autoModeration($param["ad_id"], [ "title" => $param["title"], "text" => $param["text"], "video" => $param["video"] ]) , "message" => "" ]; 
      }

  }  

  function changeStatus( $id_ad=0, $status = 0, $action="", $note="" ){
      insert("INSERT INTO uni_ads_change(ads_change_id_ad,ads_change_action,ads_change_status,ads_change_note,ads_change_date)VALUES ('".$id_ad."','".$action."','".$status."','".$note."','".date("Y-m-d H:i:s")."')");
  }

  function adAuctionSidebar( $data = [] ){

    global $settings;

    $Main = new Main();
    $Profile = new Profile();
    $ULang = new ULang();

    if($data["ad"]["ads_auction"] && in_array($data["ad"]["ads_status"], [1,2,4,5])){
      
      ?>
      <div class="box-auction" >
      <?php

      if($data["ad"]["ads_status"] == 1){

       if( strtotime($data["ad"]["ads_auction_duration"]) > time() ){
          ?>

            <p class="auction-title" ><?php echo $ULang->t("До завершения аукциона:"); ?> <strong><span <?php echo $this->auctionTimeCompletion( $data["ad"]["ads_auction_duration"], "pulse-time" ); ?> ><?php echo $this->auctionTime( $data["ad"]["ads_auction_duration"] ); ?></span></strong></p>

            
          <?php
       }else{
          ?>

            <p class="auction-title" ><?php echo $ULang->t("До завершения аукциона:"); ?> <strong><span><?php echo $this->auctionTime( $data["ad"]["ads_auction_duration"] ); ?></span></strong></p>


          <?php
       }
      
      if( $_SESSION["profile"]["id"] ){

         if( strtotime($data["ad"]["ads_auction_duration"]) > time() ){

                if($_SESSION["profile"]["id"] != $data["ad"]["ads_id_user"]){
                ?>

                  <div class="btn-custom btn-color-light mb5 open-modal mt20 width100" data-id-modal="modal-auction" >
                    <span><?php echo $ULang->t("Сделать ставку"); ?></span>
                  </div> 

                  <div class="btn-custom btn-color-light mb5 open-modal width100" data-id-modal="modal-auction-users" >
                    <span><?php echo $ULang->t("Ставки"); ?> (<?php echo count($data["auction_users"]); ?>)</span>
                  </div>
                  
                  <?php 
                  if($data["ad"]["ads_auction_price_sell"]){ 
                      
                      if( $this->getStatusSecure($data["ad"],$data["ad"]["ads_auction_price_sell"]) ){
                      ?>
                        <a class="btn-custom btn-color-danger mb15 width100"  href="<?php echo _link("buy/".$data["ad"]["ads_id"]); ?>" >
                          <span><?php echo $ULang->t("Купить сейчас за"); ?> <?php echo $Main->price($data["ad"]["ads_auction_price_sell"]); ?></span>
                        </a>
                      <?php
                      }else{
                      ?>
                        <button class="btn-custom btn-color-danger mb15 open-modal width100" data-id-modal="modal-confirm-buy" >
                          <span><?php echo $ULang->t("Купить сейчас за"); ?> <?php echo $Main->price($data["ad"]["ads_auction_price_sell"]); ?></span>
                        </button>
                      <?php                        
                      }

                  } 
                  ?>

                <?php 
                }else{ 
                ?>

                  <div class="btn-custom btn-color-light mb5 open-modal mt20 width100" data-id-modal="modal-auction-users" >
                    <span><?php echo $ULang->t("Ставки"); ?> (<?php echo count($data["auction_users"]); ?>)</span>
                  </div>

                <?php 
                }

         }else{
           ?>

              <div class="btn-custom btn-color-light mb5 open-modal mt20 width100" data-id-modal="modal-auction-users" >
                <span><?php echo $ULang->t("Ставки"); ?> (<?php echo count($data["auction_users"]); ?>)</span>
              </div>

           <?php
         }

      }else{

         ?>
         <p class="mt15 ads-status-info" ><?php echo $ULang->t("Аторизуйтесь на сайте, чтобы сделать ставку."); ?></p>
         <?php

      }

     ?>
     <hr>
     <?php

    }elseif($_SESSION["profile"]["id"]){
      
          if($_SESSION["profile"]["id"] == $data["ad"]["ads_id_user"]){

                if($data["auction_user_winner"]){ ?>

                  <div class="ads-status-info mt10 mb15" >
                    <strong><?php echo $ULang->t("Аукцион завершен"); ?></strong><br>
                    <?php echo $ULang->t("Победитель:"); ?> <a href="<?php echo _link("user/".$data["auction_user_winner"]["clients_id_hash"]); ?>"><?php echo $Profile->name($data["auction_user_winner"]); ?></a>
                  </div>
                  
                  <?php 
                  if($data["ad"]["ads_status"] == 4){

                      if( $this->getStatusSecure($data["ad"]) ){
                  ?>

                      <div class="btn-custom btn-color-danger mb5 open-modal width100" data-id-modal="modal-auction-cancel" >
                        <span><?php echo $ULang->t("Отменить сделку"); ?></span>
                      </div>

                      <hr>

                  <?php }else{ ?>

                      <div class="btn-custom btn-color-blue mb5 open-modal ads-status-sell width100" data-id="<?php echo $data["ad"]["ads_id"]; ?>" data-id-modal="modal-change-user-review" >
                        <span><?php echo $ULang->t("Подтвердить продажу"); ?></span>
                      </div>
                      
                      <div class="btn-custom btn-color-danger mb5 open-modal width100" data-id-modal="modal-auction-cancel" >
                        <span><?php echo $ULang->t("Отменить сделку"); ?></span>
                      </div>

                      <hr>

                  <?php }    

                  } 

                }else{ ?>
                  <div class="mb15" ></div>
                <?php }

          }else{ 

                  if($data["auction_user_winner"]){ ?>

                  <div class="ads-status-info mt10 mb15" >
                    <strong><?php echo $ULang->t("Аукцион завершен"); ?></strong><br>
                    <?php echo $ULang->t("Победитель:"); ?> <a href="<?php echo _link("user/".$data["auction_user_winner"]["clients_id_hash"]); ?>"><?php echo $Profile->name($data["auction_user_winner"]); ?></a>
                    <?php
                     if($data["auction_user_winner"]["clients_id"] == $_SESSION["profile"]["id"]){

                        if($data["ad"]["ads_status"] == 4 && !$data["order_secure"]){
                            
                            if( $this->getStatusSecure($data["ad"]) ){

                            ?>
                              <div class="mt10" ></div>
                              <a class="btn-custom btn-color-danger width100" href="<?php echo _link("buy/".$data["ad"]["ads_id"]); ?>" >
                                <span><?php echo $ULang->t("Оплатить"); ?> <?php echo $Main->price($data["ad"]["ads_price"]); ?></span>
                              </a> 
                              <hr>                   
                            <?php

                            }else{
                               ?>
                               <div class="mt10" ></div>
                               <?php
                               echo $ULang->t("Договоритесь с продавцом в чате или по телефону о способе передачи и оплате товара.");
                               
                            }

                        }

                     }
                    ?>
                  </div>


                  <?php   
                  }else{
                     ?>
                     <div class="mb15" ></div>
                     <?php
                  } 
                  

          } 


        ?>

        <div class="btn-custom btn-color-light mb5 open-modal width100" data-id-modal="modal-auction-users" >
          <span><?php echo $ULang->t("Ставки"); ?> (<?php echo count($data["auction_users"]); ?>)</span>
        </div>

      <?php

     }

     ?>

     </div>

     <?php

   }

  }

  function adSidebar( $data = [] ){
    global $settings;

    $Profile = new Profile();
    $Main = new Main();
    $ULang = new ULang();
    $Shop = new Shop();
    $Cart = new Cart();

    if($_SESSION["profile"]["id"] == $data["ad"]["ads_id_user"]){
        
        if( $data["ad"]["ads_status"] == 3 ){

            ?>
            <div class="ads-status-info" >
              <?php echo $ULang->t("Объявление заблокировано."); ?>
            </div>
            <?php

        }elseif( $data["ad"]["ads_status"] == 0 ){

            ?>
            <div class="ads-status-info" >
              <?php echo $ULang->t("Объявление на модерации."); ?>
            </div>
            <?php

        }elseif( $data["ad"]["ads_status"] == 6 ){
            
            ?>
            <div class="ads-status-info mb0" >
            <?php

            if( $data["ad"]["category_board_count_free"] != 0 ){
              echo $ULang->t("Ваше объявление перемещено в архив. В категории"); ?> <strong>«<?php echo $data["ad"]["category_board_name"]; ?>»</strong> <?php echo $ULang->t("вы уже публиковали"); ?> <?php echo $data["ad"]["category_board_count_free"]; ?> <?php echo ending($data["ad"]["category_board_count_free"], $ULang->t("объявление"),$ULang->t("объявления"),$ULang->t("объявлений") ).' '.$ULang->t("бесплатно"); ?>. <?php echo $ULang->t("Стоимость размещения в данную категорию"); ?> <?php echo $Main->price($data["ad"]["category_board_price"]); 
            }else{
              echo $ULang->t("Ваше объявление перемещено в архив.") . " " . $ULang->t("Стоимость размещения в данную категорию"); ?> <?php echo $Main->price($data["ad"]["category_board_price"]);
            }
            ?>

            <div class="btn-custom btn-color-green mb5 ads-cat-pay-publication mt10 width100" data-id="<?php echo $data["ad"]["ads_id"]; ?>" >
              <span><?php echo $ULang->t("Опубликовать за"); ?> <?php echo $Main->price($data["ad"]["category_board_price"]); ?></span>
            </div>

            </div>

            <span class="btn-custom btn-color-danger mb5 open-modal width100" data-id="<?php echo $data["ad"]["ads_id"]; ?>" data-id-modal="modal-delete-ads" >
              <span><?php echo $ULang->t("Удалить объявление"); ?></span>
            </span>

            <?php

        }elseif( $data["ad"]["ads_status"] == 7 ){

            ?>
            <div class="ads-status-info" >
              <strong><?php echo $ULang->t("Объявление отклонено."); ?></strong>
              <br>
              <?php if($data["ad"]["ads_note"]) echo $ULang->t("По причине:") . " " . $ULang->t($data["ad"]["ads_note"]) . ". " . $ULang->t("Отредактируйте объявление и опубликуйте снова."); else echo $ULang->t("По причине:") . " " . $ULang->t("Нарушение правил сайта") . ". " . $ULang->t("Отредактируйте объявление и опубликуйте снова."); ?>
            </div>
            <?php

        }elseif( $data["ad"]["ads_status"] == 2 ){

            ?>
            <div class="ads-status-info" >
              <?php echo $ULang->t("Объявление было снято с публикации и помещено в архив. Если объявление по-прежнему актуально, то опубликуйте его повторно."); ?>
            </div>
            <?php

        }else{

            if( strtotime($data["ad"]["ads_period_publication"]) < time() && !$data["ad"]["ads_auction"] ){

              ?>
              <div class="ads-status-info" >
                <?php echo $ULang->t("Срок размещения вашего объявления истек. Если объявление по-прежнему актуально, обновите в нем информацию или опубликуйте повторно."); ?>
              </div>
              <?php 

            }

        }

        echo $this->adActionButton($data);

        if( $data["ad"]["ads_status"] == 1 ){
            echo $this->outServicesAd($data); 
        }

    ?>

    <?php }else{ ?>
        
        <div class="board-view-button">
            
            <?php

            if($data["order_secure"]){

               if($data["order_secure"]["secure_id_user_buyer"] == $_SESSION["profile"]["id"] || $data["order_secure"]["secure_id_user_seller"] == $_SESSION["profile"]["id"]){
               ?>
                <a class="btn-custom btn-color-blue mb5 width100" href="<?php echo _link("order/".$data["order_secure"]["secure_id_order"]); ?>"  >
                    <span><?php echo $ULang->t("Перейти к заказу"); ?></span><br>
                </a>
                <hr>
               <?php
               }

            }

            if(!$data["ad"]["ads_auction"] && !$data["ad"]["ads_booking"] && !$data["order_secure"] && $settings["marketplace_status"] && $data["ad"]["ads_status"] == 1){

                if($data["ad"]["category_board_marketplace"] && $data["ad"]["ads_price"] && !$data["ad"]["ads_price_free"] && $settings["functionality"]['marketplace']){

                    if($settings['marketplace_available_cart'] == 'all'){
                        $permission_add_cart = true;
                    }elseif($settings['marketplace_available_cart'] == 'shop'){
                        $getShop = $Shop->getUserShop( $data["ad"]["ads_id_user"] );
                        if($getShop){
                            $permission_add_cart = true;
                        }
                    }

                }

                if($permission_add_cart){

                    if($settings["main_type_products"] == 'physical'){

                        if($data["ad"]["ads_available_unlimitedly"]){
                            $button = true;
                        }elseif($data["ad"]["ads_available"]){
                            $button = true;
                        }else{
                            $button = false;
                        }

                        if($button){

                            if($data["ad"]["ads_available"] < 10 && !$data["ad"]["ads_available_unlimitedly"]){
                                ?>
                                  <div class="ads-status-info" >
                                    <?php echo $ULang->t("Товар скоро закончится"); ?>
                                  </div>
                                <?php
                            }

                            ?>
                              <div class="btn-custom btn-color-blue schema-color-button ad-add-to-cart mb5 width100" data-name-add="<?php echo $ULang->t("Добавить в корзину"); ?>" data-name-delete="<?php echo $ULang->t("Удалить из корзины"); ?>" data-id="<?php echo $data["ad"]["ads_id"]; ?>" >
                                <span><?php if(!isset($_SESSION['cart'][$data["ad"]["ads_id"]])){ echo $ULang->t("Добавить в корзину"); }else{ echo $ULang->t("Удалить из корзины"); } ?></span>
                              </div>
                            <?php
                        }else{
                            ?>
                              <div class="ads-status-info" style="color: red;" >
                                <?php echo $ULang->t("Нет в наличии"); ?>
                              </div>
                            <?php
                        }

                    }else{
                        ?>
                          <div class="btn-custom btn-color-blue schema-color-button ad-add-to-cart mb5 width100" data-name-add="<?php echo $ULang->t("Добавить в корзину"); ?>" data-name-delete="<?php echo $ULang->t("Удалить из корзины"); ?>" data-id="<?php echo $data["ad"]["ads_id"]; ?>" >
                            <span><?php if(!isset($_SESSION['cart'][$data["ad"]["ads_id"]])){ echo $ULang->t("Добавить в корзину"); }else{ echo $ULang->t("Удалить из корзины"); } ?></span>
                          </div>
                        <?php                        
                    }

                }

            }

            if($this->getStatusBooking($data["ad"])){

                ?>

                <div <?php echo $Main->modalAuth( ["attr"=>'class="btn-custom btn-color-blue open-modal ad-booking-init mb5 width100" data-id-ad="'.$data["ad"]["ads_id"].'" data-id-modal="modal-booking"', "class"=>"btn-custom btn-color-blue width100 mb5"] ); ?>  >
                  <div>

                  <?php 
                  if($data["ad"]["category_board_booking_variant"] == 0){
                      ?>
                      <span class="btn-custom-title" ><?php echo $ULang->t("Забронировать"); ?></span>
                      <?php
                      if($data["ad"]["ads_booking_prepayment_percent"]){ 
                          ?>
                            <span class="btn-custom-subtitle1" ><?php echo $ULang->t("Предоплата"); ?> <?php echo $data["ad"]["ads_booking_prepayment_percent"]; ?>%</span>  
                          <?php 
                      }else{ 
                          ?>
                            <span class="btn-custom-subtitle1" ><?php echo $ULang->t("Без предоплаты"); ?></span>
                          <?php 
                      } 
                  }else{
                      ?>
                      <span class="btn-custom-title" ><?php echo $ULang->t("Арендовать"); ?></span>
                      <?php
                      if(!$data["ad"]["ads_booking_available_unlimitedly"]){ 
                          if($this->adCountActiveRent($data["ad"]["ads_id"]) >= $data["ad"]["ads_booking_available"]){
                              ?>
                                <span class="btn-custom-subtitle1" ><?php echo $ULang->t("Не доступно для аренды"); ?></span>  
                              <?php 
                          }
                      }
                  }
                  ?>    
                  </div>
                </div>

                <?php
            }

            if($data["ad"]["clients_phone"]){ 
              if($data["ad"]["clients_view_phone"]){      
              ?>
                  <div class="btn-custom btn-color-green width100 show-phone mb5" data-id="<?php echo $data["ad"]["ads_id"]; ?>" >
                    <span><?php echo $ULang->t("Показать номер телефона"); ?></span>
                  </div>
              <?php 
              }else{
              ?>
                  <div class="info-not-phone mb5">
                    <strong><?php echo $ULang->t("Номер скрыт"); ?></strong><br>
                    <?php echo $ULang->t("Пользователь предпочитает сообщения"); ?>
                  </div>
              <?php                
              } 
            }
            ?>
            
            <?php if(!$data["locked"]){ ?>

                <div <?php echo $Main->modalAuth( ["attr"=>'class="btn-custom btn-color-light ad-init-message open-modal mb5 width100" data-id-ad="'.$data["ad"]["ads_id"].'" data-id-modal="modal-chat-user"', "class"=>"btn-custom btn-color-light width100 mb5"] ); ?>  >
                  <span><?php echo $ULang->t("Написать продавцу"); ?></span>
                </div>

                <?php

                if($data["ad"]["category_board_marketplace"] && $settings["main_type_products"] == 'physical'){
                    if($data["ad"]["ads_available_unlimitedly"]){
                        $button = true;
                    }elseif($data["ad"]["ads_available"]){
                        $button = true;
                    }else{
                        $button = false;
                    }
                }else{
                    $button = true;
                }

                if(!$data["ad"]["ads_auction"] && $this->getStatusSecure($data["ad"]) && !$data["order_secure"] && $button && $data["ad"]["ads_status"] == 1){ 

                     ?>
                        <hr>
                        <a <?php echo $Main->modalAuth( ["attr"=>'class="btn-custom btn-color-gradient width100" href="'._link('buy/'.$data["ad"]["ads_id"]).'"', "class"=>"btn-custom btn-color-gradient width100"] ); ?>  >
                            <span><?php echo $ULang->t("Купить"); ?></span><br>
                        </a>

                        <div class="ad-view-secure-label mt15" ><i class="las la-shield-alt"></i> <?php echo $ULang->t("Безопасная сделка"); ?></div>
                     <?php

                } 

                if($this->getStatusDelivery($data["ad"])){ 

                     ?>
                        <div class="ad-view-delivery-label" ><i class="las la-truck"></i> <?php echo $ULang->t("Возможна доставка"); ?></div>
                     <?php

                }

                ?>

            <?php } ?> 
                            
         </div>

         <?php

    }    

  }

  function adServices($id_id=0){

    $data = [];

      $get = getAll("select * from uni_services_order INNER JOIN `uni_services_ads` ON `uni_services_ads`.services_ads_uid = `uni_services_order`.services_order_id_service where services_order_time_validity > now() and services_order_id_ads=? and services_order_status=?", [$id_id,1] );
      
      if($get){
        foreach ($get as $key => $value) {
           $data[$value["services_order_id_service"]] = $value["services_order_id_service"];
        }
      }

    return $data;

  }

  function delete($data = []){
    global $config, $settings;

    $Elastic = new Elastic();
    $Profile = new Profile();

      if($data["id"]){
          $get = getAll("SELECT ads_images,ads_id,ads_id_user FROM uni_ads WHERE ads_id IN(".$data["id"].")");
      }elseif($data["id_user"]){
          $get = getAll("SELECT ads_images,ads_id,ads_id_user FROM uni_ads WHERE ads_id_user=?", array($data["id_user"]));
      }elseif($data["id_cat"]){
          $get = getAll("SELECT ads_images,ads_id,ads_id_user,ads_id_cat FROM uni_ads WHERE ads_id_cat=?", array($data["id_cat"]));
      }


       if(count($get)){

          foreach ($get as $ad_key => $ad_value) {

              $images = $this->getImages($ad_value["ads_images"]);

              if(count($images) > 0){
                 foreach ($images as $key => $value) {

                    @unlink( $config["basePath"] . "/" . $config["media"]["big_image_ads"] . "/" . $value);
                    @unlink( $config["basePath"] . "/" . $config["media"]["small_image_ads"] . "/" . $value);
                   
                 }
              }

            update("delete from uni_ads where ads_id=?", array($ad_value["ads_id"]));
            update("delete from uni_ads_filters_variants where ads_filters_variants_product_id=?", array($ad_value["ads_id"]));
            update("delete from uni_ads_complain where ads_complain_id_ad=?", array($ad_value["ads_id"]));
            update("delete from uni_services_order where services_order_id_ads=?", array($ad_value["ads_id"]));
            update("delete from uni_favorites where favorites_id_ad=?", array($ad_value["ads_id"]));
            update("delete from uni_ads_change where ads_change_id_ad=?", array($ad_value["ads_id"]));          
            update("delete from uni_chat_users where chat_users_id_ad=?", array($ad_value["ads_id"]));          
            update("delete from uni_orders where orders_id_ad=?", array($ad_value["ads_id"]));          
            update("delete from uni_ads_views where ads_views_id_ad=?", array($ad_value["ads_id"]));          
            update("delete from uni_ads_views_display where ads_views_display_id_ad=?", array($ad_value["ads_id"])); 
            update("delete from uni_ads_comments where ads_comments_id_ad=?", array($ad_value["ads_id"]));  
            update("delete from uni_ads_packages_placements where ad_id=?", [$ad_value["ads_id"]]);  

            $Profile->deleteUserStory(0, $ad_value["ads_id_user"],$ad_value["ads_id"]);    
                
            $getSecure = findOne("uni_secure", "secure_id_ad=?", array($ad_value["ads_id"]));      
            
            if($getSecure){
              update("delete from uni_secure_disputes where secure_disputes_id_secure=?", array($getSecure["secure_id"]));
              update("delete from uni_secure_payments where secure_payments_id_order=?", array($getSecure["secure_id_order"]));
              update("delete from uni_secure where secure_id_ad=?", array($ad_value["ads_id"]));
            }

            $Elastic->delete( [ "index" => "uni_ads", "type" => "ad", "id" => $ad_value["ads_id"] ] );

          }

       }


  }

  function queryGeo(){
     if(isset($_SESSION["geo"]["data"])){

        if(isset($_SESSION["geo"]["data"]["city_id"])){

          return "ads_city_id='".$_SESSION["geo"]["data"]["city_id"]."'";

        }elseif(isset($_SESSION["geo"]["data"]["region_id"])){

          return "ads_region_id='".$_SESSION["geo"]["data"]["region_id"]."'";
          
        }elseif(isset($_SESSION["geo"]["data"]["country_id"])){

          return "ads_country_id='".$_SESSION["geo"]["data"]["country_id"]."'";
          
        }

     }
  }

  function arrayGeo(){
     if(isset($_SESSION["geo"]["data"])){

        if(isset($_SESSION["geo"]["data"]["city_id"])){

          return [ "ads_city_id" => $_SESSION["geo"]["data"]["city_id"] ];

        }elseif(isset($_SESSION["geo"]["data"]["region_id"])){

          return [ "ads_region_id" => $_SESSION["geo"]["data"]["region_id"] ];
          
        }elseif(isset($_SESSION["geo"]["data"]["country_id"])){

          return [ "ads_country_id" => $_SESSION["geo"]["data"]["country_id"] ];
          
        }

     }
  }

  function updateCountDisplay($id_ad=0,$id_user=0){
     $count = 1;
     $get = findOne('uni_ads_views_display', 'ads_views_display_id_ad=? and date(ads_views_display_date)=?', [$id_ad,date("Y-m-d")]);
     if($get){
        $count = $get['ads_views_display_count']+1;
        update("update uni_ads_views_display set ads_views_display_count=? where ads_views_display_id=?", [$count,$get['ads_views_display_id']], true);
    }else{
        insert("INSERT INTO uni_ads_views_display(ads_views_display_id_ad,ads_views_display_date,ads_views_display_count,ads_views_display_id_user)VALUES(?,?,?,?)", array($id_ad,date("Y-m-d"),1,$id_user));
    }
     update("update uni_ads set ads_count_display=? where ads_id=?", [$count,$id_ad], true);
  }

  function buttonViewsUp(){
     $Main = new Main();
     $ULang = new Ulang();
     $get = findOne("uni_services_ads", "services_ads_uid=?", [1]);
     $count = $get["services_ads_variant"] == 1 ? $get["services_ads_count_day"] : 1;
     $price = $get["services_ads_new_price"] ? $get["services_ads_new_price"] : $get["services_ads_price"];
     return  $ULang->t('Поднять на') . ' ' . $count . ' ' . ending($count, $ULang->t("день"), $ULang->t("дня"), $ULang->t("дней")) . ' ' . $ULang->t('за') . ' ' . $Main->price($price);
  }

  function autoModeration($id_ad=0, $param=[]){
     
     global $settings;

     if($settings["ads_publication_moderat"]){

       $getAd = findOne("uni_ads", "ads_id=?", [$id_ad]);

       if( $getAd["ads_status"] == 7 ){
           return 0;
       }

       if( $getAd["ads_title"] != $param["title"] ){
          return 0;
       }

       $similar_text = similar_text($getAd["ads_text"], $param["text"], $perc);

       if( intval($perc) != 100 ){
          return 0;
       }

       if( $getAd["ads_video"] != $param["video"] ){
          return 0;
       }

       $images = $this->getImages($getAd["ads_images"]);

       if(isset($_POST["gallery"])){
          foreach ($_POST["gallery"] as $key => $value) {
              if(!in_array($value, $images)){
                 return 0;
              }             
          }
       }

       return !$getAd["ads_status"] ? 0 : 1;
     
     }else{

        return 1;

     } 
     

  }

  function getAuctionWinner( $id_ad = 0 ){
    return getOne("select * from uni_ads_auction INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads_auction`.ads_auction_id_user where ads_auction_id_ad=? order by ads_auction_price desc", [$id_ad]);
  }
  
  function getSecureCommission( $amount = 0 ){
     global $settings;

     return $amount - $this->secureTotalAmountPercent( $amount );
         
  }

  function secureTotalAmountPercent( $amount = 0, $services_commission = true ){
     global $settings;

     if($settings["secure_percent_service"] && $services_commission){
        $amount = $amount - calcPercent( $amount, $settings["secure_percent_service"] );
     }

     if($settings["secure_other_payment"]){
        $amount = $amount - round($settings["secure_other_payment"], 2);
     }

     if($settings["secure_percent_payment"]){
        return $amount - calcPercent( $amount, $settings["secure_percent_payment"] );
     }elseif($settings["secure_percent_service"] && $services_commission){
        return round($amount, 2); 
     }elseif($settings["secure_other_payment"]){
        return $amount - round($settings["secure_other_payment"], 2); 
     }else{
        return round($amount, 2);
     }
         
  } 

  function getStatusSecure( $data = [], $price = 0 ){
     global $settings;

     if(!$price) $price = $data["ads_price"];

     if(!$settings["secure_payment_service_name"]){
        if(!$settings["secure_payment_balance"]){
            return false;
        }
     }
     
     if( $data["clients_secure"] && $settings["secure_status"] && $data["category_board_secure"] && ( $price >= $settings["secure_min_amount_payment"] && $price <= $settings["secure_max_amount_payment"] ) ){
          return true;
     }else{
          return false;
     }

  }

  function getStatusDelivery( $data = [] ){
     global $settings;
     if($settings["delivery_service"]){
          if($this->checkCategoryDelivery($data['category_board_id']) && $data['ads_delivery_status'] && $data['clients_delivery_status'] && $data['ads_price'] >= $settings['delivery_from_price'] && $data['ads_price'] <= $settings['delivery_before_price'] && $data['ads_delivery_weight'] >= $settings['delivery_weight_min'] && $data['ads_delivery_weight'] <= $settings['delivery_weight_max']){
              return true;
          }
     }
     return false;
  }

  function checkCategoryDelivery($id_cat){
      global $settings;
      if($settings["delivery_available_categories"]){
        $delivery_available_categories = explode(',', $settings["delivery_available_categories"]);
        if(in_array($id_cat, $delivery_available_categories)){
            return true;
        }
      }
      return false;
  }

  function cardAdOrder($data = array()){
    global $config;

    $image = $this->getImages($data["ad"]["ads_images"]);

    return '

      <div class="board-view-ads-left" >
        <div class="board-view-ads-img" >
          <img src="'.Exists($config["media"]["big_image_ads"],$image[0],$config["media"]["no_image"]).'">
        </div>
      </div>

      <div class="board-view-ads-right" >

        <a href="'.$this->alias($data["ad"]).'"  >'.$data["ad"]["ads_title"].'</a>
        <br>
        <span>'.$data["ad"]["city_name"].'</span>

      </div>

      <div class="clr" ></div>

    ';

  }

  function timeSecureReservation( $time="" ){

     $ULang = new ULang();

     if( time() < strtotime("+10 minutes", strtotime($time)) ){
         $diff = $this->dateDiff( date("Y-m-d H:i:s",strtotime("+10 minutes", strtotime($time))) );
         return $diff["min"] . ' ' . $ULang->t('мин');       
     }else{
         return '0 '.$ULang->t('мин');
     }

  }

  function secureStatusLabel( $data = [] ){

    $ULang = new ULang();

    if($data["secure_status"] == 0){ 

      return $ULang->t('Ожидается оплата');

    }elseif($data["secure_status"] == 1){

          return $ULang->t('Заказ оплачен');

    }elseif($data["secure_status"] == 2){

          if( $data["secure_id_user_buyer"] == $_SESSION["profile"]["id"] ){

             return $ULang->t('Подтвердите получение');

          }elseif( $data["secure_id_user_seller"] == $_SESSION["profile"]["id"] ){
            
             return $ULang->t('Ожидаем подтверждение покупателя');

          }

    }elseif($data["secure_status"] == 3){

          return $ULang->t('Заказ завершён');

    }elseif($data["secure_status"] == 4){

          return $ULang->t('Открыт спор');

    }elseif($data["secure_status"] == 5){

          return $ULang->t('Заказ отменен');

    }


  }

  function addSecurePayments( $data = [] ){

     insert("INSERT INTO uni_secure_payments(secure_payments_date,secure_payments_id_order,secure_payments_amount,secure_payments_status_pay,secure_payments_id_user,secure_payments_status,secure_payments_amount_percent,secure_payments_from_balance)VALUES(?,?,?,?,?,?,?,?)", [ date("Y-m-d H:i:s"), $data["id_order"], $data["amount"], $data["status_pay"], $data["id_user"], $data["status"], $data["amount_percent"], intval($data["from_balance"]) ]);

  }

  function secureResultPay( $data = [] ){
    
    $Main = new Main();
    $ULang = new ULang();

    $getPayment = findOne("uni_secure_payments", "secure_payments_id_user=? and secure_payments_id_order=? and secure_payments_status!=?", [ $data["id_user"], $data["id_order"], 0 ]);

    if($getPayment){
        if($getPayment["secure_payments_status_pay"] == 0){

          ?>
          <p class="mt15"><?php echo $Main->price( $getPayment["secure_payments_amount_percent"] ); ?> <?php echo $ULang->t("будут зачислены на ваш счет в течении 24 часа."); ?></p>
          <?php

        }elseif($getPayment["secure_payments_status_pay"] == 1){
          
          ?>
          <p class="mt15"><?php echo $Main->price( $getPayment["secure_payments_amount_percent"] ); ?> <?php echo $ULang->t("зачислены на ваш счет."); ?></p>
          <?php

        }elseif($getPayment["secure_payments_status_pay"] == 2){

          $getUser = findOne("uni_clients", "clients_id=?", [$data["id_user"]]);
          
          ?>
          <p class="mt15"><?php echo $ULang->t("Не удалось перевести деньги. Проверьте номер счета"); ?></p>
           
          <a class="btn-custom-mini btn-color-blue mb5" href="<?php echo _link("user/".$getUser["clients_id_hash"]."/settings"); ?>" > <span><?php echo $ULang->t("Указать другой счет"); ?></span> </a>

          <p class="mt10" ><?php echo $ULang->t("При возникновении трудностей с зачислением средств"); ?>, <a href="<?php echo _link("feedback"); ?>"><?php echo $ULang->t("напишите в службу поддержки"); ?></a></p> 
          <?php

        }
    }

  }

  function publicationAndStatus( $data = [] ){
    $ULang = new ULang();

      if(!$data["ads_status"]){
          return '<span class="ad-status-label ad-status-label-'.$data["ads_status"].'" >' . $this->status($data["ads_status"]) . '</span>';
      }elseif(strtotime($data["ads_period_publication"]) > time()){
          return '<span class="ad-status-label ad-status-label-'.$data["ads_status"].'" >' . $this->status($data["ads_status"]) . '</span>';
      }else{
          return '<span class="ad-status-label ad-status-label-0" >'.$ULang->t("Истек срок").'</span>';
      }

  }

  function getCountChangeOptionsCity( $data = [] ){

      if(isset($data["param_filter"]["filter"]["area"])){
        $count += count($data["param_filter"]["filter"]["area"]);
      }

      if(isset($data["param_filter"]["filter"]["metro"])){
        $count += count($data["param_filter"]["filter"]["metro"]);
      }

      return (int)$count;      
  }

  function getComments( $id_ad = 0 ){
      $array = array();
    
      $get = getAll("SELECT * FROM uni_ads_comments INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads_comments`.ads_comments_id_user WHERE ads_comments_id_ad=? ORDER By ads_comments_id desc", [$id_ad]);

      if (count($get)) { 
                        
            foreach($get AS $result){

                $array['ads_comments_id_parent'][$result['ads_comments_id_parent']][$result['ads_comments_id']] =  $result;

            }  

      }            

      return $array;
         
  }

  function outComments($id_parent = 0, $getComments=[]) {
    global $config;

    $Profile = new Profile();
    $ULang = new ULang();
      
      if (isset($getComments["ads_comments_id_parent"][$id_parent])) {
          foreach ($getComments["ads_comments_id_parent"][$id_parent] as $value) {

              ?>

                  <div <?php if($id_parent != 0){ echo 'style="margin-left: 60px;"'; } ?> >
                     <div class="module-comments-avatar" >
                       <img src="<?php echo $Profile->userAvatar($value); ?>">
                     </div>
                     <div class="module-comments-content" >
                       
                       <?php if( $_SESSION['cp_auth'][ $config["private_hash"] ] || intval($_SESSION["profile"]["id"]) == $value["ads_comments_id_user"] ){ ?>
                       <span class="module-comments-delete" data-id="<?php echo $value["ads_comments_id"]; ?>" > <i class="las la-trash"></i> <?php echo $ULang->t("Удалить"); ?> </span>
                       <?php } ?>

                       <p> <strong> <?php echo $Profile->name($value); ?> </strong> <span><?php echo datetime_format($value["ads_comments_date"]); ?></span> </p>

                       <?php
                       if($value["ads_comments_id_parent"]!=0){
                          $getMsg = getOne("SELECT * FROM uni_ads_comments INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads_comments`.ads_comments_id_user WHERE ads_comments_id=?", [$value["ads_comments_id_parent"]]);
                          ?>
                          <strong><i class="las la-share"></i> <?php echo $Profile->name($getMsg); ?></strong>,
                          <?php
                       }
                       ?>
                       
                       <?php echo $value["ads_comments_text"]; ?>

                       <?php if( intval($_SESSION["profile"]["id"]) != $value["ads_comments_id_user"] && $_SESSION['profile']['id'] ){ ?>

                       <div><span class="module-comments-otvet" data-id="<?php echo $value["ads_comments_id"]; ?>" ><?php echo $ULang->t("Ответить"); ?></span></div>

                       <?php } ?>

                       <div class="module-comments-form-otvet" >
                         <form class="module-comments-form" >
                         <textarea name="text" ></textarea>
                         <button class="module-comments-form-send" ><i class="las la-arrow-right"></i></button>
                         <input type="hidden" name="id_ad" value="<?php echo $value["ads_comments_id_ad"]; ?>" >
                         <input type="hidden" name="id_msg" value="<?php echo $value["ads_comments_id"]; ?>" >
                         <input type="hidden" name="token" value="<?php echo md5($config["private_hash"].$value["ads_comments_id"].$value["ads_comments_id_ad"]); ?>" >
                         </form>
                       </div>

                     </div>
                     <div class="clr" ></div>
                  </div>

              <?php

              $this->outComments($value["ads_comments_id"], $getComments);

          }
      }
  }

  function idsComments($parent_id=0, $getComments=[]){
      
      if(isset($getComments['ads_comments_id_parent'][$parent_id])){

            foreach($getComments['ads_comments_id_parent'][$parent_id] as $value){
              
              $ids[] = $value['ads_comments_id'];
              
              if( $getComments['ads_comments_id_parent'][$value['ads_comments_id']] ){
                $ids[] = $this->idsComments($value['ads_comments_id'],$getComments);
              }
                                                                  
            }

      }

      return implode(",", $ids);

  }

  function adPeriodPub($period=30){
      global $settings;

      if($settings["ad_create_period"]){

         $ad_create_period_list = explode(",", $settings["ad_create_period_list"]);

         if( in_array( $period , $ad_create_period_list) ){
             $ad_period = date("Y-m-d H:i:s", time() + (intval($period) * 86400) );
             $ad_period_day = (int)$period;
         }else{
             $ad_period = date("Y-m-d H:i:s", time() + ($settings["ads_time_publication_default"] * 86400) );
             $ad_period_day = $settings["ads_time_publication_default"];
         }

      }else{
         $ad_period = date("Y-m-d H:i:s", time() + ($settings["ads_time_publication_default"] * 86400) );
         $ad_period_day = $settings["ads_time_publication_default"];
      }

      return ["date"=>$ad_period, "days"=>$ad_period_day];

  }

  function linkMap( $data = [] ){

     $vars = [];

     unset( $data["param_filter"]["filter"]["sort"] );

     if($data["param_filter"]) $vars[] = http_build_query($data["param_filter"]);

     if($data["category"]["category_board_id"]) $vars[] = "id_c=" . $data["category"]["category_board_id"];

     if( count($vars) ){
         $vars_params = "?" . implode("&", $vars);
     }

     return _link( "map/" . $_SESSION["geo"]["alias"] . $vars_params );

  }

  function buildNameSubscribe($url=""){
    
    $CategoryBoard = new CategoryBoard();
    $Geo = new Geo();
    $ULang = new ULang();

    $getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

      if($url){

           $url_vars = explode("?", $url);
           $url_parse = explode("/", $url_vars[0]);

           if( $url_vars[1] ){
               
               if( strpos($url_vars[1], "filter") !== false ){
                 parse_str($url_vars[1], $param_filter);

                 if(count($param_filter["filter"])){
                    $param_filter = " &bull; " . $ULang->t("Фильтров") . " " . count($param_filter["filter"]);
                 }
               }

           }

           if( $url_parse[0] ){
               $getGeoName = $ULang->t( $Geo->aliasOneOf($url_parse[0])["name"], [ "table" => "geo", "field" => "geo_name" ] );
           }

           if( $url_parse[1] ){

               unset($url_parse[0]);
               $alias = implode("/", $url_parse);
               
               if($getCategoryBoard["category_board_chain"][$alias]["category_board_name"]){
                  return $ULang->t( $getCategoryBoard["category_board_chain"][$alias]["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ) . " &bull; " . $getGeoName . $param_filter;
               }else{
                  return $getGeoName . $param_filter;
               }

           }else{

               return $getGeoName . $param_filter;

           }
                   
      }    

  }

  function buildUrlCatalog( $data = [] ){
       
       if( count($data["param_filter"]) ){
           return trim( $_SESSION["geo"]["alias"] . "/" . $data["category"]["category_board_chain"], "/" ) . "?" . http_build_query($data["param_filter"]);
       }else{
           return trim( $_SESSION["geo"]["alias"] . "/" . $data["category"]["category_board_chain"], "/" );
       }
       
  }

  function outPrice( $param = [] ){
       
    $ULang = new ULang();
    $Main = new Main();
    
    if( $param["data"]["ads_price_old"] ){
        if(!$param["shop"]) $param["data"]["ads_price_old"] = 0;
    }
    
    if( $param["data"]["ads_price_old"] ){
      $html = '<span class="'.$param["class_price"].'" >' . $Main->adPrefixPrice($Main->price($param["data"]["ads_price"], $param["data"]["ads_currency"], $param["abbreviation_million"]),$param["data"]) . '</span>';
      $html .= '<span class="'.$param["class_price_old"].'" >' . $Main->price($param["data"]["ads_price_old"], $param["data"]["ads_currency"], $param["abbreviation_million"]) . '</span>';
    }elseif( $param["data"]["ads_price"] ){
      $html = '<span class="'.$param["class_price"].'" >'.$Main->adPrefixPrice($Main->price($param["data"]["ads_price"], $param["data"]["ads_currency"], $param["abbreviation_million"]),$param["data"]).'</span>';
    }else{

       if( $param["data"]["ads_price_free"] ){
           $html = '<span class="'.$param["class_price"].'" >'.$ULang->t("Даром").'</span>';
       }else{ 
           $html = '<span class="'.$param["class_price"].'" >'.$ULang->t("Цена не указана").'</span>';
       }

    }

    return $html;

  }

  function outAdViewPrice( $param = [] ){
       
    $ULang = new ULang();
    $Main = new Main();
    $Shop = new Shop();

    if( $param["data"]["ads_price_old"] ){
        $getShop = $Shop->getUserShop( $param["data"]["ads_id_user"] );
        if(!$getShop) $param["data"]["ads_price_old"] = 0;
    }

    if( $param["data"]["ads_price_old"] ){

      return '
        <div class="board-view-old-price" >
          '.$Main->price($param["data"]["ads_price_old"], $param["data"]["ads_currency"]).'
        </div>      
        <div class="board-view-price price-currency" >
          '.$Main->adPrefixPrice($Main->price($param["data"]["ads_price"],$param["data"]["ads_currency"]),$param["data"]).'
          '.$Main->adOutCurrency($param["data"]["ads_price"],$param["data"]["ads_currency"],$param["data"]).'
        </div>
      ';

    }elseif( $param["data"]["ads_price"] ){

      return '
        <div class="board-view-price price-currency" >
          '.$Main->adPrefixPrice($Main->price($param["data"]["ads_price"],$param["data"]["ads_currency"]),$param["data"]).'
          '.$Main->adOutCurrency($param["data"]["ads_price"],$param["data"]["ads_currency"],$param["data"]).'
        </div>
      ';

    }else{
       
       if( $param["data"]["ads_price_free"] ){
           return '<div class="board-view-price" >'.$ULang->t("Даром").'</div>';
       }else{
           return '<div class="board-view-price" >'.$ULang->t("Цена не указана").'</div>'; 
       }
       

    }


  }

  function outStatus( $service = [], $value = [] ){

    $ULang = new ULang();
    $html = "";

    if($service){
        if( isset($service[1]) && isset($service[2]) ){
            $html = '<div data-tippy-placement="top" title="'.$ULang->t("Поднятие в ленте").'" class="item-service-top" > <i class="las la-angle-double-up"></i> </div>';
            $html .= '<div data-tippy-placement="top" title="'.$ULang->t("Вип объявление").'" class="item-vip" > <i class="las la-crown"></i> </div>';
        }elseif( isset($service[1]) ){
            $html = '<div data-tippy-placement="top" title="'.$ULang->t("Поднятие в ленте").'" class="item-service-top" > <i class="las la-angle-double-up"></i> </div>';
        }elseif( isset($service[2]) ){
            $html = '<div data-tippy-placement="top" title="'.$ULang->t("Вип объявление").'" class="item-vip" > <i class="las la-crown"></i> </div>';
        }elseif( isset($service[3]) ){
            $html = '<div data-tippy-placement="top" title="'.$ULang->t("Турбо продажа").'" class="item-service-turbo" > <i class="las la-rocket"></i> </div>';
        }
    }

    if($this->getStatusSecure($value)){
      $html .= '<div class="item-secure" data-tippy-placement="top" title="'.$ULang->t("Доступна безопасная сделка").'" > <i class="las la-shield-alt"></i> </div>';           
    }

    if($value["ads_auction"]){
      $html .= '<div class="item-auction" data-tippy-placement="top" title="'.$ULang->t("Аукцион").'" > <i class="las la-gavel"></i> </div>';
    } 
    
    if($value["ads_booking"]){
        if($value["category_board_booking_variant"] == 0){
            $html .= '<div class="item-booking" data-tippy-placement="top" title="'.$ULang->t("Онлайн-бронирование").'" > <i class="las la-hourglass"></i> </div>';
        }elseif($value["category_board_booking_variant"] == 1){
            $html .= '<div class="item-booking" data-tippy-placement="top" title="'.$ULang->t("Онлайн-аренда").'" > <i class="las la-hourglass"></i> </div>';
        }
    }

    return $html;

  }

  function outStatusAdmin( $service = [], $value = [] ){

    $html = "";

    if( isset($service[1]) && isset($service[2]) ){
        $html = '<div data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Поднятие в ленте" >Top</div>';
        $html .= '<div data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Вип объявление" class="item-vip" >Vip</div>';
    }elseif( isset($service[1]) ){
        $html = '<div data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Поднятие в ленте" class="item-service-top" >Top</div>';
    }elseif( isset($service[2]) ){
        $html = '<div data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Вип объявление" class="item-vip" >Vip</div>';
    }elseif( isset($service[3]) ){
        $html = '<div data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Турбо продажа" class="item-service-turbo" >Turbo</div>';
    }

    if($this->getStatusSecure($value)){
      $html .= '<div class="item-secure" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Доступна безопасная сделка" > <i class="la la-shield"></i> </div>';           
    }

    if($value["ads_auction"]){
      $html .= '<div class="item-auction" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Аукцион" > <i class="la la-gavel"></i> </div>';
    } 
    
    if($value["ads_booking"]){
        if($value["category_board_booking_variant"] == 0){
            $html .= '<div class="item-booking" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Онлайн-бронирование" > <i class="la la-hourglass"></i> </div>';
        }elseif($value["category_board_booking_variant"] == 1){
            $html .= '<div class="item-booking" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Онлайн-аренда" > <i class="la la-hourglass"></i> </div>';
        }
    }

    return $html;

  }

  function outGallery( $data = [] ){
     global $config,$settings;

     $Ads = new Ads();
     $Shop = new Shop();
     $Profile = new Profile();
     $ULang = new ULang();

     if( $data["ad"]["clients_type_person"] == "company" ){
        $type_person = $ULang->t("Компания");
     }else{
        $type_person = $ULang->t("Частное лицо");
     }

     if( count($data["image"]) ){

         $return .= '<div class="photo-mobile-slider"  style="height:'.$data["height"].'px" >';

         foreach ( array_slice($data["image"], 0, 4) as $key => $name ) {
             
              $return .= '
              <a style="height:'.$data["height"].'px" href="'.$Ads->alias($data["ad"]).'" title="'.$data["ad"]["ads_title"].'" >
                <img class="image-autofocus" alt="'.$data["ad"]["ads_title"].'" src="'.Exists($config["media"]["small_image_ads"],$name,$config["media"]["no_image"]).'"  >
              </a>
              ';

         }

         if( $data["shop"] ){

           $return .= '
               <a class="ads-item-user-card" style="height:'.$data["height"].'px" href="'.$Shop->linkShop($data["shop"]["clients_shops_id_hash"]).'" title="'.$data["shop"]["clients_shops_title"].'" >
                    <div class="ads-item-user-card-avatar" >
                         <img class="image-autofocus" src="'.Exists($config["media"]["other"], $data["shop"]["clients_shops_logo"], $config["media"]["no_image"]).'">
                    </div>          
                    <span class="ads-item-user-card-name" >'.custom_substr($data["shop"]["clients_shops_title"], 20, "...").'</span>
                    <span class="ads-item-user-card-person" >'.$ULang->t("Магазин").'</span>
               </a>
           ';

         }else{
           
           $return .= '
               <a class="ads-item-user-card" style="height:'.$data["height"].'px" href="'._link( "user/" . $data["ad"]["clients_id_hash"] ).'" >
                    <div class="ads-item-user-card-avatar" >
                         <img class="image-autofocus" src="'.$Profile->userAvatar($data["ad"]).'">
                    </div>         
                    <span class="ads-item-user-card-name" >'.custom_substr($Profile->name($data["ad"]), 20, "...").'</span>
                    <span class="ads-item-user-card-person" >'.$type_person.'</span>
               </a>
           ';

         }

         $return .= '
           </div>
         ';


     }else{
        
         if( $data["shop"] ){

           $return .= '
               <a class="ads-item-user-card" style="height:'.$data["height"].'px;" href="'.$Ads->alias($data["ad"]).'" title="'.$data["shop"]["clients_shops_title"].'" >
                    <div class="ads-item-user-card-avatar" >
                         <img class="image-autofocus" src="'.Exists($config["media"]["other"], $data["shop"]["clients_shops_logo"], $config["media"]["no_image"]).'">
                    </div>          
                    <span class="ads-item-user-card-name" >'.custom_substr($data["shop"]["clients_shops_title"], 20, "...").'</span>
                    <span class="ads-item-user-card-person" >'.$ULang->t("Магазин").'</span>
               </a>
           ';

         }else{
           
           $return .= '
               <a class="ads-item-user-card" style="height:'.$data["height"].'px;" href="'.$Ads->alias($data["ad"]).'" >
                    <div class="ads-item-user-card-avatar" >
                         <img class="image-autofocus" src="'.$Profile->userAvatar($data["ad"]).'">
                    </div>            
                    <span class="ads-item-user-card-name" >'.custom_substr($Profile->name($data["ad"]), 20, "...").'</span>
                    <span class="ads-item-user-card-person" >'.$type_person.'</span>
               </a>
           ';

         }

     }

     return $return;

  }

  function autoTitle( $filters = [], $category = [] ){

     $title = [];
     $items = [];
     
     if( !$category["category_board_auto_title_template"] ){
         return $category["category_board_name"];
     }

     $template = explode(",", $category["category_board_auto_title_template"]);

     if( $filters ){

          foreach($filters AS $id_filter=>$array){
              
              $getFilter = findOne( "uni_ads_filters", "ads_filters_id=?", [ intval($id_filter) ] );
              
              if( $getFilter->ads_filters_type != "input" && $getFilter->ads_filters_type != "input_text" ){
                  $getItem = findOne( "uni_ads_filters_items", "ads_filters_items_id=?", [ intval($array[0]) ] );
                  $items[ $getFilter["ads_filters_alias"] ] = $getItem->ads_filters_items_value;
              }else{
                  $items[ $getFilter["ads_filters_alias"] ] = trim( clear($array[0]) );
              }
  
          } 

     } 

     if(count($template) == 1){

         if(count($items) && strpos($template[0], "{") !== false){
            foreach ($items as $filter_alias => $filter_value) {
                $template[0] = str_replace( '{'.$filter_alias.'}' , $filter_value, $template[0]);
            }
         } 
         $template[0] = trim(preg_replace('/\{.*?}/', '', $template[0]));  
         if($template[0]){
            $title[] = trim($template[0]);
         }

     }else{

         foreach ($template as $value) {
            if(count($items) && strpos($value, "{") !== false){
                
                foreach ($items as $filter_alias => $filter_value) {
                    $value = str_replace( '{'.$filter_alias.'}' , $filter_value, $value);
                }

                if($value && strpos($value, '{') === false){
                    $title[] = trim($value); 
                }
              
            }else{

                $title[] = trim($value);

            }
         }
         
     }

     if( count($title) ){
        return implode(", ", $title);
     }else{
        return $category["category_board_name"];
     }

  }

  function getCityDistance( $post = [], $ad_not_city_distance = [] ){
      global $settings;
      
      $Filters = new Filters();
      
      $ids_city = [];

      if( $_SESSION["geo"]["data"]["city_id"] ){

          $getСityDistance = getAll( "select * from uni_city_distance where (city_distance_id_city_from=".$_SESSION["geo"]["data"]["city_id"]." or city_distance_id_city_to=".$_SESSION["geo"]["data"]["city_id"].") and city_distance_km <= ".$settings["catalog_city_distance"], [] );
          if( count($getСityDistance) ){
              foreach ($getСityDistance as $key => $value) {
                  if( count($ad_not_city_distance) ){
                      if( !in_array( $value["city_distance_id_city_to"] , $ad_not_city_distance ) ){
                           $ids_city[$value["city_distance_id_city_to"]] = $value["city_distance_id_city_to"];
                      }
                      if( !in_array( $value["city_distance_id_city_from"] , $ad_not_city_distance ) ){
                        $ids_city[$value["city_distance_id_city_from"]] = $value["city_distance_id_city_from"];
                      }                     
                  }else{
                      $ids_city[$value["city_distance_id_city_to"]] = $value["city_distance_id_city_to"];
                      $ids_city[$value["city_distance_id_city_from"]] = $value["city_distance_id_city_from"];
                  }
              }
          }
          
          if( count($ids_city) ){
              return $Filters->queryFilter($post, ["navigation"=>true, "page"=>1, "disable_query_geo" => true, "extra_query" => " and ads_city_id IN(".implode(",", $ids_city).")"]);
          }

      }     

  }

  function outAdAddress($data=[]){
 
        $ULang = new ULang();

        $address = [];

        if($data["ad"]["ads_address"]){

            if(strpos(mb_strtolower($data["ad"]["ads_address"], 'UTF-8'), mb_strtolower($data["ad"]["region_name"], 'UTF-8')) === false){
                $address[] = $ULang->t( $data["ad"]["region_name"] , [ "table"=>"geo", "field"=>"geo_name" ] );
            }
            if(strpos(mb_strtolower($data["ad"]["ads_address"], 'UTF-8'), mb_strtolower($data["ad"]["city_name"], 'UTF-8')) === false){
                $address[] = $ULang->t( $data["ad"]["city_name"] , [ "table"=>"geo", "field"=>"geo_name" ] );
            }  
            $address[] = $data["ad"]["ads_address"];    
            
            if($data["areas"]) $address[] = $ULang->t("р-н") . " " . $data["areas"];

            return implode(', ', $address);
        }else{
            if($data["areas"]){
                return $ULang->t( $data["ad"]["region_name"] , [ "table"=>"geo", "field"=>"geo_name" ] ) . ", " . $ULang->t( $data["ad"]["city_name"] , [ "table"=>"geo", "field"=>"geo_name" ] ) . ", " . $ULang->t("р-н") . " " . $data["areas"];
            }else{
                return $ULang->t( $data["ad"]["region_name"] , [ "table"=>"geo", "field"=>"geo_name" ] ) . ", " . $ULang->t( $data["ad"]["city_name"] , [ "table"=>"geo", "field"=>"geo_name" ] );
            }
        }

  }

  function outAdAddressArea($data=[]){

    global $settings;

    $ULang = new ULang();
    $getArea = [];

    if($settings["main_type_products"] == 'physical'){

        if(isset($data["ads_area_ids"])){
            $getArea = getOne("select * from uni_city_area_variants INNER JOIN `uni_city_area` ON `uni_city_area`.city_area_id = `uni_city_area_variants`.city_area_variants_id_area where city_area_variants_id_area=? and city_area_variants_id_ad=?",[$data["ads_area_ids"],$data["ads_id"]]); 
        }

        if(isset($getArea["city_area_name"])){
            return $ULang->t( $data["city_name"], [ "table" => "geo", "field" => "geo_name" ] ).', '.$ULang->t("р-н").' '.$getArea["city_area_name"];
        }else{
            return $ULang->t( $data["city_name"], [ "table" => "geo", "field" => "geo_name" ] ); 
        }

    }

  }

  function CatalogOutAdGallery($images,$data){
       global $config;

       $ULang = new ULang();

       $results = '';

       if(count($images)){
           
           foreach ( array_slice($images, 0, 4) as $key_image => $name ) {
               if(file_exists($config['basePath'].'/'.$config["media"]["small_image_ads"].'/'.$name)){
                    $results .= '<img class="image-autofocus ad-gallery-hover-slider-image lazyload" data-src="'.Exists($config["media"]["small_image_ads"],$name,$config["media"]["no_image"]).'" data-key="'.$key_image.'" alt="'.$data["ads_title"].'" src="'.Exists($config["media"]["small_image_ads"],$name,$config["media"]["no_image"]).'"  >';
               }elseif(file_exists($config['basePath'].'/'.$config["media"]["big_image_ads"].'/'.$name)){
                    $results .= '<img class="image-autofocus ad-gallery-hover-slider-image lazyload" data-src="'.Exists($config["media"]["big_image_ads"],$name,$config["media"]["no_image"]).'" data-key="'.$key_image.'" alt="'.$data["ads_title"].'" src="'.Exists($config["media"]["big_image_ads"],$name,$config["media"]["no_image"]).'"  >';
               }
           }

           if(!$results){
                return '<div class="item-img-no-photo" >'.$ULang->t('Фото').'<br>'.$ULang->t('нет').'</div>';
           }

           if(count($images)>1){
              
              $results .= '<div class="ad-gallery-hover-slider" >'; 

              foreach ( array_slice($images, 0, 4) as $key_image => $name ) {
                  $results .= '<span data-key="'.$key_image.'" ></span>';
              }

              $results .= '</div>';

           }

           $results .= '<span class="item-grid-count-photo" ><i class="las la-camera"></i> '.count($images).'</span>';

           return $results;

       }else{

           return '<div class="item-img-no-photo" >'.$ULang->t('Фото').'<br>'.$ULang->t('нет').'</div>';

       } 

  }

  function adCountActiveRent($id_ad){
      return (int)getOne("select count(*) as total from uni_ads_booking where ads_booking_id_ad=? and ads_booking_date_end >= ?", [$id_ad,date('Y-m-d H:i:s')])['total'];
  }

  function userCountAvailablePaidAddCategory($id_cat, $id_user){
      return (int)getOne("select count(*) as total from uni_ads where ads_id_cat=? and ads_id_user=? and ads_status IN(0,1,2,7)", [$id_cat, $id_user])["total"];
  }

  function buildTagsSearch($data = []){
    $getCategories = (new CategoryBoard())->getCategories("where category_board_visible=1");
     if($data){
        return $getCategories["category_board_id"][$data["cat_id"]]["category_board_name"];
     }
     return "";
  }

  function getTotalCountAds(){

      $Cache = new Cache();

      $key = "total_count_ads";

      $getCache = $Cache->get( [ "table" => "uni_ads", "key" => $key ] );

      if( $getCache !== false ){

         return (int)$getCache;

      }else{
         
         $count = (int)getOne("select count(*) as total from uni_ads INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user where ads_status!='8' and clients_status!='3'")["total"];

         $Cache->set( [ "table" => "uni_ads", "key" => $key, "data" => $count ] );

         return $result;

      }

  }

  function getStatusBooking($data = []){

    global $settings;

    if($data["ads_booking"] && $settings["functionality"]['booking'] && $settings["booking_status"] && $data["category_board_booking"] && ($data["ads_price_measure"] == 'day' || $data["ads_price_measure"] == 'daynight' || $data["ads_price_measure"] == 'hour')){

         return true;

    }

    return false;

  }

      
}




?>