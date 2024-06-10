<?php 
if( !defined('unisitecms') ) exit;
?>    


<div class="row" >
  <div class="col-lg-12" >

            <div class="index-card-user" style="margin-top: 50px;" >

              <img src="<?php echo $settings["path_admin_image"] ?>/card_user_welcome.png" style="max-width:400px;" >

              <h1 style="margin-top: 25px;" > <strong>Добро пожаловать, <?php echo $_SESSION['cp_auth'][ $config["private_hash"] ]["fio"]; ?>!</strong> </h1>
              <p style="margin-top: 15px;" >Желаем Вам приятной работы в системе <?php echo $settings["site_name"]; ?>! <i class="la la-heart" style="color: red;"></i></p>
              
            </div>

  </div>
</div>
