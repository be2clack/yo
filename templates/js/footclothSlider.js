$(document).ready(function () {

var slider_footcloth_width = 0;
var slider_container = $('.ads-view-photo-slider').width();
var slider_array = [];
var slider_level_array = [];
var current_photo = 1;
var scroll_to = 0;
var slider_margin = 5;
var miniatures = [];

function footclothSliderInit(){

    slider_array = [];
    current_photo = 1;
    scroll_to = 0;
    miniatures = [];

    footclothSliderAnimate(scroll_to);

    $('.ads-view-photo-slider-item').each(function (index, element) {

      var slide_level = 0;

      slider_footcloth_width += $(element).width() + slider_margin;
      slider_array.push($(element).width() + slider_margin);

        $.each(slider_array,function(index,value){

            slide_level += slider_array[index];

        }); 

        slider_level_array.push(slide_level);   

        miniatures.push('<div data-index="'+(index+1)+'" ><img class="image-autofocus" src="'+$(element).data('thumb')+'"></div>');  

    });

    if(slider_footcloth_width > slider_container && $(window).width() > 992){
        $('.ads-view-photo-nav-left, .ads-view-photo-nav-right').css('visibility', 'visible');
    }

    $('.ads-view-photo-mini-gallery').html(miniatures.join(''));
    $('.ads-view-photo-mini-gallery > div[data-index="1"]').addClass('active');

    setInterval(() => {

        footclothSliderUpdateLoadedImage();

    }, 2000);

}

function footclothSliderUpdateLoadedImage(){
  
    slider_array = [];
    slider_level_array = [];
    slider_footcloth_width = 0;

    $('.ads-view-photo-slider-item').each(function (index, element) {

      var slide_level = 0;

      slider_footcloth_width += $(element).width() + slider_margin;
      slider_array.push($(element).width() + slider_margin);

        $.each(slider_array,function(index,value){

            slide_level += slider_array[index];

        }); 

        slider_level_array.push(slide_level);

    });

    if(slider_footcloth_width > slider_container && $(window).width() > 992){
        $('.ads-view-photo-nav-left, .ads-view-photo-nav-right').css('visibility', 'visible');
    }

}

function footclothSliderAnimate(scroll_to = 0){
    $(".ads-view-photo-slider").stop().animate({scrollLeft: scroll_to}, 250);    
}

function slider_footcloth_prev(){
    if(current_photo != 1){
        current_photo--;
        scroll_to = slider_level_array[current_photo-2];
        footclothSliderAnimate(scroll_to);
    }else{
        current_photo = 1;
        scroll_to = 0;
        footclothSliderAnimate(scroll_to);
    }

    $('.ads-view-photo-mini-gallery > div').removeClass('active');
    $('.ads-view-photo-mini-gallery > div[data-index="'+current_photo+'"]').addClass('active');

}

function slider_footcloth_next(){
    current_photo++;

    if(current_photo <= slider_array.length){

        scroll_to = slider_level_array[current_photo-2];
        footclothSliderAnimate(scroll_to);        
    }else{
        current_photo = 1;
        scroll_to = 0;
        footclothSliderAnimate(scroll_to);
    }

    $('.ads-view-photo-mini-gallery > div').removeClass('active');
    $('.ads-view-photo-mini-gallery > div[data-index="'+current_photo+'"]').addClass('active');

}

function slider_footcloth_go_over(index=1){

    current_photo = index;
    scroll_to = slider_level_array[current_photo-2];
    footclothSliderAnimate(scroll_to);

    $('.ads-view-photo-mini-gallery > div').removeClass('active');
    $('.ads-view-photo-mini-gallery > div[data-index="'+current_photo+'"]').addClass('active');

}

$(document).on('click','.ads-view-photo-nav-left', function (e) {
    
   slider_footcloth_prev();

   e.preventDefault();
});

$(document).on('click','.ads-view-photo-nav-right', function (e) {
    
   slider_footcloth_next();

   e.preventDefault();
});

$(document).on('click','.ads-view-photo-mini-gallery > div', function (e) {
    
   slider_footcloth_go_over($(this).data('index'));

   e.preventDefault();
});


$(function(){ 

    footclothSliderInit();

});

});