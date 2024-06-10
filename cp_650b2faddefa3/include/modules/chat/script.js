$(document).ready(function() {
    $.getScript('files/js/javascript.js');

    var hash_dialog;
    var id_user = 0;
    var searchUsersTimeout = null;  

    $(document).on('input','.input-search-users', function (e) {
      if (searchUsersTimeout != null) {
        clearTimeout(searchUsersTimeout);
      }
      searchUsersTimeout = setTimeout(function() {
        searchUsersTimeout = null;  

          $.ajax({type: "POST",url: "include/modules/chat/handlers/search_users.php",data: "q="+$('.input-search-users').val(),dataType: "html",cache: false,                        
              success: function (data){
                  $('.admin-chat-users-list').html(data);                                      
              }
          });

      }, 200);  
    });

    $(document).on('click','.admin-chat-users-list-item', function (e) {        
        $('.proccess_load').show(); 

        $('.admin-chat-users-list-item').removeClass("active");
        $(this).addClass("active");

        $(this).find('.admin-chat-users-list-item-count').css('visibility', 'hidden');

        hash_dialog = $(this).data('hash');
        id_user = $(this).data('id');

        loadChat(hash_dialog, id_user);
        
        e.preventDefault();
    });

    function loadChat(hash, id){

        $.ajax({
            type: "POST",url: "include/modules/chat/handlers/load_dialog.php",data:'hash='+hash+'&id='+id,dataType: "html",cache: false,                                                
            success: function (data) {
                $('.admin-chat-users-dialog').html(data);      
                $('.proccess_load').hide();      
                $(".admin-chat-users-dialog-content").scrollTop($(".admin-chat-users-dialog-content").get(0).scrollHeight);
                $('[data-toggle="popover"]').popover();                              
            }
        });

    }

    var status_send = true;

    function sendChat(){

      status_send = false;
      
      var post_data = [];
      var text = $(".admin-chat-users-dialog-footer-text").val();
      var attach = $(".admin-chat-users-dialog-footer-attach-list input").serialize();

      if(text || attach){

          $(".admin-chat-users-dialog-footer-text").val("");

          post_data.push('hash='+hash_dialog);
          post_data.push('id='+id_user);

          if(text) post_data.push('text='+encodeURIComponent(text));
          if(attach) post_data.push(attach);

          $.ajax({type: "POST",url: "include/modules/chat/handlers/send_message.php",data: post_data.join('&'),dataType: "html",cache: false,success: function (data) { 
             
             loadChat(hash_dialog, id_user); 

             status_send = true;

          }});

      }

    }

    $(document).on('keydown','.admin-chat-users-dialog-footer-send', function (e) { 
        if (e.keyCode == 13 && !e.shiftKey && status_send == true) {
          sendChat();
          e.preventDefault();
        }
    });

    $(document).on('click','.admin-chat-users-dialog-footer-attach-change', function () { $('.admin-chat-users-dialog-footer-attach-input').click(); });
    $(document).on('change','.admin-chat-users-dialog-footer-attach-input', function () {  
       if(this.files.length > 0){  
          status_send = false;
          chatAttach(this);
       }   
    });

    $(document).on("click", ".admin-chat-users-dialog-footer-attach-delete", function(e) {
        $(this).parents(".admin-chat-users-dialog-footer-attach-files-preview").remove().hide();
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

      var i = 0;

      while (i < input.files.length) {

        if (input.files && input.files[i]) {
            var reader = new FileReader();
            
            reader.onload = function (e) { 

                var uid = getRandomInt(10000, 90000);  
                $(".admin-chat-users-dialog-footer-attach-list").append('<div class="id'+uid+' admin-chat-users-dialog-footer-attach-files-preview admin-chat-users-dialog-footer-attach-files-loader" ><img class="image-autofocus" src="'+e.target.result+'" /></div>'); 

            };

            reader.readAsDataURL(input.files[i]);
        }
        
        i++
      }

      $.ajax({url: "include/modules/chat/handlers/attach.php",type: 'POST',data: data,cache: false,dataType: 'html',processData: false,contentType: false,
          success: function( respond, textStatus, jqXHR ){

               $(".admin-chat-users-dialog-footer-attach-list").append(respond);
               $(".admin-chat-users-dialog-footer-attach-files-loader").remove().hide();
               status_send = true;

          }
      });

      $(".admin-chat-users-dialog-footer-attach-input").val("");

    }

    $(document).on('click','.admin-chat-users-dialog-delete', function () {    

           swal({
              title: "Вы действительно хотите удалить диалог?",
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
                      type: "POST",url: "include/modules/chat/handlers/delete_dialog.php",data: "hash="+$(this).data("hash")+"&id="+$(this).data("id"),dataType: "html",cache: false,
                      success: function (data) {
                         location.reload();
                      }
                  });                 
              }
            })

        return false;         
    });

    $(document).on('click','.admin-chat-users-dialog-ban', function () {    

          $('.proccess_load').show();
          $.ajax({
              type: "POST",url: "include/modules/chat/handlers/ban_user.php",data: "id="+$(this).data("id"),dataType: "html",cache: false,
              success: function (data) {
                 location.reload();
              }
          });

    });

    $(document).on('click','.chat-responder-send', function (e) {   
        $('.proccess_load').show(); 
        $.ajax({
            type: "POST",url: "include/modules/chat/handlers/add_responder.php",data: $('.chat-responder-form').serialize(),dataType: "html",cache: false,                                                
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

    $(document).on('click','.admin-chat-users-list-responder-item-delete', function () {    

           swal({
              title: "Вы действительно хотите удалить рассылку?",
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
                      type: "POST",url: "include/modules/chat/handlers/delete_responder.php",data: "id="+$(this).data("id"),dataType: "html",cache: false,
                      success: function (data) {
                         location.reload();
                      }
                  });                 
              }
            })

        return false;         
    });


}); 