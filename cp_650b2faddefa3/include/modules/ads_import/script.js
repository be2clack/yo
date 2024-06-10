$(document).ready(function() {
    $.getScript('files/js/javascript.js');

    $(document).on('click','.delete-import', function (e) {  

        $('.proccess_load').show();
        $.ajax({
            type: "POST",url: "include/modules/ads_import/handlers/delete.php",data: "id="+$(this).attr("data-id")+"&action=1",dataType: "html",cache: false,
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

    $(document).on('click','.delete-import-ads', function (e) {  

           swal({
              title: "Вы действительно хотите выполнить удаление?",
              text: "Внимание! Удалятся все объявления и пользователи связанные с данным импортом!",
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
                      type: "POST",url: "include/modules/ads_import/handlers/delete.php",data: "id="+$(this).attr("data-id")+"&action=2",dataType: "html",cache: false,
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

        e.preventDefault();
    });     

    
    $(document).on('click','.form-import-continue', function (e) {
        var data_form = new FormData($('.form-import')[0]);     
        $('.proccess_load').show(); 
        $.ajax({
            type: "POST",url: "include/modules/ads_import/handlers/add_import.php",data: data_form,dataType: "json",cache: false,contentType: false,processData: false,                                                
            success: function (data) {
                if(data["status"] == true){
                    location.href = "?route=ads_import_view&id=" + data["id"];
                }else{
                    $('.proccess_load').hide();
                    notification();                    
                }             
            }
        });
        e.preventDefault();
    });

    $(document).on('click','.import-start', function (e) { 
        $('.proccess_load').show(); 
        $.ajax({
            type: "POST",url: "include/modules/ads_import/handlers/start_import.php",data: $(".form-import").serialize(),dataType: "json",cache: false,                                                
            success: function (data) {
                if(data["status"] == true){
                    location.href = "?route=ads_import";
                }else{
                    $('.proccess_load').hide();
                    notification();                    
                }             
            }
        });
        e.preventDefault();
    });


});