<!doctype html>
<html lang="<?php echo getLang(); ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Статус оплаты</title>

    <?php include $config["template_path"] . "/head.tpl"; ?>

  </head>

  <body data-prefix="<?php echo $config["urlPrefix"]; ?>" data-template="<?php echo $config["template_folder"]; ?>">
    
    <?php include $config["template_path"] . "/header.tpl"; ?>

    <div class="container mt35" >
        
        <?php if( $status == "success" ){ ?>
        <div class="row" >
            <div class="col-lg-12" >
              
              <div class="minheight400" >

               <div class="icon-circle-status mt35" >
                  <div class="status-green" >
                    <i class="las la-check"></i>
                  </div>
               </div>

                <h4 class="text-center mt30" > <strong><?php echo $ULang->t("Оплата успешно выполнена!"); ?></strong> </h4>

              </div>

            </div>
        </div>
        <?php }else{ ?>
        <div class="row" >
           <div class="col-lg-12" >
             
             <div class="minheight400" >

               <div class="icon-circle-status mt35" >
                  <div class="status-red" >
                    <i class="las la-times"></i>
                  </div>
               </div>

               <h4 class="text-center mt30" > <strong><?php echo $ULang->t("Оплата не выполнена!"); ?></strong> </h4>

               <p class="text-center mt10" ><?php echo $ULang->t("Возникли трудности с оплатой?"); ?> <a href="<?php echo _link("feedback"); ?>"><?php echo $ULang->t("Напишите нам в службу поддержки"); ?></a></p>
               
             </div>

           </div>
        </div>
        <?php } ?>
         
       <div class="mt50" ></div>

    </div>


    <?php include $config["template_path"] . "/footer.tpl"; ?>

  </body>
</html>