$(document).ready(function () {

var url_path = $("body").data("prefix");
var id_dialog = 0;
var support = 0;
var status_send = true;
var countNewMessage = 0;
var statusUpdateCount = true;

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

function loadChat(event,preloader=true){

 id_dialog = event.data("id");
 support = event.data("support");

 if(id_dialog){

    $(".module-chat-users > div").removeClass("active");
    event.addClass("active");

    if(preloader) $(".module-chat-dialog").html('<div class="chat-dialog-spinner"><div class="spinner-border text-primary" role="status"></div></div>');

     $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id=" + id_dialog + "&support=" + support + "&action=chat/load_chat",dataType: "json",cache: false,success: function (data) { 
        
        $(".module-chat-dialog").html(data["dialog"]);

        if ($(window).width() <= 992) {
            $(".module-chat-dialog-prev").show();
        }        

        resizeChat($(window).width(),$(window).height());

        if(data["count_msg"]) $(".update-count-message").html(data["count_msg"]); else $(".update-count-message").hide();
        $("[data-id="+id_dialog+"]").find(".module-chat-users-count-msg").hide();
        $("[data-id="+id_dialog+"]").find(".chat-users-info-read").hide();
        $("[data-id="+id_dialog+"]").find(".chat-users-info-notread").hide();

        $(".module-chat-dialog-content").scrollTop($(".module-chat-dialog-content").get(0).scrollHeight);

     }});
     
  }

}

function initChat(id_ad=0, id_user=0){

 $(".init-chat-body").html('<div class="chat-dialog-spinner"><div class="spinner-border text-primary" role="status"></div></div>');

 $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id_ad="+id_ad+"&id_user="+id_user+"&action=chat/init",dataType: "html",cache: false,success: function (data) { 
    if(data){
        $(".init-chat-body").html(data);
        detectMobile($(window).width(),$(window).height());
        loadChat($(".module-chat-users .active"));
    }
 }});

}

initChat($('input[name=chat_id_ad]').val());

$(document).on('click','.module-chat-users > div', function () {  
  
 loadChat($(this));

 if($(window).width() <= 992){
     $(".module-chat-users").hide();
     $(".module-chat-dialog").show();
 }

});

$(document).on('click','.module-chat-dialog-prev span', function () {  
 
 $(".module-chat-users").show();
 $(".module-chat-dialog").hide();
 $(".module-chat-dialog-prev").hide();
 $(".module-chat-users > div").removeClass("active");

});

function sendChat(voice=''){
  
  var post_data = [];
  var text = $(".chat-dialog-text").val();
  var attach = $(".chat-dialog-attach-list input").serialize();

  if(voice){
     text = '';
     attach = '';
  }

  if(text || attach || voice){

      $(".chat-dialog-text").val("");

      post_data.push('id='+id_dialog);
      post_data.push('support='+support);
      if(text) post_data.push('text='+encodeURIComponent(text));
      if(attach) post_data.push(attach);
      if(voice){
         post_data.push('voice='+voice);
         post_data.push('duration='+elapsedSeconds);
      }

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: post_data.join('&') + "&action=chat/send_chat",dataType: "json",cache: false,success: function (data) { 
         
         $(".module-chat-dialog").html(data["dialog"]);
         $("[data-id="+id_dialog+"]").find(".chat-users-info-read").hide();
         $("[data-id="+id_dialog+"]").find(".chat-users-info-notread").show();

         if ($(window).width() <= 992) {
             $(".module-chat-dialog-prev").show();
         }        

         resizeChat($(window).width(),$(window).height());

         $(".module-chat-dialog-content").scrollTop($(".module-chat-dialog-content").get(0).scrollHeight);
         status_send = true;
         elapsedSeconds = 0;

      }});

  }

}

$(document).on('keydown','.chat-dialog-send', function (e) { 
    if (e.keyCode == 13 && !e.shiftKey && status_send == true) {
      sendChat();
      e.preventDefault();
    }
});

$(document).on('click','.chat-dialog-text-send', function (e) { console.log(status_send);
    if(status_send == true){
        status_send = false;
        sendChat();
    }  
});

