$(document).ready(function() {
    $.getScript('files/js/javascript.js');
    
    var change_region_id = 0;
    var metro_change_city_id = 0;
    var area_change_city_id = 0;
    
    $('.button_country_add').click(function (e) {  
        var data_form = new FormData($("#form-data-country-add")[0]); 
        $('.proccess_load').show(); 
        $.ajax({type: "POST",url: "include/modules/cities/handlers/add.php",data: data_form,dataType: "html", cache: false,contentType: false,processData: false,                     
            success: function (data) {
                if(data == true){location.reload(); }else{$('.proccess_load').hide(); notification(); }                                           
            }
        });
        e.preventDefault();
    });    

    $('.button_country_edit').click(function (e) { 
        var data_form = new FormData($("#form-data-country-edit")[0]);   
        $('.proccess_load').show(); 
        $.ajax({type: "POST",url: "include/modules/cities/handlers/edit.php",data: data_form,dataType: "html",cache: false,contentType: false,processData: false,                     
            success: function (data) {
                if(data == true){location.reload(); }else{$('.proccess_load').hide(); notification(); }                                           
            }
        });
        e.preventDefault();
    });

    $(document).on('click','.load_edit_city', function () {     
        var data_id = $(this).attr("data-id");
        $('.proccess_load').show();
        $.ajax({type: "POST",url: "include/modules/cities/handlers/load_edit.php",data: "id="+data_id,dataType: "html",cache: false,                     
            success: function (data) {
                $('.proccess_load').hide();
                $('.modal-city-edit').html(data);                                           
            }
        });        
    });

    $(document).on('click','.load_edit_country', function () {     
        var data_id = $(this).attr("data-id");
        $.ajax({type: "POST",url: "include/modules/cities/handlers/load_edit_country.php", data: "id="+data_id,dataType: "html",cache: false,                     
            success: function (data) {
                $('.proccess_load').hide();
                $('.modal-country-edit').html(data);  
                $('.selectpicker').selectpicker('refresh');                                         
            }
        });        
    });

    $(document).on('change','.change_region_id', function () {     
        change_region_id = $(this).val();
        $('#form-data-city-add input[name=region_id]').val($(this).val());
        $('.proccess_load').show();
        reload_city(change_region_id);       
    });

    function reload_city(region_id){
         $.ajax({type: "POST",url: "include/modules/cities/handlers/load_country_city.php",data: "id="+region_id,dataType: "html",cache: false,                     
            success: function (data) {
                $('.proccess_load').hide();
                $('.cont-country-city').html(data);                                           
            }
        });       
    }
    
    $(document).on('click','.delete-country', function () {
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
                      type: "POST",url: "include/modules/cities/handlers/delete_item_country.php",data: "id="+$(this).attr("data-id"),dataType: "html",cache: false,
                      success: function (data) {
                        location.reload();
                      }
                  });                 
              }
            })
        return false;
    });

    $(document).on('click','.delete_item_city', function () {       
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
                    type: "POST",
                    url: "include/modules/cities/handlers/delete_item_city.php",
                    data: "id="+$(this).attr("data-id"),
                    dataType: "html",                     
                    success: function (data) {
                        reload_city(change_region_id);  
                        notification();                                        
                    }
                });                 
          }
        })
    });

    $(document).on('click','.delete_item_region', function () {
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
                    type: "POST",
                    url: "include/modules/cities/handlers/delete_item_region.php",
                    data: "id="+$(this).attr("data-id"),
                    dataType: "html",                     
                    success: function (data) {  
                        location.reload();                                      
                    }
                });                 
          }
        })
    });


     function cities_search(){
        var input = $(".city-input-search input").val(); 
        $('.proccess_load').show();      
        $.ajax({type: "POST",url: "include/modules/cities/handlers/search_city.php",data: "q="+input+"&id="+change_region_id,dataType: "html",success: function(data) { $('.cont-country-city').html(data); $('.proccess_load').hide();  }});    
     }


     $(document).on('input','.city-input-search input', function () {     
         cities_search();      
     });

    $(document).on('click','.modal_country_add_city', function () {     
        if(change_region_id != 0 && change_region_id != "null"){

            $("#modal_add_city").modal("show");

        }else{ notification("warning","Пожалуйста, выберите регион"); }        
    });

    $(document).on('click','.modal_country_add_region', function () {     

            var country_id = $(this).attr("country_id");
             str = prompt("Укажите название региона:", "");
             if (str != "" && str != null){
                $('.proccess_load').show();
                $.ajax({type: "POST",url: "include/modules/cities/handlers/add_region.php",data: "name="+str+"&country_id="+country_id,dataType: "html",cache: false,                     
                    success: function (data) {
                        $('.proccess_load').hide(); 
                        location.reload();                                            
                    }
                });

             }
        
    });

    function reload_metro(city_id){
         $.ajax({type: "POST",url: "include/modules/cities/handlers/load_metro_city.php",data: "id="+city_id,dataType: "html",cache: false,                     
            success: function (data) {
                $('.proccess_load').hide();
                $('.cont-metro-city').html(data);                                           
            }
        });       
    }

    $(document).on('change','.metro_change_city_id', function () {     
        metro_change_city_id = $(this).val();
        $('.proccess_load').show();
        reload_metro(metro_change_city_id);       
    });

    $(document).on('change','.area_change_city_id', function () {     
        area_change_city_id = $(this).val();
        $('.proccess_load').show();
        reload_area(area_change_city_id);       
    });

    $(document).on('click','.button_metro_add', function () {     

        $('.proccess_load').show();
        $.ajax({type: "POST",url: "include/modules/cities/handlers/add_metro.php",data: $("#form-data-metro-add").serialize()+"&id="+metro_change_city_id,dataType: "html",cache: false,                     
            success: function (data) {
                $('.proccess_load').hide(); 
                if (data==true){
                    reload_metro(metro_change_city_id);
                    notification();
                    $("#modal_country_load").modal("show");     
                    $("#modal_add_metro").modal("hide");   
                    $("#modal_add_metro input").val("");                   
                }else{
                    notification();  
                }                                            
            }
        });

    });

    $(document).on('click','.delete_metro_city, .delete_metro_station', function () {
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
                type: "POST",
                url: "include/modules/cities/handlers/delete_metro.php",
                data: "id="+$(this).attr("data-id"),
                dataType: "html",                     
                success: function (data) {
                    reload_metro(metro_change_city_id);  
                    notification();                                        
                }
            });                 
          }
        })
    });
    

    $(document).on('click','.modal_metro_add_city', function () { 
         $("#modal_country_load").modal("hide");     
         $("#modal_add_metro").modal("show");       
    });


    $('#modal_add_metro').on('hidden.bs.modal', function (e) {
         $("#modal_country_load").modal("show");
    });


    $(document).on('click','.add_metro_station', function () {     

        var metro_id = $(this).attr("data-id");
         str = prompt("Пожалуйста, укажите название станции", "");
         if (str != "" && str != null){

            $('.proccess_load').show();
            $.ajax({type: "POST",url: "include/modules/cities/handlers/add_station.php",data: "name="+str+"&id="+metro_id+"&city_id="+metro_change_city_id,dataType: "html",cache: false,                     
                success: function (data) {
                    $('.proccess_load').hide(); 
                    if (data==true){
                        reload_metro(metro_change_city_id); 
                    }
                    notification();                                            
                }
            });

         }
        
    });


    $(document).on('click','.edit_metro_station', function () {     
       
        var metro_id = $(this).attr("data-id");
        $('.proccess_load').show();
        $.ajax({type: "POST",url: "include/modules/cities/handlers/load_edit_metro.php",data: "id="+metro_id,dataType: "html",cache: false,                     
            success: function (data) {
                $('.proccess_load').hide(); 
                $("#modal_edit_metro .modal-body").html(data); 
                $("#modal_country_load").modal("hide");   
                $("#modal_edit_metro").modal("show");                                        
            }
        });

    });

    $('#modal_edit_metro').on('hidden.bs.modal', function (e) {
         $("#modal_country_load").modal("show");
    });
    
    $(document).on('click','.button_metro_edit', function () {     

        $('.proccess_load').show();
        $.ajax({type: "POST",url: "include/modules/cities/handlers/edit_metro.php",data: $("#form-data-metro-edit").serialize(),dataType: "html",cache: false,                     
            success: function (data) {
                $('.proccess_load').hide(); 
                if (data==true){
                    reload_metro(metro_change_city_id);
                    $("#modal_country_load").modal("show");     
                    $("#modal_edit_metro").modal("hide");                      
                }
                notification();                                            
            }
        });

    });

    $(document).on('click','.edit_item_region', function () {     

        $('.proccess_load').show();
        $.ajax({type: "POST",url: "include/modules/cities/handlers/load_edit_region.php",data: "id="+$(this).data("id"),dataType: "html",cache: false,                     
            success: function (data) {
                $('.proccess_load').hide(); 
                $("#modal_edit_region .modal-body").html(data);
                $("#modal_edit_region").modal("show");                                            
            }
        });

    });

    $(document).on('click','.edit_item_city', function () {     

        $('.proccess_load').show();
        $.ajax({type: "POST",url: "include/modules/cities/handlers/load_edit_city.php",data: "id="+$(this).data("id"),dataType: "html",cache: false,                     
            success: function (data) {
                $('.proccess_load').hide(); 
                $("#modal_edit_city .modal-body").html(data);
                $("#modal_edit_city").modal("show");                                            
            }
        });

    });

    $(document).on('click','.button_region_edit', function () {     

        $('.proccess_load').show();
        $.ajax({type: "POST",url: "include/modules/cities/handlers/edit_region.php",data: $("#form-data-region-edit").serialize(),dataType: "html",cache: false,                     
            success: function (data) {
                if (data==true){
                    location.reload();                      
                }else{
                    $('.proccess_load').hide();
                    notification();  
                }                                            
            }
        });

    });

    $(document).on('click','.button_city_edit', function () {     

        $('.proccess_load').show();
        $.ajax({type: "POST",url: "include/modules/cities/handlers/edit_city.php",data: $("#form-data-city-edit").serialize(),dataType: "html",cache: false,                     
            success: function (data) {
                if (data==true){
                    location.reload();                      
                }else{
                    $('.proccess_load').hide();
                    notification();  
                }                                            
            }
        });

    });

    $(document).on('click','.button_city_add', function () {     

        $('.proccess_load').show();
        $.ajax({type: "POST",url: "include/modules/cities/handlers/add_city.php",data: $("#form-data-city-add").serialize(),dataType: "html",cache: false,                     
            success: function (data) {
                if (data==true){
                    $("#modal_add_city").modal("hide");
                    reload_city(change_region_id);
                    notification();                    
                }else{
                    $('.proccess_load').hide();
                    notification();  
                }                                            
            }
        });

    });

    function reload_area(city_id){
         $.ajax({type: "POST",url: "include/modules/cities/handlers/load_area_city.php",data: "id="+city_id,dataType: "html",cache: false,                     
            success: function (data) {
                $('.proccess_load').hide();
                $('.cont-area-city').html(data);                                           
            }
        });       
    }

    $(document).on('change','.toggle-default-city', function () {   

        if($(this).prop("checked") == true){
            var status = 1;
        }else{
            var status = 0;
        } 

        $.ajax({type: "POST",url: "include/modules/cities/handlers/toggle_default_city.php",data: "id="+$(this).data("id")+"&status="+status,dataType: "html",cache: false,                     
            success: function (data) {
                notification();                                            
            }
        });

    });    

    $(document).on('click','.modal_area_add_city', function () {     

         str = prompt("Укажите название района:", "");
         if (str != "" && str != null){

            $('.proccess_load').show();
            $.ajax({type: "POST",url: "include/modules/cities/handlers/add_area.php",data: "name="+str+"&city_id="+area_change_city_id,dataType: "html",cache: false,                     
                success: function (data) {
                    $('.proccess_load').hide(); 
                    if (data==true){
                        reload_area(area_change_city_id);
                        notification();  
                    }else{
                        notification();  
                    }                                            
                }
            });

         }
        
    });

    $(document).on('click','.delete_area_city', function () {
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
                    type: "POST",
                    url: "include/modules/cities/handlers/delete_area.php",
                    data: "id="+$(this).attr("data-id"),
                    dataType: "html",                     
                    success: function (data) {  
                        reload_area(area_change_city_id);  
                        notification();                                       
                    }
                });                 
          }
        })
    });

    $(document).on("change", "select[name=code_phone]", function() {

        if($(this).val()){
             $(this).parents('.modal-body').find(".country-format-phone-input").show();      
        }else{
             $(this).parents('.modal-body').find(".country-format-phone-input").hide();               
        }

    });



});  