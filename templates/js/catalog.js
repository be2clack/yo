$(document).ready(function () {
   
 var url_path = $("body").data("prefix");
 var currentCountPage = 1;
 var loadingAdScroll = true;
 
 $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
 });
 
 function setFiltersUrl(){
  var hashes = window.location.href.split('?');
  var params = $.param($('.form-filter, .modal-form-filter, .modal-geo-options-form').serializeArray().filter(function(el) {
          return $.trim(el.value);
      }));
  history.pushState("", "", hashes[0]+"?"+params);
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

function formFilter(){
  return $.param($('.form-filter, .form-ajax-live-search, .modal-geo-options-form').serializeArray().filter(function(el) {
          return $.trim(el.value);
      }));  
}

$(document).on('change','.form-filter input', function (e) { 

    var id_filter = $(this).closest(".filter-items").attr("id-filter");
    var id_parent = $(this).closest(".filter-items").attr("main-id-filter");
    var id_item = $(this).val();
    var element = $(this);
    
    if($(this).closest(".filter-items").attr("data-ids") != undefined){
       var ids = $(this).closest(".filter-items").attr("data-ids").split(",");
    }

    if(ids){

      $.each(ids,function(index,value){

        $('div[id-filter="'+value+'"]').remove();

      });

    }

    if($(this).val() != "null"){ 
        
            $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id_filter="+id_filter+"&id_item="+id_item+"&view=catalog&action=ads/load_items_filter",dataType: "html",cache: false,success: function (data) {

                element.closest(".filter-items").after(data);

            }});

    }

    e.preventDefault();
});

 $(document).on('submit','.form-filter', function (e) { 

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: formFilter()+"&action=ads/load_filters_ads",dataType: "json",cache: false,success: function (data) {
          
          location.href = data["params"];

      }}); 

    e.preventDefault(); 

 });

 $(document).on('change','.catalog-filters-top-item-checkbox input', function (e) { 

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: formFilter()+"&action=ads/load_filters_ads",dataType: "json",cache: false,success: function (data) {
          
          location.href = data["params"];

      }}); 

    e.preventDefault(); 

 });

 $(document).on("click", ".action-clear-filter", function(e) {

     location.href = window.location.href.split("?")[0];
     e.preventDefault();

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

$(document).on("click", ".catalog-ad-view", function(e) {

    $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "view=" + $(this).data("view") + "&action=ads/catalog_view",dataType: "html",cache: false,success: function (data) {
        location.reload();
    }});

});

$(document).on('click','.filter-accept', function () { 
   
    $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: formFilter()+"&action=ads/load_filters_ads",dataType: "json",cache: false,success: function (data) {
        
        location.href = data["params"];

    }});

});

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

function catalogLoadAds( _page = 1, _this_button = null, _scroll = false ){

  loadingAdScroll = false;

    if( _this_button != null ) _this_button.prop('disabled', true);
    $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: formFilter() + "&page="+_page+"&action=ads/load_catalog_ads",dataType: "json",cache: false,success: function (data) { 
        
        if(parseInt(data['count'])) $('.catalog-count-ad').html(data['count']); else $('.catalog-count-ad').html('');

        $(".preload, .preload-scroll").hide();
        
        $(".action-load-span-start").hide();
        $(".action-load-span-end").show();

        $(".action-catalog-load-ads").hide();
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

catalogLoadAds();

$(document).on('click','.action-catalog-load-ads > button', function () { 
    
    currentCountPage = currentCountPage + 1;
    
    $(".action-load-span-start").show();
    $(".action-load-span-end").hide();

    catalogLoadAds( currentCountPage , $(this), true );   
  
});

if( $("body").data("type-loading") == 2 ){

    $(window).scroll(function(){ 

       if( ( $(document).scrollTop() + 500 ) >= $(".catalog-results").height() ){
          if(loadingAdScroll == true){

             currentCountPage = currentCountPage + 1;
            
             catalogLoadAds( currentCountPage , null, false );

          }
       }

    });

}

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

$(document).on('click','.catalog-filters-top-more-filters .btn-custom', function () { 
    
    $(".catalog-filters-top-more-filters-items").toggle();   
  
});

$('.init-slider-grid').slick({
    dots: false,
    arrows: true,
    nextArrow: '<span class="init-slider-grid-next" style="right: -5px;" ><i class="las la-arrow-right"></i></span>',
    prevArrow: '<span class="init-slider-grid-prev" style="left: -5px;" ><i class="las la-arrow-left"></i></span>',
    infinite: true, 
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

$('.slider-list-seo-filters').slick({
    dots: false,
    arrows: true,
    slidesToScroll: 2,
    nextArrow: '<span class="catalog-subcategory-slider-next" style="right: 0px;" ><i class="las la-arrow-right"></i></span>',
    prevArrow: '<span class="catalog-subcategory-slider-prev" style="left: 0px;" ><i class="las la-arrow-left"></i></span>',
    infinite: false, 
    autoplay:false,            
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

$(function(){
    
    $(".catalog-subcategory, .slider-list-seo-filters, .vip-slider-item-grid").css("visibility", "visible");

});


});

