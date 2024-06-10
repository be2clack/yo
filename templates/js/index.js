$(document).ready(function () {
   
   var url_path = $("body").data("prefix");
   var currentCountPage = 1;
   var loadingAdScroll = true;
   var sortingVariant = "recommendations";
   
   $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      error : function(jqXHR, textStatus, errorThrown) {

            if (jqXHR.status == 401){
                alert("Сессия авторизации истекла.");
            }else if (jqXHR.status == 403){
            }else if (jqXHR.status == 500){
            }

      }
   });
   
   function auctionTime(){
      $('[data-countdown="true"]').each(function (index, element) {
          $(element).countdown( $(element).attr("data-date") )
          .on('update.countdown', function(event) {
            var format = '%M '+$(".lang-js-2").html()+' %S '+$(".lang-js-3").html();
            $(element).html(event.strftime(format));
          })
          .on('finish.countdown', function(event) {
              $(element).removeClass("pulse-time").html( $(".lang-js-1").html() );
          });

      });
   }   
   
   function tippyLoad(){
        tippy('[data-tippy-placement]', {
          delay: 100,
          arrow: true,
          arrowType: 'sharp',
          size: 'regular',
          duration: 200,
          animation: 'shift-away',
          animateFill: true,
          theme: 'dark',
          distance: 10,
        });
   }
   
   function initializeSlider(){

      var slider = $('.photo-mobile-slider').not('.lightSlider').lightSlider({
          item:1,
          loop:false,
          pager:false,
          slideMargin:2,
          slideMove:1,
          speed:600,
          responsive : [
              {
                  breakpoint:800,
                  settings: {
                    item:1,
                    slideMove:1
                  }
              },
              {
                  breakpoint:480,
                  settings: {
                    item:1,
                    slideMove:1
                  }
              }
          ]
      });

   }

   function countDisplay(){
       $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "action=ads/update_count_display",dataType: "json",cache: false});
   }

   function indexLoadAds( _page = 1, _this_button = null, _scroll = false){

    loadingAdScroll = false;

        if( _this_button != null ) _this_button.prop('disabled', true);
        $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "page="+_page+"&sorting="+sortingVariant+"&action=ads/load_index_ads",dataType: "json",cache: false,success: function (data) { 
          
            $(".preload, .preload-scroll").hide();
            
            $(".action-load-span-start").hide();
            $(".action-load-span-end").show();

            $(".action-index-load-ads").hide();
            $(".catalog-results").append('<div class="load-page'+_page+' col-lg-12" ></div><div class="row no-gutters gutters10" style="display: none;" >'+data["content"]+'</div>');
            $('.load-page'+_page).next().fadeIn('slow');

            if(_scroll){

              $('html, body').animate({
              scrollTop: $('.load-page'+_page).offset().top-50
              }, 500, 'linear');

            }

            auctionTime();  
            tippyLoad();   
            initializeSlider();
            countDisplay();

            loadingAdScroll = data["found"]; 

        }});

   }

   $(document).on('click','.action-index-load-ads > button', function () { 
        
        currentCountPage = currentCountPage + 1;
        
        $(".action-load-span-start").show();
        $(".action-load-span-end").hide();

        indexLoadAds( currentCountPage , $(this), true );    

   });

   if( $("body").data("type-loading") == 2 ){

      $(window).scroll(function(){ 

         if( ( $(document).scrollTop() + 500 ) >= $(".catalog-results").height() ){
            if(loadingAdScroll == true){

               currentCountPage = currentCountPage + 1;
              
               indexLoadAds( currentCountPage , null, false );

            }
         }

      });

   }

    $('.init-slider-grid').slick({
        dots: false,
        arrows: true,
        nextArrow: '<span class="init-slider-grid-next" style="right: -5px;" ><i class="las la-arrow-right"></i></span>',
        prevArrow: '<span class="init-slider-grid-prev" style="left: -5px;" ><i class="las la-arrow-left"></i></span>',
        infinite: false, 
        autoplay:false,         
        slidesToShow: 3,   
        speed: 300,
        centerMode: false,
        variableWidth: true,
        responsive: [
          {
            breakpoint: 700,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1
            }
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1
            }
          }
        ]
    });


  $('.catalog-category-slider').owlCarousel({
    dots:false,
    loop:false,
    margin:10,
    nav:true,
    autoWidth:true,
    autoplay:false,
    autoplayTimeout:4000,
    autoplayHoverPause:false,
    navText: ['<i class="las la-arrow-left"></i>','<i class="las la-arrow-right"></i>']
  });

   $(document).on('click','.sliders-wide-item', function () {     
      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id="+$(this).data("id")+"&action=ads/media_slider_click",dataType: "html",cache: false});
   });


   $(document).on('click','.home-tabs-ads > div', function () { 
      
      $('.home-tabs-ads > div').removeClass("active");
      $(this).addClass("active");

      sortingVariant = $(this).data("tab");

      $(".preload").show();

      $(".catalog-results").html(`
              <div class="preload" >

                  <div class="spinner-grow mt35 preload-spinner" role="status">
                    <span class="sr-only"></span>
                  </div>

              </div>
        `);

      indexLoadAds();

   });

   indexLoadAds();

});