$(document).on('click','.chat-user-delete', function (e) {  

  $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id=" + id_dialog + "&action=chat/delete_chat",dataType: "json",cache: false,success: function (data) { 

     $("[data-id="+id_dialog+"]").hide();

     if($(window).width() <= 992){
         $(".module-chat-users").show();
         $(".module-chat-dialog").hide();
         $(".module-chat-dialog-prev").hide();
         $(".module-chat-users > div").removeClass("active");
     }else{
         $(".module-chat-dialog").html(data["dialog"]);
     }

     $("#modal-chat-user-confirm-delete .modal-custom-close").click(); 

  }});

  e.preventDefault();

});

$(document).on('click','.chat-user-block', function (e) {  

  $('.chat-user-block').prop('disabled', true);

  $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id=" + id_dialog + "&action=chat/chat_user_locked",dataType: "json",cache: false,success: function (data) { 

     $(".module-chat-dialog").html(data["dialog"]);
     $('.chat-user-block').prop('disabled', false);
     $("#modal-chat-user-confirm-block .modal-custom-close").click();
     resizeChat($(window).width(),$(window).height());

  }});

  e.preventDefault();

});

$(document).on('click','.dialog-header-menu i', function (e) {  

  $(".chat-options-list").toggle();

});

$(document).on('click','.chat-dialog-attach-change', function () { $('.chat-dialog-attach-input').click(); });
$(document).on('change','.chat-dialog-attach-input', function () {  
   if(this.files.length > 0){  
      status_send = false;
      chatAttach(this);
      $('.chat-dialog-text-send').show();
   }   
});

$(document).on("click", ".chat-dialog-attach-delete", function(e) {
    $(this).parents(".attach-files-preview").remove().hide();
    
    resizeChat($(window).width(),$(window).height());

    e.preventDefault();
});

function getRandomInt(min, max)
{   
   return Math.floor(Math.random() * (max - min + 1)) + min;
}

function chatAttach(input) {

  var data = new FormData();
  $.each( input.files, function( key, value ){
      data.append( key, value );
  });

  data.append('action', 'chat/attach_files');
 
  var i = 0;

  while (i < input.files.length) {

    if (input.files && input.files[i]) {
        var reader = new FileReader();
        
        reader.onload = function (e) { 

            var uid = getRandomInt(10000, 90000);  
            $(".chat-dialog-attach-list").append('<div class="id'+uid+' attach-files-preview attach-files-loader" ><img class="image-autofocus" src="'+e.target.result+'" /></div>'); 
            resizeChat($(window).width(),$(window).height());

        };

        reader.readAsDataURL(input.files[i]);
    }
    
    i++
  }

  $.ajax({url: url_path + "systems/ajax/controller.php",type: 'POST',data: data,cache: false,dataType: 'html',processData: false,contentType: false,
      success: function( respond, textStatus, jqXHR ){

           $(".chat-dialog-attach-list").append(respond);
           $(".attach-files-loader").remove().hide();
           status_send = true;

      }
  });

  $(".chat-dialog-attach-input").val("");

}

$(document).on('click','.ad-init-message', function (e) {  
    if($(this).data('id-ad') != undefined){
        initChat($(this).data('id-ad'));
    }else if($(this).data('id-user') != undefined){
        initChat(0,$(this).data('id-user'));
    }
});

function updateCount(){

    if(statusUpdateCount){
       $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id="+$('.module-chat-users div.active').data('id')+"&action=chat/update_chat",dataType: "json",cache: false,success: function (data) { 
           
           if(data["auth"]){

               if($(".chat-message-counter").length){

                   if( parseInt(data["all"]) ){
                       
                       $(".chat-message-counter").html(data["all"]).css('display', 'inline-flex');

                       if( countNewMessage != data["all"] ){

                          countNewMessage = data["all"];

                       } 

                   }else{
                        $(".chat-message-counter").hide();
                   }

               }

               if($('#modal-chat-user').is(':visible') && $('#modal-chat-user').length){
                  if($('.module-chat-users div.active').length){

                       if(parseInt(data["active"])){
                           loadChat($(".module-chat-users .active"), false); 
                       } 

                  }
               }

               if(data["hash_counts"]){
                   $.each(data["hash_counts"],function(index,value){

                      $('.module-chat-users div[data-id='+index+'] .module-chat-users-count-msg').html(value).css('display', 'inline-flex');

                   });
               }

               if(data["view"]){
                   $.each(data["view"],function(index,value){

                      if(value){
                            $("[data-id="+index+"]").find(".chat-users-info-read").show();
                            $("[data-id="+index+"]").find(".chat-users-info-notread").hide();
                      }

                   });
               }

           }else{

               statusUpdateCount = false;

           }

       }}); 
    }

}

