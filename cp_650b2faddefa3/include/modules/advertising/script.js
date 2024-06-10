$(document).ready(function() {

    $.getScript('files/js/javascript.js');

    $(document).on('change','#rad-1', function (e) { $(".box-rad-1").show(); $(".box-rad-2").hide(); });
    $(document).on('change','#rad-2', function (e) { $(".box-rad-2").show(); $(".box-rad-1").hide(); });

    $(document).on('change','#cat-rad-1', function (e) { $(".box-cat-rad-1").hide(); });
    $(document).on('change','#cat-rad-2', function (e) { $(".box-cat-rad-1").show(); });

    $(document).on('change','#city-rad-1', function (e) { $(".box-city-rad-1").hide(); });
    $(document).on('change','#city-rad-2', function (e) { $(".box-city-rad-1").show(); });

    $(document).on('change','select[name=banner_position]', function (e) { 

        if($(this).val() == ""){
          $("input[name=title]").val("");
        }else{
          $("input[name=title]").val($(this).find("option:selected").attr("data-title"));
        }
      
    });

    $(document).on('click','.delete-advertising', function () {    
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
                      type: "POST",url: "include/modules/advertising/handlers/delete.php",data: "id="+uid,dataType: "html",cache: false,
                      success: function (data) {
                        location.href = "?route=advertising";
                      }
                  });                 
              }
            })

        return false;         
    });
    
    $(document).on('click','.add-advertising', function (e) {
        var data_form = new FormData($('.form-data')[0]);      
        $('.proccess_load').show(); 
        $.ajax({
            type: "POST",url: "include/modules/advertising/handlers/add.php",data: data_form,dataType: "html",cache: false,contentType: false,processData: false,                                                
            success: function (data) {
                if (data==true){
                    location.href = "?route=advertising";  
                }else{
                    $('.proccess_load').hide();
                    notification();  
                }                                           
            }
        });
        e.preventDefault();
    });
    
    $(document).on('click','.edit-advertising', function (e) {  
        var data_form = new FormData($('.form-data')[0]);     
        $('.proccess_load').show(); 
        $.ajax({
            type: "POST",url: "include/modules/advertising/handlers/edit.php",data: data_form,dataType: "html",cache: false,contentType: false,processData: false,                                                
            success: function (data) {
                if (data==true){
                    location.reload();  
                }else{
                    $('.proccess_load').hide();
                    notification();  
                }                                           
            }
        });
        e.preventDefault();
    });

   
    $('.select_advertising_position').on('change', function(data){
        $(".box-pages").hide();
        if($(this).val() == "result"){
            $('.index_out_advertising_position').show();
            $(".box-categories").show();
        }else if($(this).val() == "index_center" || $(this).val() == "index_top" || $(this).val() == "index_bottom"){
            $(".box-categories,.index_out_advertising_position").hide();
        }else if($(this).val() == "blog_top" || $(this).val() == "blog_bottom" || $(this).val() == "blog_sidebar" || $(this).val() == "blog_view_sidebar" || $(this).val() == "blog_view_top" || $(this).val() == "blog_view_bottom"){
            $(".box-categories,.bnr_ids_cat_blog").show();
            $(".bnr_ids_cat_board,.index_out_advertising_position").hide();
        }else if($(this).val() == "catalog_sidebar" || $(this).val() == "catalog_top" || $(this).val() == "catalog_bottom" || $(this).val() == "ad_view_top" || $(this).val() == "ad_view_sidebar" || $(this).val() == "ad_view_bottom"){
            $(".box-categories,.bnr_ids_cat_board").show();
            $(".bnr_ids_cat_blog,.index_out_advertising_position").hide();
        }else if($(this).val() == "stretching"){
            $(".box-categories,.index_out_advertising_position").hide();
            $(".box-pages").show();
        }
    });    
    
     $(document).on('input click','.action-input-search-city', function () {     
        var myThis = $(this); 
        $.ajax({type: "POST",url: "include/modules/advertising/handlers/city.php",data: "q="+myThis.val(),dataType: "html",cache: false,success: function (data) { if(data != false){ myThis.next().html(data).show(); }else{ myThis.next().html("").hide() }  }});
     });

     $(document).on('click','.SearchCityResults .item-city', function () {      
        $('.SearchCityResults').hide();
        $(this).parent().parent().find("input").val( "" );
        $(".container-geo").append( '<div> <input type="hidden" name="geo['+$(this).attr("data-geo-name")+'][]" value="'+$(this).attr("data-id")+'" > '+$(this).attr("data-name")+' <i class="la la-times"></i> </div>' ).show();
     });

     $(document).on('click', function(e) {
        if (!$(e.target).closest(".action-input-search-city").length && !$(e.target).closest(".custom-results").length) {
          $('.custom-results').hide();
        }
        e.stopPropagation();
     });

     $(document).on('click','.container-geo > div', function () {      
        $(this).hide();
        $(this).find("input").remove();
     });

    
       
});  