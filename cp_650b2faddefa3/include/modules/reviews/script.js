$(document).ready(function() {
    $.getScript('files/js/javascript.js');

    $(document).on('click','.delete-review', function () {    

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
                      type: "POST",url: "include/modules/reviews/handlers/delete.php",data: "id="+$(this).attr("data-id"),dataType: "html",cache: false,
                      success: function (data) {
                         location.reload();
                      }
                  });                 
              }
            })

        return false;         
    });

    $(document).on('click','.publication-review', function () {    
          $('.proccess_load').show();
          $.ajax({
              type: "POST",url: "include/modules/reviews/handlers/publication.php",data: "id="+$(this).attr("data-id"),dataType: "html",cache: false,
              success: function (data) {
                location.reload();
              }
          });

        return false;         
    });

    
});