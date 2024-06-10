<!doctype html>
<html lang="<?php echo getLang(); ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="<?php echo $ULang->t(urldecode($data["page"]["seo_text"]), [ "table"=>"uni_pages", "field"=>"seo_text" ]); ?>">

    <title><?php echo $ULang->t($data["page"]["title"], [ "table"=>"uni_pages", "field"=>"title" ]); ?></title>

    <?php include $config["template_path"] . "/head.tpl"; ?>

  </head>

  <body data-prefix="<?php echo $config["urlPrefix"]; ?>" data-template="<?php echo $config["template_folder"]; ?>">
    
    <?php include $config["template_path"] . "/header.tpl"; ?>

    <div class="container" >
       
       <nav aria-label="breadcrumb">
 
          <ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">

            <li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
              <a itemprop="item" href="<?php echo $config["urlPath"]; ?>">
              <span itemprop="name"><?php echo $ULang->t("Главная"); ?></span>
              </a>
              <meta itemprop="position" content="1">
            </li>

            <li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
              <span itemprop="name"><?php echo $ULang->t($data["page"]["name"], [ "table"=>"uni_pages", "field"=>"name" ]); ?></span>
              <meta itemprop="position" content="2">
            </li>                 
          </ol>

        </nav>
          
        <div class="row" >
            <div class="col-lg-12" >
              <h2 class="mb30" ><?php echo $ULang->t($data["page"]["title"], [ "table"=>"uni_pages", "field"=>"title" ]); ?></h2>
            </div>
            <div class="col-lg-3" >
              <div class="page-menu-list" >
                 <?php
                    echo $Main->pageMenu($data);
                 ?>
                <hr>
                <a class="page-feedback" href="<?php echo _link("feedback"); ?>" >
                   <div class="page-feedback-icon" > <span><i class="las la-question-circle"></i></span> </div>
                   <div class="page-feedback-name" > <strong><?php echo $ULang->t("Служба поддержки"); ?></strong> <?php echo $ULang->t("Поможем решить проблему"); ?> </div>
                   <div class="clr" ></div>
                </a>                 
              </div>
            </div>
            <div class="col-lg-9" >

              <div class="bg-container schema-text-white" >
                 <?php echo textReplace( $ULang->t(urldecode($data["page"]["text"]), [ "table"=>"uni_pages", "field"=>"text" ]) ); ?>
              </div>

            </div>
        </div>
         
          
       <div class="mt50" ></div>


    </div>


    <?php include $config["template_path"] . "/footer.tpl"; ?>

  </body>
</html>