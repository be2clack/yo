$(document).ready(function() {
    $.getScript('files/js/javascript.js');
    
    $(document).on('click','.delete-page', function () {    
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
                  type: "POST",url: "include/modules/promo_pages/handlers/delete.php",data: "id="+$(this).attr("data-id"),dataType: "html",cache: false,
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
    
    $(document).on('click','.action-page-add', function (e) {       
        $('.proccess_load').show(); 
        $.ajax({
            type: "POST",url: "include/modules/promo_pages/handlers/add.php",data: $(".form-data-page-add").serialize(),dataType: "html",cache: false,                                                
            success: function (data) {
                if (data==true){
                    location.href = "?route=promo_pages";  
                }else{
                    $('.proccess_load').hide();
                    notification();  
                }                                           
            }
        });
        e.preventDefault();
    });    
    
    $(document).on('click','.action-page-edit', function (e) {       
        $('.proccess_load').show(); 
        $.ajax({
            type: "POST",url: "include/modules/promo_pages/handlers/edit.php",data: $(".form-data-page-edit").serialize(),dataType: "html",cache: false,                                                
            success: function (data) {
                $('.proccess_load').hide();
                notification();              
            }
        });
        e.preventDefault();
    });     

    $(document).on('change','.toggle-status', function () {   

        if($(this).prop("checked") == true){
            var status = 1;
        }else{
            var status = 0;
        } 

        $.ajax({type: "POST",url: "include/modules/promo_pages/handlers/status.php",data: "id="+$(this).data("id")+"&status="+status,dataType: "html",cache: false,                     
            success: function (data) {
                notification();                                            
            }
        });

    });

    $(document).on('click','.load-edit-page', function (e) {   
        $.ajax({
            type: "POST",url: "include/modules/promo_pages/handlers/load_edit.php",data: "id="+$(this).attr("data-id"),dataType: "html",cache: false,                                                
            success: function (data) {
                $(".form-data-page-edit").html(data);  
                $("#modal-page-edit").modal("show");     
            }
        });
        e.preventDefault();
    });

}); 