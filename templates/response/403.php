<!doctype html>
<html lang="<?php echo $settings["lang_site_default"]; ?>">

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

	<title><?php echo $ULang->t( "Доступ к сайту временно закрыт" ); ?></title>

    <link href="<?php echo $config["urlPath"]; ?>/<?php echo $config['template_folder']; ?>/css/line-awesome.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Ubuntu&display=swap" rel="stylesheet">
    <style type="text/css">
    	body{ font-family: 'Ubuntu', sans-serif; background-color: #f7f8fa; }
        
        .container{
            background-color: white; border-top-left-radius: 200px; border-top-right-radius: 50px; border-bottom-left-radius: 50px; border-bottom-right-radius: 200px;  padding: 30px; margin: 100px auto; max-width: 800px; text-align: center; 
        }

        .contact p{
            font-size: 17px; font-weight: 400; margin-top: 0; margin-bottom: 0px;
        }

        .contact i{
            font-size: 35px;
        }

        .contact > div{
            display: inline-block; margin: 0 15px;
        }
        .contact{
            text-align: center; margin-bottom: 25px;
        }
        .social-icon{
            display: inline-block; margin-right: 3px;
        }
    .social-icon img{
        height: 32px;
    }
        img{
            margin-top: -100px; 
        }

        h1{
            margin-bottom: 35px; font-size: 30px;
        }

    </style>
</head>
<body>

<noindex>

<div class="img" >
    <div class="container" >

    	<img src="<?php echo $config["urlPath"]; ?>/<?php echo $config['template_folder']; ?>/images/root-access_4417096.png" >
    	<h1>
         <?php
            if($settings["access_text"]){
                echo $settings["access_text"];
            }else{
                echo $ULang->t( "Доступ к сайту временно закрыт" );
            }
         ?>   
        </h1>
        
        <div class="contact" >

            <?php if($settings["contact_phone"] || $settings["contact_email"]){ ?>
            <p style="margin-bottom: 15px;" ><strong><?php echo $ULang->t( "Наши контакты" ); ?></strong></p>
            <?php } ?>

            <?php if($settings["contact_phone"]){ ?>
            <div>
            <i class="las la-mobile-alt"></i>
        	<p><?php echo $settings["contact_phone"]; ?></p>
            </div>
            <?php } ?>
            <?php if($settings["contact_email"]){ ?>
            <div>
            <i class="las la-at"></i>
        	<p><?php echo $settings["contact_email"]; ?></p>
            </div>
            <?php } ?>
        </div>

        <?php if($this->socialLink()){ ?>

           <p style="margin-bottom: 30px;" ><strong><?php echo $ULang->t( "Следите за нами в социальных сетях" ); ?></strong></p>

           <?php echo $this->socialLink(); ?>

        <?php } ?>


    </div>

</div>

</noindex>

</body>
</html>