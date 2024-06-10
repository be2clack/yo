$(document).ready(function () {
   
   var url_path = $("body").data("prefix");
   
   $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });

   function showLoadProcess(el){
        el.prop('disabled', true);
        el.html('<span class="spinner-border spinner-border-sm spinner-load-process" role="status" ></span> '+el.html());
   }

   function hideLoadProcess(el){
        el.prop('disabled', false);
        $('.spinner-load-process').remove();
   }

   $('.lightgallery').lightGallery();
   
   function getRandomInt(min, max)
   {   
       return Math.floor(Math.random() * (max - min + 1)) + min;
   }

   $(document).on('click','.confirm-cancel-order-marketplace', function (e) { 
      
      var id = $(this).data("id");

      $(".confirm-cancel-order-marketplace").prop('disabled', true);

        $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id="+id+"&action=order/order_cancel_deal_marketplace",dataType: "html",cache: false,success: function (data) { 
            location.reload();
        }});

      e.preventDefault();
   });

   $(document).on('click','.confirm-delete-order-marketplace', function (e) { 
      
      var id = $(this).data("id");

      $(".confirm-delete-order-marketplace").prop('disabled', true);

        $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id="+id+"&action=order/order_delete_marketplace",dataType: "json",cache: false,success: function (data) { 
            location.href = data['link'];
        }});

      e.preventDefault();
   });

    $(document).on('change','.order-change-status', function (e) {

         $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id="+$(this).data('id')+"&status="+$('.order-change-status option:selected').val()+"&action=order/order_change_status",dataType: "html",cache: false,success: function (data) { 
             location.reload();
         }});

    });

    $(document).on('submit','.form-dispute-secure', function (e) { 

        el = $('.form-dispute-secure-button');
        showLoadProcess(el);

        var data_form = new FormData($(this)[0]);
        data_form.append('action', 'order/add_disputes'); 

        $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: data_form,dataType: "json",cache: false,contentType: false,processData: false,                        
            success: function (data){
                if( data["status"] == true ){
                   location.reload();
                }else{
                   alert( data["answer"] );
                   hideLoadProcess(el);
                }                                            
            }
        });

      e.preventDefault();
   });

   $('.dispute-secure-attach').click(function (e) { $('.file-dispute-attach').click(); });

   $('.file-dispute-attach').on('change', function(){ 

      if(this.files.length != 0){
         $(".dispute-secure-attach").html( $(".lang-js-4").html() + " (" + this.files.length + ")" );
      }else{
         $(".dispute-secure-attach").html( $(".lang-js-5").html() );
      }        

   });

   $(document).on('click','.go-to-payment-order', function (e) { 
      
        el = $(this);
        showLoadProcess(el);

        $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: $('.form-delivery').serialize()+"&id_ad="+$(this).data('id-ad')+"&id_order="+$(this).data('id-order')+"&action=order/payment_order",dataType: "json",cache: false,success: function (data) { 
            if(data["status"] == true){
                if( data["redirect"]["link"] ){
                   location.href = data["redirect"]["link"];
                }else{
                   $('body').append('<div class="redirect-form-pay" ></div>');
                   $(".redirect-form-pay").html(data["redirect"]["form"]);
                   $(".form-pay .pay-trans").click();               
                }
            }else{
                if(data["answer"]){
                    alert(data["answer"]);
                    hideLoadProcess(el);
                }else{
                    location.reload();
                }
            }
        }});

      e.preventDefault();
   });

   $(document).on('click','.go-to-payment-balance-order', function (e) { 
      
        el = $(this);
        showLoadProcess(el);

        $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: $('.form-delivery').serialize()+"&id_ad="+$(this).data('id-ad')+"&id_order="+$(this).data('id-order')+"&from_balance=1&action=order/payment_order",dataType: "json",cache: false,success: function (data) { 
            if(data["status"] == true){
                location.reload();
            }else{
                if(data["answer"]){
                    alert(data["answer"]);
                }else{
                    if(data["balance"] == false){
                        $("#modal-balance").show();
                        $(".modal-balance-summa").html( data["balance_total"] );
                        $("body").css("overflow", "hidden");
                    }else{
                        location.reload();
                    }
                }
                hideLoadProcess(el);
            }
        }});

      e.preventDefault();
   });

   $(document).on('click','.confirm-transfer-goods', function (e) { 
      
      var id = $(this).data("id");

      $(".confirm-transfer-goods").prop('disabled', true);

        $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id="+id+"&action=order/confirm_transfer_goods",dataType: "html",cache: false,success: function (data) { 
            location.reload();
        }});

      e.preventDefault();
   });

   $(document).on('click','.confirm-receive-goods', function (e) { 
      
      var id = $(this).data("id");

      $(".confirm-receive-goods").prop('disabled', true);

        $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id="+id+"&action=order/confirm_receive_goods",dataType: "html",cache: false,success: function (data) { 
            location.reload();
        }});

      e.preventDefault();
   });

   $(document).on('click','.confirm-cancel-order', function (e) { 
      
      var id = $(this).data("id");

      $(".confirm-cancel-order").prop('disabled', true);

        $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id="+id+"&action=order/order_cancel_deal",dataType: "html",cache: false,success: function (data) { 
            location.reload();
        }});

      e.preventDefault();
   });


});

