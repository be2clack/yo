<!doctype html>
<html lang="<?php echo getLang(); ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?php echo $ULang->t("Объявление готово к публикации"); ?></title>

    <?php include $config["template_path"] . "/head.tpl"; ?>

  </head>

  <body data-prefix="<?php echo $config["urlPrefix"]; ?>"  data-template="<?php echo $config["template_folder"]; ?>" >
    
    <?php include $config["template_path"] . "/header.tpl"; ?>

    <div class="container minheight700" >
       
        <div class="row" >
            <div class="col-lg-12" >

              <div class="bg-container" style="text-align: center; margin-top: 80px;" >

                <img src="<?php echo $settings["path_tpl_image"] . '/wallet-115848_115822.png'; ?>" height="128px" >

                <h4 class="mt30" ><strong><?php echo $ULang->t("Ваше объявление перемещено в архив"); ?></strong></h4>
                <h6><?php echo $ULang->t("Стоимость размещения в категорию") . " «" . $data["category_board_name"] . "»" . " " . $Main->price($data["category_board_price"]); ?></h6>

                <span class="btn-custom btn-color-green ads-cat-pay-publication mt25 schema-color-button" style="display: inline-block;" data-id="<?php echo $data["ads_id"]; ?>" ><?php echo $ULang->t("Опубликовать за"); ?> <?php echo $Main->price($data["category_board_price"]); ?></span>

                <?php
                  if($settings["board_type_ad_publication"] == "paid"){
                    ?>
                    <a class="btn-custom btn-color-blue mt10 schema-color-button" href="<?php echo _link( "user/" . $_SESSION["profile"]["data"]["clients_id_hash"] . "/packages" ); ?>" style="display: inline-block;" ><?php echo $ULang->t("Подключить пакет"); ?></a>
                    <?php
                  }
                ?>

                <div class="mt20" ></div>               
                  
              </div>

            </div>
        </div>
         
          
       <div class="mt50" ></div>


    </div>


    <?php include $config["template_path"] . "/footer.tpl"; ?>

  </body>
</html>