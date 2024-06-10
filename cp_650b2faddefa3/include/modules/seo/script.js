$(document).ready(function() {
    $.getScript('files/js/javascript.js');
    
    $(document).on('click','.edit-seo', function (e) { 
        var data_form = new FormData($('.form-data')[0]);
        data_form.append('form[index][text]', CKEDITOR.instances["text1"].getData());
        data_form.append('form[board][text]', CKEDITOR.instances["text2"].getData());
        data_form.append('form[blog_category][text]', CKEDITOR.instances["text3"].getData());
        data_form.append('form[blog][text]', CKEDITOR.instances["text5"].getData());
        data_form.append('form[board_geo][text]', CKEDITOR.instances["text4"].getData());
        data_form.append('form[shops][text]', CKEDITOR.instances["shops_text"].getData());
        data_form.append('form[shops_category][text]', CKEDITOR.instances["shops_category_text"].getData());
        $('.proccess_load').show(); 
        $.ajax({type: "POST",url: "include/modules/seo/handlers/edit.php",data: data_form, dataType: "html",cache: false,contentType: false,processData: false,                        
            success: function (data) {
                $('.proccess_load').hide(); notification();                                           
            }
        });
        e.preventDefault();
    });    

    $(document).on('click','.delete-seo-filter', function () {    
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
                      type: "POST",url: "include/modules/seo/handlers/delete_filter.php",data: "id="+uid,dataType: "html",cache: false,
                      success: function (data) {
                        if(data == true){
                          location.reload();                    
                        }else{
                          $('.proccess_load').hide();
                          notification();                          
                        }
                      }
                  });                 
              }
            })

        return false;         
    });

    $(document).on('click','.add-seo-filter', function (e) {    
        var data_form = new FormData($('.form-data')[0]);
        data_form.append('text', CKEDITOR.instances.text.getData());      
        $('.proccess_load').show(); 
        $.ajax({
            type: "POST",url: "include/modules/seo/handlers/add_filter.php",data: data_form,dataType: "html",cache: false,contentType: false,processData: false,                                                
            success: function (data) {
                if (data==true){
                    location.href = "?route=seo_filters";  
                }else{
                    $('.proccess_load').hide();
                    notification();  
                }                                           
            }
        });
        e.preventDefault();
    });    
    
    $(document).on('click','.edit-seo-filter', function (e) {
        var data_form = new FormData($('.form-data')[0]);
        data_form.append('text', CKEDITOR.instances.text.getData());        
        $('.proccess_load').show(); 
        $.ajax({
            type: "POST",url: "include/modules/seo/handlers/edit_filter.php",data: data_form,dataType: "html",cache: false,contentType: false,processData: false,                                                
            success: function (data) {
                if (data==true){
                    location.reload();
                }else{
                    $('.proccess_load').hide();
                    notification();  
                }          
            }
        });
        e.preventDefault();
    });

    $(document).on('input click','.setUrlParameters', function (e) {
        $.ajax({
            type: "POST",url: "include/modules/seo/handlers/set_param.php",data: "url=" + encodeURIComponent($(this).val()) ,dataType: "json",cache: false,                                                
            success: function (data) {
               if( data["alias_geo"] ) $("input[name=alias_geo]").val( data["alias_geo"] );             
               if( data["alias_category"] ) $("input[name=alias_category]").val( data["alias_category"] );             
               if( data["params"] ) $("input[name=url]").val( data["params"] );             
            }
        });
        e.preventDefault();
    });

    $(document).on('change','input[name=geo_auto]', function (e) {
        if( $(this).prop("checked") == true ){
            $("input[name=alias_geo]").attr("disabled", true);
        }else{
            $("input[name=alias_geo]").attr("disabled", false);
        }
        e.preventDefault();
    });

    $(document).on('change','.tab-content-2-change-condition', function (e) {
        if( $(this).val() == 0 ){
            $(".tab-content-2-condition-1").show();
            $(".tab-content-2-condition-2").hide();
            $(".tab-content-2-makros-2,.tab-content-2-makros-3,.tab-content-2-makros-4,.tab-content-2-makros-5").show();
        }else{
            $(".tab-content-2-condition-1").hide();
            $(".tab-content-2-condition-2").show();
            $(".tab-content-2-makros-2,.tab-content-2-makros-3,.tab-content-2-makros-4,.tab-content-2-makros-5").hide();
        }
        e.preventDefault();
    });

    $(document).on('change','.tab-content-6-change-condition', function (e) {
        if( $(this).val() == 0 ){
            $(".tab-content-6-condition-1").show();
            $(".tab-content-6-condition-2").hide();
        }else{
            $(".tab-content-6-condition-1").hide();
            $(".tab-content-6-condition-2").show();
        }
        e.preventDefault();
    });

    $(document).on('change','.tab-content-4-change-condition', function (e) {
        if( $(this).val() == 0 ){
            $(".tab-content-4-condition-1").show();
            $(".tab-content-4-condition-2").hide();
            $(".tab-content-4-makros-1,.tab-content-4-makros-2,.tab-content-4-makros-3,.tab-content-4-makros-4,.tab-content-4-makros-5,.tab-content-4-makros-6").show();
        }else{
            $(".tab-content-4-condition-1").hide();
            $(".tab-content-4-condition-2").show();
            $(".tab-content-4-makros-1,.tab-content-4-makros-2,.tab-content-4-makros-3,.tab-content-4-makros-4,.tab-content-4-makros-5,.tab-content-4-makros-6").hide();
        }
        e.preventDefault();
    });

}); 