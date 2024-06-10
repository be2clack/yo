$(document).ready(function () {

   $(document).on('click','.uni-select-name', function () {
      var parent = $(this).parent();
      $(".uni-select").not(parent).removeClass("uni-select-open").attr("data-status",0);
      if( $(this).parent().attr("data-status") == 0 ){
          $(this).parent().toggleClass("uni-select-open").attr("data-status",1);
      }else{
          $(this).parent().removeClass("uni-select-open").attr("data-status",0);
      }
   });

   $(document).on('click', function(e) {
      if (!$(e.target).closest(".uni-select").length) {
        $(".uni-select").removeClass("uni-select-open").attr("data-status",0);
      }
      e.stopPropagation();
   });

   $(document).on('click','.uni-select-list > label', function () {
      var input = $(this).find("input");
      var parent = $(this).parent();
      var name = parent.parent().find(".uni-select-name");

      if( input.attr("type") == "radio" ){
         parent.find("input").not(input).removeAttr("checked");
         name.find("span").html( $(this).find("span").html() );
         parent.find("label").removeClass("uni-select-item-active");
         $(this).addClass("uni-select-item-active");
         $(".uni-select").removeClass("uni-select-open").attr("data-status",0);
      }else{

         if ($(input).prop("checked") == true){
             $(this).addClass("uni-select-item-active");
         }else{
             $(this).removeClass("uni-select-item-active");
         }

         var count = parent.find("input:checked").length;

         if(!count){
            name.find("span").html( name.data("name") );
         }else if(count == 1){
            name.find("span").html( parent.find(".uni-select-item-active span").html() );
         }else if(count > 1){
            name.find("span").html( $(".lang-js-4").html() + " ("+count+")" );
         }else{
            name.find("span").html( name.data("name") );
         }

      }


   });

   $('.uni-select').each(function (index, element) {
        
        if( $(element).find(".uni-select-item-active").length == 1 ){
           $(element).find(".uni-select-name span").html( $(element).find(".uni-select-item-active span").html() );
        }else if( $(element).find(".uni-select-item-active").length > 1 ){
           $(element).find(".uni-select-name span").html( $(".lang-js-4").html() +" ("+$(element).find(".uni-select-item-active").length+")" );
        }

   });

   $(document).on('input','.uni-select-list-search input', function () {
      
      var str = $(this).val().toLowerCase();
      var el = $(this).parents(".uni-select-list").find('label');

      el.show();
      
      el.each(function(){
        if ($(this).text().toLowerCase().indexOf(str) < 0){
            $(this).hide();
        }
      });  
        
   });




});