$(document).ready(function () {
   
   var url_path = $("body").data("prefix");
   var currentCountPage = 1;
   var loadingScroll = true;
   
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

   shopsLoad();
      
   function shopsLoad( _page = 1, _this_button = null, _scroll = false ){

    loadingScroll = false;

        if( _this_button != null ) _this_button.prop('disabled', true);
        $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id_c=" + $("input[name=id_c]").val() + "&page="+_page+"&action=shop/shops_load",dataType: "json",cache: false,success: function (data) { 

            $(".action-load-span-start").hide();
            $(".action-load-span-end").show();

            $(".action-shops-load").hide();
            $(".catalog-results").append('<div class="load-page'+_page+'" ></div><div>'+data["content"]+'</div>');
            $('.load-page'+_page).next().fadeIn('slow');

            if(_scroll){

              $('html, body').animate({
              scrollTop: $('.load-page'+_page).offset().top-50
              }, 500, 'linear');

            }
 
            loadingScroll = data["found"]; 

            $(".preload, .preload-scroll").hide();

        }});

   }

   $(document).on('click','.action-shops-load > button', function () { 
        
        currentCountPage = currentCountPage + 1;
        
        $(".action-load-span-start").show();
        $(".action-load-span-end").hide();

        shopsLoad( currentCountPage , $(this), true );    

   });

   if( $("body").data("type-loading") == 2 ){

      $(window).scroll(function(){ 

         if( ( $(document).scrollTop() + 500 ) >= $(".catalog-results").height() ){
            if(loadingScroll == true){

               $(".preload-scroll").show();
               
               currentCountPage = currentCountPage + 1;
              
               shopsLoad( currentCountPage , null, false );

            }
         }

      });

   }
    


});