setInterval(function() {
   updateCount();
}, 2000);

updateCount();

$(window).on('resize', function(){

    detectMobile($(this).width(),$(this).height());
    resizeChat($(this).width(),$(this).height());

});

function detectMobile(width, height){

    if (width <= 992) {

        if($('.module-chat-users div.active').length){

            $('.module-chat-users').hide();
            $('.module-chat-dialog').show();
            $('.module-chat-dialog-prev').show();

        }else{

            if($('.module-chat-users').length){

                $('.module-chat-users').show();
                $('.module-chat-dialog').hide();
                $('.module-chat-dialog-prev').hide();

            }else{

                $('.module-chat-dialog').show();
                
            }

        }

    }else{
        $('.module-chat-users').show();
        $('.module-chat-dialog').show();
        $('.module-chat-dialog-prev').hide();
    }

}

function resizeChat(width, height){
    var heightDisplay = height;
    var heightMain = $('.module-chat').innerHeight();
    var heightHeader = $('.module-chat-dialog-header').innerHeight();
    var heightContent = $('.module-chat-dialog-content').innerHeight();
    var heightFooter = $('.module-chat-dialog-footer').innerHeight();

    if (width <= 992) {
        $('.module-chat-dialog-content').css('height',(heightDisplay - (heightHeader + heightFooter))+'px');
    }else{
        $('.module-chat-dialog-content').css('height',(heightMain - (heightHeader + heightFooter))+'px');        
    }
    
    $('.module-chat-dialog-content').css('margin-top',heightHeader+'px');
    $('.module-chat-dialog-content').css('margin-bottom',heightFooter+'px');
}

var context, analyser, timerId, streamSource, currentStream, recordAction, mediaRecorder, voiceDuration, audioPlayer, audioPlay;
var columns = [];
var audioColumnsCount = 45;
var elapsedSeconds = 0;

var audioLoop = new Uint8Array(audioColumnsCount*2);
var audioChunks = [];
var audioBlob = null;

function getElapsedTimeString(total_seconds) {
  function time_string(num) {
    return ( num < 10 ? "0" : "" ) + num;
  }
  var minutes = Math.floor(total_seconds / 60);
  total_seconds = total_seconds % 60;
  var seconds = Math.floor(total_seconds);
  minutes = time_string(minutes);
  seconds = time_string(seconds);
  var currentTimeString = minutes + ":" + seconds;
  return currentTimeString;
}

function mediaRecorderDataAvailable(e) {
  audioChunks.push(e.data);
}

function mediaRecorderAction(){

    if(recordAction == 'send'){
       
          audioBlob = new Blob(audioChunks, { type: 'audio/mp3' });   
          const formData = new FormData();

          formData.append('voice', audioBlob);
          formData.append('action', 'chat/save_voice');

          $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: formData,dataType: "json",cache: false,contentType: false,processData: false,                        
                success: function (data){
                    if(data['status']){
                        sendChat(data['name']); 
                    }                      
                }
          });

    }

    currentStream.getTracks().forEach( track => track.stop() );
    mediaRecorder = null;
    audioBlob = null;
    audioChunks = [];
    clearInterval(timerId);
    $('.chat-dialog-audio-timer span').html('00:00');
    $('.module-chat-dialog-footer-box2').hide();
    $('.module-chat-dialog-footer-box1').show();
}

function loop() {
    window.requestAnimationFrame(loop);
    analyser.getByteFrequencyData(audioLoop);
    for(var i = 0 ; i < audioColumnsCount ; i++){
        height = audioLoop[i+audioColumnsCount];
        $(columns[i]).css('minHeight', height+'%');
        $(columns[i]).css('opacity', 0.008*height);
    }
}

