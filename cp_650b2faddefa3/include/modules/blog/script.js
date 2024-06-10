$(document).ready(function() {
    $.getScript('files/js/javascript.js');

    $(document).on('click','.nav-item a', function (e) {
       history.pushState('', '', $(this).attr("data-route"));
    });    

    $(document).on('click','.add-article', function (e) {
        var data_form = new FormData($('.form-data')[0]);
        data_form.append('text', encodeURIComponent(CKEDITOR.instances.text.getData()));        
        $('.proccess_load').show(); 
        $.ajax({
            type: "POST",url: "include/modules/blog/handlers/add_article.php",data: data_form,dataType: "html",cache: false,contentType: false,processData: false,                                                
            success: function (data) { console.log(data);
                if (data==true){
                    location.href = "?route=blog";  
                }else{
                    $('.proccess_load').hide();
                    notification();  
                }                                           
            }
        });
        e.preventDefault();
    });

    $(document).on('click','.edit-article', function (e) {
        var data_form = new FormData($('.form-data')[0]);
        data_form.append('text', encodeURIComponent(CKEDITOR.instances.text.getData()));        
        $('.proccess_load').show(); 
        $.ajax({
            type: "POST",url: "include/modules/blog/handlers/edit_article.php",data: data_form,dataType: "html",cache: false,contentType: false,processData: false,                                                
            success: function (data) { console.log(data);
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

    $(document).on('click','.add-category', function (e) {
        var data_form = new FormData($('.form-data')[0]);
        data_form.append('text', CKEDITOR.instances.text.getData());        
        $('.proccess_load').show(); 
        $.ajax({
            type: "POST",url: "include/modules/blog/handlers/add_category.php",data: data_form,dataType: "html",cache: false,contentType: false,processData: false,                                                
            success: function (data) { console.log(data);
                if (data==true){
                    location.href = "?route=blog&tab=category";  
                }else{
                    $('.proccess_load').hide();
                    notification();  
                }                                           
            }
        });
        e.preventDefault();
    });

    $(document).on('click','.edit-category', function (e) {
        var data_form = new FormData($('.form-data')[0]);
        data_form.append('text', CKEDITOR.instances.text.getData());        
        $('.proccess_load').show(); 
        $.ajax({
            type: "POST",url: "include/modules/blog/handlers/edit_category.php",data: data_form,dataType: "html",cache: false,contentType: false,processData: false,                                                
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
            $.ajax({url: "include/modules/blog/handlers/sorting_category.php",type: 'POST',data: {arrays:arr},
                success: function(data){
                   notification();
                }
            });
        }           
    });

    $(document).on('click','.delete-category', function () {    

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
                      type: "POST",url: "include/modules/blog/handlers/delete_category.php",data: "id="+$(this).data("id"),dataType: "html",cache: false,
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

    $(document).on('click','.delete-article', function () {    

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
                      type: "POST",url: "include/modules/blog/handlers/delete_article.php",data: "id="+$(this).data("id"),dataType: "html",cache: false,
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

    $(document).on('click','.delete-comment', function () {    

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
                      type: "POST",url: "include/modules/blog/handlers/delete_comment.php",data: "id="+$(this).data("id"),dataType: "html",cache: false,
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

    $(document).on('click','.board-open-podcat', function () {
      var uid = $(this).attr("uid");
      var ids = $(this).attr("data-ids");
      if($(this).attr("status") == "hide"){
        $("tr[parent-id='"+uid+"']").show();
        $(this).attr("status","show");
        $(this).parent().find(".icon-open-cat").attr("class", 'la la-minus icon-open-cat');
      }else{
        $("tr[parent-id='"+uid+"']").hide(); 
        $(ids).hide();
        $(ids).find(".board-open-podcat").attr("status","hide");
        $(this).attr("status","hide");
        $(this).parent().find(".icon-open-cat").attr("class", 'la la-plus icon-open-cat');
        $(ids).find(".icon-open-cat").attr("class", 'la la-plus icon-open-cat');
      }      
    });

    $(document).on('change','.toggle-status', function () {   

        if($(this).prop("checked") == true){
            var status = 1;
        }else{
            var status = 0;
        } 

        $.ajax({type: "POST",url: "include/modules/blog/handlers/status_article.php",data: "id="+$(this).data("id")+"&status="+status,dataType: "html",cache: false,                     
            success: function (data) {
                notification();                                            
            }
        });

    });

    $(document).on('change','.checkbox_prop', function (e) { 
        if($(this).prop("checked") == false){ $(".form-data-articles input.input_prop_id").prop("checked", false); $(".action_group_block").hide(); } else { $(".form-data-articles input.input_prop_id").prop("checked", true); $(".action_group_block").show(); }
    });    

    $(document).on('change','.form-data-articles input.input_prop_id', function (e) {
        if($(this).prop("checked") == false){ if($(".form-data-articles input.input_prop_id:checked").length == 0) $(".action_group_block").hide(); } else { $(".action_group_block").show(); }
    });

    $(document).on('click','.action_group', function () {  

        var ids = $(".form-data-articles input.input_prop_id").serialize();
        var action = $(this).data("action");

        if(action == "delete"){
          
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
                      type: "POST",url: "include/modules/blog/handlers/delete_article.php",data: ids,dataType: "html",cache: false,
                      success: function (data) {
                        location.reload();
                      }
                  });                 
              }
            })

        }

        
        return false;         
    });


});  