$(document).ready(function() {
    $.getScript('files/js/javascript.js');

    $(document).on('submit','.form-data', function (e) {    

     var form = $(this).serialize();
     
        $('.proccess_load').show();
        $.ajax({
            type: "POST",url: "include/modules/search/handlers/edit.php",data: form,dataType: "html",cache: false,
            success: function (data) {
                location.reload();                          
            }
        });

      e.preventDefault();

    });

    $(document).on('click','.item-search-add', function (e) {  

       var random = Math.floor(Math.random() * (10000 - 99999) + 99999);  

       $('.item-search-container').append(`
              <div class="item-search-gray" >
                <div class="item-search-gray-row" >
                    <div class="item-search-gray-row-flex-1" >
                       <div class="item-search-gray-box" >
                         <input type="text" class="form-control" name="keywords[add][`+random+`][text]" placeholder="Ключевое слово" value="" >
                       </div>
                       <div class="item-search-gray-box" >
                         <input type="text" class="form-control" name="keywords[add][`+random+`][macros]" placeholder="Макросы" value="" >
                       </div>                                            
                    </div>
                    <div class="item-search-gray-row-flex-2" >
                        <div class="item-search-delete" data-id="0" ><i class="la la-trash"></i></div>    
                    </div>                                        
                </div>
              </div>
        `);

    });

    $(document).on('click','.item-search-delete', function (e) { 

       var elm = $(this);   

       if(!elm.data('id')){
          elm.parents('.item-search-gray').remove().hide();
       }else{

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
                      type: "POST",url: "include/modules/search/handlers/delete.php",data: "id="+elm.data('id'),dataType: "html",cache: false,
                      success: function (data) {
                          elm.parents('.item-search-gray').remove().hide();
                          $('.proccess_load').hide();
                          notification();                          
                      }
                  });                 
              }
            })

       }

    });

    function copyLink(el) {
        var $tmp = $("<input>");
        $("body").append($tmp);
        $tmp.val($(el).html()).select();
        document.execCommand("copy");
        $tmp.remove();
    }  

    $(document).on('click','.filter-copy', function () {    
       
        notification("success", "Фильтр скопирован");

        copyLink($(this));

        $("#modal-list-filters").modal("hide");

    });

    $(document).on('click','.modal-filters-macros-toggle', function () {    
       
        $(this).next().toggle();

    });

}); 