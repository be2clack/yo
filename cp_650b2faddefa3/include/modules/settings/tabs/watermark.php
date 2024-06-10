<div class="tab-pane fade <?php if($tab == "watermark"){ echo 'active show'; } ?>" id="tab-watermark" role="tabpanel" aria-labelledby="tab-watermark">

<div class="form-group row d-flex align-items-center mb-5">
<label class="col-lg-3 form-control-label">Водяной знак на фото</label>
<div class="col-lg-6">
    <label>
      <input class="toggle-checkbox-sm" value="1" type="checkbox" name="watermark_status" <?php if($settings["watermark_status"] == "1"){ echo 'checked=""'; } ?> >
      <span><span></span></span>
    </label>
</div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
<label class="col-lg-3 form-control-label">Вид водяного знака</label>
<div class="col-lg-6">
   <select class="selectpicker" name="watermark_type" >
       <option value="caption" <?php if($settings["watermark_type"] == "caption"){echo 'selected=""';}?> >Надпись</option>
       <option value="img" <?php if($settings["watermark_type"] == "img"){echo 'selected=""';}?> >Изображение</option>
   </select>
</div>
</div>

<div class="watermark-box-img" <?php if($settings["watermark_type"] == "img"){echo 'style="display: block;"';}else{ echo 'style="display: none;"'; } ?> >

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Изображение</label>
    <div class="col-lg-6">
       <input type="file" name="watermark_img" class="form-control" >
       <small>Выберите файл в формате PNG</small>
       <br>
        <?php  
          if( file_exists( $config["basePath"] . "/" . $config["media"]["other"] . "/" . $settings["watermark_img"] ) ){
            ?>
              <img class="watermark-image" src="<?php echo $config["urlPath"] . "/" . $config["media"]["other"] . "/" . $settings["watermark_img"]; ?>" />
            <?php 
          }
        ?>

    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Позиция</label>
    <div class="col-lg-6">
       <select class="selectpicker" name="watermark_pos" >
           <option value="1" <?php if($settings["watermark_pos"] == 1){echo 'selected=""';}?> >Верхний левый угол</option>
           <option value="2" <?php if($settings["watermark_pos"] == 2){echo 'selected=""';}?> >Верхний правый угол</option>
           <option value="3" <?php if($settings["watermark_pos"] == 3){echo 'selected=""';}?> >Нижний левый угол</option>
           <option value="4" <?php if($settings["watermark_pos"] == 4){echo 'selected=""';}?> >Нижний правый угол</option>
           <option value="5" <?php if($settings["watermark_pos"] == 5){echo 'selected=""';}?> >Центр</option>
       </select>
    </div>
 </div>

</div>

<div class="watermark-box-caption" <?php if($settings["watermark_type"] == "caption"){echo 'style="display: block;"';}else{ echo 'style="display: none;"'; } ?> >

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Надпись</label>
    <div class="col-lg-6">
       <input type="text" name="watermark_caption" class="form-control" value="<?php echo $settings["watermark_caption"]; ?>" >
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Шрифт</label>
    <div class="col-lg-6">
       <select name="watermark_caption_font" class="selectpicker" >
           <?php
               $dir = $config["basePath"]."/".$config["folder_admin"]."/files/fonts/watermark/";
               if(is_dir($dir)){
                $name = scandir($dir);
                for($i=2; $i<=(sizeof($name)-1); $i++) {                         
                      
                    if($settings["watermark_caption_font"] == $name[$i]){
                      $selected = 'selected=""';
                    }else{ $selected = ''; }

                   if(is_file($dir.$name[$i]) && $name[$i] != '.' && pathinfo($name[$i], PATHINFO_EXTENSION) == 'ttf'){                           
                      echo '<option '.$selected.' value="'.$name[$i].'" >'.$name[$i].'</option>';

                   }

                }
             }                               
           ?>
       </select>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Размер надписи</label>
    <div class="col-lg-2">
       <select class="selectpicker" name="watermark_caption_size" >
           <option value="big" <?php if($settings["watermark_caption_size"] == "big"){echo 'selected=""';}?> >Большой</option>
           <option value="medium" <?php if($settings["watermark_caption_size"] == "medium"){echo 'selected=""';}?> >Средний</option>
           <option value="small" <?php if($settings["watermark_caption_size"] == "small"){echo 'selected=""';}?> >Маленький</option>
       </select>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Прозрачность</label>
    <div class="col-lg-2">
       <input type="text" name="watermark_caption_opacity" class="form-control" value="<?php echo $settings["watermark_caption_opacity"]; ?>" >
    </div>
 </div>

</div>

<div class="form-group row d-flex align-items-center mb-5">
<label class="col-lg-3 form-control-label"></label>
<div class="col-lg-6 text-right">
   <a class="btn btn-primary" href="<?php echo $config["urlPath"] . "/" . $config["folder_admin"] . "/include/modules/settings/watermark_preview.php" ?>" target="_blank" >Предпросмотр</a>
</div>
</div>

</div>