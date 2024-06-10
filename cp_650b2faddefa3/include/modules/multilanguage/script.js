$(document).ready(function() {
    $.getScript('files/js/javascript.js');


    $(document).on('submit','.form-data', function (e) {    

     CKupdate();
     var form = $(this).serialize();
     
        $('.proccess_load').show();
        $.ajax({
            type: "POST",url: "include/modules/multilanguage/handlers/edit.php",data: form,dataType: "html",cache: false,
            success: function (data) {
               $('.proccess_load').hide();
               notification();                          
            }
        });

      e.preventDefault();

    });

    function CKupdate() {
        for (instance in CKEDITOR.instances)
            CKEDITOR.instances[instance].updateElement();
    }

    $(document).on('submit','.form-data-keys', function (e) {    

     var form = $(this).serialize();
     
        $('.proccess_load').show();
        $.ajax({
            type: "POST",url: "include/modules/multilanguage/handlers/edit_keys.php",data: form,dataType: "html",cache: false,
            success: function (data) {
               $('.proccess_load').hide();
               notification();                          
            }
        });

      e.preventDefault();

    });


   
    
});    