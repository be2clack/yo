$(document).ready(function() {
    $.getScript('files/js/javascript.js');

    $(document).on('change','#option-toggle-privileges1', function (e) { $(".checkbox-privileges").prop("checked", true); });
    $(document).on('change','#option-toggle-privileges2', function (e) { $(".checkbox-privileges").prop("checked", false); });

    $(document).on('change','.select-role', function (e) { 
         if($( this ).val() == 1){
            $(".checkbox-privileges").prop("checked", true);
         }else if($( this ).val() == 2){
            $(".checkbox-privileges").prop("checked", false);
            $("input[value=control_responder], input[value=control_orders], input[value=view_orders], input[value=control_statistics]").prop("checked", true);
         }else if($( this ).val() == 3){
            $(".checkbox-privileges").prop("checked", false);
            $("input[value=control_board], input[value=control_blog], input[value=control_page]").prop("checked", true);
         }else if($( this ).val() == 4){
            $(".checkbox-privileges").prop("checked", false);
            $("input[value=processing_board]").prop("checked", true);
         }else if($( this ).val() == 5){
            $(".checkbox-privileges").prop("checked", false);
            $("input[value=control_tpl]").prop("checked", true);
         }else if($( this ).val() == 6){
            $(".checkbox-privileges").prop("checked", false);
            $("input[value=control_tpl], input[value=control_settings], input[value=control_statistics]").prop("checked", true);
         }else if($( this ).val() == 7){
            $(".checkbox-privileges").prop("checked", false);
            $("input[value=control_seo], input[value=control_statistics], input[value=control_blog]").prop("checked", true);
         }else if($( this ).val() == 8){
            $(".checkbox-privileges").prop("checked", false);
            $("input[value=control_secure]").prop("checked", true);
         }
    });
    
    $(document).on('click','.add-admin', function (e) { 

        var data_form = new FormData($('.form-data')[0]);

        $('.proccess_load').show(); 
            $.ajax({
                type: "POST",url: "include/modules/admin/handlers/add.php",data: data_form,dataType: "html",cache: false,
                contentType: false,processData: false,                        
                success: function (data) {
                    if(data == true){
                      location.href = "?route=users";
                    }else{
                      $('.proccess_load').hide();
                      notification();                        
                    }                                         
                }
            });
        e.preventDefault();
    });
    
    $(document).on('click','.edit-admin', function (e) { 

        var data_form = new FormData($('.form-data')[0]);

        $('.proccess_load').show(); 
            $.ajax({
                type: "POST",url: "include/modules/admin/handlers/edit.php",data: data_form,dataType: "html",cache: false,
                contentType: false,processData: false,                        
                success: function (data) {
                    if(data == true){
                      location.reload();
                    }else{
                      $('.proccess_load').hide();
                      notification();                        
                    }                                         
                }
            });
        e.preventDefault();
    });

    $(document).on('click','.delete-admin', function () {    
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
                      type: "POST",url: "include/modules/admin/handlers/delete.php",data: "id="+uid,dataType: "html",cache: false,
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

    
    
});    