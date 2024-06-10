$(document).ready(function() {
    $.getScript('files/js/javascript.js');

    $(document).on('click','.load_edit_services', function (e) {  
    var data_id = $(this).attr("data-id");    
        $('.proccess_load').show(); 
        $.ajax({
            type: "POST",url: "include/modules/services_ad/handlers/load_edit.php",data: "id="+data_id,dataType: "html",cache: false,                                                
            success: function (data) {
                $('.proccess_load').hide();
                $(".form-services-edit").html(data);
                $("#modal-services-edit").modal("show");              
            }
        });
        e.preventDefault();
    });     
    
    $(document).on('click','.load_edit_tariff', function (e) {  
    var data_id = $(this).attr("data-id");    
        $('.proccess_load').show(); 
        $.ajax({
            type: "POST",url: "include/modules/services_ad/handlers/load_edit_tariff.php",data: "id="+data_id,dataType: "html",cache: false,                                                
            success: function (data) {
                $('.proccess_load').hide();
                $(".form-tariff-edit").html(data);
                $("#modal-tariff-edit").modal("show");              
            }
        });
        e.preventDefault();
    });

    $(document).on('click','.delete_tariff', function () {
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
                      type: "POST",url: "include/modules/services_ad/handlers/delete_tariff.php",data: "id="+$(this).attr("data-id"),dataType: "html",cache: false,
                      success: function (data) {
                        location.reload();
                      }
                  });                 
              }
            })
        return false;
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
        containment:'.sort-container',
        handle:'.move-sort',
        stop: function(){
            var arr = $('.sort-container').sortable("toArray");
            $.ajax({url: "include/modules/services_ad/handlers/sorting.php",type: 'POST',data: {arrays:arr},
                success: function(data){
                   notification();
                }
            });
        }           
    });


    $('.sort-container-tariff').sortable({ 
        axis: 'y',
        opacity: 0.5,
        cursor: "move",
        helper: fixHelper,
        containment:'.sort-container-tariff',
        handle:'.move-sort-tariff',
        stop: function(){
            var arr = $('.sort-container-tariff').sortable("toArray");
            $.ajax({url: "include/modules/services_ad/handlers/sorting_tariff.php",type: 'POST',data: {arrays:arr},
                success: function(data){
                   notification();
                }
            });
        }           
    });
    $(document).on('click','.board-edit-services', function (e) {
        var data_form = new FormData($('.form-services-edit')[0]);     
        $('.proccess_load').show(); 
        $.ajax({
            type: "POST",url: "include/modules/services_ad/handlers/edit.php",data: data_form,dataType: "html",cache: false,contentType: false,processData: false,                                                
            success: function (data) {
                $('.proccess_load').hide();
                notification();              
            }
        });
        e.preventDefault();
    });

    $(document).on('click','.board-edit-tariff', function (e) {   
        $('.proccess_load').show(); 
        $.ajax({
            type: "POST",url: "include/modules/services_ad/handlers/edit_tariff.php",data: $('.form-tariff-edit').serialize(),dataType: "html",cache: false,                                                
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

    $(document).on('click','.board-add-tariff', function (e) {   
        $('.proccess_load').show(); 
        $.ajax({
            type: "POST",url: "include/modules/services_ad/handlers/add_tariff.php",data: $('.form-tariff-add').serialize(),dataType: "html",cache: false,                                                
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

    $(document).on('click','.board-edit-tariff-services', function (e) {   
        $('.proccess_load').show(); 
        $.ajax({
            type: "POST",url: "include/modules/services_ad/handlers/edit_services_tariff.php",data: $('.form-tariff-services-edit').serialize(),dataType: "html",cache: false,                                                
            success: function (data) {
                location.reload();              
            }
        });
        e.preventDefault();
    });

    $(document).on('change','#rad-1', function (e) { $(".box-variant-services1").show(); $(".box-variant-services2").hide(); });
    $(document).on('change','#rad-2', function (e) { $(".box-variant-services2").show(); $(".box-variant-services1").hide(); });

});