$(document).on("click", ".chat-dialog-audio-start", function(e) {

    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
      alert('Your browser does not support recording!');
      return;
    }

    $(".attach-files-preview").remove().hide();
    $('.chat-dialog-audio-container').html('<div class="audio-column-line" ></div>');

    resizeChat();
    columns = [];

    if (!mediaRecorder) {

      audioStop();

      for(var i = 0 ; i < audioColumnsCount ; i++){
        columns[i] = $('<div>', {'class': 'audio-column'}).appendTo('.chat-dialog-audio-container');
      }

      context = new (window.AudioContext || window.webkitAudioContext)();
      analyser = context.createAnalyser();

      navigator.mediaDevices.getUserMedia({ audio: true })
        .then((stream) => {

            currentStream = stream;
            mediaRecorder = new MediaRecorder(stream);

            mediaRecorder.ondataavailable = mediaRecorderDataAvailable;
            mediaRecorder.onstop = mediaRecorderAction;

            streamSource = context.createMediaStreamSource(stream);
            streamSource.connect(analyser);    

            mediaRecorder.start();

            timerId = setInterval(function() {
              elapsedSeconds++;
              $('.chat-dialog-audio-timer span').text(getElapsedTimeString(elapsedSeconds));
              if(elapsedSeconds == 60){
                  recordAction = 'send';
                  clearInterval(timerId);
                  mediaRecorder.stop();                
              }
            }, 1000);

            $('.module-chat-dialog-footer-box1').hide();
            $('.module-chat-dialog-footer-box2').show();

            loop();

        })
        .catch((err) => {
          alert(`The following error occurred: ${err}`);
        });

    }

});

$(document).on("click", ".chat-dialog-audio-cancel, .modal-chat-user-close, .module-chat-dialog-prev span", function(e) {
    recordAction = 'cancel';
    elapsedSeconds = 0;
    audioStop();
    if(mediaRecorder) mediaRecorder.stop();
});

$(document).on("click", ".chat-dialog-audio-send", function(e) {
    recordAction = 'send';
    mediaRecorder.stop();
});

$(document).on("click", ".player-voice-action-play", function(e) {
   
   var parent = $(this).parents('.player-voice');
   $(this).prop('disabled', true);

   $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id_hash=" + id_dialog + "&id=" + $(this).data('id') + "&action=chat/play_voice",dataType: "html",cache: false,success: function (data) { 

        initAudio(data,parent);

   }});

});

function audioStop(){
    if(audioPlayer){
       audioPlayer.currentTime = 0;
       audioPlayer.pause();
    }
    clearInterval(audioPlay);
    $('.player-voice-action-play').show();
    $('.player-voice-action-stop').hide();
    $('.player-voice-progress-track').width('0%');
}

$(document).on("click", ".player-voice-action-stop", function(e) {
    audioStop();
});

$(document).on("click", ".player-voice-progress-bg", function(e) {
    var parent = $(this).parents('.player-voice');
    var progress = parent.find('.player-voice-progress-track');
    var duration = parent.find('.player-voice-time').data('duration');
    let widthLeft = $(this).offset().left;
    let x = e.pageX - widthLeft;
    let xPersent =  x / $(this).width() * 100;
    let currentTime = duration * (xPersent / 100);
    progress.width((currentTime * 100) / duration + '%');
    audioPlayer.currentTime = currentTime;
});

function initAudio(data,parent){

  var duration = parent.find('.player-voice-time').data('duration');
  var progress = parent.find('.player-voice-progress-track');
  audioPlayer = $('.module-chat-dialog-footer-box2 audio').get(0);
  parent.find('.player-voice-action-play').prop('disabled', false);

  audioStop();
  
  parent.find('.player-voice-action-play').hide();
  parent.find('.player-voice-action-stop').show();

  audioPlayer.src = data;
  audioPlayer.play();

  audioPlay = setInterval(function() {
     let audioTime = audioPlayer.currentTime;
     let audioLength = Math.round(duration);
     progress.width((audioTime * 100) / audioLength + '%');
     if (Math.round((audioTime * 100) / audioLength) == 100) {
        clearInterval(audioPlay);
        $('.player-voice-action-play').show();
        $('.player-voice-action-stop').hide();
     }
  }, 10);

}





});