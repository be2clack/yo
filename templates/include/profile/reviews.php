
<h3 class="mb35 user-title" > <strong><?php echo $data["page_name"]; ?></strong> <span style="font-size: 23px;" ><?php echo isset($data["reviews"]) ? count($data["reviews"]) : 0; ?></span> </h3>
<?php

if($data["reviews"]){

 foreach ($data["reviews"] as $key => $value) {
    include $config["template_path"] . "/include/reviews_user.php";
 }

}else{
 ?>
  <div class="user-block-no-result" >

     <img src="<?php echo $settings["path_tpl_image"]; ?>/card-placeholder.svg">
     <p><?php echo $ULang->t("Все отзывы пользователя будут отображаться на этой странице."); ?></p>
    
  </div>                       
 <?php
}