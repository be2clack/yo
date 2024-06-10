$(document).ready(function() {
    $.getScript('files/js/javascript.js');


    $(document).on('click','.delete-ads', function () {    
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
                      type: "POST",url: "include/modules/board/handlers/delete.php",data: "id="+uid,dataType: "html",cache: false,
                      success: function (data) {
                        location.reload();
                      }
                  });                 
              }
            })

        return false;         
    });

    $(document).on('click','.delete-complaint', function () {    
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
                      type: "POST",url: "include/modules/board/handlers/delete_complaint.php",data: "id="+uid,dataType: "html",cache: false,
                      success: function (data) {
                        location.reload();
                      }
                  });                 
              }
            })

        return false;         
    });

    $(document).on('click','.archive-complaint', function () {    
     var uid = $(this).attr("data-id");
     
        $('.proccess_load').show();
        $.ajax({
            type: "POST",url: "include/modules/board/handlers/archive_complaint.php",data: "id="+uid,dataType: "html",cache: false,
            success: function (data) {
              location.reload();
            }
        }); 
                
    });

    $(document).on('change','.checkbox_prop', function (e) { 
        if($(this).prop("checked") == false){ $(".form-data input.input_prop_id").prop("checked", false); $(".action_group_block").hide(); } else { $(".form-data input.input_prop_id").prop("checked", true); $(".action_group_block").show(); }
    });    

    $(document).on('change','.form-data input.input_prop_id', function (e) {
        if($(this).prop("checked") == false){ if($(".form-data input.input_prop_id:checked").length == 0) $(".action_group_block").hide(); } else { $(".action_group_block").show(); }
    });


    $(document).on('click','.action_group', function () {  

        var ids = $(".form-data input.input_prop_id").serialize();
        var action = $(this).data("action");

        if(action == "publication"){

          $('.proccess_load').show();
          $.ajax({
              type: "POST",url: "include/modules/board/handlers/status.php",data: ids + "&status=1",dataType: "html",cache: false,
              success: function (data) {
                location.reload();
              }
          });

        }else if(action == "not_publication"){

          $('.proccess_load').show();
          $.ajax({
              type: "POST",url: "include/modules/board/handlers/status.php",data: ids + "&status=2",dataType: "html",cache: false,
              success: function (data) {
                location.reload();
              }
          });

        }else if(action == "extend"){
          
          $('.proccess_load').show();
          $.ajax({
              type: "POST",url: "include/modules/board/handlers/extend.php",data: ids,dataType: "html",cache: false,
              success: function (data) {
                location.reload();
              }
          });

        }else if(action == "delete"){
          
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
                      type: "POST",url: "include/modules/board/handlers/delete.php",data: ids,dataType: "html",cache: false,
                      success: function (data) {
                        location.reload();
                      }
                  });                 
              }
            })

        }

        
        return false;         
    });


    $(document).on('click','.feed-toggle-param', function () {  
       
        $(".feed-toggle-param-text").toggle();  

    });

    $(document).on('click','.button-add-package', function () {    

          $('.proccess_load').show();
          $.ajax({
              type: "POST",url: "include/modules/board/handlers/add_package.php",data: $(".form-add-package").serialize(),dataType: "json",cache: false,
              success: function (data) {
                if(data["status"] == true){
                  location.href = "?route=ad_packages";                    
                }else{
                  $('.proccess_load').hide();
                  notification();                          
                }
              }
          });

        return false;         
    });

    $(document).on('click','.button-edit-package', function () {    

          $('.proccess_load').show();
          $.ajax({
              type: "POST",url: "include/modules/board/handlers/edit_package.php",data: $(".form-edit-package").serialize(),dataType: "json",cache: false,
              success: function (data) {
                if(data["status"] == true){
                  location.reload();                   
                }else{
                  $('.proccess_load').hide();
                  notification();                          
                }                  
              }
          });

        return false;         
    });

    $(document).on('click','.delete-package', function () {    
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
                      type: "POST",url: "include/modules/board/handlers/delete_package.php",data: "id="+uid,dataType: "html",cache: false,
                      success: function (data) {
                        location.reload();
                      }
                  });                 
              }
            })

        return false;         
    });

    $(document).on('click','.load-edit-package', function () {

        $('.proccess_load').show();
        $.ajax({type: "POST",url: "include/modules/board/handlers/load_edit_package.php",data: "id="+$(this).data("id"),dataType: "html",success: function (data) {
        
          $(".form-edit-package").html(data); $('.proccess_load').hide(); $('.selectpicker').selectpicker('refresh');

        }});

    });


}); 