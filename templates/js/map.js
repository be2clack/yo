$(document).ready(function () {
   
var url_path = $("body").data("prefix");
var statusOpenSidebar = false;

$.ajaxSetup({
 headers: {
   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
 }
});

function setFiltersUrl(){

  var hashes = window.location.href.split('?');
      
  var params = $.param($('.modal-form-filter, .modal-geo-options-form').serializeArray().filter(function(el) {
          return $.trim(el.value);
      }));

      history.pushState("", "", hashes[0]+"?"+params);

}

$(document).on('click','.filter-accept', function () { 
    setFiltersUrl();
    location.reload();
});

$(document).on("click", ".map-search-offer-container-close", function(e) {
     
      $('.map-search-offer-container').hide();
      $('.map-search-sidebar').css('bottom', 'auto');

});

$(document).on("click", ".action-clear-filter", function(e) {
     
     if( $("input[name=id_c]").val() == "0" ){
        location.href = window.location.href.split("?")[0];
     }else{
        location.href = window.location.href.split("?")[0] + "?id_c=" + $("input[name=id_c]").val();
     }

     e.preventDefault();

});

$(document).on('change','.modal-form-filter input', function (e) { 

    var id_filter = $(this).closest(".filter-items").attr("id-filter");
    var id_parent = $(this).closest(".filter-items").attr("main-id-filter");
    var id_item = $(this).val();
    var element = $(this);
    
    if($(this).closest(".filter-items").attr("data-ids") != undefined){
       var ids = $(this).closest(".filter-items").attr("data-ids").split(",");
    }

    if(ids){

      $.each(ids,function(index,value){

        $('div[id-filter="'+value+'"]').remove();

      });

    }

    if($(this).val() != "null"){ 
        
          $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id_filter="+id_filter+"&id_item="+id_item+"&view=modal&action=ads/load_items_filter",dataType: "html",cache: false,success: function (data) {

              element.closest(".filter-items").after(data);

          }});
     
    }

    e.preventDefault();
});

function detectMobile(height){

    if(!height){
       height = $(window).height();
    }

    if ($(window).width() <= 992) {
      $(".map-search-container, .map-search-instance").height(height - $('.header-wow-mobile').height());
    }else{
      $(".map-search-container, .map-search-instance").height(height - $('.header-wow').height());
    }

    //$(".map-search-offers-list").height( $('.map-search-sidebar').outerHeight() - $(".map-search-offers-header").outerHeight() - 20 );

}

detectMobile();

$(window).on('resize', function(){

  detectMobile($(this).height());

});

});



