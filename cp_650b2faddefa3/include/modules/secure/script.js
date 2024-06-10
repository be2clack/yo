$(document).ready(function() {
    $.getScript('files/js/javascript.js');
    
    $(document).on('click','.secure-cancel-order', function () {   

     var uid = $(this).data("id");
     
           swal({
              title: "Вы действительно хотите отменить сделку?",
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
                      type: "POST",url: "include/modules/secure/handlers/cancel.php",data: "id="+uid,dataType: "html",cache: false,
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
    
    $(document).on('click','.secure-disputes-variants span', function () {   
        
        $(".secure-disputes-variants span").removeClass("active");
        $(this).addClass("active");
        $(".secure-disputes-textarea").val( $(this).data("info") );
        $(".secure-disputes-action-accept").show();
        $("input[name=status]").val( $(this).data("status") );

    });

    $(document).on('submit','.form-secure-disputes', function (e) {   

        $('.proccess_load').show();
        $.ajax({
            type: "POST",url: "include/modules/secure/handlers/edit.php",data: $(this).serialize(),dataType: "json",cache: false,
            success: function (data) {
              if( data["status"] == true ){ location.reload(); }else{ alert( data["answer"] ); $('.proccess_load').hide(); }
            }
        }); 

        e.preventDefault();         
    });

    $(document).on('click','.secure-pay-out', function (e) {   

        $('.proccess_load').show();
        $.ajax({
            type: "POST",url: "include/modules/secure/handlers/pay_out.php",data: "id="+$(this).data("id")+"&status="+$(this).data("status")+"&id_user="+$(this).data("id-user"),dataType: "html",cache: false,
            success: function (data) {
              location.reload();
            }
        }); 

        e.preventDefault();         
    });

    $(document).on('click','.delete-secure', function (e) {   

       swal({
          title: "Вы действительно хотите выполнить действие?",
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
                    type: "POST",url: "include/modules/secure/handlers/delete.php",data: "id="+$(this).data("id"),dataType: "html",cache: false,
                    success: function (data) {
                      location.reload();
                    }
                });                 
          }
        }) 

        e.preventDefault();         
    });

    $(document).on('click','.secure-order-repaid', function (e) {   

        $('.proccess_load').show();
        $.ajax({
            type: "POST",url: "include/modules/secure/handlers/repaid.php",data: "id="+$(this).data("id"),dataType: "html",cache: false,
            success: function (data) {
              location.reload();
            }
        });

        e.preventDefault();         
    });

    $(document).on('click','.secure-order-accept-payment', function (e) {   

        $('.proccess_load').show();
        $.ajax({
            type: "POST",url: "include/modules/secure/handlers/accept_payment.php",data: "id="+$(this).data("id"),dataType: "html",cache: false,
            success: function (data) {
              location.reload();
            }
        });

        e.preventDefault();         
    });


}); 