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

   $(document).on('click','.auth-forgot', function (e) {   
      $(".msg-error").hide();  
      var this_ = $(this);
      showLoadProcess(this_);
      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "login=" + $(".auth-block-tab-forgot .auth-forgot-login").val() + "&captcha=" + $(".auth-block-tab-forgot input[name=captcha]").val() + "&action=profile/forgot",dataType: "json",cache: false,success: function (data) { 
          if(data["status"] == true){
               alert(data["answer"]);
               captcha();
          }else{

               $.each( data["answer"] ,function(index,value){
                 
                 $(".auth-block-tab-forgot .msg-error[data-name="+index+"]").html(value).show();

               });

          }
          hideLoadProcess(this_);
      }});
      e.preventDefault();
   });

   $(document).on('click','.action-auth-send', function (e) {  
      
      $(".msg-error").hide();
      var this_ = $(this);

      showLoadProcess(this_);

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "user_login=" + $(".auth-block-tab-auth input[name=user_login]").val() + "&user_pass=" + $(".auth-block-tab-auth input[name=user_pass]").val() + "&save_auth=" + $(".auth-block-tab-auth input[name=save_auth]").val() + "&captcha=" + $(".auth-block-tab-auth input[name=captcha]").val() + "&action=profile/check_auth",dataType: "json",cache: false,success: function (data) { 

         if(data["captcha"]){
            $(".auth-captcha").show();
            captcha();
         }

         if( data["status"] == true ){
            
            location.href = data['location'];

         }else{

            if(data["status_user"] == 2){
               $("#modal-auth-block").show();
               $("body").css("overflow", "hidden");
            }else if(data["status_user"] == 3){
               $("#modal-auth-delete").show();
               $("body").css("overflow", "hidden");
            }else{

               $.each( data["answer"] ,function(index,value){
                 
                 $(".auth-block-tab-auth .msg-error[data-name="+index+"]").html(value).show();

               });

            }

            hideLoadProcess(this_);

         }
 

      }});

      e.preventDefault();

   });

   $(document).on('click','.action-reg-send', function (e) {  
      
      $(".msg-error").hide();
      var this_ = $(this);

      showLoadProcess(this_);

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "user_login=" + $(".auth-block-tab-reg input[name=user_login]").val() + "&captcha=" + $(".auth-block-tab-reg input[name=captcha]").val() + "&action=profile/registration",dataType: "json",cache: false,success: function (data) { 

         if( data["status"] == true ){
             
             if(data['confirmation']){
                $(".auth-block-right-box-tab-1-1").hide();
                $(".auth-block-right-box-tab-1-2").show();
                $('.auth-block-right-box-tab-1-2 input[name=user_code_login]').attr('placeholder',data['confirmation_title']);
             }else{
                $(".auth-block-right-box-tab-1-1").hide();
                $(".auth-block-right-box-tab-1-3").show();                
             }

         }else{

            if(data["status_user"] == 2){
               $("#modal-auth-block").show();
               $("body").css("overflow", "hidden");
            }else if(data["status_user"] == 3){
               $("#modal-auth-delete").show();
               $("body").css("overflow", "hidden");
            }else{

               $.each( data["answer"] ,function(index,value){
                 
                 $(".auth-block-tab-reg .msg-error[data-name="+index+"]").html(value).show();

               });

            }

         }

         hideLoadProcess(this_);  

      }});

      e.preventDefault();

   });

   $(document).on('click','.action-reg-verify', function (e) {  
      
      $(".msg-error").hide();
      var this_ = $(this);

      showLoadProcess(this_);

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "user_login=" + $(".auth-block-tab-reg input[name=user_login]").val() + "&user_code_login=" + $(".auth-block-tab-reg input[name=user_code_login]").val() + "&captcha=" + $(".auth-block-tab-reg input[name=captcha]").val() + "&action=profile/verify_login",dataType: "json",cache: false,success: function (data) { 

         if(data["captcha"]){
            $(".auth-block-right-box-tab-1-2 .auth-captcha,.auth-block-right-box-tab-1-3 .auth-captcha").show();
            if(data['captcha_reload']){
               captcha();
            }
         }else{
            $(".auth-block-right-box-tab-1-2 .auth-captcha,.auth-block-right-box-tab-1-3 .auth-captcha").hide();
         }

         if( data["status"] == true ){
            
            $(".auth-block-right-box-tab-1-2").hide();
            $(".auth-block-right-box-tab-1-3").show();

         }else{

            $.each( data["answer"] ,function(index,value){
              
              $(".auth-block-tab-reg .msg-error[data-name="+index+"]").html(value).show();

            });

         }

         hideLoadProcess(this_);   

      }});

      e.preventDefault();

   });

   $(document).on('click','.action-reg-finish', function (e) {  
      
      $(".msg-error").hide();
      var this_ = $(this);
      
      showLoadProcess(this_);

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "user_login=" + $(".auth-block-tab-reg input[name=user_login]").val() + "&user_code_login=" + $(".auth-block-tab-reg input[name=user_code_login]").val() + "&user_pass=" + $(".auth-block-tab-reg input[name=user_pass]").val() + "&user_name=" + $(".auth-block-tab-reg input[name=user_name]").val() + "&action=profile/reg_finish",dataType: "json",cache: false,success: function (data) { 

         if( data["status"] == true ){
            
            location.href = data['location'];

         }else{

            if(data["answer"]){
               $.each( data["answer"] ,function(index,value){
                 
                 $(".auth-block-tab-reg .msg-error[data-name="+index+"]").html(value).show();

               });

               hideLoadProcess(this_);
            }else{
               location.reload();
            }

         }  

      }});

      e.preventDefault();

   });

   $(document).on('click','.auth-block-right-box-tab-1-2-back > span', function (e) {  

      $(".auth-block-right-box-tab-1-2").hide();
      $(".auth-block-right-box-tab-1-1").show();

   });

   $(document).on('click','.auth-block-tabs > span', function (e) {  

      $('.auth-block-tabs > span').removeClass('active');
      $(this).addClass('active');
      $(".auth-block-tab").hide();
      $(".auth-block-tab-"+$(this).data('tab')).show();

   });

   $(document).on('click','.action-forgot', function (e) {  

      $('.auth-block-tabs > span').removeClass('active');
      $(".auth-block-tab").hide();
      $(".auth-block-tab-forgot").show();

   });

   function captcha(){
      $('.auth-captcha img').attr('src', url_path + 'systems/captcha/captcha.php?name=auth');
   }

   $(document).on('click','.event-point-auth', function () {     
      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "location="+document.location.href+"&action=profile/event_point_auth",dataType: "html",cache: false});
   });

   $(document).on('click','.auth-captcha img', function () { 
     
     captcha();

   });

   captcha();


});