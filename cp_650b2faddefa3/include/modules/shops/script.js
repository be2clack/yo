$(document).ready(function() {
    $.getScript('files/js/javascript.js');

    $(document).on('click','.delete-shop', function () {    

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
                      type: "POST",url: "include/modules/shops/handlers/delete.php",data: "id="+$(this).attr("data-id"),dataType: "html",cache: false,
                      success: function (data) {
                         location.reload();
                      }
                  });                 
              }
            })

        return false;         
    });

    $(document).on('click','.change-status-shop', function () {    
          $('.proccess_load').show();
          $.ajax({
              type: "POST",url: "include/modules/shops/handlers/change_status.php",data: "id="+$(this).data("id")+"&status="+$(this).data("status"),dataType: "html",cache: false,
              success: function (data) {
                  location.reload();
              }
          });

        return false;         
    });

    $(document).on('click','.deny-publication-shop', function () {    
        $(".form-deny-publication-shop input[name=id]").val($(this).data("id"));       
    });
    
    $(document).on('click','.action-deny-publication-shop', function () {    
          $('.proccess_load').show();
          $.ajax({
              type: "POST",url: "include/modules/shops/handlers/change_status.php",data: $(".form-deny-publication-shop").serialize(),dataType: "json",cache: false,
              success: function (data) {
                if(data["status"] == true){
                  location.reload();
                }else{
                  notification();
                  $('.proccess_load').hide();
                }
              }
          });

        return false;         
    });

});