$(document).ready(function() {
    $.getScript('files/js/javascript.js');
    
    $(document).on('click','.booking-order-accept', function (e) {   

        $('.proccess_load').show();
        $.ajax({
            type: "POST",url: "include/modules/booking/handlers/status.php",data: "id="+$(this).data("id"),dataType: "html",cache: false,
            success: function (data) {
              location.reload();
            }
        });

        e.preventDefault();         
    });

}); 