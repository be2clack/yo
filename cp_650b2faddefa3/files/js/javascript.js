
var url_path = $("body").data("prefix");

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

notification();

$(document).on('click','.change-img', function () { $(this).parent().parent().find('.input-img').click(); });
$(document).on('change','.input-img', function () {    
    fileReader(this, ".change-img", "60px");
    $(this).parent().find(".small-image-delete").show();
    $(this).parent().find("input[name=image_delete]").val("0");
});

$(document).on('click','.change-img-edit', function () { $('.input-img-edit').click(); });
$(document).on('change','.input-img-edit', function () {    
    fileReader(this, ".change-img-edit", "60px");
});

$(document).on('click','.change-img-bnr', function () { $('.input-img-bnr').click(); });
$(document).on('change','.input-img-bnr', function () {    
    fileReader(this, ".change-img-bnr", "150px");
});

function notification(type_="", text_="") {
    if(!type_ && !text_){
        $.ajax({
            type: "POST",url: url_path + "systems/ajax/controller.php",
            data: "action=admin/notification",
            dataType: "json",
            cache: false,
            success: function(data) {

                if (data["success"]) {

                    new Noty({
                        type: 'success',
                        layout: 'topRight',
                        text: data["success"],
                        timeout: '3000'
                    }).show();

                }else if(data["error"]){
     
                    new Noty({
                        type: 'error',
                        layout: 'topRight',
                        text: data["error"],
                        timeout: '3000'
                    }).show();

                }else if(data["warning"]){
     
                    new Noty({
                        type: 'warning',
                        layout: 'topRight',
                        text: data["warning"],
                        timeout: '3000'
                    }).show();

                }


            }
        });
    }else{
        new Noty({
            type: type_,
            layout: 'topRight',
            text: text_,
            timeout: '3000'
        }).show();        
    }
}

$('[data-toggle="popover"]').popover();

$(document).on('input','.oninput-slider', function () {    
 var val = $(this).attr("fl-slider-name");
 var id = $(this).attr("fl-slider-id");
 if($(this).val() != ""){
    $(".fl-slider"+id).html(val+" ("+$(this).val()+")");
 }else{
    $(".fl-slider"+id).html("Не выбрано"); 
 }
});

$(document).on('change','.variant_count_day1', function () {   
  var uid = $(this).attr("uid");   
  $(".services_radio_box1"+uid).show();
  $(".services_radio_box2"+uid).hide();
});
$(document).on('change','.variant_count_day2', function () {  
  var uid = $(this).attr("uid");    
  $(".services_radio_box2"+uid).show();
  $(".services_radio_box1"+uid).hide();
});


$(document).on('click','.CheckBoxAll', function () {    
         if ($(this).prop("checked") == true){
            $(".ListCheckBox").prop("checked", true); 
         }else{
            $(".ListCheckBox").prop("checked", false);    
         }
});

var select_chk = true;
$('.all_select_chk').click(function () {
         if (select_chk == true){
            $(".admin-list-priv input").prop("checked", true);
            select_chk = false; 
         }else{
            $(".admin-list-priv input").prop("checked", false); 
            select_chk = true;   
         }
});
      
$(document).on('input','.setTranslate', function () {
    $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "name="+$(this).val()+"&action=admin/translate",dataType: "html",cache: false,
        success: function (data) {
           $(".outTranslate").val(data);
        }
    });         
});

$(document).on('click','.delete_fast_tab', function (e) {
    var el = $(this);
    $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "index="+el.data("index")+"&action=admin/delete_fast_tab",dataType: "html",cache: false,
        success: function (data) {
           $("a[data-index="+el.data("index")+"]").hide();
        }
    }); 
    e.preventDefault();        
});

function fileReader(input,selector,width=0) { 

    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
              $(selector).attr("src",e.target.result);               
              if(width) $(selector).attr("width",width);               
        };

        reader.readAsDataURL(input.files[0]);
    }
}

$( ".category-toggle-relation" ).click(function() {
  $( ".box-category-relation" ).toggle();
});

$('.datetime').datetimepicker();

function number_format(number, decimals, dec_point, thousands_sep) {
  number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + (Math.round(n * k) / k)
        .toFixed(prec);
    };

  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
    .split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '')
    .length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1)
      .join('0');
  }
  return s.join(dec);
}

$(document).on('click','.small-image-delete', function () {    
    $(this).parent().find("input[name=image_delete]").val("1");
    $(this).parent().find("img").attr("src", $("body").data("media-other") + "/icon_photo_add.png" );
    $('.input-img').val('');
    $(this).hide();
});

$(document).on('change','.form-refused-ads select', function () {  
   
    if( $(this).val() == "" ){
       $(".refused-ads-comment").show();
    }else{
       $(".refused-ads-comment").hide();
       $(".refused-ads-comment textarea").val("");
    }  

});

$(document).on('change','.form-block-ads select', function () {  
   
    if( $(this).val() == "" ){
       $(".block-ads-comment").show();
    }else{
       $(".block-ads-comment").hide();
       $(".block-ads-comment textarea").val("");
    }  

});

$(document).on('change','.form-block-user select', function () {  
   
    if( $(this).val() == "" ){
       $(".block-user-comment").show();
    }else{
       $(".block-user-comment").hide();
       $(".block-user-comment textarea").val("");
    }  

});

$(document).on('click','.action-block-ads', function () {  
  $('.proccess_load').show();
  $.ajax({
      type: "POST",url: "include/modules/board/handlers/block.php",data: $(".form-block-ads").serialize(),dataType: "html",cache: false,
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

$(document).on('click','.action-refused-ads', function () {  
  $('.proccess_load').show();
  $.ajax({
      type: "POST",url: "include/modules/board/handlers/refused.php",data: $(".form-refused-ads").serialize(),dataType: "html",cache: false,
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

$(document).on('click','.change-status-user', function () {  

 var id = $(this).data("id");
 var status = $(this).data("status");

     if(status == 2){

      $("#modal-block-user").modal("show");
      $(".form-block-user input[name=id_user]").val(id);

     }else{

          $('.proccess_load').show();
          $.ajax({
              type: "POST",url: "include/modules/clients/handlers/status.php",data: "id="+id+"&status="+status,dataType: "html",cache: false,
              success: function (data) {
                 location.reload();
              }
          });

     }
 
    return false;         
});

$(document).on('click','.action-block-user', function () {  
    
  $('.proccess_load').show();
  $.ajax({
      type: "POST",url: "include/modules/clients/handlers/block.php",data: $(".form-block-user").serialize(),dataType: "html",cache: false,
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


$(document).on('click','.change-status-ads', function () {  

 var id = $(this).data("id");
 var status = $(this).data("status");

     if(status == 3){

      $("#modal-block-ads").modal("show");
      $(".form-block-ads input[name=id_ad]").val(id);

     }else if(status == 7){

      $("#modal-refused-ads").modal("show");
      $(".form-refused-ads input[name=id_ad]").val(id);

     }else{

          $('.proccess_load').show();
          $.ajax({
              type: "POST",url: "include/modules/board/handlers/status.php",data: "id="+id+"&status="+status,dataType: "html",cache: false,
              success: function (data) {
                 location.reload();
              }
          });

     }
 
    return false;         
});

