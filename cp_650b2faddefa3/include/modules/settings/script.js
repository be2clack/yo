$(document).ready(function() {
    $.getScript('files/js/javascript.js');
    
    $(document).on('click','.load-defaul-logo', function () {  $('.input-defaul-logo').click(); });
    $(document).on('change','.input-defaul-logo', function () {    
        fileReader(this, ".load-defaul-logo", "150px");
    });

    $(document).on('click','.load-mobile-logo', function () {  $('.input-mobile-logo').click(); });
    $(document).on('change','.input-mobile-logo', function () {    
        fileReader(this, ".load-mobile-logo", "32px");
    });

    $(document).on('click','.load-favicon', function () {  $('.input-favicon').click(); });
    $(document).on('change','.input-favicon', function () {    
        fileReader(this, ".load-favicon", "32px");
    });

    $(document).on('click','.load-pwa', function () {  $('.input-pwa').click(); });
    $(document).on('change','.input-pwa', function () {    
        fileReader(this, ".load-pwa", "32px");
    });

    $(document).on('click','.load-requisites-image-signature', function () {  $('.input-requisites-image-signature').click(); });
    $(document).on('change','.input-requisites-image-signature', function () {    
        $('input[name=requisites_image_signature_delete]').val('0');
        $('.settings-requisites-image-signature-delete').show();
        fileReader(this, ".load-requisites-image-signature", "32px");
    });

    $(document).on('click','.load-requisites-image-print', function () {  $('.input-requisites-image-print').click(); });
    $(document).on('change','.input-requisites-image-print', function () {
        $('input[name=requisites_image_print_delete]').val('0');    
        $('.settings-requisites-image-print-delete').show();
        fileReader(this, ".load-requisites-image-print", "32px");
    });

    $(document).on('click','.save-settings', function (e) {    
        var data_form = new FormData($('.form-data')[0]);

        $('.proccess_load').show(); 
            $.ajax({
                type: "POST",url: "include/modules/settings/handlers/edit.php",data: data_form,dataType: "html",cache: false,
                contentType: false,processData: false,                        
                success: function (data) {
                    location.reload();                                           
                }
            });
        e.preventDefault();
    });

    $(document).on('click','.test-send-smtp', function () {   

      $(".result-log").val("");  

            $.ajax({
                type: "POST",url: "include/modules/settings/handlers/test_send_smtp.php",dataType: "html",
                cache: false,
                success: function (data) {
                    $(".result-log").val(data);   
                    notification();                 
                }
            });

    });
    
    $(document).on('click','.test-send-sms', function () {     

      $(".result-log").val("");

            $.ajax({
                type: "POST",url: "include/modules/settings/handlers/test_send_sms.php",dataType: "html",
                cache: false,
                success: function (data) {
                    $(".result-log").val(data);   
                    notification();                 
                }
            });            
    });

    $(document).on('click','.test-send-telegram', function () {     

      $(".result-log").val("");
      
            $.ajax({
                type: "POST",url: "include/modules/settings/handlers/test_send_telegram.php",dataType: "html",
                cache: false,
                success: function (data) {
                    $(".result-log").val(data);   
                    notification();                 
                }
            });            
    });

    $(document).on('change click','input[name=api_id_telegram]', function () {     

            $.ajax({
                type: "POST",url: "include/modules/settings/handlers/get_telegram_chat_id.php",data: "token=" + $(this).val() ,dataType: "json",
                cache: false,
                success: function (data) {
                  if(data["status"]){
                    $("input[name=chat_id_telegram]").val( data["answer"] );                 
                  }else{

                    if( data["answer"]["result"] ){
                       alert( data["answer"] );
                    }
                    
                  }
                }
            });            
    });

    $('.settings-add-currency').click(function () {  

    $('.proccess_load').show();   

        $.ajax({
            type: "POST",url: "include/modules/settings/handlers/add_currency.php",data: $(".form-add-currency").serialize(),dataType: "html",
            cache: false,success: function (data) {

                $('.proccess_load').hide();
                if(data == true){
                  location.reload();                    
                }else{
                  notification();                          
                }
                   
            }
        });        
    });

    $('.delete-currency').click(function () {     
     var uid = $(this).attr("uid");

       swal({
          title: "Вы действительно хотите выполнить удаление?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: "Да",
          cancelButtonText: "Нет"
        }).then((result) => {
          if (result.value) {
              $('.proccess_load').show();
              $.ajax({
                  type: "POST",url: "include/modules/settings/handlers/delete_currency.php",data: "id="+uid,dataType: "html",cache: false,
                  success: function (data) {
                    $('.proccess_load').hide();
                    if(data == true){
                      location.reload();                    
                    }else{
                      notification();                          
                    }
                  }
              });                 
          }
        })
         
    });

    $(document).on("change", ".settings-access-site", function() {

        if($( this ).prop("checked") == false){
             $(".settings-access-out-text").attr("disabled", false).prop("checked", true);
             $(".settings-access-redirect").attr("disabled", false).prop("checked", false);
             $(".settings-access-redirect-link").attr("disabled", true);
             $(".settings-access-text").attr("disabled", false);   
             $(".settings-access-ip").attr("disabled", false);         
        }else{
             $(".settings-access-out-text").attr("disabled", true).prop("checked", false);
             $(".settings-access-redirect").attr("disabled", true).prop("checked", false);
             $(".settings-access-redirect-link").attr("disabled", true);
             $(".settings-access-text").attr("disabled", true);
             $(".settings-access-ip").attr("disabled", true);              
        }

     });

    $(document).on("change", ".settings-access-redirect", function() {

             $(".settings-access-redirect-link").attr("disabled", false);
             $(".settings-access-text").attr("disabled", true);            

    });

    $(document).on("change", ".settings-access-out-text", function() {

             $(".settings-access-redirect-link").attr("disabled", true);
             $(".settings-access-text").attr("disabled", false);                 

    });

    $(document).on("change", "input[name=robots_manual_setting]", function() {

             $(".robots_manual_setting").toggle();
             $(".robots_index_site").toggle();

    });

    $(document).on("click", ".setting-open-email", function() {

        var uid = $(this).attr("data-id");

              $('.proccess_load').show();
              $.ajax({
                  type: "POST",url: "include/modules/settings/handlers/load_email_tpl.php",data: "id="+uid,dataType: "html",cache: false,
                  success: function (data) {
                      $('.proccess_load').hide();
                      $(".container-templates").html(data); 
                      $("#modal-email-templates").modal("show");                    
                  }
              });                

    });

    $('.settings-edit-email-template').click(function () {

        var data_form = new FormData($(".form-email-templates")[0]);

        var email_text = CKEDITOR.instances['email_text'];
        if(email_text){
          data_form.append('email_text', CKEDITOR.instances.email_text.getData());
        }

        $('.proccess_load').show();

            $.ajax({type: "POST",url: "include/modules/settings/handlers/edit_email_tpl.php",data: data_form,dataType: "html",cache: false,contentType: false,processData: false,                        
                success: function (data) {
                    $('.proccess_load').hide();
                    notification();                                           
                }
            });

    });

    $(document).on("change", "#responder-rad-1", function() {

            $("input[name=smtp_host]").attr("disabled", true);
            $("input[name=smtp_port]").attr("disabled", true);
            $("input[name=smtp_username]").attr("disabled", true);
            $("input[name=smtp_password]").attr("disabled", true);                   

    });

    $(document).on("change", "#responder-rad-2", function() {

            $("input[name=smtp_host]").attr("disabled", false);
            $("input[name=smtp_port]").attr("disabled", false);
            $("input[name=smtp_username]").attr("disabled", false);
            $("input[name=smtp_password]").attr("disabled", false);         

    });

    $(document).on("change", ".change-payment", function() {
      var code = $( this ).val();
        $('.proccess_load').show();

            $.ajax({type: "POST",url: "include/modules/settings/handlers/load_payment.php",data: "code="+code,dataType: "html",cache: false,                        
                success: function (data) {
                    $('.proccess_load').hide();
                    notification();  
                    $(".param-payment").html(data); 
                    $(".selectpicker").selectpicker('refresh');                                        
                }
            });          

    });

    $(document).on("change", "#option-toggle-editor1", function() {
          CKEDITOR.replace("email_text");               
    });

    $(document).on("change", "#option-toggle-editor2", function() {
        var instance = CKEDITOR.instances['email_text'];
        if(instance){      
           CKEDITOR.instances['email_text'].destroy(true);  
        }   
    });

    var fixHelper = function(e, ui) {
        ui.children().each(function() {
            $(this).width($(this).width());
        });
        return ui;
    };

    $('.sort-container').sortable({ 
        axis: 'y',
        opacity: 0.5,
        cursor: "move",
        helper: fixHelper,
        handle:'.move-sort',
        stop: function(){
        var arr = $('.sort-container').sortable("toArray");
        $.ajax({url: "include/modules/settings/handlers/sorting_lang.php",type: 'POST',data: {arrays:arr},
          success: function(data){
              notification();
          }
        });
      }         
    });
    
    $(document).on("click", ".delete-lang", function() {     
     var uid = $(this).attr("data-id");

       swal({
          title: "Вы действительно хотите выполнить удаление?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: "Да",
          cancelButtonText: "Нет"
        }).then((result) => {
          if (result.value) {
              $('.proccess_load').show();
              $.ajax({
                  type: "POST",url: "include/modules/settings/handlers/delete_lang.php",data: "id="+uid,dataType: "html",cache: false,
                  success: function (data) {
                    $('.proccess_load').hide();
                    if(data == true){
                      location.reload();                    
                    }else{
                      notification();                          
                    }
                  }
              });                 
          }
        })
         
    });
    
    $(document).on("click", ".add-lang", function() {

    var data_form = new FormData($('.form-add-lang')[0]);

    $('.proccess_load').show();   

        $.ajax({
            type: "POST",url: "include/modules/settings/handlers/add_lang.php",data: data_form,dataType: "html",
            cache: false,contentType: false,processData: false,success: function (data) { console.log(data);

                $('.proccess_load').hide();
                if(data == true){
                  location.reload();                    
                }else{
                  notification();                          
                }
                   
            }
        });        
    });
    
    $(document).on("click", ".edit-lang", function() {

    var data_form = new FormData($('.form-edit-lang')[0]);

    $('.proccess_load').show();   

        $.ajax({
            type: "POST",url: "include/modules/settings/handlers/edit_lang.php",data: data_form,dataType: "html",
            cache: false,contentType: false,processData: false,success: function (data) { console.log(data);

                $('.proccess_load').hide();
                if(data == true){
                  location.reload();                    
                }else{
                  notification();                          
                }
                   
            }
        });        
    });
    
    $(document).on('click','.load_edit_lang', function () {     
        var data_id = $(this).attr("data-id");
        $.ajax({type: "POST",url: "include/modules/settings/handlers/load_edit_lang.php", data: "id="+data_id,dataType: "html",cache: false,                     
            success: function (data) {
                $('.proccess_load').hide();
                $('.form-edit-lang').html(data);  
                $('#modal-edit-lang').modal("show");                                         
            }
        });        
    });

    $(document).on("change", "select[name=map_vendor]", function() {

        if($(this).val() == "google"){
           $(".map-google-key").show();
           $(".map-yandex-key").hide();
           $(".map-openstreetmap-key").hide();
        }else if($(this).val() == "yandex"){
           $(".map-yandex-key").show();
           $(".map-google-key").hide();
           $(".map-openstreetmap-key").hide();
        }else if($(this).val() == "openstreetmap"){
           $(".map-openstreetmap-key").show();
           $(".map-google-key").hide();
           $(".map-yandex-key").hide();
        }else{
           $(".map-google-key").hide();
           $(".map-yandex-key").hide();
           $(".map-openstreetmap-key").hide();
        }                

    });

    $(document).on("change", "select[name=country_default]", function() {

        var country = $( this ).find('option:selected').attr("data-id");

          $('.proccess_load').show();
          $.ajax({
              type: "POST",url: "include/modules/settings/handlers/load_region.php",data: "country="+country,dataType: "html",cache: false,
              success: function (data) {
                  $('.proccess_load').hide();
                  if(data){
                    $('.settings-region-box').html(data).show();
                  }else{
                    $('.settings-city-box').hide();
                  }
                  $('.selectpicker').selectpicker();
              }
          });                

    });

    $(document).on("change", "select[name=region_id]", function() {

        var region = $( this ).find('option:selected').val();

          $('.proccess_load').show();
          $.ajax({
              type: "POST",url: "include/modules/settings/handlers/load_city.php",data: "region="+region,dataType: "html",cache: false,
              success: function (data) {
                  $('.proccess_load').hide();
                  if(data){
                    $('.settings-city-box').html(data).show();
                  }else{
                    $('.settings-city-box').hide();
                  }
                  $('.selectpicker').selectpicker();
              }
          });                

    });

    $(document).on("change", "select[name=sms_service]", function() {

        if( $(this).find('option:selected').data("param") == "id" ){
          $('.sms_service_id').show();
          $('.sms_service_login_pass').hide();
        }else if( $(this).find('option:selected').data("param") == "login:pass" ){
          $('.sms_service_id').hide();
          $('.sms_service_login_pass').show();
        }else{
          $('.sms_service_id').hide();
          $('.sms_service_login_pass').hide();          
        }  

        if( $(this).find('option:selected').data("label") == "1" ){
            $(".sms_service_label").show();
        }else{
            $(".sms_service_label").hide();
        }        

        if( $(this).find('option:selected').data("call") == "1" ){
            $(".sms_service_method_send").show();
        }else{
            $(".sms_service_method_send").hide();
            $('.sms_service_method_send_sms').show();
        }

        $('.sms_service_method_send option:first').prop('selected', true);

        $(".selectpicker").selectpicker('refresh');

    });

    $(document).on("change", "select[name=sms_service_method_send]", function() {

        if( $(this).val() == "sms" ){
            $(".sms_service_method_send_sms").show();
        }else{
            $(".sms_service_method_send_sms").hide();
        }        

    });

    $(document).on('click','.test-payment', function () {     
        var name = $(this).data("name");
        $.ajax({type: "POST",url: "include/modules/settings/handlers/test_payment.php", data: "payment="+name,dataType: "json",cache: false,                     
            success: function (data) { console.log(data);
               if( data["link"] ){
                  location.href = data["link"];
               }else if( data["form"] ){
                  $("body").append( '<div class="temp-payment-form" >' + data["form"] + '</div>' );
                  $(".form-pay .pay-trans").click();
               }                                         
            }
        });        
    });

    $(document).on("change", ".checkbox-receipt", function() {

        if($( this ).prop("checked") == true){ 
             $(".payment-receipt").show();         
        }else{
             $(".payment-receipt").hide();             
        }

     });

    $(document).on("change", "select[name=watermark_type]", function() {

        if($( this ).val() == "caption"){ 
             $(".watermark-box-caption").show();         
             $(".watermark-box-img").hide();         
        }else{
             $(".watermark-box-caption").hide();         
             $(".watermark-box-img").show();              
        }

     });

     $(document).on("change", "select[name=delivery_service]", function() {

        if($(this).val()){ 
             $(".settings-box-options-delivery").show();                
        }else{
             $(".settings-box-options-delivery").hide();              
        }

     });

    $(document).on('click','.cache-clear', function (e) {    

        $('.proccess_load').show(); 
            $.ajax({
                type: "POST",url: "include/modules/settings/handlers/cache_clear.php",data: "",dataType: "html",cache: false,                        
                success: function (data) {
                    $('.proccess_load').hide();
                    notification();                                          
                }
            });

        e.preventDefault();
    });

    $(document).on("change", "input[name=user_shop_discount_status]", function() {

        if($( this ).prop("checked") == false){
             $(".settings-shop-discount").hide();        
        }else{
             $(".settings-shop-discount").show();             
        }

     });

    $(document).on("change", "input[name=user_stories_paid_add]", function() {

        if($( this ).prop("checked") == true){ 
             $(".stories-paid-option").show();         
        }else{
             $(".stories-paid-option").hide();             
        }

     });

    $('.settings-widget-sorting-home').sortable({ 
        axis: 'y',
        opacity: 0.5,
        cursor: "move",
        helper: fixHelper,
        handle:'.settings-widget-sorting-move',
        stop: function(){
            var arr = $('.settings-widget-sorting-home').sortable("toArray");
            $('input[name=home_widget_sorting]').val(arr.join(","));
        }         
    });

    $('.settings-widget-sorting-home-app').sortable({ 
        axis: 'y',
        opacity: 0.5,
        cursor: "move",
        helper: fixHelper,
        handle:'.settings-widget-sorting-move',         
    });

    $(document).on("change", ".settings-requisites-change-legal-form", function() {

        $(".settings-requisites-legal-form-1,.settings-requisites-legal-form-2").hide();

        if($(this).find('option:selected').val() == 1){ 
           $(".settings-requisites-legal-form-1").show();         
        }else if($(this).find('option:selected').val() == 2){
           $(".settings-requisites-legal-form-2").show();             
        }

     });

    $(document).on("click", ".settings-requisites-image-signature-delete", function() {
        $('input[name=requisites_image_signature_delete]').val('1');
        $('.load-requisites-image-signature').attr("src", $("body").data("media-other") + "/icon_photo_add.png" );
        $('.input-requisites-image-signature').val('');
    });

    $(document).on("click", ".settings-requisites-image-print-delete", function() {
        $('input[name=requisites_image_print_delete]').val('1');
        $('.load-requisites-image-print').attr("src", $("body").data("media-other") + "/icon_photo_add.png" );
        $('.input-requisites-image-print').val('');
    });

    $(document).on("click", ".settings-app-promo-slider-add", function() {
        $('.settings-app-promo-slider-container').append(`
              <div class="settings-app-promo-slider-item" >
                 <div class="row" >
                    <div class="col-lg-6" ><input type="text" class="form-control" name="app_home_promo_slider_list[title][]" placeholder="Заголовок" ></div>
                    <div class="col-lg-6" ><input type="text" class="form-control" name="app_home_promo_slider_list[image][]" placeholder="Ссылка на изображение" ></div>
                    <div class="col-lg-12" >
                       <textarea name="app_home_promo_slider_list[desc][]" class="form-control" rows="6" placeholder="Краткое описание" ></textarea>
                    </div>
                 </div>
                 <div class="text-right" ><span class="btn btn-sm btn-gradient-01 settings-app-promo-slider-remove"><i class="la la-trash"></i> Удалить</span></div>
              </div>
        `);
    });

    $(document).on("click", ".settings-app-promo-slider-remove", function() {
        $(this).parents(".settings-app-promo-slider-item").remove().hide();
    });

    function loadUpdate(){
        $.ajax({type: "POST",url: "include/modules/settings/handlers/load_info_update.php",dataType: "json",cache: false,                        
            success: function (data) {
                $(".updates-box-widget").html(data["update"]);  
                $(".updates-box-widget-patch-fix").html(data["patch"]);                                     
            }
        });        
    }

    $(document).on('click','.updates-init-payment-install', function (e) {
        var el = this;
        $(el).attr("disabled", true);
        $('.proccess_load').show(); 
            $.ajax({
                type: "POST",url: "include/modules/settings/handlers/init_payment_update.php",data: "version="+$(this).data("version"),dataType: "json",cache: false,                        
                success: function (data) {
                    $('.proccess_load').hide();
                    if(data["status"] == true){
                        window.open(data["link"], '_blank');
                    }else{
                        alert(data["answer"]);
                    } 
                    $(el).attr("disabled", false);                                        
                }
            });

        e.preventDefault();
    });

    $(document).on('click','.updates-init-free-install', function (e) {
        var el = this;
        $(el).attr("disabled", true);
        $('.proccess_load').show(); 
            $.ajax({
                type: "POST",url: "include/modules/settings/handlers/install_update.php",data: "version="+$(this).data("version"),dataType: "json",cache: false,                        
                success: function (data) {
                    if(data["status"] == true){
                        location.reload();
                    }else{
                        alert(data["answer"]);
                        $('.proccess_load').hide();
                    }   
                    $(el).attr("disabled", false);                                    
                }
            });

        e.preventDefault();
    });

    $(document).on('click','.updates-init-patch-install', function (e) {
        var el = this;
        $(el).attr("disabled", true);
        $('.proccess_load').show(); 
            $.ajax({
                type: "POST",url: "include/modules/settings/handlers/install_patch.php",data: "version="+$(this).data("version"),dataType: "json",cache: false,                        
                success: function (data) {
                    if(data["status"] == true){
                        location.reload();
                    }else{
                        alert(data["answer"]);
                        $('.proccess_load').hide();
                    }   
                    $(el).attr("disabled", false);                                    
                }
            });

        e.preventDefault();
    });

    $(document).on('click','.updates-action-open-log', function (e) {
        $('.proccess_load').show(); 
            $.ajax({
                type: "POST",url: "include/modules/settings/handlers/load_log_update.php",data: "id="+$(this).data("id"),dataType: "html",cache: false,                        
                success: function (data) {
                    $('.proccess_load').hide(); 
                    $(".modal-updates-log-container").html(data);                             
                }
            });

        e.preventDefault();
    });

    $(document).on('click','.init-modal-updates-variant-install', function (e) {

        $(".modal-updates-variant-install-actions").attr("data-version", $(this).data("version"));

        e.preventDefault();
    });

    $(document).on('click','.updates-load-manually-install', function (e) {
        $(".proccess_load").show();
        $.ajax({
            type: "POST",url: "include/modules/settings/handlers/manually_updates.php",data: "version="+$(this).data("version"),dataType: "html",cache: false,                        
            success: function (data) {
                $("#modal-updates-variant-install").modal("hide");
                $(".modal-updates-manually-container").html(data);       
                $(".proccess_load").hide();                             
            }
        });
        e.preventDefault();
    });


    $(document).on("click", ".settings-app-promo-banner-add", function() {
        $('.settings-app-promo-banner-container').append(`
              <div class="settings-app-promo-banner-item" >
                 <div class="row" >
                    <div class="col-lg-6" ><input type="text" class="form-control" name="app_home_promo_banner_list[link][]" placeholder="Ссылка на источник" ></div>
                    <div class="col-lg-6" ><input type="text" class="form-control" name="app_home_promo_banner_list[image][]" placeholder="Ссылка на изображение" ></div>
                 </div>
                 <div class="text-right" ><span class="btn btn-sm btn-gradient-01 settings-app-promo-banner-remove"><i class="la la-trash"></i> Удалить</span></div>
              </div>
        `);
    });

    $(document).on("click", ".settings-app-promo-banner-remove", function() {
        $(this).parents(".settings-app-promo-banner-item").remove().hide();
    });


    $(document).on("change", "select[name=board_type_ad_publication]", function() {

        if($(this).val() == "free"){
           $(".container-board-price-ad-publication").hide();
        }else if($(this).val() == "paid"){
           $(".container-board-price-ad-publication").show();
        }               

    });

    $(document).on('click','.save-chat-snippets-message', function (e) { console.log($(".form-chat-snippets-message").serialize());
        $(".proccess_load").show();
        $.ajax({
            type: "POST",url: "include/modules/settings/handlers/app_save_snippets_chat.php",data: $(".form-chat-snippets-message").serialize(),dataType: "html",cache: false,                        
            success: function (data) {
                $("#modal-chat-snippets-message").modal("hide");       
                $(".proccess_load").hide();                             
            }
        });
        e.preventDefault();
    });

    loadUpdate();


}); 