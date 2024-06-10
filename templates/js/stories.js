$(document).ready(function () {
   
   var url_path = $("body").data("prefix");
   var changeIdAd = 0;
   var changeIdCity = 0;
   var changeIdRegion = 0;
   var changeIdCountry = 0;
   var changeIdCategory = 0;
   var changeIdStory = 0;
   
   $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
   
   var totalStory = 0;
   var indexStory = 1;
   var indexUserStories = 0;
   var storyProgress;
   var openModalStories;

   function showLoadProcess(el){
        el.prop('disabled', true);
        el.html('<span class="spinner-border spinner-border-sm spinner-load-process" role="status" ></span> '+el.html());
   }

   function hideLoadProcess(el){
        el.prop('disabled', false);
        $('.spinner-load-process').remove();
   }

   function updateViewStory(id=0){
      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id_story="+id+"&action=profile/update_view_story",dataType: "html",cache: false});     
   }

   function loadStories(index=0){
      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "cat_id="+$("input[name=id_c]").val()+"&index="+index+"&action=profile/load_user_stories",dataType: "html",cache: false,success: function (data) {
          
          if(data){
              $('.modal-view-user-stories').show();
              $('.modal-view-user-stories-container').html(data);

              totalStory = parseInt($('.modal-view-user-stories-item').length);
              indexStory = 1;

              if($('.modal-view-user-stories-item.active .story-video').length){
                 $('.modal-view-user-stories-item.active .story-video')[0].currentTime = 0;
                 $('.modal-view-user-stories-item.active .story-video')[0].play();
              }

              progressStories($('.modal-view-user-stories-item.active').data('duration'), indexStory);

              updateViewStory($('.modal-view-user-stories-item.active').data('id'));

          }else{
              closeModalStories();
          }

      }});     
   }

   $(document).on('click','.slider-user-stories-item', function (e) { 

      indexUserStories = parseInt($(this).data('index'));

      var hashes = window.location.href.split('#');
      history.pushState("", "", hashes[0]+"#stories");


      openModalStories = setInterval(function () {

        var sr = window.location.href.indexOf("#stories");
        if(sr == "-1"){
            closeModalStories();
            clearInterval(openModalStories);
        }

      },400);

      loadStories(indexUserStories);

      e.preventDefault(); 

   });  

   function controlStoriesNext(){
      
      let i = 1;

      if($('.story-video').length){
        $('.story-video')[0].pause();
      }

      if(indexStory < totalStory){

        indexStory = indexStory + 1;

        $('.modal-view-user-stories-item').hide();
        $('.modal-view-user-stories-item[data-index="'+indexStory+'"]').show();

        while (i < indexStory) {
          $('.modal-view-user-stories-progress>span[data-index="'+i+'"]').html("");
          $('.modal-view-user-stories-progress>span[data-index="'+i+'"]').append('<span class="end" ></span>');
          i++;
        }

        $('.modal-view-user-stories-progress>span[data-index="'+indexStory+'"]').html("");
        $('.modal-view-user-stories-progress>span[data-index="'+indexStory+'"]').append('<span class="start" ></span>');

        if($('.modal-view-user-stories-item[data-index="'+indexStory+'"] .story-video').length){
           $('.modal-view-user-stories-item[data-index="'+indexStory+'"] .story-video')[0].currentTime = 0;
           $('.modal-view-user-stories-item[data-index="'+indexStory+'"] .story-video')[0].play();
        }

        progressStories($('.modal-view-user-stories-item[data-index="'+indexStory+'"]').data('duration'), indexStory);

        updateViewStory($('.modal-view-user-stories-item[data-index="'+indexStory+'"]').data('id'));

      }else{
        $('.modal-view-user-stories-progress>span[data-index="'+indexStory+'"]').html("");
        $('.modal-view-user-stories-progress>span[data-index="'+indexStory+'"]').append('<span class="end" ></span>');
        indexUserStories = indexUserStories + 1;
        loadStories(indexUserStories);
      }

      $('.modal-view-user-stories-right-menu-list').hide();

   } 

   function controlStoriesPrev(){
      
      let i = 1;

      if($('.story-video').length){
        $('.story-video')[0].pause();
      }

      if(indexStory > 1){

        indexStory = indexStory - 1;

        $('.modal-view-user-stories-item').hide();
        $('.modal-view-user-stories-item[data-index="'+indexStory+'"]').show();

        $('.modal-view-user-stories-progress>span').html("");
        $('.modal-view-user-stories-progress>span').append('<span class="start" ></span>');

        while (i < indexStory) {
          $('.modal-view-user-stories-progress>span[data-index="'+i+'"]').html("");
          $('.modal-view-user-stories-progress>span[data-index="'+i+'"]').append('<span class="end" ></span>');
          i++;
        }

        if($('.modal-view-user-stories-item[data-index="'+indexStory+'"] .story-video').length){
           $('.modal-view-user-stories-item[data-index="'+indexStory+'"] .story-video')[0].currentTime = 0;
           $('.modal-view-user-stories-item[data-index="'+indexStory+'"] .story-video')[0].play();
        }

        progressStories($('.modal-view-user-stories-item[data-index="'+indexStory+'"]').data('duration'), indexStory);

        updateViewStory($('.modal-view-user-stories-item[data-index="'+indexStory+'"]').data('id'));

      }else{
        indexUserStories = indexUserStories - 1;
        loadStories(indexUserStories);
      }

      $('.modal-view-user-stories-right-menu-list').hide();

   }

   $(document).on('tap','.modal-view-user-stories-item-control-right', function (e) { 
        controlStoriesNext();
   });

   $(document).on('tap','.modal-view-user-stories-item-control-left', function (e) { 
        controlStoriesPrev();
   });

   $(document).on('press', '.modal-view-user-stories-item-control-right', function(e) {
       storyProgress.css("animation-play-state", "paused");
       if($('.story-video').length){
          $(".story-video")[0].pause();
       }      
   });

   $(document).on('mouseup touchend', '.modal-view-user-stories-item-control-right', function(e) {
       storyProgress.css("animation-play-state", "running");
       if($('.modal-view-user-stories-item[data-index="'+indexStory+'"] .story-video').length){
          $('.modal-view-user-stories-item[data-index="'+indexStory+'"] .story-video')[0].play();
       }       
   });


  function progressStories(duration=10, index=0) {

      $('.modal-view-user-stories-progress>span[data-index="'+index+'"]').html("");
      $('.modal-view-user-stories-progress>span[data-index="'+index+'"]').append('<span class="animation" ></span>');

      storyProgress = $('.modal-view-user-stories-progress>span[data-index="'+index+'"] span');

      storyProgress.css("animation-duration", duration+"s");

      storyProgress.on('animationend', function () {
        controlStoriesNext();
      });

      storyProgress.css("animation-play-state", "running");

  }

   $(document).on('click','.modal-view-user-stories', function (e) { 

        if (!$(e.target).closest(".modal-view-user-stories-container").length) {
           closeModalStories();
        }

        e.stopPropagation();

   });

   $(document).on('click','.modal-view-user-stories-close', function (e) { 

       closeModalStories();

   });

   function closeModalStories(){
       $('.modal-view-user-stories').fadeOut(200, function(){
          $('.modal-view-user-stories-container').html("");
       });
       var hashes = window.location.href.split('#');
       history.pushState("", "", hashes[0]);           
       $("body").css("overflow", "auto");
       if($('.story-video').length){
          $(".story-video")[0].pause();
       }    
   }

   $(document).on('click','.action-user-story-image-add', function (e) { 

      $('.modal-custom-bg').hide();
      $('input[name=story_media]').val('');
      $('.modal-user-story-image-form input[name=story_media]').click();

   });

   $(document).on('change','.modal-user-story-image-form input[name=story_media]', function () { 

        $('.modal-user-story-add-container-maker').html('<div class="preload-box-align-center" ><div class="spinner-grow preload-spinner" role="status"><span class="sr-only"></span></div></div>');
        $(".modal-user-story-add-maker").show();   

        var data_form = new FormData($('.modal-user-story-image-form')[0]);
        data_form.append('action', 'profile/story_load_add');
        data_form.append('type', 'image');

        $.ajax({url: url_path + "systems/ajax/controller.php",type: 'POST',data: data_form,cache: false, processData: false,contentType: false,dataType: 'html', success: function(data){

           $('.modal-user-story-add-container-maker').html(data);

        }});

   });

   $(document).on('click','.action-user-story-video-add', function (e) { 

      $('.modal-custom-bg').hide();
      $('input[name=story_media]').val('');
      $('.modal-user-story-video-form input[name=story_media]').click();

   });

   function progressHandle(event) {
      console.log("Total size:"+ event.total + "  uploaded:" + event.loaded);
   }

   $(document).on('change','.modal-user-story-video-form input[name=story_media]', function () {    

        $('.modal-user-story-add-container-maker').html('<div class="preload-box-align-center" ><div class="spinner-grow preload-spinner" role="status"><span class="sr-only"></span></div><div class="modal-user-story-add-maker-progress-load-video" >Загружено <strong>0%</strong></div></div>');
        $(".modal-user-story-add-maker").show();

        var data_form = new FormData($('.modal-user-story-video-form')[0]);
        data_form.append('action', 'profile/story_load_add');
        data_form.append('type', 'video');

        $.ajax({
          xhr: function() {
            let xhr = new XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt) {
              if (evt.lengthComputable) {
                var percentComplete = evt.loaded / evt.total;
                percentComplete = parseInt(percentComplete * 100);
                if(percentComplete){
                    $('.modal-user-story-add-maker-progress-load-video').show();
                    $('.modal-user-story-add-maker-progress-load-video strong').html(percentComplete+'%');
                }
              }
            }, false);
            return xhr;
          },
          url: url_path + "systems/ajax/controller.php",
          type: "POST",
          data: data_form,
          contentType: false,
          processData: false,
          success: function(data) {
             $('.modal-user-story-add-container-maker').html(data);
          }
        });

   });

   $(document).on('click','.user-story-publication', function () {    

        var el = $(this);
        showLoadProcess(el);

        $.ajax({url: url_path + "systems/ajax/controller.php",type: 'POST',data: 'name='+$(this).data('name')+'&type='+$(this).data('type')+'&id='+changeIdAd+'&city_id='+changeIdCity+'&region_id='+changeIdRegion+'&country_id='+changeIdCountry+'&category_id='+changeIdCategory+'&action=profile/story_publication',cache: false,dataType: 'json',
              success: function( respond, textStatus, jqXHR ){

                if(respond['status']){
                    location.reload();
                }else{
                    if(respond['balance']){
                        alert(respond['answer']);      
                        hideLoadProcess(el);                 
                    }else{
                        $(".modal-balance-summa").html(respond["balance_summa"]);
                        $('.modal-user-story-add-maker, #modal-user-story-add').hide();      
                        $("#modal-balance").show();
                        $("body").css("overflow", "hidden"); 
                        hideLoadProcess(el);
                    }                    
                }

              }
        }); 

   });

   $(document).on('click','.modal-user-story-add-header-maker-close', function (e) { 

       $('.modal-user-story-add-maker').hide();
       $("body").css("overflow", "auto");

   });

   $(document).on('click','.modal-user-story-add-change-promovere', function (e) { 

       $('.modal-user-story-add-footer-promovere-list').toggle();
       $('.modal-user-story-add-footer-ads-list').hide();

   });

   $(document).on('click','.modal-user-story-add-change-location', function (e) { 

       $('.modal-user-story-add-footer-location-list').toggle();
       $('.modal-user-story-add-footer-ads-list,.modal-user-story-add-footer-promovere-list').hide();

   });

   $(document).on('click','.modal-user-story-add-change-category', function (e) { 

       $('.modal-user-story-add-footer-category-list').toggle();
       $('.modal-user-story-add-footer-ads-list,.modal-user-story-add-footer-promovere-list,.modal-user-story-add-footer-location-list').hide();

   });

   $(document).on('click','.modal-user-story-add-footer-promovere-list > div', function (e) { 

        $(".modal-user-story-add-change-location > span").html($(this).data("name")); 
        
       if($(this).data('type') == 'profile'){
         changeIdAd = 0;
         $('.modal-user-story-add-change-promovere > span').html('Свой профиль');
         $('.modal-user-story-add-footer-promovere-list').hide();
       }else{
         $('.modal-user-story-add-footer-promovere-list').hide();
         $(".modal-user-story-add-footer-ads-list").show();
       }

   });

   $(document).on('click','.modal-user-story-add-footer-ads-list-search > div', function (e) { 

       changeIdAd = $(this).data('id');
       $('.modal-user-story-add-change-promovere > span').html($(this).data('title'));
       $('.modal-user-story-add-footer-promovere-list').hide();
       $('.modal-user-story-add-footer-ads-list').hide();

   });

   $(document).on('input','.modal-user-story-add-footer-ads-list input', function () {    

        $.ajax({url: url_path + "systems/ajax/controller.php",type: 'POST',data: "search=" + $(this).val() + "&action=profile/story_search_ads",cache: false,dataType: 'html',success: function(data){

            $('.modal-user-story-add-footer-ads-list-search').html(data);

        }}); 

   });

   $(document).on('input','.modal-user-story-add-footer-location-list input', function () {    

        $.ajax({url: url_path + "systems/ajax/controller.php",type: 'POST',data: "search=" + $(this).val() + "&action=profile/story_search_location",cache: false,dataType: 'html',success: function(data){

            $('.modal-user-story-add-footer-location-list-search').html(data);

        }}); 

   });

   $(document).on('input','.modal-user-story-add-footer-category-list input', function () {    

        $.ajax({url: url_path + "systems/ajax/controller.php",type: 'POST',data: "search=" + $(this).val() + "&action=profile/story_search_category",cache: false,dataType: 'html',success: function(data){

            $('.modal-user-story-add-footer-category-list-search').html(data);

        }}); 

   });

   $(document).on('click','.modal-user-story-add-footer-location-list-search > div', function () {    

        changeIdCity = $(this).attr("id-city");
        changeIdRegion = $(this).attr("id-region");
        changeIdCountry = $(this).attr("id-country");

        $(".modal-user-story-add-change-location > span").html($(this).data("name")); 
        $(".modal-user-story-add-footer-location-list").hide();

   });

   $(document).on('click','.modal-user-story-add-footer-category-list-search > div', function () {    

        changeIdCategory = $(this).attr("id-cat");

        $(".modal-user-story-add-change-category > span").html($(this).data("name")); 
        $(".modal-user-story-add-footer-category-list").hide();

   });

   $(document).on('click','.modal-view-user-stories-right-menu-delete', function () {    
      changeIdStory = $(this).data('story-id');   
   });

   $(document).on('click','.user-story-delete', function () {    

        var el = $(this);
        showLoadProcess(el);

        $.ajax({url: url_path + "systems/ajax/controller.php",type: 'POST',data: "story_id=" + changeIdStory + "&action=profile/story_delete",cache: false,dataType: 'html',success: function(data){

            location.reload();

        }}); 

   });

   $(document).on('click','.modal-view-user-stories-right-menu > i', function (e) { 

       $('.modal-view-user-stories-right-menu-list').toggle();

   });

   $('.modal-view-user-stories').bind("contextmenu",function(e){
        return false;
   });




});