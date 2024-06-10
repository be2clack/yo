<!doctype html>
<html lang="<?php echo $settings["lang_site_default"]; ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?php echo $ULang->t("Объявления на карте"); ?> - <?php echo $settings["site_name"]; ?></title>

    <?php include $config["template_path"] . "/head.tpl"; ?>

  </head>

  <body data-prefix="<?php echo $config["urlPrefix"]; ?>" data-template="<?php echo $config["template_folder"]; ?>" >

      <?php include $config["template_path"] . "/header.tpl"; ?>
    
      <div class="map-search-container" >

        <div class="map-search-sidebar" >

            <div class="map-search-offers-header text-right" >            
                <button class="btn-custom-mini btn-color-blue map-search-controls-filters open-modal" data-id-modal="modal-map-filters" ><i class="las la-filter mr5"></i> <?php echo $ULang->t("Фильтры"); ?> <?php if($Filters->mapCountChangeFilters($data["param_filter"]["filter"])){ ?> <span class="map-label-count" ><?php echo $Filters->mapCountChangeFilters($data["param_filter"]["filter"]); ?></span> <?php } ?></button> 
            </div>

            <div class="map-search-offer-container" >
              <div class="map-search-offers" >

                   <div class="text-right" ><button class="btn-custom-mini btn-color-light map-search-offer-container-close" ><?php echo $ULang->t("Закрыть"); ?></button></div>

                   <div class="map-search-offers-list" ></div>   

              </div>
            </div>

        </div>

        <div class="map-search-instance" id="map_instance" ></div>

      </div>

    <?php include $config["template_path"] . "/footer.tpl"; ?>

    <?php if($settings["map_vendor"] == "yandex"){ ?>

      <script src="//api-maps.yandex.ru/2.1/?apikey=<?php echo $settings["map_yandex_key"]; ?>&lang=ru_RU" type="text/javascript"></script>

    <?php }elseif($settings["map_vendor"] == "google"){ ?>

      <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $settings["map_google_key"]; ?>&libraries=places"></script>
      <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>

    <?php }elseif($settings["map_vendor"] == "openstreetmap"){ ?>

      <link rel="stylesheet" href="https://unpkg.com/leaflet@0.7.3/dist/leaflet.css" />
      
      <script src="https://unpkg.com/leaflet@0.7.3/dist/leaflet.js" ></script> 

      <script src="<?php echo $config["urlPath"].'/'.$config["template_folder"]; ?>/js/leaflet.markercluster.js" ></script> 
      <link rel="stylesheet" href="<?php echo $config["urlPath"].'/'.$config["template_folder"]; ?>/css/leaflet.markercluster.css" />

    <?php } ?>

    <script type="text/javascript">
      
      $(document).ready(function () {

          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });

         function countDisplay(){
            $.ajax({type: "POST",url: $("body").data("prefix") + "systems/ajax/controller.php",data: "action=ads/update_count_display",dataType: "json",cache: false});
         }

         function loadOffers(ids,page){

              $(".map-search-offers-list").html(`
                      <div class="preload" >
                          <div class="spinner-grow preload-spinner" role="status">
                            <span class="sr-only"></span>
                          </div>
                      </div>
              `);

              $.ajax({type: "POST",url: $("body").data("prefix") + "systems/ajax/controller.php",data: "page=" + page + "&ids=" + ids + "&action=ads/load_offers_map",dataType: "json",cache: false,                        
                  success: function (data){
                      $(".map-search-offers-list").html( data["offers"] );         
                      countDisplay();                                                    
                  }
              });

         }

         <?php if($settings["map_vendor"] == "yandex"){ ?>

          var idsString = "";
          var map = null;
          var labelGeoObjects;
          var objectManager;

          var coorTopLeft;
          var coorTopRight;
          var coorBottomLeft;
          var coorBottomRight;

          function loadPoints(page=1){

              var bounds = map.getBounds();
              coorTopLeft = bounds[1][0];
              coorTopRight = bounds[1][1];
              coorBottomLeft = bounds[0][0];
              coorBottomRight = bounds[0][1];

              $.ajax({type: "POST",url: $("body").data("prefix") + "systems/ajax/controller.php",data: $(".modal-form-filter").serialize() + "&coorTopLeft=" + coorTopLeft + "&coorTopRight=" + coorTopRight + "&coorBottomLeft=" + coorBottomLeft + "&coorBottomRight=" + coorBottomRight + "&page=" + page + "&action=ads/load_points_map",dataType: "json",cache: false,                        
                success: function (data){

                  objectManager.add(data);

                  if(page < data["pages"] && data["total"]!=0){
                    page = page + 1;
                    loadPoints(page);
                  }

                }
            });

          }

          ymaps.ready(['Map', 'Polygon']).then(function() {
        
            map = new ymaps.Map('map_instance', { center: [<?php echo $data["geo_lat"]; ?>,<?php echo $data["geo_lon"]; ?>], zoom: 10, controls: [] });

            geoObjects = [];
            
            objectManager = new ymaps.ObjectManager({
                clusterize: true,
                gridSize: 18,
                openBalloonOnClick: false,
                clusterDisableClickZoom: true
            });

            objectManager.objects.options.set('preset', 'islands#redDotIcon');
            objectManager.clusters.options.set('preset', 'islands#redDotIcon');
            map.geoObjects.add(objectManager);

            loadPoints();

            map.events.add('boundschange', function(e) { 

              var bounds = map.getBounds();
              coorTopLeft = bounds[1][0];
              coorTopRight = bounds[1][1];
              coorBottomLeft = bounds[0][0];
              coorBottomRight = bounds[0][1];

              loadPoints();

            });

            objectManager.clusters.events.add('click', function (e) {

                var ids = [];
                var cluster = objectManager.clusters.getById(e.get('objectId')),
                objects = cluster.properties.geoObjects;

                objects.forEach(function(element) {
                   ids.push(element.id); 
                });

                $('.map-search-offer-container').show();
                $('.map-search-sidebar').css('bottom', '15px');

                idsString = ids.join(',');

                loadOffers(ids.join(','),1);            

            });

            objectManager.objects.events.add('click', function (e) {
                var objectId = e.get('objectId'),  
                object = objectManager.objects.getById(objectId);
                if(object.id != undefined){
                  $('.map-search-offer-container').show();
                  $('.map-search-sidebar').css('bottom', '15px');                  
                  loadOffers(object.id);
                }
            });

            
          });

          $(document).on('click','.pagination-map-offers a', function (e) { 

              loadOffers(idsString, $(this).data("page") );

              e.preventDefault();
          });


      <?php 
      }elseif($settings["map_vendor"] == "google"){

         ?>

          var clusterMarkers = [];
          var ids = [];
          var map = null;
          var options_googlemaps = null;
          var idsString = "";
          var gMapsLoaded = false;
          var markerCluster = null;

          var coorTopLeft = "";
          var coorTopRight = "";
          var coorBottomLeft = "";
          var coorBottomRight = "";

          function loadPoints(page=1){

              $.ajax({type: "POST",url: $("body").data("prefix") + "systems/ajax/controller.php",data: $(".modal-form-filter").serialize() + "&coorTopLeft=" + coorTopLeft + "&coorTopRight=" + coorTopRight + "&coorBottomLeft=" + coorBottomLeft + "&coorBottomRight=" + coorBottomRight + "&page=" + page + "&action=ads/load_points_map",dataType: "json",cache: false,                        
                success: function (data){

                  if(data["total"]!=0){

                    if(page == 1 && markerCluster != null){
                         clusterMarkers = [];
                         ids = [];
                         idsString = "";
                         gMapsLoaded = false;
                         markerCluster = null;
                          options_googlemaps = {
                              zoom: 6,
                              center: new google.maps.LatLng(<?php echo $data["geo_lat"]; ?>,<?php echo $data["geo_lon"]; ?>),
                              mapTypeId: google.maps.MapTypeId.ROADMAP
                          }

                          map = new google.maps.Map(document.getElementById("map_instance"), options_googlemaps);
                    }

                    $.each(data["features"], function (key, data) {
                      
                      let latLng = new google.maps.LatLng(data["geometry"]["coordinates"][0], data["geometry"]["coordinates"][1]);
                      
                      let marker = new google.maps.Marker({
                        position: latLng,
                        map: map,
                        title: data["id"],
                      });

                      clusterMarkers.push(marker);

                      google.maps.event.addListener(marker, "click", (function(marker) {
                          return function() {

                              if(data["id"] && data["id"] != undefined){
                                 $('.map-search-offer-container').show();
                                 $('.map-search-sidebar').css('bottom', '15px');                                 
                                 loadOffers(data["id"]);
                              }

                          }
                      })(marker));

                    });

                    markerCluster = new MarkerClusterer(map, clusterMarkers,
                      { imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
                    });

                    google.maps.event.addListener(markerCluster,'clusterclick',function(cluster) {

                      ids = [];
                      var markers = cluster.getMarkers();

                      for(var i = 0; i < markers.length; i++) {
                          ids.push(markers[i].getTitle()); 
                      }

                      $('.map-search-offer-container').show();
                      $('.map-search-sidebar').css('bottom', '15px');

                      idsString = ids.join(',');

                      loadOffers(ids.join(','),1);  

                    });

                    if(page < data["pages"]){
                      page = page + 1;
                      loadPoints(page);
                    }

                  }

                }
            });

          }

          window.gMapsCallback = function(){
              gMapsLoaded = true;
              $(window).trigger("gMapsLoaded");
          }
          window.loadGoogleMaps = function(){
              if(gMapsLoaded) return window.gMapsCallback();
              var script_tag = document.createElement("script");
              script_tag.setAttribute("type","text/javascript");
              script_tag.setAttribute("src","https://maps.googleapis.com/maps/api/js?key=<?php echo $settings["map_google_key"]; ?>&callback=gMapsCallback");
              (document.getElementsByTagName("head")[0] || document.documentElement).appendChild(script_tag);
          }

          $(window).bind("gMapsLoaded", initMap);
          window.loadGoogleMaps();

          function initMap() {

              options_googlemaps = {
                  zoom: 6,
                  center: new google.maps.LatLng(<?php echo $data["geo_lat"]; ?>,<?php echo $data["geo_lon"]; ?>),
                  mapTypeId: google.maps.MapTypeId.ROADMAP
              }

              map = new google.maps.Map(document.getElementById("map_instance"), options_googlemaps);

              loadPoints();

              google.maps.event.addListener(map, 'idle', function(){

                let ne = map.getBounds().getNorthEast();
                let sw = map.getBounds().getSouthWest();

                coorTopLeft = ne.lat();
                coorTopRight = ne.lng();
                coorBottomLeft = sw.lat();
                coorBottomRight = sw.lng();

                loadPoints();

              });

          }

          $(document).on('click','.pagination-map-offers a', function (e) { 

              loadOffers(idsString, $(this).data("page") );

              e.preventDefault();
          });

          google.maps.event.addDomListener(window, "load", initMap);

         <?php

      }elseif($settings["map_vendor"] == "openstreetmap"){ ?>

          var coorTopLeft = "";
          var coorTopRight = "";
          var coorBottomLeft = "";
          var coorBottomRight = "";
          var map = null;
          var markers = L.markerClusterGroup();
          var ids = [];
          var geoJsonLayer = null;

          function loadPoints(page=1){

              var bounds = map.getBounds();
              southWest = bounds.getSouthWest();
              northEast = bounds.getNorthEast();

              coorTopLeft = northEast.lat;
              coorTopRight = northEast.lng;
              coorBottomLeft = southWest.lat;
              coorBottomRight = southWest.lng;

              $.ajax({type: "POST",url: $("body").data("prefix") + "systems/ajax/controller.php",data: $(".modal-form-filter").serialize() + "&coorTopLeft=" + coorTopLeft + "&coorTopRight=" + coorTopRight + "&coorBottomLeft=" + coorBottomLeft + "&coorBottomRight=" + coorBottomRight + "&page=" + page + "&action=ads/load_points_map",dataType: "json",cache: false,                        
                success: function (data){

                  if(data["total"]!=0){

                    if(page == 1 && geoJsonLayer != null){
                      markers.clearLayers (); 
                    }

                    geoJsonLayer = L.geoJson(data, {
                      onEachFeature: function (feature, layer) {  
                        ids.push(feature.id);
                        layer.on('click', function () {
                          $('.map-search-offer-container').show();
                          $('.map-search-sidebar').css('bottom', '15px');                          
                          loadOffers(feature.id);
                        });                        
                      }
                    });

                    markers.addLayer(geoJsonLayer);

                    map.addLayer(markers);

                    if(page < data["pages"]){
                      page = page + 1;
                      loadPoints(page);
                    }

                  }

                }
            });

          }

          map = L.map('map_instance').setView([<?php echo $data["geo_lat"]; ?>,<?php echo $data["geo_lon"]; ?>], 12);

          L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/streets-v11/tiles/{z}/{x}/{y}?access_token=<?php echo $settings["map_openstreetmap_key"]; ?>', {
              attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
          }).addTo(map);

          loadPoints();

          map.on('moveend', function(e) {

              var bounds = map.getBounds();
              southWest = bounds.getSouthWest();
              northEast = bounds.getNorthEast();

              coorTopLeft = northEast.lat;
              coorTopRight = northEast.lng;
              coorBottomLeft = southWest.lat;
              coorBottomRight = southWest.lng;

              loadPoints();

          });

      <?php } ?>

      }); 

    </script> 
  
    <div class="modal-custom-bg" id="modal-map-filters" style="display: none;" >
        <div class="modal-custom" style="max-width: 750px;" >

          <span class="modal-custom-close" ><i class="las la-times"></i></span>
          
          <div class="modal-map-container" >

            <h4> <strong><?php echo $ULang->t("Категории и фильтры"); ?></strong> </h4>
              
                <form class="modal-form-filter mt25" >

                  <?php echo $Filters->outFormFilters('map',['data'=>$data, 'categories'=>$getCategoryBoard]); ?>

                  <input type="hidden" name="id_c" value="<?php echo intval($_GET['id_c']); ?>" >

                </form>

          </div>

          <div class="modal-map-footer adaptive-buttons" >

                <?php if($data["param_filter"]["filter"] && !$data["filter"]){ ?>
                <div><button class="btn-custom btn-color-light action-clear-filter" > <?php echo $ULang->t("Сбросить"); ?> </button></div>
                <?php } ?>

                <div><button class="btn-custom btn-color-blue filter-accept" > <?php echo $ULang->t("Применить"); ?> </button></div>

          </div>

        </div>
    </div>


  </body>
</html>