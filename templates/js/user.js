$(document).ready(function () {
   
   var url_path = $("body").data("prefix");
   var id_review = 0;
   var change_id_ad = 0;
   var cookieOptions = {expires: 30, path: '/'};

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

   $(document).on("click", ".warning-seller-safety-close", function(e) {
        $(".user-warning-seller-safety").hide();
        $.cookie("seller-safety", "hide", cookieOptions);
   });

   if($.cookie("seller-safety") != "hide"){
       $(".user-warning-seller-safety").show();
   }

   $('.lightgallery').lightGallery();

   $(document).on('click','.user-avatar-replace', function (e) { $('#user-form-avatar > input').click(); });
   $('#user-form-avatar > input').on('change', function(){ 

        var data_form = new FormData($('#user-form-avatar')[0]);
        data_form.append('action', 'profile/user_avatar'); 

        $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: data_form,dataType: "json",cache: false,contentType: false,processData: false,                        
            success: function (data){
               if(!data["error"]){ 
                  $('.user-avatar-img img').attr("src",data["img"]);
               }else{
                  alert(data["error"]);
               }                                            
            }
        });

   });

   $(document).on('click','.profile-user-block', function (e) {  

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id=" + $(this).data("id") + "&action=profile/profile_user_locked",dataType: "json",cache: false,success: function (data) { 

         location.reload();

      }});

      e.preventDefault();

   });

   $(document).on('click','.user-menu-tab > div', function () {  

      var tab = $(this).data("id-tab");

      $(".user-menu-tab-content").hide();
      $(".user-menu-tab > div").removeClass("active");
      $(this).addClass("active");
      $(".user-menu-tab-content[data-id-tab="+tab+"]").show();

   });

   $(document).on('click','.user-balance-payment > div', function () {  

      $(".user-balance-payment > div").removeClass("active");
      $(this).addClass("active");
      $(this).find("input").prop("checked", true);

   });

   $(document).on('click','.user-balance-summa > div', function () {  

      $(".user-balance-summa > div").removeClass("active");
      $(this).addClass("active");
      $(this).find("input").prop("checked", true);

      if( $(this).find("input").val() ){
         $(".balance-input-amount-variant1").hide();
      }else{
         $(".balance-input-amount-variant1").show();
      }

   });

   $(document).on('submit','.form-balance', function (e) {  

      $('.form-balance').find("button").prop("disabled", true);

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: $(this).serialize() + "&action=profile/balance_payment",dataType: "json",cache: false,success: function (data) { 

         if(data["status"]){
            if( data["redirect"]["link"] ){
               location.href = data["redirect"]["link"];
            }else{
               $("body").html(data["redirect"]["form"]);
               $(".form-pay .pay-trans").click();               
            }
         }else{
            alert(data["answer"]);
         }

         $('.form-balance').find("button").prop("disabled", false);

      }});

      e.preventDefault();

   });

   $(document).on('click','.user-mobile-menu > span', function (e) {  

      $(this).next().toggle();

   });

   $(document).on('submit','.user-form-settings', function (e) {  

      var el = $(".user-form-settings button");
      showLoadProcess(el);

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: $(this).serialize() + "&action=profile/user_edit",dataType: "json",cache: false,success: function (data) { 

         if(data["status"]){

            location.href = data["location"];

         }else{

            hideLoadProcess(el);

            var temp = [];

            $.each( data["answer"] ,function(index,value){
              
              temp.push(index);

              $(".msg-error[data-name="+index+"]").html(value).show();

            });

            $('html, body').animate({ scrollTop: $(".msg-error[data-name="+temp[0]+"]").offset().top-130 }, 500);

         }

      }});

      e.preventDefault();

   });

  $(document).on('change','input[name=status]', function (e) { 
      
      if($(this).val() == "user"){
         $(".user-name-company").hide();
      }else{
         $(".user-name-company").show();
      }

  });

   $(document).on('click','.user-edit-pass', function (e) {  

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "user_current_pass=" + $("input[name=user_current_pass]").val() + "&user_new_pass=" + $("input[name=user_new_pass]").val() + "&action=profile/user_edit_pass",dataType: "json",cache: false,success: function (data) { 

         if(data["status"]){
            location.reload();
         }else{
            alert(data["answer"]);
         }

      }});

      e.preventDefault();

   });

   $(document).on('click','.user-edit-email', function (e) {  
      
      $('.user-edit-email').prop('disabled', true);

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "user_email=" + $("input[name=email]").val() + "&action=profile/user_edit_email",dataType: "json",cache: false,success: function (data) { 

         if(data["status"]){
            $(".confirm-edit-email").html(data["answer"]).show();
            $("input[name=email]").hide();
            $(".modal-custom-button-show").hide();
            $(".modal-custom-button-hide").show();
         }else{
            alert(data["answer"]);
            $('.user-edit-email').prop('disabled', false);
         }

      }});

      e.preventDefault();

   });

   $(document).on('click','.user-edit-phone-send', function (e) {  
      
      $('.user-edit-phone-send').prop('disabled', true);

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "phone=" + $("input[name=phone]").val() + "&action=profile/user_edit_phone_send",dataType: "json",cache: false,success: function (data) { 

         if(data["status"]){
            $("#modal-edit-phone input[name=code]").show();
            $("#modal-edit-phone .input-phone-format").hide();
            $(".user-edit-phone-send").hide();
            $(".user-edit-phone-save").show();
         }else{
            alert(data["answer"]);
            $('.user-edit-phone-send').prop('disabled', false);
         }

      }});

      e.preventDefault();

   });

   $(document).on('click','.user-edit-phone-save', function (e) {  
      
      $('.user-edit-phone-save').prop('disabled', true);

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "code=" + $("input[name=code]").val() + "&phone=" + $("input[name=phone]").val() + "&action=profile/user_edit_phone_save",dataType: "json",cache: false,success: function (data) { 

         if(data["status"]){
            location.reload();
         }else{
            alert(data["answer"]);
            $('.user-edit-phone-save').prop('disabled', false);
         }

      }});

      e.preventDefault();

   });

   $(document).on('change','.modal-edit-notifications-content input', function (e) {  
      
      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: $(".form-edit-notifications").serialize() + "&action=profile/user_edit_notifications",dataType: "html",cache: false,success: function (data) { 
      }});

      e.preventDefault();

   });

   $(document).on('click','.user-edit-score', function (e) {  
      
      $(this).prop('disabled', true);

      if($("input[name=user_score_type]:checked").val() != null){
         var user_score_type = $("input[name=user_score_type]:checked").val();
      }else{
         var user_score_type = $("input[name=user_score_type]").val();
      }
      
      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "user_score=" + $("input[name=user_score]").val() + "&user_score_type=" + user_score_type + "&action=profile/user_edit_score",dataType: "json",cache: false,success: function (data) { 

         if(data["status"]){
            location.reload();
         }else{
            alert(data["answer"]);
            $('.user-edit-score').prop('disabled', false);
         }

      }});

      e.preventDefault();

   });

   $(document).on('click','.user-edit-score-booking', function (e) {  
      
      $(this).prop('disabled', true);

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "user_score_booking=" + $("input[name=user_score_booking]").val() + "&action=profile/user_edit_score_booking",dataType: "json",cache: false,success: function (data) { 

         if(data["status"]){
            location.reload();
         }else{
            alert(data["answer"]);
            $('.user-edit-score-booking').prop('disabled', false);
         }

      }});

      e.preventDefault();

   });

   $(document).on('click','.user-delete-review', function (e) {  
      
      $(this).prop('disabled', true);

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id=" + id_review + "&action=profile/delete_review",dataType: "html",cache: false,success: function (data) { 

         location.reload();

      }});

      e.preventDefault();

   });

   $(document).on('click','.user-review-item-delete', function (e) {  
      
      id_review = $(this).data("id");

   });

   $(document).on('click','.profile-subscriptions-ad-delete', function (e) {  
      
      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id=" + $(this).data("id") + "&action=profile/delete_ads_subscriptions",dataType: "html",cache: false,success: function (data) { 

         location.reload();

      }});

      e.preventDefault();

   });

   $(document).on('click','.profile-subscriptions-ad-period', function (e) {  
      
      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id=" + $(this).data("id") + "&period=" + $(this).data("period") + "&action=profile/period_ads_subscriptions",dataType: "html",cache: false,success: function (data) { 

         location.reload();

      }});

      e.preventDefault();

   });

   $(document).on('click','.profile-subscriptions-shop-delete', function (e) {  

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id=" + $(this).data("id") + "&action=profile/delete_shop_subscriptions",dataType: "html",cache: false,success: function (data) { 

         location.reload();

      }});

      e.preventDefault();

   });

   var change_ad_review = 0;

   $('.user-add-review-list-ads .mini-list-ads').on('click', function(){ 

       change_ad_review = $(this).data("id");
       $(".user-add-review-tab-2").hide();
       $(".user-add-review-tab-3").show();
       $(".form-user-add-review input[name=id_ad]").val(change_ad_review);
        
   });

   $('.user-add-review-tab-prev').on('click', function(){ 

       var parent = $(this).parents(".user-add-review-tab");

       parent.hide();
       parent.prev().show();

       $('.user-add-review-tab-2 input[type=radio]').prop("checked", false);
        
   });

   $('.user-add-review-tab-1 input[type=radio]').on('change', function(){ 

      if($(this).val() == 'seller'){
         $('.user-add-review-box-buyer').show();
         $('.user-add-review-box-seller').hide();
      }

      if($(this).val() == 'buyer'){
         $('.user-add-review-box-seller').show();
         $('.user-add-review-box-buyer').hide();
      }

      $(".user-add-review-tab-1").hide();
      $(".user-add-review-tab-2").show();
        
   });

   $('.user-add-review-tab-3 input[type=radio]').on('change', function(){ 

       $(".user-add-review-tab-3").hide();
       $(".user-add-review-tab-4").show();
        
   });

   function getRandomInt(min, max)
   {   
       return Math.floor(Math.random() * (max - min + 1)) + min;
   }

   function attach_reviews(input) {

      var data = new FormData();
      $.each( input.files, function( key, value ){
          data.append( key, value );
      });

      data.append('action', 'profile/load_reviews_attach_files');
     
     var i = 0;
     var count_load_img = input.files.length;

      while (i < count_load_img) {

        if (input.files && input.files[i]) {
            var reader = new FileReader();
            
            reader.onload = function (e) { 


                    var uid = getRandomInt(10000, 90000);
                    
                    $(".user-add-review-attach-files").append('<div class="id'+uid+' attach-files-preview attach-files-loader" ><img class="image-autofocus" src="'+e.target.result+'" /></div>'); 
            
               
            };

            reader.readAsDataURL(input.files[i]);
        }
        
        i++
      }
   
      $.ajax({url: url_path + "systems/ajax/controller.php",type: 'POST',data: data,cache: false,dataType: 'html',processData: false,contentType: false,
          success: function( respond, textStatus, jqXHR ){

               $(".user-add-review-attach-files").append(respond);
               $(".attach-files-loader").hide();
               $('.form-user-add-review button').prop('disabled', false);

          }
      });

      $(".input_attach_files").val("");

   }

   $(document).on('click','.user-add-review-attach-change', function () { $('.input_attach_files').click(); });
   $(document).on('change','.input_attach_files', function () {  
       if(this.files.length > 0){  
          $('.form-user-add-review button').prop('disabled', true);
          attach_reviews(this);
       }   
   });

    $(document).on("click", ".attach-files-delete", function(e) {
        $(this).parents(".attach-files-preview").hide().remove();
        e.preventDefault();
    });

    $(document).on('submit','.form-user-add-review', function (e) {  

      $(this).prop('disabled', true);
      
      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: $(this).serialize() + "&action=profile/add_review_user",dataType: "json",cache: false,success: function (data) { 

           if( data["status"] == true ){

               $("#modal-user-add-review").hide();
               $("#modal-notification").show();
               $(".modal-notification-text").html(data["answer"]);
               $("body").css("overflow", "hidden");

           }else{
               alert( data["answer"] );
           }

      }});

      e.preventDefault();

   });

   $(document).on('click','.profile-scheduler-delete', function (e) {  
      
      $(this).prop('disabled', true);

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id=" + $(this).data('id') + "&action=profile/scheduler_ad_delete",dataType: "html",cache: false,success: function (data) { 

         location.reload();

      }});

      e.preventDefault();

   });

   $(document).on('click','.user-list-ad-info-menu > svg', function (e) {  
      
      $('.user-list-ad-info-menu-list').not(this).hide();
      $(this).next().toggle();

   });

   $(document).on('click','.action-remove-publication', function () {     
      change_id_ad = $(this).data("id");
   }); 

   $(document).on('click','.profile-ads-status-sell', function () {  
     showLoadProcess($(this));   
     $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id_ad="+change_id_ad+"&action=ads/ads_status_sell",dataType: "html",cache: false,success: function (data) { 
        location.reload();
     }});
   });

   $(document).on('click','.profile-ads-remove-publication', function () {   
     showLoadProcess($(this));  
     $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id_ad="+change_id_ad+"&action=ads/remove_publication",dataType: "html",cache: false,success: function (data) { 
        location.reload();
     }});
   });   

   $(document).on('click','.profile-ads-publication', function () {  
     showLoadProcess($(this));   
     $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id_ad="+$(this).data("id")+"&action=ads/ads_publication",dataType: "html",cache: false,success: function (data) { 
        location.reload();
     }});
   });

   $(document).on('click','.profile-ads-delete', function () {     
     showLoadProcess($(this));
     $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id_ad="+change_id_ad+"&action=ads/ads_delete",dataType: "html",cache: false,success: function (data) { 
        location.reload();
     }});
   });

   $(document).on('click','.statistics-load-info-user', function () {     
     $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id="+$(this).data('id')+"&action=profile/statistics_load_info_user",dataType: "html",cache: false,success: function (data) { 
         $('.modal-statistics-load-info-user-content').html(data);
     }});
   });

   $(document).on('click','.profile-statistics-change-ad input[type=radio], .profile-booking-calendar-change-ad input[type=radio]', function () {     
        location.href = $(this).val();
   });

   $(document).on('click','.profile-init-add-card', function () {   
      $('.profile-init-add-card').prop('disabled', true);  
      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id="+$(this).data('id')+"&action=profile/profile_add_card",dataType: "json",cache: false,success: function (data) { 
          if(data['status'] == true){
             location.href = data['link'];
          }else{
             alert(data['answer']);
             $('.profile-init-add-card').prop('disabled', false);
          }
      }});
    });

    $(document).on('click','.profile-init-delete-card', function () {  
      $('.profile-init-delete-card').prop('disabled', true);    
      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id="+$(this).data('id')+"&action=profile/profile_delete_card",dataType: "json",cache: false,success: function (data) { 
          if(data['status'] == true){
             location.reload();
          }else{
             alert(data['answer']);
             $('.profile-init-delete-card').prop('disabled', false);
          }
      }});
    });

   $(document).on('click','.user-requisites-save', function (e) {  

      var el = $(this);
      showLoadProcess(el);

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: $('.user-requisites-form').serialize() + "&action=profile/user_requisites_edit",dataType: "json",cache: false,success: function (data) { 

         if(data["status"]){

            location.reload();

         }else{

            hideLoadProcess(el);

            alert(data["errors"]);

         }

      }});

      e.preventDefault();

   });

   $(document).on('change','.user-change-legal-form', function (e) {  

      $('.user-requisites-legal-form-1,.user-requisites-legal-form-2').hide();

      if($(this).val() == '1'){
         $('.user-requisites-legal-form-1').show();
      }else if($(this).val() == '2'){
         $('.user-requisites-legal-form-2').show();
      }

   });

   $(document).on('change','.user-balance-variant-list input[name=balance_variant]', function (e) {  

      $('.user-balance-variant-1,.user-balance-variant-2').hide();

      if($(this).val() == 1){
         $('.user-balance-variant-1').show();
      }else if($(this).val() == 2){
         $('.user-balance-variant-2').show();
      }

   });

   $(document).on('submit','.form-balance-invoice', function (e) {  

      var el = $('.form-balance-invoice').find("button");
      showLoadProcess(el);

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: $(this).serialize() + "&action=profile/balance_invoice",dataType: "json",cache: false,success: function (data) { 

         if(data["status"]){
            $('.form-balance-invoice input[name=amount]').val('');
            window.open(data["link"], '_blank');
         }else{
            alert(data["answer"]);
         }
         
         hideLoadProcess(el);

      }});

      e.preventDefault();

   });

   $(document).on('click','.modal-booking-calendar-cancel-date', function (e) {  

      var el = $(this);
      showLoadProcess(el);

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id_ad=" + $(this).data('id-ad') + "&date=" + $(this).data('date') + "&action=profile/cancel_date_booking_calendar",dataType: "json",cache: false,success: function (data) { 

         location.reload();

      }});

      e.preventDefault();

   });

   $(document).on('click','.modal-booking-calendar-allow-date', function (e) {  

      var el = $(this);
      showLoadProcess(el);

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id_ad=" + $(this).data('id-ad') + "&date=" + $(this).data('date') + "&action=profile/allow_date_booking_calendar",dataType: "json",cache: false,success: function (data) { 

         location.reload();

      }});

      e.preventDefault();

   });

   var dataOrders = [];

   function loadBookingOrdersDate(){

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id_ad=" + $('.booking-calendar-input-change-ad').val() + "&action=profile/load_orders_date_booking_calendar",dataType: "json",cache: false,success: function (data) {

         dataOrders = data;

         $(".profile-booking-calendar").html('');

         $(".profile-booking-calendar").datepicker({
            onSelect: function(date){

               $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id_ad=" + $('.booking-calendar-input-change-ad').val() + "&date=" + date + "&action=profile/load_orders_booking_calendar",dataType: "html",cache: false,success: function (data) { 

                  $(".modal-booking-calendar-orders-load-content").html(data);
                  $(".modal-booking-calendar-orders-date").html(date);
                  $("#modal-booking-calendar-orders").show();
                  $("body").css("overflow", "hidden");

               }});

               setTimeout(() => {

                  $.each(dataOrders,function(index,value){

                     var date = new Date(Date.parse(index));

                     if(value['count'] == 0){
                        $('.ui-datepicker-calendar td[data-month="'+date.getMonth()+'"]td[data-year="'+date.getFullYear()+'"]').find('a[data-date="'+date.getDate()+'"]').addClass('ui-calendar-disabled-date');
                     }else{
                        $('.ui-datepicker-calendar td[data-month="'+date.getMonth()+'"]td[data-year="'+date.getFullYear()+'"]').find('a[data-date="'+date.getDate()+'"]').append('<div class="ui-datepicker-calendar-order-item" >'+value['title']+'</div>');
                     }

                  });

               }, 10);

            },
         });

         $.each(dataOrders,function(index,value){

            var date = new Date(Date.parse(index));

            if(value['count'] == 0){
               $('.ui-datepicker-calendar td[data-month="'+date.getMonth()+'"]td[data-year="'+date.getFullYear()+'"]').find('a[data-date="'+date.getDate()+'"]').addClass('ui-calendar-disabled-date');
            }else{
               $('.ui-datepicker-calendar td[data-month="'+date.getMonth()+'"]td[data-year="'+date.getFullYear()+'"]').find('a[data-date="'+date.getDate()+'"]').append('<div class="ui-datepicker-calendar-order-item" >'+value['title']+'</div>');
            }

         });

      }});

   }

   $(document).on('click','.ui-datepicker-next', function (e) {

      if(dataOrders){
         setTimeout(() => {

            $.each(dataOrders,function(index,value){

               var date = new Date(Date.parse(index));

               if(value['count'] == 0){
                  $('.ui-datepicker-calendar td[data-month="'+date.getMonth()+'"]td[data-year="'+date.getFullYear()+'"]').find('a[data-date="'+date.getDate()+'"]').addClass('ui-calendar-disabled-date');
               }else{
                  $('.ui-datepicker-calendar td[data-month="'+date.getMonth()+'"]td[data-year="'+date.getFullYear()+'"]').find('a[data-date="'+date.getDate()+'"]').append('<div class="ui-datepicker-calendar-order-item" >'+value['title']+'</div>');
               }

            });

         }, 10);
      }

      e.preventDefault();

   });

   $(document).on('click','.ui-datepicker-prev', function (e) {  

      if(dataOrders){
         setTimeout(() => {

            $.each(dataOrders,function(index,value){

               var date = new Date(Date.parse(index));

               if(value['count'] == 0){
                  $('.ui-datepicker-calendar td[data-month="'+date.getMonth()+'"]td[data-year="'+date.getFullYear()+'"]').find('a[data-date="'+date.getDate()+'"]').addClass('ui-calendar-disabled-date');
               }else{
                  $('.ui-datepicker-calendar td[data-month="'+date.getMonth()+'"]td[data-year="'+date.getFullYear()+'"]').find('a[data-date="'+date.getDate()+'"]').append('<div class="ui-datepicker-calendar-order-item" >'+value['title']+'</div>');
               }

            });

         }, 10);
      }

      e.preventDefault();

   });

   $(document).on('click','.send-user-verification', function (e) {  

      var el = $(this);
      showLoadProcess(el);

      var data_form = new FormData($('.modal-user-verification-form')[0]);
      data_form.append('action', 'profile/user_add_verification');

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: data_form,dataType: "json",cache: false,contentType: false,processData: false,success: function (data) { 

         hideLoadProcess(el);

         if(data["status"] == true){
            $(".modal-user-verification-tab-1").hide();
            $(".modal-user-verification-tab-2").show();
         }else{
            alert(data["answer"]);
         }

      }});

      e.preventDefault();

   });

   $(document).on('click','.modal-user-verification-change-doc', function (e) { $('.modal-user-verification-form input[name=doc]').click(); });
   $(document).on('click','.modal-user-verification-change-photo', function (e) { $('.modal-user-verification-form input[name=photo]').click(); });
   
   $('.modal-user-verification-form input[name=doc]').on('change', function(){ 

       if (this.files && this.files[0]) {
           var reader = new FileReader();
           reader.onload = function (e) {
                 $(".modal-user-verification-change-doc-img img").attr("src",e.target.result);   
                 $(".modal-user-verification-change-doc-img").fadeIn();                           
           };

           reader.readAsDataURL(this.files[0]);
       }

   });

   $('.modal-user-verification-form input[name=photo]').on('change', function(){ 

       if (this.files && this.files[0]) {
           var reader = new FileReader();
           reader.onload = function (e) {
                 $(".modal-user-verification-change-photo-img img").attr("src",e.target.result);   
                 $(".modal-user-verification-change-photo-img").fadeIn();                           
           };

           reader.readAsDataURL(this.files[0]);
       }

   });

   $(document).on('click','.profile-open-shop-user', function (e) {  

      var el = $(this);
      showLoadProcess(el);

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "action=profile/open_shop",dataType: "json",cache: false,success: function (data) { 

         location.reload();

      }});

      e.preventDefault();

   });

   $(document).on('click','.modal-ad-packages-item', function (e) {  

      $('.modal-ad-packages-item').removeClass("active");
      $(this).addClass("active");

      $('.modal-ad-packages-item input[type=radio]').prop("checked", false);
      $(this).find("input[type=radio]").prop("checked", true);

      $('.modal-ad-packages-amount-payment').html($(this).data("amount"));

   });

   $(document).on('click','.modal-ad-packages-payment > div', function (e) {  

      $('.modal-ad-packages-payment > div').removeClass("active");
      $(this).addClass("active");

      $(".modal-ad-packages-action-payment-box").show();

   });

   function loadPackagesCategory(catId=0){

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "cat_id=" + catId + "&action=profile/load_packages_category",dataType: "html",cache: false,success: function (data) { 

         $(".packages-ads-container").html(data);

      }});

   }

   $(document).on('change','#modal-ad-packages select[name=cat_id]', function (e) { 

      loadPackagesCategory($(this).val());

   });

   $(document).on('click','.modal-ad-packages-action-payment', function (e) {  

      var el = $(".modal-ad-packages-action-payment");
      showLoadProcess(el);

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "cat_id=" + $('.packages-ads-container input[name=cat_id]').val() + "&id=" + ($(".modal-ad-packages-item.active").data("id") != undefined ? $(".modal-ad-packages-item.active").data("id") : "") + "&type=" + ($(".modal-ad-packages-payment > div.active").data("type") != undefined ? $(".modal-ad-packages-payment > div.active").data("type") : "") + "&action=profile/init_payment_package",dataType: "json",cache: false,success: function (data) { 

         if(data["status"]){
            if(data["type"] == 'balance'){

               location.reload();

            }else{
               if( data["redirect"]["link"] ){
                  location.href = data["redirect"]["link"];
               }else{
                  $("body").html(data["redirect"]["form"]);
                  $(".form-pay .pay-trans").click();               
               }
            }
         }else{
            if(data["balance"]){
               $("#modal-ad-packages").hide();
               $("#modal-balance").show();
               $(".modal-balance-summa").html( data["balance"] );
               $("body").css("overflow", "hidden");                 
            }else{
               alert(data["answer"]);
            }
            hideLoadProcess(el);
         }

         
      }});

   });


   $(document).on('click','.modal-packages-payment-copy', function (e) { 

      loadPackagesCategory($(this).data("catid"));

   });



   $(function(){ 

       loadPackagesCategory($('#modal-ad-packages select[name=cat_id]:first').val());
       loadBookingOrdersDate();

   });

});