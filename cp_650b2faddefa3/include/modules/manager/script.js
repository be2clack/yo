$(document).ready(function() {
    $.getScript('files/js/javascript.js');

    $(document).on('click','.delete-file', function () {    
           
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
                      type: "POST",url: "include/modules/manager/handlers/delete.php",data: "id="+$(this).data("id"),dataType: "html",cache: false,
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
    

    $('.add-manager-file').on('click', function(data){
         var form_data = new FormData($('.form-data')[0]);
         $('.proccess_load').show();
            $.ajax({type: "POST",url: "include/modules/manager/handlers/add.php",data: form_data,dataType: "html",cache: false,contentType: false, processData: false,                
                success: function (data) {
                    if(data == true){
                      location.reload();
                    }else{
                      $('.proccess_load').hide();
                      notification();
                    }                    
                }
            });
    });    


    function copyLink(el) {
        var $tmp = $("<input>");
        $("body").append($tmp);
        $tmp.val($(el).val()).select();
        document.execCommand("copy");
        $tmp.remove();
    }  

    $(document).on('click','.manager-copy-link', function () {    
       
        notification("success", "Ссылка успешно скопирована");

        copyLink($(this).next());

    });



});