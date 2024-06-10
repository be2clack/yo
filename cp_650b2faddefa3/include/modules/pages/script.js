$(document).ready(function() {
    $.getScript('files/js/javascript.js');
    
    $(document).on('click','.delete-page', function () {    
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
                      type: "POST",url: "include/modules/pages/handlers/delete.php",data: "id="+uid,dataType: "html",cache: false,
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
    
    $(document).on('click','.add-page', function (e) {
        var data_form = new FormData($('.form-data')[0]);
        data_form.append('text', encodeURIComponent(CKEDITOR.instances.text.getData()) );        
        $('.proccess_load').show(); 
        $.ajax({
            type: "POST",url: "include/modules/pages/handlers/add.php",data: data_form,dataType: "html",cache: false,contentType: false,processData: false,                                                
            success: function (data) {
                if (data==true){
                    location.href = "?route=pages";  
                }else{
                    $('.proccess_load').hide();
                    notification();  
                }                                           
            }
        });
        e.preventDefault();
    });    
    
    $(document).on('click','.edit-page', function (e) {
        var data_form = new FormData($('.form-data')[0]);
        data_form.append('text', encodeURIComponent(CKEDITOR.instances.text.getData()) );        
        $('.proccess_load').show(); 
        $.ajax({
            type: "POST",url: "include/modules/pages/handlers/edit.php",data: data_form,dataType: "html",cache: false,contentType: false,processData: false,                                                
            success: function (data) {
                $('.proccess_load').hide();
                notification();              
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
        containment:'.sort-container',
        handle:'.move-sort',
        stop: function(){
    		var arr = $('.sort-container').sortable("toArray");
    		$.ajax({url: "include/modules/pages/handlers/sorting.php",type: 'POST',data: {arrays:arr},
    			success: function(data){
                   notification();
    			}
    		});
    	}        	
    });
  

    $(document).on('change','.toggle-status', function () {   

        if($(this).prop("checked") == true){
            var status = 1;
        }else{
            var status = 0;
        } 

        $.ajax({type: "POST",url: "include/modules/pages/handlers/status.php",data: "id="+$(this).data("id")+"&status="+status,dataType: "html",cache: false,                     
            success: function (data) {
                notification();                                            
            }
        });

    });


}); 