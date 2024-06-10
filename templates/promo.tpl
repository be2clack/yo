<!doctype html>
<html lang="<?php echo getLang(); ?>" style="min-height: 100%;" >
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="<?php echo $data["page"]["promo_pages_desc"]; ?>">

    <title><?php echo $data["page"]["promo_pages_title"]; ?></title>
    <base href="<?php echo $config["urlPath"]; ?>/">

    <?php include $config["template_path"] . "/head.tpl"; ?>

  </head>

  <body data-prefix="<?php echo $config["urlPrefix"]; ?>" style="background-color: <?php echo $data["page"]["promo_pages_color"] ? $data["page"]["promo_pages_color"] : "white"; ?>" data-template="<?php echo $config["template_folder"]; ?>" >

  <div class="promo-area">
  
  <?php
  if( $_SESSION['cp_auth'][ $config["private_hash"] ] && $_SESSION["cp_control_page"] ){

      if( $data["page"]["promo_pages_html_edit"] ){
          echo $data["page"]["promo_pages_html_edit"];
      }else{

         ?>

            <div class="container" style="max-width: 1000px;" >

                <div class="promo-content" id="dragula" ></div>
               
                <div class="promo-add-element" >
                    <span class="open-modal" data-id-modal="modal-promo-elements" > <i class="las la-plus"></i> </span>
                </div>

            </div>

         <?php
      }

  }else{
      echo $data["page"]["promo_pages_html_public"];
  }
  ?>

  </div>
  
  <?php
  if( $_SESSION['cp_auth'][ $config["private_hash"] ] && $_SESSION["cp_control_page"] ){
  ?>

    <div class="modal-custom-bg bg-click-close"  id="modal-promo-elements" style="display: none;" >
        <div class="modal-custom animation-modal" style="max-width: 600px;" >

          <span class="modal-custom-close" ><i class="las la-times"></i></span>

          <h4> <strong>Добавление элемента</strong> </h4>

          <div class="mt30" ></div>

          <div class="modal-promo-elements-list" >
              <?php
              $dir = $config["template_path"]."/include/promo/";
              if(is_dir($dir)){
                $name = scandir($dir);
                for($i=2; $i<=(sizeof($name)-1); $i++) {
                    if(is_file($dir.$name[$i]) && $name[$i] != '.' && pathinfo($name[$i], PATHINFO_EXTENSION) == 'html' ){ 
                      $pathinfo = pathinfo($dir.$name[$i]);                     
                      ?>
                      <div data-name="<?php echo $pathinfo["filename"]; ?>" >
                         <span><?php echo $data["elements"][ $pathinfo["filename"] ]; ?></span>
                      </div>
                      <?php
                    }
                }
              }                
              ?>
          </div>

          <div class="mt30" ></div>

        </div>
    </div>

    <div class="modal-custom-bg"  id="modal-promo-element-text-edit" style="display: none;" >
        <div class="modal-custom animation-modal" style="max-width: 650px;" >

          <span class="modal-custom-close" ><i class="las la-times"></i></span>

          <textarea class="promo-element-text-edit" rows="10" name="text_editor" id="summernote" ></textarea>

          <button class="btn-custom btn-color-blue width100 promo-element-text-edit-accept mt20" >Применить</button>

        </div>
    </div>
    
    <div class="promo-menu-control-fixed" >

    <div class="promo-menu-control-fixed-button" >
        
        <div class="btn-custom btn-color-green promo-action-save" style="margin-right: 3px;" >Сохранить</div>
        <div class="btn-custom btn-color-blue promo-action-body-settings" style="margin-left: 3px;" >Настройки</div>

        <span class="clr" ></span>

    </div>

    <div class="promo-menu-control promo-menu-control-body" >

        <span class="promo-menu-control-close" > <i class="las la-times"></i> </span>
         
        <div>
          <label>Отображать логотип</label>
          <label class="checkbox">
            <input type="checkbox" name="logotip" value="1" <?php if( $data["page"]["promo_pages_logotip"] ){ echo 'checked=""'; } ?> >
            <span></span>
          </label>
        </div> 

        <div>
          <label>Ширина контейнера, px</label>
          <input type="number" name="body_width" class="form-control" value=""  >
        </div>

        <div>
          <label>Цвет фона</label>
          <input type="text" name="body_color" class="minicolors form-control" autocomplete="off" >
        </div>      

    </div>

    <div class="promo-menu-control promo-menu-control-element" >

        <span class="promo-menu-control-close" > <i class="las la-times"></i> </span>
         
        <div>
          <label>Внешние отступы</label>
          <div class="row" >
             <div class="col-lg-6" > <label>Верхний</label> <input type="text" name="element_margin_top" class="form-control"  > </div>
             <div class="col-lg-6" > <label>Нижний</label> <input type="text" name="element_margin_bottom" class="form-control" > </div>
             <div class="col-lg-6" > <label class="mt10" >Левый</label> <input type="text" name="element_margin_left" class="form-control"  > </div>
             <div class="col-lg-6" > <label class="mt10" >Правый</label> <input type="text" name="element_margin_right" class="form-control" > </div>
          </div>     
        </div>

        <div>
          <label>Внутрение отступы</label>
          <input type="text" name="element_padding" class="form-control"  >     
        </div>

        <div>
          <label>Скругление углов</label>
          <input type="text" name="element_border_radius" class="form-control"  >     
        </div>

        <div>
          <label>Цвет фона</label>
          <input type="text" name="element_color" class="minicolors form-control" autocomplete="off" >
        </div>      

    </div>

    <div class="promo-menu-control promo-menu-control-image" >

        <span class="promo-menu-control-close" > <i class="las la-times"></i> </span>
         
        <div>
          <label>Ширина изображения</label>
          <input type="text" name="image_width" class="form-control"  >     
        </div>

        <div>
          <label>Высота изображения</label>
          <input type="text" name="image_height" class="form-control"  >     
        </div>

        <div>
          <div class="btn-custom btn-color-danger width100 promo-element-image-delete mt20" >Удалить</div>
        </div>

    </div>

    <div class="promo-menu-control promo-menu-control-button" style="max-height: 500px; overflow: auto;" >

        <span class="promo-menu-control-close" > <i class="las la-times"></i> </span>
         
        <div>
          <label>Название</label>
          <input type="text" name="button_name" class="form-control"  >     
        </div>

        <div>
          <label>Ссылка</label>
          <input type="text" name="button_link" class="form-control"  >     
        </div>

        <div>
          <label>Размер шрифта</label>
          <input type="text" name="button_font_size" class="form-control"  >     
        </div>

        <div>
          <label>Верхний отступ</label>
          <input type="text" name="button_margin_top" class="form-control"  >     
        </div>

        <div>
          <label>Внутрение отступы</label>
          <input type="text" name="button_padding" class="form-control"  >     
        </div>

        <div>
          <label>Скругление углов</label>
          <input type="text" name="button_border_radius" class="form-control"  >     
        </div>

        <div>
          <label>Цвет фона</label>
          <input type="text" name="button_color" class="minicolors form-control" autocomplete="off" >
        </div>      

        <div>
          <label>Цвет текста</label>
          <input type="text" name="button_color_text" class="minicolors form-control" autocomplete="off" >
        </div>

        <div>
          <div class="btn-custom btn-color-danger width100 promo-element-button-delete mt20" >Удалить</div>
        </div>

    </div>

    <div class="promo-menu-control promo-menu-control-icon" >

        <span class="promo-menu-control-close" > <i class="las la-times"></i> </span>
         
        <div>
          <label>Размер иконки</label>
          <input type="text" name="icon_size" class="form-control"  >     
        </div>

        <div>
          <label>Цвет</label>
          <input type="text" name="icon_color" class="minicolors form-control" autocomplete="off" >
        </div>

        <div>
           <label>Иконки</label>
           <ul class="promo-icon-list" >
              <li><i class="las la-check"></i></li>
              <li><i class="las la-check-circle"></i></li>
              <li><i class="las la-exclamation-circle"></i></li>
              <li><i class="las la-bell"></i></li>
              <li><i class="las la-exclamation-triangle"></i></li>
              <li><i class="las la-history"></i></li>
              <li><i class="las la-cocktail"></i></li>
              <li><i class="las la-home"></i></li>
              <li><i class="las la-birthday-cake"></i></li>
              <li><i class="las la-balance-scale"></i></li>
              <li><i class="las la-briefcase"></i></li>
              <li><i class="las la-chart-area"></i></li>
              <li><i class="las la-certificate"></i></li>
              <li><i class="las la-bullseye"></i></li>
              <li><i class="las la-compass"></i></li>
              <li><i class="las la-globe"></i></li>
              <li><i class="las la-phone"></i></li>
              <li><i class="las la-project-diagram"></i></li>
              <li><i class="las la-wallet"></i></li>
              <li><i class="las la-fire-alt"></i></li>
              <li><i class="las la-map-marked"></i></li>
              <li><i class="las la-dollar-sign"></i></li>
              <li><i class="las la-gift"></i></li>
              <li><i class="las la-comment-alt"></i></li>
              <li><i class="las la-frown"></i></li>
              <li><i class="las la-smile"></i></li>
              <li><i class="las la-meh"></i></li>
              <li><i class="las la-code"></i></li>
              <li><i class="las la-filter"></i></li>
              <li><i class="las la-shield-alt"></i></li>
              <li><i class="las la-at"></i></li>
              <li><i class="las la-mobile"></i></li>
              <li><i class="las la-server"></i></li>
              <li><i class="las la-power-off"></i></li>
              <li><i class="las la-brush"></i></li>
              <li><i class="las la-tools"></i></li>
              <li><i class="las la-euro-sign"></i></li>
              <li><i class="las la-clock"></i></li>
              <li><i class="las la-calendar"></i></li>
              <li><i class="las la-eye"></i></li>
              <li><i class="las la-edit"></i></li>
              <li><i class="las la-magic"></i></li>
              <li><i class="las la-kiss-wink-heart"></i></li>
              <li><i class="las la-grin-squint-tears"></i></li>
              <li><i class="las la-grin-tongue-squint"></i></li>
              <li><i class="las la-kiss-beam"></i></li>
              <li><i class="las la-smile-wink"></i></li>
              <li><i class="las la-coins"></i></li>
              <li><i class="las la-handshake"></i></li>
              <li><i class="las la-hand-peace"></i></li>
              <li><i class="las la-thumbs-up"></i></li>
              <li><i class="las la-plus"></i></li>
              <li><i class="las la-brain"></i></li>
              <li><i class="las la-rocket"></i></li>
           </ul>
           <div class="clr" ></div>
        </div>
      
    </div>

    </div>

    <input type="file" class="input-logo-img" style="display: none;" >
    <input type="file" class="input-other-img" style="display: none;" >

    <?php } ?>

    <?php include $config["template_path"] . "/footer.tpl"; ?>
    
    <script type="text/javascript">
      
    $(document).ready(function () {

    <?php
    if( $_SESSION['cp_auth'][ $config["private_hash"] ] && $_SESSION["cp_control_page"] ){
    ?>

        var url_path = $("body").data("prefix");
        var edit_el = "";

        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $("input[name=body_width]").val( $(".container").width() );

        $('#summernote').summernote( {
            minHeight: 200,
            lang: 'ru-RU'
        });

        $(document).on('click','.promo-element-text-edit-accept', function () {    
          
           edit_el.html( $(".promo-element-text-edit").val() );

           $("#modal-promo-element-text-edit").hide();
           $("body").css("overflow", "auto");

           //save();

        });

        $(document).on('click','.promo-element-text', function () {    
          
          edit_el = $(this); 

          $('#summernote').summernote('code', edit_el.html());

          $("#modal-promo-element-text-edit").show();
          $("body").css("overflow", "hidden");
          
        });
 
        $(document).on('click','.promo-controls-edit', function () {    
          
          edit_el = $(this).parent().parent();

          $(".promo-menu-control").hide();
          $(".promo-menu-control-element").show();

          $("input[name=element_margin_top]").val( $(this).parent().parent().css("margin-top") );
          $("input[name=element_margin_bottom]").val( $(this).parent().parent().css("margin-bottom") );
          $("input[name=element_margin_left]").val( $(this).parent().parent().css("margin-left") );
          $("input[name=element_margin_right]").val( $(this).parent().parent().css("margin-right") );
          $("input[name=element_padding]").val( $(this).parent().parent().css("padding") );
          $("input[name=element_border_radius]").val( $(this).parent().parent().css("border-radius") );
          $("input[name=element_color]").minicolors('value', $(this).parent().parent().css("background-color") );

        });

        $(document).on('click','.promo-element-button a', function () {    
          
          edit_el = $(this);

          $(".promo-menu-control").hide();
          $(".promo-menu-control-button").show();

          $("input[name=button_name]").val( $(this).html() );
          $("input[name=button_link]").val( $(this).attr("href") );
          $("input[name=button_margin_top]").val( $(this).css("margin-top") );
          $("input[name=button_padding]").val( $(this).css("padding") );
          $("input[name=button_border_radius]").val( $(this).css("border-radius") );
          $("input[name=button_font_size]").val( $(this).css("font-size") );
          $("input[name=button_color]").minicolors('value', $(this).css("background-color") );
          $("input[name=button_color_text]").minicolors('value', $(this).css("color") );
          
          return false;

        });

        $(document).on('input','input[name=button_name]', function () { edit_el.html( $(this).val() ); });
        $(document).on('input','input[name=button_link]', function () { edit_el.attr( "href", $(this).val() ); });
        $(document).on('input','input[name=button_margin_top]', function () { edit_el.css( "margin-top", $(this).val() ); });
        $(document).on('input','input[name=button_padding]', function () { edit_el.css( "padding", $(this).val() ); });
        $(document).on('input','input[name=button_border_radius]', function () { edit_el.css( "border-radius", $(this).val() ); });
        $(document).on('input','input[name=button_font_size]', function () { edit_el.css( "font-size", $(this).val() ); });
        $(document).on('input','input[name=button_color]', function () { edit_el.css( "background-color", $(this).val() ); });
        $(document).on('input','input[name=button_color_text]', function () { edit_el.css( "color", $(this).val() ); });

        $(document).on('click','.promo-element-icon i', function () {    
          
          edit_el = $(this);

          $(".promo-menu-control").hide();
          $(".promo-menu-control-icon").show();

          $("input[name=icon_color]").minicolors('value', $(this).css("color") );
          $("input[name=icon_size]").val( $(this).css("font-size") );
          
        });

        $(document).on('click','.promo-icon-list i', function () {    
          
          edit_el.attr("class", $(this).attr("class") );
          
        });


        $(document).on('input','input[name=icon_color]', function () { edit_el.css( "color", $(this).val() ); });
        $(document).on('input','input[name=icon_size]', function () { edit_el.css( "font-size", $(this).val() ); });

        $(document).on('click','.promo-element-image-delete', function () {    
          
          var result = confirm("Вы действительно хотите выполнить удаление?");

          if(result)  {
            edit_el.after(`
              <div class="promo-element-add-img" >
                <span><i class="las la-image"></i></span>
              </div>
            `);
            edit_el.remove().hide();
            $(".promo-menu-control").hide();
          }
          
        });

        $(document).on('click','.promo-element-button-delete', function () {    
          
          var result = confirm("Вы действительно хотите выполнить удаление?");

          if(result)  {
             edit_el.remove().hide();
             $(".promo-menu-control").hide();
          }

        });

        $(document).on('click','.promo-element-image', function () {    
          
          edit_el = $(this);

          $(".promo-menu-control").hide();
          $(".promo-menu-control-image").show();

          $("input[name=image_width]").val( $(this).css("width") );
          $("input[name=image_height]").val( $(this).css("height") );

        });

        $(document).on('input','input[name=image_width]', function () { edit_el.css("width", $(this).val() ); $("input[name=image_height]").val( edit_el.css("height") ); });
        $(document).on('input','input[name=image_height]', function () { edit_el.css("height", $(this).val() ); $("input[name=image_width]").val( edit_el.css("width") ); });

        $(document).on('input','input[name=element_margin_top]', function () { edit_el.css("margin-top", $(this).val() ); });
        $(document).on('input','input[name=element_margin_bottom]', function () { edit_el.css("margin-bottom", $(this).val() ); });
        $(document).on('input','input[name=element_margin_left]', function () { edit_el.css("margin-left", $(this).val() ); });
        $(document).on('input','input[name=element_margin_right]', function () { edit_el.css("margin-right", $(this).val() ); });
        $(document).on('input','input[name=element_padding]', function () { edit_el.css("padding", $(this).val() ); });
        $(document).on('input','input[name=element_border_radius]', function () { edit_el.css("border-radius", $(this).val() ); });


        $(document).on('click','.promo-action-body-settings', function () {

            $(".promo-menu-control").hide();
            $(".promo-menu-control-body").show();
            
        });

        $(document).mouseup(function (e){
            var div = $(".promo-menu-control");
            if (!div.is(e.target)
                && div.has(e.target).length === 0) {
                $(".promo-menu-control").hide();
            }
        });

        $(document).on('click','.promo-menu-control-close', function () {

            $(this).parent().hide();
            
        });

        $(document).on('click','.modal-promo-elements-list > div', function () {    
          
          var el = $(this); 
          
          $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "name=" + el.data("name") + "&action=promo/load_element",dataType: "html",cache: false,success: function (data) { 
              
              $(".promo-content").append(data);
              $("#modal-promo-elements").hide();
              $("body").css("overflow", "auto");

          }});

        });

        $(document).on('click','.promo-controls-delete', function () {    
          
          var el = $(this); 
          
          var result = confirm("Вы действительно хотите выполнить удаление?");

          if(result)  {
              el.parent().parent().remove().hide();
          }

        });

        $(document).on('click','.promo-controls-copy', function () {    
          
              var clone = $(this).parent().parent().clone();
              $(this).parent().parent().after(clone);

        });

        $(document).on('click','.promo-action-save', function () {    
          
              save();

              var el = $(this);

              el.html( '<i class="las la-check"></i> Сохранено' );

              setTimeout(function(){
                el.html( 'Сохранить' );
              }, 1000);

        });

        function save(){

            if( !$(".promo-logo img").length ){
                 $(".promo-logo").remove().hide();
                 $("input[name=logotip]").prop("checked", false);
            }

            $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "color=" + $("input[name=body_color]").val() + "&logo=" + ( $("input[name=logotip]").prop("checked") ? 1 : 0 ) + "&html=" + encodeURIComponent($(".promo-area").html()) + "&id=<?php echo $data["page"]["promo_pages_id"]; ?>&action=promo/save",dataType: "html",cache: false,success: function (data) { 


            }});

        }

        $(document).on('input','input[name=body_color]', function () {    
           $("body").css("background-color", $(this).val() );
        });

        $(document).on('input','input[name=element_color]', function () {    
           edit_el.css("background-color", $(this).val() );
        });

        $(document).on('input','input[name=body_width]', function () {    
           $(".container").css("max-width", $(this).val() + "px" );
        });

        $(document).on('change','input[name=logotip]', function () {    
           
          if($(this).prop("checked") == true){
              
              $(".container").prepend(`
                <div class="promo-logo" >
                  
                    <span><i class="las la-plus"></i></span>
                  
                </div>
              `);

          }else{
              $(".promo-logo").remove().hide();
          }


        });

        dragula([document.getElementById("dragula")], {
          moves: function (el, container, handle) {
            return handle.className === 'las la-arrows-alt';
          }
        });
       
        $(".minicolors").minicolors({
          theme: 'bootstrap'
        });

        $("input[name=body_color]").minicolors('value', $("body").css("background-color") );

        function loadImage(input, element, name) { 

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {    
                      
                      $.ajax({url: url_path + "systems/ajax/controller.php",type: 'POST',data: "image=" + encodeURIComponent(e.target.result) + "&action=promo/image",cache: false,dataType: 'html',
                        success: function( data ){

                            if( name == "logo" ){
                                element.html( '<img src="'+data+'" class="promo-logo-link" />' ); 
                            }else if( name == "other" ){
                                element.html( '<img src="'+data+'" class="promo-element-image" />' ); 
                            }

                        }
                    });
  
                };

                reader.readAsDataURL(input.files[0]);
                $(input).val("");

            }
        }

        $(document).on('click','.promo-logo span, .promo-logo img', function () { $('.input-logo-img').click(); });
        $(document).on('change','.input-logo-img', function () {    
            loadImage(this, $(".promo-logo"), "logo" );
        });

        $(document).on('click','.promo-element-add-img span', function () { $('.input-other-img').click(); edit_el = $(this).parent(); });
        $(document).on('change','.input-other-img', function () {    
            loadImage(this, edit_el, "other" );
        });


    <?php }else{
      ?>

        $(document).on('click','.promo-logo-link', function () {    
            location.href="<?php echo _link(); ?>";
        });

      <?php
    } ?>

    });

    </script>

  </body>
</html>