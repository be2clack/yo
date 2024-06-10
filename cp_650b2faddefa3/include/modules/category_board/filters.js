$(document).ready(function() {
    $.getScript('files/js/javascript.js');
    
    var fixHelper = function(e, ui) {
        ui.children().each(function() {
            $(this).width($(this).width());
        });
        return ui;
    };

    $('.list-podfilter').sortable({ 
        opacity: 0.5,
        cursor: "move",
        handle:'.sort-move-podfilter'           
    });

    $('.sort-container-filter').sortable({ 
        axis: 'y',
        opacity: 0.5,
        cursor: "move",
        helper: fixHelper,
        containment:'.sort-container-filter',
        handle:'.move-sort-filter',
        stop: function(){
            var arr = $('.sort-container-filter').sortable("toArray");
            $.ajax({url: "include/modules/category_board/handlers/sorting_filter.php",type: 'POST',data: {arrays:arr},
                success: function(data){
                   notification();
                }
            });
        }           
    });



    $(document).on('click','.action-add-filter', function () {

        $('.proccess_load').show();
        $.ajax({type: "POST",url: "include/modules/category_board/handlers/add_filter.php",data: $(".form-add-filter").serialize(),dataType: "html",success: function (data) { if(data == true){ location.reload();} else {$('.proccess_load').hide(); notification();} }});

    });

    $(document).on('click','.action-load-edit-filter', function () {

        $('.proccess_load').show();
        $.ajax({type: "POST",url: "include/modules/category_board/handlers/load_edit_filter.php",data: "id="+$(this).attr("data-id"),dataType: "html",success: function (data) {
        
          $(".form-edit-filter").html(data); $('.proccess_load').hide(); $('.selectpicker').selectpicker('refresh'); 

          $('.list-podfilter').sortable({ 
              opacity: 0.5,
              cursor: "move",
              handle:'.sort-move-podfilter'           
          });

        }});

    });

    $(document).on('click','.action-load-edit-podfilter', function () {

        $('.proccess_load').show();
        $.ajax({type: "POST",url: "include/modules/category_board/handlers/load_edit_podfilter.php",data: "id="+$(this).attr("data-id")+"&id_parent="+$(this).attr("data-id-parent") ,dataType: "html",success: function (data) {
        
         $(".form-edit-podfilter").html(data); $('.proccess_load').hide(); $('.selectpicker').selectpicker('refresh'); 

          $('.list-podfilter').sortable({ 
              opacity: 0.5,
              cursor: "move",
              handle:'.sort-move-podfilter'           
          });         

        }});

    });

    $(document).on('click','.action-edit-podfilter', function () {

        $('.proccess_load').show();
        $.ajax({type: "POST",url: "include/modules/category_board/handlers/edit_podfilter.php",data: $(".form-edit-podfilter").serialize() ,dataType: "html",success: function (data) { $('.proccess_load').hide(); notification(); }});

    });

    $(document).on('click','.action-load-add-podfilter', function () {

        $('.proccess_load').show();
        $.ajax({type: "POST",url: "include/modules/category_board/handlers/load_add_podfilter.php",data: "id="+$(this).attr("data-id-filter"),dataType: "html",success: function (data) {
         
         $(".form-add-podfilter").html(data); $('.proccess_load').hide(); $('.selectpicker').selectpicker('refresh'); 

          $('.list-podfilter').sortable({ 
              opacity: 0.5,
              cursor: "move",
              handle:'.sort-move-podfilter'           
          });

        }});

    });

    $(document).on('click','.action-add-podfilter', function () {

        $('.proccess_load').show();
        $.ajax({type: "POST",url: "include/modules/category_board/handlers/add_podfilter.php",data: $(".form-add-podfilter").serialize(),dataType: "html",success: function (data) { if(data == true){ location.reload();} else {$('.proccess_load').hide(); notification();} }});

    });

    $(document).on('click','.action-edit-filter', function () {

        $('.proccess_load').show();
        $.ajax({type: "POST",url: "include/modules/category_board/handlers/edit_filter.php",data: $(".form-edit-filter").serialize(),dataType: "html",success: function (data) { if(data == true){ location.reload();} else {$('.proccess_load').hide(); notification();} }});

    });

    $(document).on('click','.action-edit-alias-filter', function () {

        $('.proccess_load').show();
        $.ajax({type: "POST",url: "include/modules/category_board/handlers/alias_filter.php",data: $(".form-alias-filter").serialize(),dataType: "html",success: function (data) { location.reload(); }});

    });

    $(document).on('change','.select-item-podfilter', function () {

        var id_filter = $('.select-item-podfilter option:selected').attr("data-id-filter");

        $.ajax({type: "POST",url: "include/modules/category_board/handlers/load_items_podfilter.php",data: "id_filter=" + id_filter + "&id_item=" + $(this).val() ,dataType: "html",success: function (data) { 
            
            $(".list-item-podfilter").html( data );

            $('.list-podfilter').sortable({ 
                opacity: 0.5,
                cursor: "move",
                handle:'.sort-move-podfilter'           
            });

        }});

    });

    $(document).on('click','.filter-delete', function () {   

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
                      type: "POST",url: "include/modules/category_board/handlers/delete_filter.php",data: "id="+uid,dataType: "html",cache: false,
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

    
    $(document).on('click','.action-add-item-filter', function (e) { 
       var html = '<div class="podfilter-item hide-podfilter" ><input type="text" class="form-control" value="" name="value_filter[add][]" /><i class="la la-arrows-v sort-move-podfilter" ></i><i class="la la-times delete-podfilter" ></i></div>';
       $(html).appendTo(".list-podfilter");  
       var div = $(".list-podfilter");
       div.scrollTop(div.prop('scrollHeight'));
       e.preventDefault();
    });

    $(document).on('click','.delete-podfilter', function () {
       $( this ).parent().find("input").remove();
       $( this ).parent().hide();
    });

    $(document).on('change','select[name=type_filter]', function () {
      if($( this ).val()){

         if($( this ).val() == "input"){
            $(".filter-slider-hint").show();
            $(".box-value-filters").show(); 
         }else if($( this ).val() == "input_text"){
            $(".box-value-filters").hide();   
            $(".filter-slider-hint").hide();        
         }else{
            $(".filter-slider-hint").hide(); 
            $(".box-value-filters").show();
         }

      }   
    });  

    $(document).on('click','.action-load-copy-filter', function () {

        $('.proccess_load').show();
        $.ajax({type: "POST",url: "include/modules/category_board/handlers/load_copy_filter.php",data: "id="+$(this).attr("data-id"),dataType: "html",success: function (data) { $(".form-copy-filter").html(data); $('.proccess_load').hide(); $('.selectpicker').selectpicker('refresh'); }});

    });  

    $(document).on('click','.action-add-copy-filter', function () {

        $('.proccess_load').show();
        $.ajax({type: "POST",url: "include/modules/category_board/handlers/copy_filter.php",data: $(".form-copy-filter").serialize(),dataType: "html",success: function (data) { if(data == true){ location.reload();} else {$('.proccess_load').hide(); notification();} }});

    });

    $(document).on('click','.action-load-alias-filter', function () {

        $('.proccess_load').show();
        $.ajax({type: "POST",url: "include/modules/category_board/handlers/load_alias_filter.php",data: "id="+$(this).attr("data-id"),dataType: "html",success: function (data) { $(".form-alias-filter").html(data); $('.proccess_load').hide(); }});

    });


    $(document).on('click','.filters-list-category-toggle', function () {

        $(this).parent().find(".filters-list-category-hide").toggleClass("filters-list-category-active");

    });


});