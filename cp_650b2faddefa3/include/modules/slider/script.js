$(document).ready(function() {
    $.getScript('files/js/javascript.js');
    
    $(document).on('click','.delete-slide', function () {    
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
                      type: "POST",url: "include/modules/slider/handlers/delete.php",data: "id="+$(this).attr("data-id"),dataType: "html",cache: false,
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
    
    $(document).on('click','.action-slide-add', function (e) {
        var data_form = new FormData($('.form-data-slide-add')[0]);     
        $('.proccess_load').show(); 
        $.ajax({
            type: "POST",url: "include/modules/slider/handlers/add.php",data: data_form,dataType: "html",cache: false,contentType: false,processData: false,                                                
            success: function (data) {
                if (data==true){
                    location.href = "?route=promo_slider";  
                }else{
                    $('.proccess_load').hide();
                    notification();  
                }                                           
            }
        });
        e.preventDefault();
    });    

    $(document).on('click','.action-slide-edit', function (e) {
        var data_form = new FormData($('.form-data-slide-edit')[0]);     
        $('.proccess_load').show(); 
        $.ajax({
            type: "POST",url: "include/modules/slider/handlers/edit.php",data: data_form,dataType: "html",cache: false,contentType: false,processData: false,                                                
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

    $(document).on('click','.action-slide-settings-edit', function (e) {    
        $('.proccess_load').show(); 
        $.ajax({
            type: "POST",url: "include/modules/slider/handlers/edit_settings.php",data: $(".form-data-slide-settings").serialize(),dataType: "html",cache: false,                                                
            success: function (data) {
                $('.proccess_load').hide();
                notification();                                            
            }
        });
        e.preventDefault();
    });

    $(document).on('click','.load-edit-slide', function (e) {
        var id = $(this).attr("data-id");      
        $.ajax({
            type: "POST",url: "include/modules/slider/handlers/load_edit.php",data: "id="+id,dataType: "html",cache: false,                                                
            success: function (data) {
                $(".form-data-slide-edit").html(data);  
                $("#modal-slide-edit").modal("show");     

                $(".minicolors-edit").minicolors({

                  swatches: $(".minicolors-edit").attr('data-swatches') ? $(".minicolors-edit").attr('data-swatches').split('|') : [],
                  change: function(value, opacity) {
                    if( !value ) return;
                    if( opacity ) value += ', ' + opacity;
                    if( typeof console === 'object' ) {
                      console.log(value);
                    }
                  },
                  theme: 'bootstrap'
                });

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
    	  opacity: 0.5,
        cursor: "move",
        helper: fixHelper,
        handle:'.move-sort',
        stop: function(){
    		var arr = $('.sort-container').sortable("toArray");
    		$.ajax({url: "include/modules/slider/handlers/sorting.php",type: 'POST',data: {arrays:arr},
    			success: function(data){
                   notification();
    			}
    		});
    	}        	
    });
  

    $(".minicolors").minicolors({

      swatches: $(".minicolors").attr('data-swatches') ? $(".minicolors").attr('data-swatches').split('|') : [],
      change: function(value, opacity) {
        if( !value ) return;
        if( opacity ) value += ', ' + opacity;
        if( typeof console === 'object' ) {
          console.log(value);
        }
      },
      theme: 'bootstrap'
    });

    $(document).on('change','.toggle-status', function () {   

        if($(this).prop("checked") == true){
            var status = 1;
        }else{
            var status = 0;
        } 

        $.ajax({type: "POST",url: "include/modules/slider/handlers/status.php",data: "id="+$(this).data("id")+"&status="+status,dataType: "html",cache: false,                     
            success: function (data) {
                notification();                                            
            }
        });

    });

    $(document).on('click','.dropdown-item-page', function () {   

        $( "input[name=link]" ).val( $(this).data("alias") );

    });


}); 