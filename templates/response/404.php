<!doctype html>
<html lang="<?php echo $settings["lang_site_default"]; ?>">

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

	<title><?php echo $ULang->t( "404 - страница не существует!" ); ?></title>

    <link href="<?php echo $config["urlPath"]; ?>/<?php echo $config['template_folder']; ?>/css/line-awesome.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Ubuntu&display=swap" rel="stylesheet">
    <style type="text/css">
    	body{ font-family: 'Ubuntu', sans-serif; background-color: #f7f8fa; }
        
        .container{
            background-color: white; border-top-left-radius: 200px; border-top-right-radius: 50px; border-bottom-left-radius: 50px; border-bottom-right-radius: 200px;  padding: 30px; margin: 100px auto; max-width: 800px; text-align: center; 
        }

        a{
            font-size: 22px;
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

    	<img src="<?php echo $config["urlPath"]; ?>/<?php echo $config['template_folder']; ?>/images/word_18_2875628.png" >
    	<h1><?php echo $ULang->t( "404 - страница не существует!" ); ?></h1>
        
        <a href="<?php echo $config["urlPath"]; ?>" ><i class="las la-arrow-left"></i> <?php echo $ULang->t( "Вернуться на сайт" ); ?></a>


    </div>

</div>

</noindex>

</body>
</html>