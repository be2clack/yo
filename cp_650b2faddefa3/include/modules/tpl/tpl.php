<?php 
if( !defined('unisitecms') ) exit;

$arrayFiles = getTplFiles();

?>

<div class="row">
   <div class="page-header">
      <div class="">
         <div class="row" >
            <div class="col-lg-12" >
                <h2 class="page-header-title">Управление шаблоном</h2>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="row" >
   <div class="col-lg-12" >
      <div class="widget has-shadow">

         <div class="widget-body">
             
             <?php if(!$settings["demo_view"] && !$settings["demo_installment"]){ ?>
             <p><strong>Файлы шаблона</strong></p>

                <?php
                   if(is_dir($config["template_path"])){
                    $name = scandir($config["template_path"]);
                    for($i=2; $i<=(sizeof($name)-1); $i++) {                         
                          
                        if($_GET["file"] == $name[$i]){
                          $active = 'active';
                        }else{ $active = ''; }

                       if(is_file($config["template_path"]."/".$name[$i]) && $name[$i] != '.' && pathinfo($name[$i], PATHINFO_EXTENSION) == 'tpl'){                           
                          echo "<a style='margin-right: 5px; margin-bottom: 5px;' class='btn btn-secondary btn-sm ".$active."' href='?route=tpl&file=".$name[$i]."&type=file'>".$name[$i]."</a>";

                       }
                    }
                 }  
                ?> 

                <br /><br /> 

                <?php } ?>

                <p><strong>Стили сайта</strong></p> 

                <?php

                    $css_array = ["styles.css"];
                    foreach ($css_array as $key => $value) {

                        if($_GET["file"] == $value){
                          $active = 'active';
                        }else{ $active = ''; }
                       
                        echo "<a style='margin-right: 5px; margin-bottom: 5px;' class='btn btn-secondary btn-sm ".$active."' href='?route=tpl&file=".$value."&type=css'>".$value."</a>";

                    }
                    
                ?>  
                
                <?php if(!$settings["demo_view"] && !$settings["demo_installment"]){ ?>
                <br /><br />

                <p><strong>JavaScript файлы</strong></p> 

                <?php
                    $js_array = [ "create.js", "auth.js", "user.js", "vendor.js", "blog.js", "index.js", "catalog.js", "view.js", "update.js", "shop.js", "shops.js", "promo.js", "cart.js" ];
                    foreach ($js_array as $key => $value) {

                        if($_GET["file"] == $value){
                          $active = 'active';
                        }else{ $active = ''; }
                       
                        echo "<a style='margin-right: 5px; margin-bottom: 5px;' class='btn btn-secondary btn-sm ".$active."' href='?route=tpl&file=".$value."&type=js'>".$value."</a>";

                    }

                  }
                ?> 
         
            <form method="post" id="form-tpl" > 

                <?php
                  if( $arrayFiles[ $_GET["file"] ] && $_GET["type"] ){
                   
                     if($_GET["type"] == "file"){
                       $dir = $config["template_path"]."/".str_replace('/', '', $_GET["file"]);
                     }elseif($_GET["type"] == "css"){
                       $dir = $config["template_path"]."/css/".str_replace('/', '', $_GET["file"]);
                     }elseif($_GET["type"] == "js"){
                       $dir = $config["template_path"]."/js/".str_replace('/', '', $_GET["file"]);
                     }  
                    
                    if(file_exists($dir)){      
                     $fp = @fopen ($dir, 'r' );
                      if ($fp) {
                          $statusOpen = true;
                          $size = @filesize ($dir);
                          $content = @fread ($fp, $size);
                          @fclose ($fp); 
                      }

                    }

                  }  
                ?> 
             
              <?php if($statusOpen){ ?>

                  <textarea id="textarea-tpl" name="area_tpl" ><?php echo str_replace (array ('<','>'), array ('&lt;','&gt;'),  trim($content)); ?></textarea>
                  <input type="hidden" name="file" value="<?php echo str_replace('/', '', $_GET["file"]); ?>" />
                  <input type="hidden" name="type" value="<?php echo $_GET["type"]; ?>" />

              <?php } ?>
              
           </form>
   
         </div>

      </div>
      
      <?php if($statusOpen){ ?>
      <div class="text-right">
         <button type="button" class="btn btn-success edit_tpl" >Сохранить</button>        
      </div>
      <?php } ?>

   </div>
</div>

<script type="text/javascript" src="include/modules/tpl/script.js"></script>

<?php if($statusOpen){ ?>
<script type="text/javascript">
    
    $(document).ready(function() {

        var editor = CodeMirror.fromTextArea(document.getElementById("textarea-tpl"), {
          lineNumbers: true,
          styleActiveLine: true,
          matchBrackets: false,
          enterMode: 'keep',
          indentWithTabs: false,
          indentUnit: 1,
          tabMode: 'classic',
          theme: 'lucario'
        });

                    
        $('.edit_tpl').click(function (e) {
            
          var data_form = new FormData($('#form-tpl')[0]);
          data_form.append('area_tpl', editor.getValue());
          $('.proccess_load').show();
              $.ajax({
                  type: "POST",url: "include/modules/tpl/handlers/edit.php",data: data_form,dataType: "html",cache: false,contentType: false,processData: false,success: function (data) {
                      $('.proccess_load').hide();
                      notification();                    
                  }
              });
                           
          e.preventDefault();

        });

      });

</script>

<?php
 }
?>