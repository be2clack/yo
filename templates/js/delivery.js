$(document).ready(function () {

   var url_path = $("body").data("prefix");
   
   $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });

   $(document).on('click','.cart-delivery-box-item', function (e) {

        $('.cart-delivery-box-item').removeClass('active');
        $(this).addClass('active');

        if($(this).data('type') == 'boxberry'){
            $('.cart-delivery-box-item-boxberry').show();
            $('.cart-delivery-box-item-self').hide();
            $('input[name=delivery_type]').val('boxberry');
        }else if($(this).data('type') == 'self'){
            $('.cart-delivery-box-item-self').show();
            $('.cart-delivery-box-item-boxberry').hide();
            $('input[name=delivery_type]').val('self');
        }

   });

   $(document).on('input click','.action-input-search-delivery-city', function () {     
      var myThis = $(this); 
      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "q="+myThis.val()+"&action=order/delivery_search_city",dataType: "html",cache: false,success: function (data) { if(data != false){ myThis.next().html(data).show(); }else{ myThis.next().html('').hide() }  }});
   });

   $(document).on('click','.SearchDeliveryCityResults .item-city', function () {      
      $('.SearchDeliveryCityResults').hide();
      loadPoints($(this).attr("id-city"));
      $(this).parent().parent().find("input").val( $(this).attr("data-city") );
   });

   $(document).on('click','.delivery-change-point', function () {   
      var id_point = $(this).data('id-point');  
      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id_point="+id_point+"&action=order/delivery_change_point",dataType: "html",cache: false,success: function (data) {

        $("body").css("overflow", "auto");
        $(".modal-custom-bg").hide();

        $('input[name="delivery_id_point"]').val(id_point);

        loadPoint(id_point);

      }});
   });

   $(document).on('click','.action-change-delivery-point', function () {   

      if(!map_load){
         setTimeout(function() { $('.modal-delivery-point-map-init').html(''); loadPoints(); }, 1000);
      }

      map_load = true;

   });

  var map = null;
  var map_load = false;
  
  function loadPoints(id_city=''){

       $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id_city="+id_city+"&action=order/delivery_load_points",dataType: "json",cache: false,success: function (data) { 

          if($('#modal-delivery-point input[name=map_vendor]').val() == 'yandex'){

              if(id_city) $('.modal-delivery-point-map-init').html('');

              ymaps.ready(['Map']).then(function() {

                map = new ymaps.Map('modal-delivery-point-map-init', { center: [55.75, 37.62], zoom: 12, controls: [] });

                geoObjects = [];
                getPointData = [];
                points = [];
                
                clusterer = new ymaps.Clusterer({
                  preset: "islands#invertedDarkBlueClusterIcons",
                  groupByCoordinates: false,
                  clusterDisableClickZoom: true,
                  clusterHideIconOnBalloonOpen: false,
                  geoObjectHideIconOnBalloonOpen: false,
                  openBalloonOnClick: false,
                });

                getPointData = data['data'];
                points = data['gps'];

                var i = 0;

                $.each(points , function(index, point) { 
                  geoObjects[i] = new ymaps.Placemark(point, getPointData[index], { preset: "islands#redDotIcon" } );
                  i++;
                });

                clusterer.add(geoObjects);
                map.geoObjects.add(clusterer);
     
                clusterer.options.set({
                  gridSize: 70,
                  clusterDisableClickZoom: false
                });

                map.setBounds(clusterer.getBounds(), {
                  checkZoomRange: true
                }).then(function(){ if(map.getZoom() > 12) map.setZoom(12); });

              });

          }else if($('#modal-delivery-point input[name=map_vendor]').val() == 'google'){

              if(id_city) $('.modal-delivery-point-map-init').html('');

              var map;
              var gMapsLoaded = false;

              window.gMapsCallback = function(){
                  gMapsLoaded = true;
                  $(window).trigger("gMapsLoaded");
              }

              window.loadGoogleMaps = function(){
                  if(gMapsLoaded) return window.gMapsCallback();

                  var script_tag = document.createElement("script");
                  script_tag.setAttribute("type","text/javascript");
                  script_tag.setAttribute("id","scriptMapsCallback");
                  script_tag.setAttribute("src","https://maps.googleapis.com/maps/api/js?key="+$('#modal-delivery-point input[name=map_vendor_key]').val()+"&callback=gMapsCallback");
                  (document.getElementsByTagName("head")[0] || document.documentElement).appendChild(script_tag);

              }
              
              $(window).bind("gMapsLoaded", initMap);
              window.loadGoogleMaps();

              function initMap() {

                  var markerArray = [];
                  var infoWindow = new google.maps.InfoWindow();
                  var marker, i;
                  
                  if(!id_city){
                      var options_googlemaps = {
                          zoom: 6,
                          center: new google.maps.LatLng(55.75, 37.62),
                          mapTypeId: google.maps.MapTypeId.ROADMAP
                      }   
                  }else{
                      var options_googlemaps = {
                          zoom: 6,
                          center: new google.maps.LatLng(data['last_gps'][0], data['last_gps'][1]),
                          mapTypeId: google.maps.MapTypeId.ROADMAP
                      }                    
                  }               

                  $.each(data['data'], function(index, point) { 

                    var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(point['gps'][0],point['gps'][1]),
                            map: map,
                            title: point['title'],
                            content: point['content']
                        });

                    markerArray.push(marker);

                  });
                  
                  map = new google.maps.Map(document.getElementById("modal-delivery-point-map-init"), options_googlemaps);

                  var markerCluster = new MarkerClusterer(map, markerArray,
                              { imagePath: "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m" });

                  for(i = 0; i < markerArray.length; i++) {
                     
                        google.maps.event.addListener(markerArray[i], "click", (function(marker) {
                              return function() {

                                   infoWindow.setContent(this.content);
                                   infoWindow.open(map, this);

                              }
                        })(marker));

                  }
                  
              }

              google.maps.event.addDomListener(window, "load", initMap);

          }else if($('#modal-delivery-point input[name=map_vendor]').val() == 'openstreetmap'){

               var minlat = 200, minlon = 200, maxlat = -200, maxlon = -200;

               if(id_city){
                  
                  $('.modal-delivery-point-map-init').remove();
                  $('.modal-delivery-point-map').append('<div class="modal-delivery-point-map-init" id="modal-delivery-point-map-init" ></div>');

                  map = L.map('modal-delivery-point-map-init').setView([data['last_gps'][0], data['last_gps'][1]], 12);
                  L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/streets-v11/tiles/{z}/{x}/{y}?access_token='+$('#modal-delivery-point input[name=map_vendor_key]').val(), {
                  attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                  }).addTo(map);

                   $.each(data['data'], function(index, point) { 

                       var lon = point['gps'][1];
                       var lat = point['gps'][0];
                       var popupText = point['content'];

                        var markerLocation = new L.LatLng(lat, lon);
                        var marker = new L.Marker(markerLocation);

                        if (minlat > lat) minlat = lat;
                        if (minlon > lon) minlon = lon;
                        if (maxlat < lat) maxlat = lat;
                        if (maxlon < lon) maxlon = lon;

                        marker.bindPopup(popupText);
                        map.addLayer(marker);

                   });

                   c1 = L.latLng(minlat, minlon);
                   c2 = L.latLng(maxlat, maxlon);

                   map.fitBounds(L.latLngBounds(c1, c2));

               }else{

                   map = L.map('modal-delivery-point-map-init').setView([55.75, 37.62], 12);
                   L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/streets-v11/tiles/{z}/{x}/{y}?access_token='+$('#modal-delivery-point input[name=map_vendor_key]').val(), {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                   }).addTo(map);

               }

          }


       }});

  }

  function loadPoint(id_point=''){

    $('.container-delivery-point').slideUp();

       $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id_point="+id_point+"&action=order/delivery_load_point",dataType: "html",cache: false,success: function (data) { 

           $('.container-delivery-point').html(data).slideDown();

       }});

  }

  $(document).on('input click','.action-input-search-delivery-point-send', function () {     
      var myThis = $(this); 
      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "q="+myThis.val()+"&action=order/delivery_search_point_send",dataType: "html",cache: false,success: function (data) { if(data != false){ myThis.next().html(data).show(); }else{ myThis.next().html('').hide() }  }});
  });

  $(document).on('click','.SearchDeliveryPointSendResults .item-city', function () {      
      $('.SearchDeliveryPointSendResults').hide();
      $('input[name="delivery_id_point_send"]').val($(this).attr("id-point"));
      $(this).parent().parent().find("input").val( $(this).attr("data-city") );
  });

});