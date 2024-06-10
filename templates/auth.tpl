<!doctype html>
<html lang="<?php echo getLang(); ?>">
  <head>
    <meta charset="utf-8">
    
    <title><?php echo $ULang->t("Вход в личный кабинет"); ?></title>
    
    <?php include $config["template_path"] . "/head.tpl"; ?>

  </head>

  <body  data-prefix="<?php echo $config["urlPrefix"]; ?>" data-template="<?php echo $config["template_folder"]; ?>" >

   <div class="container" >

   <div class="auth-logo" >
        <a class="h-logo" href="<?php echo _link(); ?>" title="<?php echo $ULang->t($settings["title"]); ?>" >
            <img src="<?php echo $settings["logotip"]; ?>" alt="<?php echo $ULang->t($settings["title"]); ?>">
        </a>       
   </div>

   <div class="auth-block" >

     <div class="row no-gutters" >
        <div class="col-lg-5 d-none d-lg-block" >

           <div class="auth-block-left" >

              <h4 class="auth-left-box-title" ><?php echo $settings["title"]; ?></h4>
              <p class="auth-left-box-desc" ><?php echo $ULang->t("Удобный сервис, который позволяет быстро и безопасно продавать и покупать товары онлайн."); ?></p>
                  
              <div class="ul-list-box mt25" >
                <div class="ul-list-icon" > <i class="las la-check"></i> </div>
                <div class="ul-list-title" >
                  <p><strong><?php echo $ULang->t("Общайтесь"); ?></strong></p>
                  <p><?php echo $ULang->t("по объявлениям в чатах"); ?></p>
                </div>
                <div class="clr" ></div>
              </div>
              <div class="ul-list-box">
                <div class="ul-list-icon" > <i class="las la-check"></i> </div>
                <div class="ul-list-title" >
                  <p><strong><?php echo $ULang->t("Размещайте"); ?></strong></p>
                  <p><?php echo $ULang->t("объявления бесплатно"); ?></p>
                </div>
                <div class="clr" ></div>                      
              </div>
              <div class="ul-list-box">
                <div class="ul-list-icon" > <i class="las la-check"></i> </div>
                <div class="ul-list-title" >
                  <p><strong><?php echo $ULang->t("Покупайте со скидкой"); ?></strong></p>
                  <p><?php echo $ULang->t("по безопасной сделке"); ?></p>
                </div>  
                <div class="clr" ></div>                    
              </div> 
              <div class="ul-list-box">
                <div class="ul-list-icon" > <i class="las la-check"></i> </div>
                <div class="ul-list-title" >
                  <p><strong><?php echo $ULang->t("Продавайте товары"); ?></strong></p>
                  <p><?php echo $ULang->t("Просто и безопасно"); ?></p>
                </div>
                <div class="clr" ></div>                      
              </div>                                                                                                   

           </div>
          
        </div>
        <div class="col-lg-7 col-12" >
          
           <div class="auth-block-right" >
               
              <?php include $config["template_path"] . "/include/auth.php"; ?>

           </div>

        </div>
     </div>
     
   </div>

  <div class="auth-agreement" >
    <?php echo $ULang->t("Авторизуясь на сайте, Вы принимаете условия"); ?> <a href="<?php echo _link("polzovatelskoe-soglashenie"); ?>"><?php echo $ULang->t("Пользовательского соглашения"); ?></a>, <a href="<?php echo _link("privacy-policy"); ?>"><?php echo $ULang->t("Политики конфиденциальности"); ?></a> <?php echo $ULang->t("и подтверждаете согласие на передачу и обработку своих данных"); ?>
  </div>

  </div>

   <?php include $config["template_path"] . "/footer.tpl"; ?>

  </body>
</html>