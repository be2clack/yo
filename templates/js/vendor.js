$(document).ready(function () {
   
   var url_path = $("body").data("prefix");
   var page_name = $("body").data("page-name");
   var currentCountPage = 1;
   var loadingAdScroll = false;
   var cookieOptions = {expires: 3, path: '/'};
   var arrayOpenModal = [];
   
   $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
   
   $('.inputNumber').inputNumber({ thousandSep: ' ' });

   $.datepicker.regional['ru'] = {
        closeText: 'Закрыть',
        prevText: 'Предыдущий',
        nextText: 'Следующий',
        currentText: 'Сегодня',
        monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
        monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн','Июл','Авг','Сен','Окт','Ноя','Дек'],
        dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
        dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
        dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
        weekHeader: 'Не',
        dateFormat: 'dd.mm.yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
   };

   $.datepicker.setDefaults($.datepicker.regional['ru']);

   function initCatalogInputDates(){

       $(".catalog-change-date-from").datepicker({
          minDate: 0,
          dateFormat: 'dd.mm.yy',
       });

       $(".catalog-change-date-to").datepicker({
          minDate: 0,
          dateFormat: 'dd.mm.yy',
       });

   }

   initCatalogInputDates();

   function showLoadProcess(el){
        el.prop('disabled', true);
        el.html('<span class="spinner-border spinner-border-sm spinner-load-process" role="status" ></span> '+el.html());
   }

   function hideLoadProcess(el){
        el.prop('disabled', false);
        $('.spinner-load-process').remove();
   }

   function initPhoneMask(format){

       if($(".phone-mask").length){

          $.mask.definitions['9'] = false;
          $.mask.definitions['_'] = "[0-9]";
          
          if(format){
            $(".phone-mask").mask(format);
          }

       }

   }

   initPhoneMask($(".phone-mask").data("format"));

   $(document).on('change','.modal-form-filter input', function (e) { 

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
            
                $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id_filter="+id_filter+"&id_item="+id_item+"&view=modal&action=ads/load_items_filter",dataType: "html",cache: false,success: function (data) {

                    element.closest(".filter-items").after(data);

                }});

        }

        e.preventDefault();
   });

   $(document).on('input','.catalog-list-options-search input', function () {
        var str = $(this).val().toLowerCase();
        
        $(this).parent().parent().find('.catalog-list-options-items > div').show();
        
        $(this).parent().parent().find('.catalog-list-options-items > div').each(function(){
          if ($(this).find("label").text().toLowerCase().indexOf(str) < 0){
              $(this).hide();
          }
        });  
   });

   $(document).on('click','.toggle-list-options > span', function (e) { 

        $(this).parent().find(".catalog-list-options-content").toggle();
        $(this).parent().toggleClass("catalog-list-options-active");

   });

   $(document).on('click','.input-phone-format-list > span', function () { 
       initPhoneMask($(this).data("format"));
       $(".phone-mask").val($(this).data("format"));
       $('.input-phone-format-change img').attr('src', $(this).data("icon"));
       $('.input-phone-format-list').hide();
   });

   $(document).on('click','.input-phone-format-change', function () { 
       $('.input-phone-format-list').toggle();
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

   function header_fixed_menu(){
       $('.header-big-menu').css('top', $('.header-wow-sticky-container').outerHeight() );
   }

   tippyLoad();
   auctionTime();

   $(document).on('click','.captcha-update', function () { 
     
     $(this).attr("src", $(this).attr("src") + "&r="+ Math.random());

   });

   function captchaFeedback(){
      $('.feedback .captcha-container img').attr('src', url_path + 'systems/captcha/captcha.php?name=feedback');
   }

   $(document).on('click','.header-button-menu-catalog', function () { 
     
     header_fixed_menu();
     $('.header-big-menu').toggle();
     $(this).toggleClass('header-button-menu-catalog-active');

   });

   $(document).on('click','.action-user-route-back', function (e) {
      
      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",dataType: "html",data: "action=ads/mobile_user_step_route",cache: false,success: function (data) { 
            location.href = data;
      }});

      e.preventDefault();

   });

   $(document).on('click','.toolbar-dropdown-toggle > span', function (e) { 

		$(".toolbar-dropdown-toggle > span").attr("data-toggle", 0).removeClass("toolbar-dropdown-active");
		$(".toolbar-dropdown-toggle .toolbar-dropdown-box").hide();

        if($(this).attr("data-toggle") == 0){
            $(this).addClass("toolbar-dropdown-active"); 
            $(this).next().show();
        	$(this).attr("data-toggle", 1);
        }else{
            $(this).removeClass("toolbar-dropdown-active"); 
            $(this).next().hide();
        }

	});

	$(document).mouseup(function (e) {
	    var container = $(".toolbar-dropdown-box");
	    if (container.has(e.target).length === 0){
			$(".toolbar-dropdown-toggle > span").attr("data-toggle", 0).removeClass("toolbar-dropdown-active");
			$(".toolbar-dropdown-toggle .toolbar-dropdown-box").hide();
	    }
	});

   $(document).on('click','.modal-custom-close', function (e) { 

		$(this).parent().parent().hide();
		$("body").css("overflow", "auto");

        if(arrayOpenModal.length > 1 && arrayOpenModal[0] != '#'+$(this).parent().parent().attr('id')){
          $("body").css("overflow", "hidden");
          $(arrayOpenModal[0]).show();
        }else{
          arrayOpenModal = [];
        }

   });

   $(document).on('click','.button-click-close', function (e) { 

        $(this).closest(".modal-custom-bg").hide();
        $("body").css("overflow", "auto");

        if(arrayOpenModal.length > 1 && arrayOpenModal[0] != '#'+$(this).closest(".modal-custom-bg").attr('id')){
          $("body").css("overflow", "hidden");
          $(arrayOpenModal[0]).show();
        }else{
          arrayOpenModal = [];
        }

   });

   $(document).on('click','.bg-click-close', function (e) { 

        if (!$(e.target).closest(".modal-custom").length) {
            $(this).hide();
            $("body").css("overflow", "auto");

            if(arrayOpenModal.length > 1 && arrayOpenModal[0] != '#'+$(this).attr('id')){
              $("body").css("overflow", "hidden");
              $(arrayOpenModal[0]).show();
            }else{
              arrayOpenModal = [];
            }
        }

        e.stopPropagation();

   });

   $(document).on('click','.open-modal', function (e) { 
        
      var id_modal = "#" + $(this).data("id-modal");

      arrayOpenModal.push(id_modal);

      $(".modal-custom-bg").hide();
      $("body").css("overflow", "hidden");

	  $(id_modal).show();
	  
      if($(this).data("id-modal") == 'modal-order-service'){

            $('.modal-ads-services-slider').slick({
                dots: false,
                arrows: true,
                nextArrow: '<span class="init-slider-grid-next" style="right: -5px;" ><i class="las la-arrow-right"></i></span>',
                prevArrow: '<span class="init-slider-grid-prev" style="left: -5px;" ><i class="las la-arrow-left"></i></span>',
                infinite: false, 
                autoplay: false,         
                slidesToShow: 3,   
                speed: 300,
                centerMode: false,
                variableWidth: $('.ads-services-tariffs').length >= 3 ? false : true,
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

      }

      e.preventDefault();

   });

   $(document).on('input click','.action-input-search-city', function () {     
      var myThis = $(this); 
      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "q="+myThis.val()+"&action=geo/search_city",dataType: "html",cache: false,success: function (data) { if(data != false){ myThis.next().html(data).show(); }else{ myThis.next().html('').hide() }  }});
   });

   $(document).on('click','.SearchCityResults .item-city', function () {      
      $('.SearchCityResults').hide();
      $('input[name="city_id"]').val( $(this).attr("id-city") );
      $(this).parent().parent().find("input").val( $(this).attr("data-city") );
   });

   $(document).on('click','.dropdown-click', function () {  
      
      $(this).find(".toolbar-dropdown-box").toggle();

   });

   $(document).on('click','.header-mobile-menu-dropdown-open', function () {  

      $(this).parents().find(".header-mobile-menu-dropdown").toggle();

   });

   $(document).on('click','.toggle-favorite', function () {    
      var _this = $(this); 
      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id_ad=" + _this.data("id") + "&action=profile/favorite",dataType: "json",cache: false,success: function (data) { 

         if(data["auth"]){
           if(data["status"]){
             $(".toggle-favorite[data-id="+_this.data("id")+"]").find("i").attr("class","ion-ios-heart");
           }else{
             $(".toggle-favorite[data-id="+_this.data("id")+"]").find("i").attr("class","ion-ios-heart-outline");
           }
         }else{
           $("#modal-auth").show();
           $("body").css("overflow", "hidden");
         }

      }});
   });   

   $(document).on('input click','.sticky-search-geo-input', function () {     
      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "q="+$(this).val()+"&action=geo/search_city_region",dataType: "html",cache: false,success: function (data) { if(data != false){ $('.sticky-search-geo-list').hide(); $('.sticky-search-geo-results').html(data).show(); }else{ $('.sticky-search-geo-results').html("").hide() }  }});
   });

   $(document).on('click','.sticky-search-geo-list .item-city, .sticky-search-geo-results .item-city', function () {      

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "city_id="+$(this).attr("id-city")+"&region_id="+$(this).attr("id-region")+"&country_id="+$(this).attr("id-country")+"&action=geo/change_city",dataType: "html",cache: false,success: function (data) { 
         location.reload();
      }});

   });

   $(document).on('click','.cities-alphabet span', function () {      

      var data_id = $(this).data("id");

      $('html, body').animate({ scrollTop: $("#"+data_id).offset().top-70 }, 500);

   });

   $(document).on('click','.uni-dropdown-name', function () {
      $(this).next().show();
   });

   $(document).on('click','.uni-dropdown-content span', function () {
      $(this).parent().parent().find(".uni-dropdown-name span").html( $(this).data("name") );
      $("input[name="+$(this).data("input")+"]").val( $(this).data("value") );
      $('.uni-dropdown-content').hide();
      if( $(this).data("input") == "currency" ){
          $(".static-currency-sign").html( $(this).data("name") );
      }
   });

   $(document).on('click','.ads-cat-pay-publication', function () {     
      $(".ads-cat-pay-publication").prop('disabled', true);
      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id_ad="+$(this).data("id")+"&action=ads/pay_category_publication",dataType: "json",cache: false,success: function (data) { 
         if(data["status"] == true){

            location.href = data["location"];

         }else{
               
            $("#modal-balance").show();
            $(".modal-balance-summa").html( data["balance"] );
            $("body").css("overflow", "hidden");

            $(".ads-cat-pay-publication").prop('disabled', false);
         }
      }});
   });

    var $star_rating = $('.star-rating-js span');
    var SetRatingStar = function() {
        return $star_rating.each(function() {
            if (parseInt($("input[name=rating]").val()) >= parseInt($(this).data('rating'))) {
                return $(this).removeClass('ion-ios-star-outline').addClass('ion-ios-star');
            } else {
                return $(this).removeClass('ion-ios-star').addClass('ion-ios-star-outline');
            }
        });
    };
    $(document).on("click", ".star-rating-js span", function(e) {
        var rating = $(this).data('rating');
        $("input[name=rating]").val(rating);
        return SetRatingStar();
    });
    SetRatingStar();

   $(document).on('submit','.form-review-message', function (e) { 

      $(".form-review-message button").prop('disabled', true);

        $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: $(this).serialize()+"&action=profile/add_review",dataType: "json",cache: false,success: function (data) { 
            if( data["status"] == true ){
               location.href = data["redirect"];
            }else{
               alert( data["answer"] );
               $(".form-review-message button").prop('disabled', false);
            }
        }});

      e.preventDefault();
   });

   $(document).on('click','.feedback-answers-questions', function () { 

       $(this).find("div").toggle();

   });

   $(document).on('submit','.feedback', function (e) { 

      $(".feedback-form button").prop('disabled', true);

        $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: $(this).serialize() + "&action=ads/feedback",dataType: "json",cache: false,                        
            success: function (data){
                if( data["status"] == true ){
                   $(".feedback-success").show();
                   $(".feedback").hide();
                }else{
                   alert( data["answer"] );
                   $(".feedback-form button").prop('disabled', false);
                }                                            
            }
        });

      e.preventDefault();
   });

   $(document).on('click','.modal-geo-options-tab > div', function () { 
       
       $(".modal-geo-options-tab div").removeClass("active");
       $(this).addClass("active");
       $(".modal-geo-options-tab-content > div").hide();
       $(".modal-geo-options-tab-content [data-tab="+$(this).data("id")+"]").show();

   });

   $(document).on('submit','.form-user-subscribe', function (e) { 

      $(".form-user-subscribe button").prop('disabled', true);

        $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: $(this).serialize() + "&action=ads/user_subscribe",dataType: "json",cache: false,                        
            success: function (data){
                if( data["status"] == true ){
                   $(".user-subscribe-success").show();
                   $(".form-user-subscribe").hide();
                }else{
                   alert( data["answer"] );
                   $(".form-user-subscribe button").prop('disabled', false);
                }                                            
            }
        });

      e.preventDefault();
   });

    var adSearchTimeout = null;  
    $('.ajax-live-search').keyup(function() { 
        
      var parent = $(this).parents('.parents-ajax-live-search');
      var form = parent.find( ".form-ajax-live-search" );
      var results = parent.find( ".results-ajax-live-search" );

      if (adSearchTimeout != null) {
        clearTimeout(adSearchTimeout);
      }
      adSearchTimeout = setTimeout(function() {
        adSearchTimeout = null;  

          $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: form.serialize()+"&page="+page_name+"&action=ads/ads_search",dataType: "html",cache: false,                        
              success: function (data){
                  $('.sticky-search-geo-area-list').hide();
                  $('.sticky-search-control-geo-area').hide();
                  if(data){
                     results.html(data).show(); 
                  }else{
                     results.html("").hide();
                  }                                      
              }
          });

      }, 200);  
    }); 

   $(document).on('click','.ajax-live-search', function (e) { 
       
       var results = $(this).parents( ".parents-ajax-live-search" ).find( ".results-ajax-live-search" );
       
       if( results.html() != "" ){
           results.show();
           $('.sticky-search-geo-area-list').hide();
           $('.sticky-search-control-geo-area').hide();           
       }

   });

   $(document).on('click', function(e) { 

      if(!$(e.target).closest(".results-ajax-live-search").length && !$(e.target).closest(".ajax-live-search").length) {
          $(".results-ajax-live-search").hide();
      }

      if(!$(e.target).closest(".sticky-search-geo-input").length && !$(e.target).closest(".sticky-search-control-geo-change").length && !$(e.target).closest(".sticky-search-geo-list").length) {
          $(".container-search-geo, .sticky-search-geo-list, .sticky-search-geo-results").hide();
          $(".container-search-goods").show();
      }

      if(!$(e.target).closest(".modal-search-geo-input").length && !$(e.target).closest(".modal-search-control-geo-change").length) {
          $(".modal-search-geo-results").hide();
      }

      if(!$(e.target).closest(".ajax-live-search").length && !$(e.target).closest(".sticky-search-control-geo-area-change").length && !$(e.target).closest(".sticky-search-geo-area-list").length) {
          $(".sticky-search-geo-area-list, .sticky-search-control-geo-area").hide();
          $(".sticky-search-control-geo").show();
      }

      if(!$(e.target).closest(".catalog-filters-top-more-filters-items").length && !$(e.target).closest(".catalog-filters-top-more-filters .btn-custom").length) {
          $(".catalog-filters-top-more-filters-items").hide();
      }

      if(!$(e.target).closest(".ajax-live-search").length && !$(e.target).closest(".sticky-search-control-geo-area-change").length && !$(e.target).closest(".sticky-search-geo-area-list").length) {
          $(".sticky-search-control-geo").show();
      }else{
          $(".sticky-search-control-geo").hide();
      }

      if(!$(e.target).closest(".uni-dropdown").length) {
          $('.uni-dropdown-content').hide();
      }

      if(!$(e.target).closest(".toolbar-dropdown-js").length && !$(e.target).closest(".dropdown-click").length) {
          $('.toolbar-dropdown-js').hide();
      }

      if(!$(e.target).closest(".action-input-search-city").length && !$(e.target).closest(".modal-geo-search").length && !$(e.target).closest(".custom-results").length) {
          $('.custom-results').hide();
      }

      if(!$(e.target).closest(".input-phone-format-change").length && !$(e.target).closest(".input-phone-format-list").length) {
          $('.input-phone-format-list').hide();
      }

      if(!$(e.target).closest(".user-list-ad-info-menu-list").length && !$(e.target).closest(".user-list-ad-info-menu").length) {
          $('.user-list-ad-info-menu-list').hide();
      }

      e.stopPropagation();

   });

    $(document).on("click", ".block-cookies span", function(e) {
        $(".block-cookies").fadeOut(200, function(){
            $.cookie("cookie-policy", "hide", cookieOptions);
        });
    });

    if($.cookie("cookie-policy") != "hide"){
       setTimeout('$(".block-cookies").show();', 2000);
    }

   $(document).on('click','.catalog-more-filter-show', function (e) { 

       $(".catalog-more-filter").removeClass("catalog-more-filter-action");
       $(".catalog-more-filter-show").hide();
       $(".catalog-more-filter-hide").show();

   });

   $(document).on('click','.catalog-more-filter-hide', function (e) { 

       $(".catalog-more-filter").addClass("catalog-more-filter-action");
       $(".catalog-more-filter-show").show();
       $(".catalog-more-filter-hide").hide();

   });

   $(document).on('click','.item-country-hover', function (e) { 

        var element = $(this);

        $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "alias=" + $(this).data("alias") + "&action=geo/load_country_city",dataType: "html",cache: false,                        
            success: function (data){
                $('.item-country-hover').removeClass("active");
                element.addClass("active");
                element.parent().parent().parent().parent().find(".modal-country-container").html( data );                                      
            }
        });

   });
   
   $(document).on('click','.modal-ads-subscriptions-add', function (e) { 

       $(".modal-ads-subscriptions-add").prop('disabled', true);

         $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: $(".modal-ads-subscriptions-form").serialize()+"&action=ads/modal_ads_subscriptions_add",dataType: "json",cache: false,success: function (data) { 
             if( data["status"] == true ){
                $(".modal-ads-subscriptions-block-1").hide();
                $(".modal-ads-subscriptions-block-2").show();
             }else{
                alert( data["answer"] );
                $(".modal-ads-subscriptions-add").prop('disabled', false);
             }
         }});

       e.preventDefault();
   });

   $(document).on('click','.catalog-ads-subscriptions-add', function (e) { 

         $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: $(".modal-ads-subscriptions-form").serialize() + "&action=ads/catalog_ads_subscriptions_add",dataType: "json",cache: false,success: function (data) { 
             if( data["status"] == true ){
                 if( data["auth"] == true ){
                   $("#modal-ads-subscriptions").show();
                   $("body").css("overflow", "hidden");
                   $(".modal-ads-subscriptions-block-1").hide();
                   $(".modal-ads-subscriptions-block-2").show();                   
                 }else{
                   $("#modal-ads-subscriptions").show();
                   $("body").css("overflow", "hidden");
                   $(".modal-ads-subscriptions-block-1").show();
                   $(".modal-ads-subscriptions-block-2").hide();                   
                 }
             }else{
                 $("#modal-ads-subscriptions").show();
                 $("body").css("overflow", "hidden");
                 $(".modal-ads-subscriptions-block-1").show();
                 $(".modal-ads-subscriptions-block-2").hide();
             }
         }});

       e.preventDefault();
   });

   $(document).on('click','.open-big-menu', function (e) {
        $(".header-big-category-menu").toggle();
        $(".header-wow-sticky-menu .la-bars").toggle();
        $(".header-wow-sticky-menu .la-times").toggle();       
   });

   $(document).on('click','.mobile-open-big-menu', function (e) {
        $(".header-mobile-menu").toggle();
        $(".mobile-icon-menu-close").toggle();
        $(".mobile-icon-menu-open").toggle();
        $(".mobile-footer-menu").toggle();
   });

   var up_scroll = 0;
   var down_scroll = 0;

   header_wow_sticky( $(document).scrollTop() );

   function header_wow_sticky(scroll){

        header_fixed_menu();

        if( $("body").data( "header-sticky" ) == true ){
            if ( scroll > $(".header-wow-top").outerHeight() && !$('.header-wow-sticky').hasClass('header-wow-sticky-active') ) {
              $('.header-wow-sticky-container').addClass('header-wow-sticky-active');
             } else if ( scroll <= $(".header-wow-top").outerHeight() ) {
              $('.header-wow-sticky-container').removeClass('header-wow-sticky-active');
            }
        }

        if(scroll <= 400){
            $(".header-wow-mobile-category").css('transform', 'translate3d(0px, 0px, 0px)');
        }else{

            if(up_scroll){

                if(scroll <= up_scroll){
                  $(".header-wow-mobile-category").css('transform', 'translate3d(0px, 0px, 0px)');
                  up_scroll = parseInt(scroll)-150;                 
                }

            }else{
                up_scroll = parseInt(scroll)-150;
            }            

            if(scroll > down_scroll){
              $(".header-wow-mobile-category").css('transform', 'translate3d(0px, -'+$(".header-wow-mobile-category").outerHeight()+'px, 0px)');
              up_scroll = parseInt(scroll)-150;
            }

        }

        down_scroll = scroll;

   }

    $(window).scroll(function () {

       header_wow_sticky( $(this).scrollTop() );

       if($(window).scrollTop()+$(window).height()>=$(document).height()-150) 
       {
          $('.floating-menu,.floating-link').hide();
       }else{
          $('.floating-menu,.floating-link').show();
       }

    });

   $(document).on('click','.site-color-change', function (e) {
     
         $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "color=" + $(this).data("color") + "&action=ads/site_color",dataType: "html",cache: false,success: function (data) { 
             location.reload();
         }});

   });

   $(document).on('click','.modal-edit-site-menu-save', function (e) {
     
         $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: $(".modal-edit-site-menu-form").serialize() + "&action=admin/save_menu",dataType: "html",cache: false,success: function (data) { 
             location.reload();
         }});

   });

   $(document).on('click','.modal-edit-site-menu-add', function (e) {
         
         var key = Math.random();

         $(".modal-edit-site-menu-list").append( `
               <div>
                  <div class="row" >
                     <div class="col-lg-6 col-6" >
                        <input type="text" name="menu[`+key+`][name]" class="form-control" placeholder="Название" >
                     </div>
                     <div class="col-lg-5 col-5" >
                        <input type="text" name="menu[`+key+`][link]" class="form-control" placeholder="Ссылка" >
                     </div>
                     <div class="col-lg-1 col-1" >
                        <span class="modal-edit-site-menu-delete" > <i class="las la-trash"></i> </span>
                     </div>                                                
                  </div>
               </div>
          ` );

   });

   $(document).on('click','.modal-edit-site-menu-delete', function (e) {
     
         $(this).parent().parent().parent().remove().hide();

   });

   $(document).on('click','.floating-menu-catalog, .floating-menu-shops', function (e) {
     
      e.preventDefault();

   });

   $(document).on('click','.header-box-register-bonus-close', function (e) {
     
      $(".header-box-register-bonus").hide();
      $.cookie("registerBonus", "hide", cookieOptions);

   });

   $(document).on('click','.header-mobile-menu-close', function (e) {
     
      $(".header-mobile-menu").hide();

   });

   $(document).on('click','.sticky-search-control-geo-change', function (e) {
     
      $(".container-search-goods").hide();
      $(".container-search-geo, .sticky-search-geo-list").show();

   });

   $(document).on('click','.sticky-search-control-geo-cancel', function (e) {
     
      $(".container-search-geo, .sticky-search-geo-list, .sticky-search-geo-results").hide();
      $(".container-search-goods").show();

   });

   $(document).on('click','.sticky-search-control-geo-area-change', function (e) {
     
      $(".sticky-search-geo-area-list").show();
      $(".sticky-search-control-geo-area").css('display', 'inline-flex');
      $(".sticky-search-control-geo").hide();

   });

   $(document).on('click','.sticky-search-control-geo-area-cancel', function (e) {
     
      $(".sticky-search-geo-area-list, .sticky-search-control-geo-area").hide();
      $(".sticky-search-control-geo").show();

   });

   $(document).on('click','.mobile-fixed-menu-header-close', function (e) {
     
      $(".mobile-fixed-menu").fadeOut(150);
      $("body").css("overflow", "auto");

   });

   $(document).on('click','.mobile-fixed-menu_all-category-open', function (e) {
      
      $(".mobile-fixed-menu_all-category").fadeIn(150);
      $("body").css("overflow", "hidden");

   });

   $(document).on('click','.mobile-fixed-menu_link-category', function (e) {
      
      if($(this).data('parent')){

        $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id="+$(this).data('id')+"&action=ads/mobile_menu_load_category",dataType: "html",cache: false,success: function (data) { 
             $('.mobile-fixed-menu_all-category .mobile-fixed-menu-content').html(data);
        }});

        return false;
      }

   });

   $(document).on('click','.mobile-fixed-menu_prev-category', function (e) {
      
      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id="+$(this).data('id')+"&action=ads/mobile_menu_load_category",dataType: "html",cache: false,success: function (data) { 
             $('.mobile-fixed-menu_all-category .mobile-fixed-menu-content').html(data);
        }});

   });

   $(document).on('click','.mobile-fixed-menu_all-menu-open', function (e) {
      
      $(".mobile-fixed-menu_all-menu").fadeIn(150);
      $("body").css("overflow", "hidden");

   });

   $(document).on('click','.mobile-fixed-menu_catalog_filters-open', function (e) {
      
      $(".mobile-fixed-menu_catalog_filters-menu").fadeIn(150);
      $("body").css("overflow", "hidden");

   });

   $(document).on('click','.mobile-fixed-menu_shops-category-open', function (e) {
      
      $(".mobile-fixed-menu_shops-category").fadeIn(150);
      $("body").css("overflow", "hidden");

   });

   $(document).on('click','.mobile-fixed-menu_blog-category-open', function (e) {
      
      $(".mobile-fixed-menu_blog-category").fadeIn(150);
      $("body").css("overflow", "hidden");

   });

   $(document).on({
        mouseenter: function() {
            $( this ).parent().parent().find("img.ad-gallery-hover-slider-image").hide();
            $( this ).parent().parent().find("img[data-key="+$( this ).attr("data-key")+"]").show();
        }
   }, '.ad-gallery-hover-slider span');

   $(document).on('change','.change-lang-select', function (e) {
      
      location.href=$(this).val();

   });

   $(document).on('change','.modal-filter-select-category', function (e) {

        $('.modal-form-filter input[name=id_c]').val($(this).val());
      
        $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id="+$(this).val()+"&action=ads/mobile_menu_load_subcategory",dataType: "json",cache: false,success: function (data) { 
             $('.select-box-subcategory').html(data['subcategory']);
             $('.select-box-filters').html(data['filters']);

             $('.uni-select').each(function (index, element) {
                    
                if( $(element).find(".uni-select-item-active").length == 1 ){
                   $(element).find(".uni-select-name span").html( $(element).find(".uni-select-item-active span").html() );
                }else if( $(element).find(".uni-select-item-active").length > 1 ){
                   $(element).find(".uni-select-name span").html( $(".lang-js-4").html() +" ("+$(element).find(".uni-select-item-active").length+")" );
                }

             });

             initCatalogInputDates();

        }});

   });

   $(document).on('input click','.modal-search-geo-input', function () {     
      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "q="+$(this).val()+"&action=geo/search_city_region",dataType: "html",cache: false,success: function (data) { if(data != false){ $('.modal-search-geo-results').html(data).show(); }else{ $('.modal-search-geo-results').html("").hide() }  }});
   });

   $(document).on('click','.modal-search-geo-results .item-city', function () {      

      $('.modal-search-geo-input').val( $(this).data('name') );
      $('.modal-search-geo-results').html("").hide(); 
      $("input[name=city_id]").val($(this).attr("id-city"));

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "city_id="+$(this).attr("id-city")+"&region_id="+$(this).attr("id-region")+"&country_id="+$(this).attr("id-country")+"&action=geo/mobile_menu_load_geo",dataType: "html",cache: false,success: function (data) {

          $('.select-box-city-options').html(data);

      }});

   });

   $(document).on('click','.ads-form-ajax .SearchMetroResults div', function (e) { 

        var color = $(this).data("color");
        var name = $(this).data("name");
        var id = $(this).data("id");

        if( !$(".ads-container-metro-station").find("input[value="+id+"]").length ){
        
        $(".ads-container-metro-station").append('<span><i style="background-color:'+color+';"></i>'+name+' <i class="las la-times ads-metro-delete"></i><input type="hidden" value="'+id+'" name="metro[]"></span>');
        
        }

        $(".SearchMetroResults").hide();
        $(".action-input-search-metro").val("");

   });

   $(document).on('click','.modal-form-filter .SearchMetroResults div', function (e) { 

        var color = $(this).data("color");
        var name = $(this).data("name");
        var id = $(this).data("id");

        if( !$(".ads-container-metro-station").find("input[value="+id+"]").length ){
        
        $(".ads-container-metro-station").append('<span><i style="background-color:'+color+';"></i>'+name+' <i class="las la-times ads-metro-delete"></i><input type="hidden" value="'+id+'" name="filter[metro][]"></span>');
        
        }

        $(".SearchMetroResults").hide();
        $(".action-input-search-metro").val("");

   });

   $(document).on('click','.ads-metro-delete', function (e) { 

        $(this).parent().remove();

   });

   $(document).on('input click', '.action-input-search-metro', function (e) {
      $.ajax({
        type: "POST",
        url: url_path + "systems/ajax/controller.php",
        data: "city_id=" + $("input[name=city_id]").val() + "&search=" + $(this).val() + "&action=geo/search_metro",
        dataType: "html",
        cache: false,
        success: function (data) {
          if (data != false) {
            $(".SearchMetroResults").html(data).show();
          } else {
            $(".SearchMetroResults").html("").hide();
          }
        }
      });
   });

    function formFilter(){
      return $.param($('.modal-form-filter').serializeArray().filter(function(el) {
              return $.trim(el.value);
          }));  
    }

    $(document).on('submit','.modal-form-filter', function (e) { 

          $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: formFilter()+"&action=ads/load_filters_ads",dataType: "json",cache: false,success: function (data) {
              
              location.href = data["params"];

          }}); 

        e.preventDefault(); 

    });

    $(document).on('submit','.modal-complaint-form', function (e) { 

        $(".modal-complaint-form button").prop('disabled', true);

        $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: $(this).serialize()+"&action=ads/complaint",dataType: "json",cache: false,success: function (data) { 

            if(data["auth"] == true){

                  if(data["status"] == true){
                     $(".modal-complaint-form").hide();
                     $(".modal-complaint-notification").show();
                     $(".modal-complaint-notification h4").html(data["answer"]);
                  }else{
                     alert(data["answer"]);
                     $(".modal-complaint-form button").prop('disabled', false);
                  }

            }else{
                 
                 location.reload();                

            }

        }});

        e.preventDefault();
        
    });

   $(document).on('click','.init-complaint', function (e) {
        $('.modal-complaint-form input[name=id]').val($(this).data('id'));
        $('.modal-complaint-form input[name=action_complain]').val($(this).data('action'));
   });

   $(document).on('click','.dropdown-box-list-nested-toggle > a', function (e) {
        $(this).next().toggle();
        return false;
   });

   $(document).on('click','.user-tariff-box-item', function (e) {

        $('.user-tariff-box-item').removeClass('active'); 
        $(this).addClass('active');
        $('input[name=tariff_id]').val($(this).data('id'));

        $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: $('.settings-tariff-form').serialize()+"&action=profile/change_services_tariff",dataType: "json",cache: false,success: function (data) {
              
           $('.settings-tariff-sidebar-calc-item-price').html(data['price_tariff']);
           $('.settings-tariff-sidebar-calc-item-itog').html(data['total']);
           $('.settings-tariff-sidebar-calc-activate').html(data['button']);
           
           if(data['sidebar']){
              $('.settings-tariff-sidebar-calc').show();
           }else{
              $('.settings-tariff-sidebar-calc').hide();
           }

        }}); 

        e.preventDefault(); 

   });

   $(document).on('click','.settings-tariff-sidebar-calc-activate', function (e) {

        var el = $(this);
        showLoadProcess(el);

        $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "tariff_id="+$('input[name=tariff_id]').val()+"&action=profile/activate_services_tariff",dataType: "json",cache: false,success: function (data) {
            
           if(data['status'] == true){

               location.href = data['redirect'];

           }else{

               if(data["balance"]){
                   $("#modal-balance").show();
                   $(".modal-balance-summa").html(data["balance"]);
                   $("body").css("overflow", "hidden");
               }else{
                   if(data['answer']) alert(data['answer']);
               }

               hideLoadProcess(el);
           }

        }}); 

        e.preventDefault(); 

   });

   $(document).on('click','.profile-tariff-activate', function (e) {

        var el = $(this);
        showLoadProcess(el);

        $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "tariff_id="+el.data('id')+"&action=profile/activate_services_tariff",dataType: "json",cache: false,success: function (data) {
            
           if(data['status'] == true){

               location.reload();

           }else{

               if(data["balance"]){
                   $("#modal-balance").show();
                   $(".modal-balance-summa").html(data["balance"]);
                   $("body").css("overflow", "hidden");
               }else{
                   if(data['answer']) alert(data['answer']);
               }

               hideLoadProcess(el);
           }

        }}); 

        e.preventDefault(); 

   });

   $(document).on('click','.settings-tariff-delete', function (e) {

        showLoadProcess($(this));

        $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "action=profile/delete_services_tariff",dataType: "html",cache: false,success: function (data) {
            
           location.reload();

        }}); 

        e.preventDefault(); 

   });

   $(document).on('change','.change-autorenewal-tariff', function (e) {
        $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "status="+($(this).prop('checked') ? 1 : 0)+"&action=profile/autorenewal_services_tariff",dataType: "html",cache: false}); 
        e.preventDefault(); 
   });

   $('.init-slider-tariff').slick({
        dots: false,
        arrows: true,
        nextArrow: '<span class="init-slider-grid-next" style="right: -5px;" ><i class="las la-arrow-right"></i></span>',
        prevArrow: '<span class="init-slider-grid-prev" style="left: -5px;" ><i class="las la-arrow-left"></i></span>',
        infinite: true, 
        autoplay: false,         
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

   function loadHeaderCategories(){
        $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "action=ads/catalog_load_categories_header",dataType: "html",cache: false,success: function (data) {
            
           $('.catalog-header-big-menu').html(data);

           $(".header-big-category-menu-left > div").on("mouseover", function () {
                var id = $(this).data("id");
                $(".header-big-subcategory-menu-list").hide();
                $('.header-big-subcategory-menu-list[data-id-parent="'+id+'"]').show();
           });

        }});     
   }

   loadHeaderCategories();

   if(!$.cookie("registerBonus") && $('.header-box-register-bonus').data("status") == 1){
       setTimeout(function() {
          $(".header-box-register-bonus").show();
       }, 5000);
   }

   function geoFormFilter(){
      return $.param($('.form-filter, .form-ajax-live-search, .modal-geo-options-form').serializeArray().filter(function(el) {
              return $.trim(el.value);
          }));  
   }

   $(document).on('click','.submit-geo-options-form', function (e) { 

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: geoFormFilter()+"&action=ads/load_filters_ads",dataType: "json",cache: false,success: function (data) {
          
          location.href = data["params"];

      }}); 

      e.preventDefault(); 

   });


   $(document).on('input','.cities-search-input input', function () {     
      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "q="+$(this).val()+"&action=geo/search_cities_city",dataType: "html",cache: false,success: function (data) { $('.cities-search-list-container').html(data); }});
   });


   $(function(){ 
      
      captchaFeedback();
      $(".vip-block").css("visibility", "visible");

   });


});

