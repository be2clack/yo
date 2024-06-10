$(document).ready(function() {
    $.getScript('files/js/javascript.js');

    $(document).on('change','select[name=content]', function () {  

        if( $(this).val() == "1" ){

            $(".rss-container-blog").show();
            $(".rss-container-ads").hide();

        }else if( $(this).val() == "2" ){

            $(".rss-container-ads").show();
            $(".rss-container-blog").hide();

        }else{

            $(".rss-container-ads").hide();
            $(".rss-container-blog").hide();

        }
      
    });

    $(document).on('change','input[name=blog_turbo]', function () {  

        if( $(this).prop("checked") ){
            $(".rss-container-blog-turbo-metrics").show();
        }else{
            $(".rss-container-blog-turbo-metrics").hide();
        }
      
    });

    $(document).on('change','input[name=ads_turbo]', function () {  

        if( $(this).prop("checked") ){
            $(".rss-container-ads-turbo-metrics").show();
        }else{
            $(".rss-container-ads-turbo-metrics").hide();
        }
      
    });

    $(document).on('change input','.form-ajax select, .form-ajax input, .form-ajax textarea', function () {  

        if( $("select[name=content] option:selected").val() == "1" ){

            $.ajax({
                type: "POST",url: "include/modules/rss/handlers/load.php",data: $(".form-ajax").serialize() + "&content=1",dataType: "html",cache: false,
                success: function (data) {
                    $(".blog_link").html( data );
                }
            });

        }else if( $("select[name=content] option:selected").val() == "2" ){

            $.ajax({
                type: "POST",url: "include/modules/rss/handlers/load.php",data: $(".form-ajax").serialize() + "&content=2",dataType: "html",cache: false,
                success: function (data) {
                    $(".ads_link").html( data );
                }
            });

        }
      
    });


}); 