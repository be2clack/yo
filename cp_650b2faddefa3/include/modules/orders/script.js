$(document).ready(function() {
    $.getScript('files/js/javascript.js');

 
    $(document).on('click','.delete-order', function () {    
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
                      type: "POST",url: "include/modules/orders/handlers/delete.php",data: "id="+uid,dataType: "html",cache: false,
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


    $(document).on('click','.change-status-order', function () {    
     var uid = $(this).attr("data-id");
     var status = $(this).attr("data-status");
     
          $('.proccess_load').show();
          $.ajax({
              type: "POST",url: "include/modules/orders/handlers/status.php",data: "id="+uid+"&status="+status,dataType: "html",cache: false,
              success: function (data) {
                if(data == true){
                  location.reload();                    
                }else{
                  $('.proccess_load').hide();
                  notification();                  
                }
              }
          }); 

        return false;         
    });


    $(document).on('click','.orders-filters-reset', function () {
        $(".orders-filters-form input[type=date]").val('');    
        $(".orders-filters-form option:selected").prop("selected", false)
        $(".orders-filters-form").submit();        
    });

    $(document).on('click','.orders-filters-accept', function () {    
        $(".orders-filters-form").submit();        
    });

    $('.order-list-summary-slider').slick({
        dots: true,
        arrows: false,
        infinite: true, 
        autoplay: true,         
        slidesToShow: 1,   
        speed: 300,
        centerMode: false
   });

  tippy('[data-tippy-placement]', {
    delay: 100,
    arrow: true,
    arrowType: 'sharp',
    size: 'regular',
    duration: 200,
    animation: 'shift-away',
    animateFill: true,
    theme: 'dark',
    distance: 10,
  });

 
});