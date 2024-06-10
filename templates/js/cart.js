$(document).ready(function () {

    var url_path = $("body").data("prefix");
   
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    function showLoadProcess(el){
        el.prop('disabled', true);
        el.html('<span class="spinner-border spinner-border-sm spinner-load-process" role="status" ></span> '+el.html());
    }

    function hideLoadProcess(el){
        el.prop('disabled', false);
        $('.spinner-load-process').remove();
    }

	function openCart(view_cart, link_cart){

        if(view_cart == 'modal'){
            $("#modal-cart").show();
            $("body").css("overflow", "hidden");            
        }

        if(view_cart == 'sidebar'){

            if($('.sidebar-cart').width() > $(document).width()){
                $('.sidebar-cart').css('width', $(document).width() + 'px');
            }

            $(".sidebar-cart-bg").fadeIn(200);
            $(".sidebar-cart").animate({"right": "0px"}, 300);
            $("body").css("overflow", "hidden");
        }

        if(view_cart == 'page'){
        	$("#modal-notification-cart").show();
		    $("body").css("overflow", "hidden");
        }

   }

    function actionAfterOrder(view_cart){

        if(view_cart == 'modal'){
            $("#modal-cart").hide();
            $("#modal-order-accept").show();
            $("body").css("overflow", "hidden");     
            loadCart();       
        }

        if(view_cart == 'sidebar'){
            $(".sidebar-cart").animate({"right": "-" + $(".sidebar-cart").width() + "px"}, 300, function(){
                $(".sidebar-cart-bg").hide();
                $("body").css("overflow", "hidden");
                $("#modal-order-accept").show();
                loadCart();
            });
        }

        if(view_cart == 'page'){
            $(".cart-box-1").hide();
            $(".cart-box-2").show();
        }

   }

   function loadCart(){

         $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id=" + $(this).data('id') + "&action=cart/load_cart",dataType: "json",cache: false,success: function (data) { 
              $('.cart-container').html( data['items'] );
              $('.cart-info').html( data['info'] );
              $('.cart-item-counter').html( data['counter'] );
              $('.cart-itog').html( data['itog'] );
              if( data['counter'] ){
                  $('.label-count-cart').show();
              }else{
                  $('.label-count-cart').hide();
              }
         }});

   }

   $(document).on('click','.ad-add-to-cart', function (e) {

         var this_button = $(this);
     
         $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id=" + $(this).data('id') + "&action=cart/add_to_cart",dataType: "json",cache: false,success: function (data) { 
             if( data['status'] == true ){
                 
                 loadCart();

                 if(data['action'] == 'add'){
                    this_button.html(this_button.data('name-delete'));
                    openCart(data['view_cart'], data['link_cart']);
                 }else{
                    this_button.html(this_button.data('name-add'));
                 }
                 
             }else{

                alert(data['answer']);

             }
         }});

   });

   $(document).on('click','.sidebar-cart-open', function (e) {

         if($('.sidebar-cart').width() > $(document).width()){
            $('.sidebar-cart').css('width', $(document).width() + 'px');
         }

         $(".sidebar-cart-bg").fadeIn(200);
         $(".sidebar-cart").animate({"right": "0px"}, 300);
         $("body").css("overflow", "hidden");
   });

   $(document).on('click','.sidebar-cart-close', function (e) {
     
         $(".sidebar-cart").animate({"right": "-" + $(".sidebar-cart").width() + "px"}, 300, function(){
            $(".sidebar-cart-bg").hide();
            $("body").css("overflow", "auto");
         });

   });

   $(document).on('click','.cart-goods-item-count-change', function (e) {

         var _this = $(this);
         var id = $(this).parents('.cart-goods-item').data('id');

         $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id=" + id + "&variant=" + $(this).data('action') + "&action=cart/change_count",dataType: "json",cache: false,success: function (data) { 
              
              if(data["status"]){
                  _this.parents('.cart-goods-item').find('input.cart-goods-item-count').val(data["count"]);
                  _this.parents('.cart-goods-item').find('div.cart-goods-item-content-price-info').html(data["total"]);

                  $('.cart-info').html( data['info'] );
                  $('.cart-item-counter').html( data['counter'] );
                  $('.cart-itog').html( data['itog'] );

                  if( data['counter'] ){
                      $('.label-count-cart').show();
                  }else{
                      $('.label-count-cart').hide();
                  }
              }else{
                  loadCart();
              }

         }});

   });

   $(document).on('click','.cart-goods-item-delete', function (e) {

         var id = $(this).parents('.cart-goods-item').data('id');

         $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id=" + id + "&action=cart/delete",dataType: "html",cache: false,success: function (data) { 
              
              loadCart();
              $('.ad-add-to-cart[data-id="'+id+'"]').html($('.ad-add-to-cart[data-id="'+id+'"]').data('name-add'));

         }});

   });

   $(document).on('click','.cart-page-goods-item-delete', function (e) {

         var id = $(this).parents('.cart-goods-item').data('id');

         $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id=" + id + "&action=cart/delete",dataType: "html",cache: false,success: function (data) { 
              
              location.reload();

         }});

   });

   $(document).on('click','.cart-clear', function (e) {

         $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "action=cart/clear",dataType: "html",cache: false,success: function (data) { 
              
              location.reload();

         }});

   });

   $(document).on('click','.cart-payment-order', function (e) {

         var el = $(this);
         showLoadProcess(el);

         $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "action=cart/payment_order&"+$('.form-delivery').serialize(),dataType: "json",cache: false,success: function (data) { 
              
                if(data["status"] == true){

                    if(data["redirect"]["link"]){
                       location.href = data["redirect"]["link"];
                    }else{
                       $('body').append('<div class="redirect-form-pay" ></div>');
                       $(".redirect-form-pay").html(data["redirect"]["form"]);
                       $(".form-pay .pay-trans").click();               
                    }

                }else{

                   if(!data['auth']){
                      $('#modal-auth').show();
                      $("body").css("overflow", "hidden");
                   }else{
                      alert(data["errors"]);
                   }

                   hideLoadProcess(el);

                }

         }});

   });

   $(document).on('click','.cart-payment-balance-order', function (e) {

         var el = $(this);
         showLoadProcess(el);

         $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "action=cart/payment_order&from_balance=1&"+$('.form-delivery').serialize(),dataType: "json",cache: false,success: function (data) { 
              
            if(data["status"] == true){
                location.href = data["redirect"];
            }else{
                if(data["errors"]){
                    alert(data["errors"]);
                }else{
                    if(data["balance"] == false){
                        $("#modal-balance").show();
                        $(".modal-balance-summa").html( data["balance_total"] );
                        $("body").css("overflow", "hidden");
                    }else{
                        location.reload();
                    }
                }
                hideLoadProcess(el);
            }

         }});

   });

   loadCart();    

});